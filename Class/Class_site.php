<?php

class AdvancedCDNDetector {
    private static function multiSearch(array $haystack, array $needles, bool $caseSensitive = false): bool {
        $method = $caseSensitive ? 'strpos' : 'stripos';

        foreach ($needles as $needle) {
            foreach ($haystack as $value) {
                if ($method($value, $needle) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    private static function normalizeHeaders(array $headers): array {
        // Normaliza las claves de los encabezados y elimina duplicados
        $normalizedHeaders = [];
        foreach ($headers as $key => $value) {
            $lowerKey = strtolower($key); // Convierte la clave a minúsculas
            $normalizedHeaders[$lowerKey] = $value; // Sobrescribe valores duplicados
        }
        return $normalizedHeaders;
    }

    private static function detectPayments(string $content, array $headers): array {
        $headers = self::normalizeHeaders($headers); // Asegura claves únicas
        $paymentPlatforms = [
            'Stripe' => [
                'headers' => ['stripe', 'x-stripe'],
                'content' => ['stripe.com', 'stripe-js', 'Stripe.js', 'stripe-payment']
            ],
            'PayPal' => [
                'headers' => ['x-paypal'],
                'content' => ['paypal-integration','paypal-push-cart.js']
            ],
            'Payflow-Express' => [
                'headers' => ['paypal', 'x-paypal'],
                'content' => ['paypal\/payflowexpress', 'pilot-payflowlink.paypal.com']
            ],
            'Braintree' => [
                'headers' => ['braintree', 'x-braintree'],
                'content' => ['braintreepayments.com', 'braintree-payment', 'braintree.js']
            ],
            'Square' => [
                'headers' => ['square', 'x-square'],
                'content' => ['squareup.com', 'square-payment', 'square.js']
            ],
            'Adyen' => [
                'headers' => ['adyen', 'x-adyen'],
                'content' => ['adyen.com', 'adyen-payment', 'adyen.js']
            ]
        ];

        $detectedPayments = [];
        foreach ($paymentPlatforms as $platform => $signatures) {
            $headerMatch = self::multiSearch(array_keys($headers), $signatures['headers']) ||
                          self::multiSearch(array_values($headers), $signatures['headers']);
            $contentMatch = self::multiSearch([$content], $signatures['content']);

            if ($headerMatch || $contentMatch) {
                $detectedPayments[] = $platform;
            }
        }

        return $detectedPayments ?: ['Not Found'];
    }

    private static function detectSecurities(array $headers, string $cookies, string $content): array {
        $headers = self::normalizeHeaders($headers); // Asegura claves únicas
        $securitySignatures = [
            'Cloudflare' => [
                'headers' => ['cf-ray', 'cf-cache-status', 'cf-visitor', 'cf-chl'],
                'cookies' => ['cf_clearance', '__cfduid'],
                'content' => []
            ],
            'CloudFront' => [
                'headers' => ['x-amz-cf-id', 'x-amz-cf-cache-status', 'x-cdn'],
                'cookies' => [],
                'content' => []
            ],
            'Imperva' => [
                'headers' => ['x-iinfo', 'x-cdn', 'x-incapsula-request-id'],
                'cookies' => ['visid_incap_', 'incap_ses_', 'imperva_'],
                'content' => ['imperva', 'incapsula']
            ],
            'Fastly' => [
                'headers' => ['fastly-', 'x-serve-time','fastly-io', 'fastly-cache-hit'],
                'cookies' => [],
                'content' => []
            ],
            'Akamai' => [
                'headers' => ['x-akamai-', 'akamai-request-'],
                'cookies' => [],
                'content' => []
            ],
            'StackPath' => [
                'headers' => ['x-stackpath-cache', 'x-stackpath-cdn', 'x-stackpath-id'],
                'cookies' => [],
                'content' => []
            ],
            'reCAPTCHA' => [
                'headers' => ['content-security-policy-report-only'=>'https://www.google.com/recaptcha/'],
                'cookies' => ['_GRECAPTCHA'], // Cookie específica de reCAPTCHA
                'content' => [
                    'recaptcha/api.js', // Script de integración de reCAPTCHA
                    'recaptcha.net'
                ]
            ],
            'Generic CAPTCHA' => [
                'headers' => [],
                'cookies' => [],
                'content' => [
                    'Please verify you are a human', // Mensaje genérico de CAPTCHA
                    'captcha/api.js' // Scripts relacionados con CAPTCHA
                ]
            ]
        ];

        $detectedPlatforms = [];

        // Verificación de firmas
        foreach ($securitySignatures as $platform => $signatures) {
            $headerMatch = false;
            $contentMatch = false;

            // Verifica encabezados con coincidencias exactas o parciales
            foreach ($signatures['headers'] as $headerKey => $headerValue) {
                if (is_int($headerKey)) { // Claves simples en array
                    if (array_key_exists($headerValue, $headers)) {
                        $headerMatch = true;
                        break;
                    }
                } elseif (
                    isset($headers[$headerKey]) &&
                    stripos($headers[$headerKey], $headerValue) !== false
                ) {
                    $headerMatch = true;
                    break;
                }
            }

            // Verifica contenido con búsqueda en lista
            if (!empty($signatures['content'])) {
                $contentMatch = self::multiSearch([$content], $signatures['content']);
            }

            // Agrega la plataforma detectada si se encuentra coincidencia
            if ($headerMatch || $contentMatch) {
                $detectedPlatforms[] = $platform;
            }
        }

        return $detectedPlatforms ?: ['Not Found'];
    }

    private static function detectECommerce(string $content, array $headers): array {
        $headers = self::normalizeHeaders($headers); // Asegura claves únicas
        $ecommercePlatforms = [
            'Shopify' => [
                'headers' => ['shopify', 'cdn.shopify', 'x-shopify'],
                'content' => ['Shopify.shop', 'shopify-buy', 'Shopify.theme', 'cdn.shopify.com']
            ],
            'WooCommerce' => [
                'headers' => ['woocommerce', 'wp-content'],
                'content' => ['woocommerce_cart_hash', 'woocommerce_items_in_cart', '/wp-content/plugins/woocommerce/']
            ],
            'Magento' => [
                'headers' => ['magento', 'x-magento'],
                'content' => ['x-magento-init', 'catalogsearch', 'Magento_Ui']
            ],
            'Prestashop' => [
                'headers' => ['prestashop', 'ps_'],
                'content' => ['ps_shoppingcart', 'prestashop', '/modules/ps_']
            ],
            'BigCommerce' => [
                'headers' => ['bigcommerce', 'cdn.bigcommerce'],
                'content' => ['bigcommerce.com', 'bc-sdk', 'stencil-', 'cdn.bigcommerce.com']
            ],
            'OpenCart' => [
                'headers' => ['opencart'],
                'content' => ['route=product', 'opencart', 'product/category', 'catalog/view']
            ],
            'Volusion' => [
                'headers' => ['x-powered-by' => 'Volusion'],
                'content' => [] // Nada que buscar en el contenido
            ]
        ];

        $detectedPlatforms = [];
        foreach ($ecommercePlatforms as $platform => $signatures) {
            $headerMatch = false;
            $contentMatch = false;

            // Verifica encabezados
            foreach ($signatures['headers'] as $headerKey => $headerValue) {
                foreach ($headers as $responseKey => $responseValue) {
                    if (is_int($headerKey)) { // Caso genérico (sin clave-valor)
                        if (stripos($responseKey, $headerValue) !== false || stripos($responseValue, $headerValue) !== false) {
                            $headerMatch = true;
                            break 2;
                        }
                    } elseif (strtolower($headerKey) === strtolower($responseKey) && stripos($responseValue, $headerValue) !== false) {
                        $headerMatch = true;
                        break 2;
                    }
                }
            }

            // Verifica contenido
            $contentMatch = self::multiSearch([$content], $signatures['content']);

            if ($headerMatch || $contentMatch) {
                $detectedPlatforms[] = $platform;
            }
        }

        return $detectedPlatforms ?: ['Not Found'];
    }

    public static function analyze($response, $link): string {
        $headers = self::normalizeHeaders($response->headers->response ?? []);
        $cookies = $headers['set-cookie'] ?? '';
        $content = $response->body ?? '';

        // Elimina duplicados en el encabezado "Server"
        if (isset($headers['server'])) {
            $headers['server'] = implode(',', array_unique(explode(',', $headers['server'])));
        }

        $cookiesArray = is_string($cookies) ? explode('; ', $cookies) : (array)$cookies;

        return json_encode([
            'Payments' => self::detectPayments($content, $headers),
            'Securities' => self::detectSecurities($headers, implode('; ', $cookiesArray), $content),
            'eCommerce' => self::detectECommerce($content, $headers),
            'Server' => [
                'Hostname' => parse_url($link, PHP_URL_HOST),
                'Server' => $headers['server'] ?? 'Unknown',
                'Status' => $response->code ?? 'Unknown'
            ]
        ], JSON_PRETTY_PRINT);
    }
}