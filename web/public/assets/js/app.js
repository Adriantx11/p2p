// Siesta Checker Web - Main JavaScript

// Global app object
window.SiestaChecker = {
    config: {
        apiUrl: '/api',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',
        version: '1.0.0'
    },
    
    // Initialize app
    init() {
        this.setupCSRFToken();
        this.setupGlobalErrorHandler();
        this.setupLoadingOverlay();
        this.setupTooltips();
        this.setupAutoSave();
        
        console.log('Siesta Checker Web initialized');
    },
    
    // Setup CSRF token for all AJAX requests
    setupCSRFToken() {
        // Add CSRF token to all fetch requests
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            if (options.method && options.method.toUpperCase() === 'POST') {
                if (options.body instanceof FormData) {
                    options.body.append('csrf_token', SiestaChecker.config.csrfToken);
                } else if (options.headers && options.headers['Content-Type'] === 'application/json') {
                    const body = JSON.parse(options.body || '{}');
                    body.csrf_token = SiestaChecker.config.csrfToken;
                    options.body = JSON.stringify(body);
                }
            }
            return originalFetch(url, options);
        };
    },
    
    // Global error handler
    setupGlobalErrorHandler() {
        window.addEventListener('error', (event) => {
            console.error('Global error:', event.error);
            this.showAlert('danger', 'Ha ocurrido un error inesperado. Por favor, recarga la página.');
        });
        
        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);
            this.showAlert('warning', 'Error de conexión. Verifica tu conexión a internet.');
        });
    },
    
    // Loading overlay management
    setupLoadingOverlay() {
        this.loadingOverlay = document.getElementById('loading-overlay');
    },
    
    showLoading(message = 'Cargando...') {
        if (this.loadingOverlay) {
            this.loadingOverlay.querySelector('p').textContent = message;
            this.loadingOverlay.classList.remove('d-none');
        }
    },
    
    hideLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.classList.add('d-none');
        }
    },
    
    // Setup tooltips
    setupTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Auto-save functionality for forms
    setupAutoSave() {
        const forms = document.querySelectorAll('[data-autosave]');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.autoSaveForm(form);
                });
            });
        });
    },
    
    autoSaveForm(form) {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        localStorage.setItem(`autosave_${form.id}`, JSON.stringify(data));
    },
    
    restoreForm(formId) {
        const saved = localStorage.getItem(`autosave_${formId}`);
        if (saved) {
            const data = JSON.parse(saved);
            const form = document.getElementById(formId);
            if (form) {
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = data[key];
                    }
                });
            }
        }
    },
    
    // Alert system
    showAlert(type, message, duration = 5000) {
        const alertContainer = document.getElementById('alerts-container');
        if (!alertContainer) return;
        
        const alertId = 'alert_' + Date.now();
        const alert = document.createElement('div');
        alert.id = alertId;
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${this.getAlertIcon(type)} ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                const alertElement = document.getElementById(alertId);
                if (alertElement) {
                    const bsAlert = new bootstrap.Alert(alertElement);
                    bsAlert.close();
                }
            }, duration);
        }
        
        return alertId;
    },
    
    getAlertIcon(type) {
        const icons = {
            'success': '<i class="fas fa-check-circle me-2"></i>',
            'danger': '<i class="fas fa-exclamation-triangle me-2"></i>',
            'warning': '<i class="fas fa-exclamation-circle me-2"></i>',
            'info': '<i class="fas fa-info-circle me-2"></i>'
        };
        return icons[type] || '';
    },
    
    // API helper methods
    async apiCall(endpoint, options = {}) {
        const url = this.config.apiUrl + endpoint;
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, finalOptions);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'API Error');
            }
            
            return data;
        } catch (error) {
            console.error('API Call Error:', error);
            throw error;
        }
    },
    
    // Utility functions
    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    },
    
    formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return `${hours}h ${minutes}m ${secs}s`;
        } else if (minutes > 0) {
            return `${minutes}m ${secs}s`;
        } else {
            return `${secs}s`;
        }
    },
    
    validateCreditCard(cardNumber) {
        // Remove spaces and dashes
        cardNumber = cardNumber.replace(/[\s\-]/g, '');
        
        // Check if only digits
        if (!/^\d+$/.test(cardNumber)) {
            return false;
        }
        
        // Check length
        if (cardNumber.length < 13 || cardNumber.length > 19) {
            return false;
        }
        
        // Luhn algorithm
        let sum = 0;
        let alternate = false;
        
        for (let i = cardNumber.length - 1; i >= 0; i--) {
            let digit = parseInt(cardNumber.charAt(i));
            
            if (alternate) {
                digit *= 2;
                if (digit > 9) {
                    digit = (digit % 10) + 1;
                }
            }
            
            sum += digit;
            alternate = !alternate;
        }
        
        return (sum % 10) === 0;
    },
    
    getCardType(cardNumber) {
        const patterns = {
            'visa': /^4[0-9]{12}(?:[0-9]{3})?$/,
            'mastercard': /^5[1-5][0-9]{14}$/,
            'amex': /^3[47][0-9]{13}$/,
            'discover': /^6(?:011|5[0-9]{2})[0-9]{12}$/,
            'diners': /^3[0689][0-9]{11}$/,
            'jcb': /^(?:2131|1800|35\d{3})\d{11}$/
        };
        
        cardNumber = cardNumber.replace(/[\s\-]/g, '');
        
        for (const [type, pattern] of Object.entries(patterns)) {
            if (pattern.test(cardNumber)) {
                return type;
            }
        }
        
        return 'unknown';
    },
    
    maskCard(cardNumber) {
        cardNumber = cardNumber.replace(/[\s\-]/g, '');
        if (cardNumber.length < 10) return cardNumber;
        
        const first = cardNumber.substring(0, 6);
        const last = cardNumber.substring(cardNumber.length - 4);
        const middle = '*'.repeat(cardNumber.length - 10);
        
        return first + middle + last;
    },
    
    // Export functionality
    exportToCSV(data, filename = 'export.csv') {
        if (!data || data.length === 0) {
            this.showAlert('warning', 'No hay datos para exportar.');
            return;
        }
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => 
                headers.map(header => 
                    JSON.stringify(row[header] || '')
                ).join(',')
            )
        ].join('\n');
        
        this.downloadFile(csvContent, filename, 'text/csv');
    },
    
    exportToJSON(data, filename = 'export.json') {
        if (!data) {
            this.showAlert('warning', 'No hay datos para exportar.');
            return;
        }
        
        const jsonContent = JSON.stringify(data, null, 2);
        this.downloadFile(jsonContent, filename, 'application/json');
    },
    
    downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    },
    
    // Copy to clipboard
    copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                this.showAlert('success', 'Copiado al portapapeles', 2000);
            }).catch(() => {
                this.fallbackCopyToClipboard(text);
            });
        } else {
            this.fallbackCopyToClipboard(text);
        }
    },
    
    fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            this.showAlert('success', 'Copiado al portapapeles', 2000);
        } catch (err) {
            this.showAlert('danger', 'No se pudo copiar al portapapeles');
        }
        
        document.body.removeChild(textArea);
    },
    
    // Theme management
    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    },
    
    getTheme() {
        return localStorage.getItem('theme') || 'light';
    },
    
    toggleTheme() {
        const currentTheme = this.getTheme();
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
        return newTheme;
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    SiestaChecker.init();
    
    // Apply saved theme
    SiestaChecker.setTheme(SiestaChecker.getTheme());
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Auto-hide alerts after some time
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 10000);
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+K or Cmd+K for quick search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('#quickBin');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Esc to close modals
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        });
    }
});

// Export global functions for backward compatibility
window.showAlert = SiestaChecker.showAlert.bind(SiestaChecker);
window.showLoading = SiestaChecker.showLoading.bind(SiestaChecker);
window.hideLoading = SiestaChecker.hideLoading.bind(SiestaChecker);
window.copyToClipboard = SiestaChecker.copyToClipboard.bind(SiestaChecker);