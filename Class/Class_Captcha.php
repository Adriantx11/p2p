<?php

class CapSolver
{
    private $apiKey;
    private $apiUrl = 'https://api.capsolver.com';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Crear una tarea en CapSolver.
     *
     * @param string $taskType El tipo de tarea (ej. ReCaptchaV2TaskProxyless).
     * @param array $taskData Los datos necesarios para la tarea.
     * @return string|null El ID de la tarea creada.
     */
    private function createTask($taskType, array $taskData)
    {
        $payload = [
            'clientKey' => $this->apiKey,
            'task' => array_merge(['type' => $taskType], $taskData)
        ];

        $response = $this->makeRequest('/createTask', $payload);

        if (isset($response['errorId']) && $response['errorId'] === 0) {
            return $response['taskId'];
        }

        throw new Exception('Error al crear la tarea: ' . ($response['errorDescription'] ?? 'Unknown error'));
    }

    /**
     * Obtener la solución para una tarea.
     *
     * @param string $taskId El ID de la tarea.
     * @return array|null La solución del captcha.
     */
    private function getTaskResult($taskId)
    {
        $payload = [
            'clientKey' => $this->apiKey,
            'taskId' => $taskId
        ];

        while (true) {
            $response = $this->makeRequest('/getTaskResult', $payload);

            if (isset($response['status']) && $response['status'] === 'ready') {
                return $response['solution'];
            }

            if (isset($response['errorId']) && $response['errorId'] !== 0) {
                throw new Exception('Error al obtener la solución: ' . ($response['errorDescription'] ?? 'Unknown error'));
            }

            sleep(3); // Esperar antes de volver a intentar
        }
    }

    /**
     * Resolver un ReCaptcha V2.
     *
     * @param string $taskType Tipo de tarea (ej. ReCaptchaV2TaskProxyless).
     * @param array $taskData Datos necesarios para la tarea.
     * @return array La solución del captcha.
     */
    public function recaptchav2($taskType, array $taskData)
    {
        $taskId = $this->createTask($taskType, $taskData);
        return $this->getTaskResult($taskId);
    }

    /**
     * Realizar una solicitud HTTP.
     *
     * @param string $endpoint El endpoint de la API.
     * @param array $payload Los datos a enviar.
     * @return array|null La respuesta decodificada.
     */
    private function makeRequest($endpoint, array $payload)
    {
        $ch = curl_init($this->apiUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            throw new Exception("Error en la solicitud: HTTP {$httpCode}");
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
