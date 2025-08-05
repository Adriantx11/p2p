<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-search me-2 text-primary"></i>
                    Credit Card Checker
                </h1>
                <p class="text-muted mb-0">Verificar tarjetas de crédito con múltiples gateways</p>
            </div>
            <div>
                <span class="badge bg-success fs-6">
                    <i class="fas fa-shield-alt me-1"></i>
                    Seguro
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Checker Form -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Verificar Tarjetas
                </h5>
            </div>
            <div class="card-body">
                <form id="checkerForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gateway" class="form-label">Gateway de Pago</label>
                            <select class="form-select" id="gateway" name="gateway" required>
                                <option value="">Seleccionar Gateway...</option>
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                                <option value="braintree">Braintree</option>
                                <option value="AuthNet">Authorize.net</option>
                                <option value="payeezy">Payeezy</option>
                                <option value="b3">B3</option>
                                <option value="bluepay">BluePay</option>
                                <option value="cardknox">Cardknox</option>
                                <option value="cyber">CyberSource</option>
                                <option value="heartlandportico">Heartland Portico</option>
                                <option value="payflow">PayFlow</option>
                                <option value="recurly">Recurly</option>
                                <option value="spreedly">Spreedly</option>
                                <option value="usaepay">USAePay</option>
                                <option value="vbv">VBV</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Monto (USD)</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="1" min="1" max="100" step="0.01">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cards" class="form-label">Tarjetas de Crédito</label>
                        <textarea class="form-control" id="cards" name="cards" rows="10" 
                                  placeholder="Formato: 4111111111111111|12|2025|123
Una tarjeta por línea (máximo 50 tarjetas)" required></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Formato: NÚMERO|MES|AÑO|CVV (ejemplo: 4111111111111111|12|2025|123)
                        </div>
                    </div>
                    
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-play me-2"></i>
                                Iniciar Verificación
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" onclick="clearForm()">
                                <i class="fas fa-trash me-2"></i>
                                Limpiar
                            </button>
                        </div>
                        <div>
                            <small class="text-muted">
                                <span id="cardCount">0</span> tarjetas detectadas
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Gateways Disponibles</h6>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge bg-success">32 Activos</span>
                        <span class="badge bg-info">PayPal</span>
                        <span class="badge bg-info">Stripe</span>
                        <span class="badge bg-info">Braintree</span>
                        <span class="badge bg-secondary">+29 más</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6>Límites</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>Máximo 50 tarjetas por verificación</li>
                        <li><i class="fas fa-check text-success me-2"></i>Rate limit: 60 req/min</li>
                        <li><i class="fas fa-check text-success me-2"></i>Timeout: 30 segundos</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6>Formatos Soportados</h6>
                    <div class="bg-light p-2 rounded">
                        <code class="small">
                            4111111111111111|12|2025|123<br>
                            5555555555554444|01|2024|456<br>
                            378282246310005|06|2026|789
                        </code>
                    </div>
                </div>
                
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Aviso:</strong> Solo para fines educativos y testing. No usar con tarjetas reales sin autorización.
                </div>
            </div>
        </div>
        
        <!-- Quick Test Cards -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-vial me-2"></i>
                    Tarjetas de Prueba
                </h6>
            </div>
            <div class="card-body">
                <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="loadTestCards('visa')">
                    <i class="fab fa-cc-visa me-2"></i>VISA Test Cards
                </button>
                <button class="btn btn-outline-success btn-sm w-100 mb-2" onclick="loadTestCards('mastercard')">
                    <i class="fab fa-cc-mastercard me-2"></i>MasterCard Test Cards
                </button>
                <button class="btn btn-outline-info btn-sm w-100" onclick="loadTestCards('amex')">
                    <i class="fab fa-cc-amex me-2"></i>AMEX Test Cards
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Results Section -->
<div class="row mt-4" id="resultsSection" style="display: none;">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list-ul me-2"></i>
                    Resultados de Verificación
                </h5>
                <div>
                    <button class="btn btn-outline-success btn-sm" onclick="exportResults('csv')">
                        <i class="fas fa-download me-1"></i>CSV
                    </button>
                    <button class="btn btn-outline-primary btn-sm" onclick="exportResults('json')">
                        <i class="fas fa-download me-1"></i>JSON
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Progress Bar -->
                <div id="progressContainer" class="mb-4" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Progreso de verificación</span>
                        <span id="progressText">0/0</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                
                <!-- Summary Stats -->
                <div class="row mb-4" id="summaryStats" style="display: none;">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-success mb-0" id="approvedCount">0</h4>
                            <small class="text-muted">Aprobadas</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-danger mb-0" id="declinedCount">0</h4>
                            <small class="text-muted">Declinadas</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-warning mb-0" id="errorCount">0</h4>
                            <small class="text-muted">Errores</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-info mb-0" id="avgTime">0ms</h4>
                            <small class="text-muted">Tiempo Promedio</small>
                        </div>
                    </div>
                </div>
                
                <!-- Results Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="resultsTable">
                        <thead>
                            <tr>
                                <th>Tarjeta</th>
                                <th>Estado</th>
                                <th>Mensaje</th>
                                <th>Tiempo (ms)</th>
                                <th>Gateway</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentResults = [];

// Count cards as user types
document.getElementById('cards').addEventListener('input', function() {
    const cards = this.value.trim().split('\n').filter(line => line.trim() !== '');
    document.getElementById('cardCount').textContent = cards.length;
});

// Form submission
document.getElementById('checkerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const cards = document.getElementById('cards').value.trim().split('\n').filter(line => line.trim() !== '');
    
    if (cards.length === 0) {
        showAlert('warning', 'Por favor ingresa al menos una tarjeta.');
        return;
    }
    
    if (cards.length > 50) {
        showAlert('danger', 'Máximo 50 tarjetas por verificación.');
        return;
    }
    
    // Show results section and progress
    document.getElementById('resultsSection').style.display = 'block';
    document.getElementById('progressContainer').style.display = 'block';
    document.getElementById('summaryStats').style.display = 'none';
    
    // Clear previous results
    document.getElementById('resultsTableBody').innerHTML = '';
    currentResults = [];
    
    // Update progress
    updateProgress(0, cards.length);
    
    // Disable form
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verificando...';
    
    try {
        const response = await fetch('/api/checker', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            currentResults = result.data;
            displayResults(result.data);
            showSummaryStats(result.data);
            showAlert('success', `Verificación completada. ${result.data.length} tarjetas procesadas.`);
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', 'Error de conexión. Inténtalo de nuevo.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-play me-2"></i>Iniciar Verificación';
        document.getElementById('progressContainer').style.display = 'none';
    }
});

function updateProgress(current, total) {
    const percentage = (current / total) * 100;
    document.getElementById('progressBar').style.width = percentage + '%';
    document.getElementById('progressText').textContent = `${current}/${total}`;
}

function displayResults(results) {
    const tbody = document.getElementById('resultsTableBody');
    tbody.innerHTML = '';
    
    results.forEach(result => {
        const row = document.createElement('tr');
        const statusClass = getStatusClass(result.status);
        const statusIcon = getStatusIcon(result.status);
        
        row.innerHTML = `
            <td><code>${result.card}</code></td>
            <td><span class="badge ${statusClass}">${statusIcon} ${result.status.toUpperCase()}</span></td>
            <td>${result.message}</td>
            <td>${result.response_time}ms</td>
            <td><span class="badge bg-secondary">${document.getElementById('gateway').value.toUpperCase()}</span></td>
        `;
        
        tbody.appendChild(row);
    });
}

function showSummaryStats(results) {
    const approved = results.filter(r => r.status === 'approved').length;
    const declined = results.filter(r => r.status === 'declined').length;
    const errors = results.filter(r => r.status === 'error').length;
    const avgTime = results.reduce((sum, r) => sum + (r.response_time || 0), 0) / results.length;
    
    document.getElementById('approvedCount').textContent = approved;
    document.getElementById('declinedCount').textContent = declined;
    document.getElementById('errorCount').textContent = errors;
    document.getElementById('avgTime').textContent = Math.round(avgTime) + 'ms';
    
    document.getElementById('summaryStats').style.display = 'block';
}

function getStatusClass(status) {
    const classes = {
        'approved': 'bg-success',
        'declined': 'bg-danger',
        'insufficient_funds': 'bg-warning',
        'invalid_card': 'bg-secondary',
        'error': 'bg-dark'
    };
    return classes[status] || 'bg-secondary';
}

function getStatusIcon(status) {
    const icons = {
        'approved': '✅',
        'declined': '❌',
        'insufficient_funds': '💳',
        'invalid_card': '⚠️',
        'error': '🔴'
    };
    return icons[status] || '❓';
}

function clearForm() {
    document.getElementById('checkerForm').reset();
    document.getElementById('cardCount').textContent = '0';
    document.getElementById('resultsSection').style.display = 'none';
    currentResults = [];
}

function loadTestCards(type) {
    const testCards = {
        'visa': [
            '4111111111111111|12|2025|123',
            '4012888888881881|01|2024|456',
            '4222222222222|06|2026|789'
        ],
        'mastercard': [
            '5555555555554444|12|2025|123',
            '5105105105105100|01|2024|456',
            '5200828282828210|06|2026|789'
        ],
        'amex': [
            '378282246310005|12|2025|1234',
            '371449635398431|01|2024|5678',
            '378734493671000|06|2026|9012'
        ]
    };
    
    document.getElementById('cards').value = testCards[type].join('\n');
    document.getElementById('cardCount').textContent = testCards[type].length;
}

function exportResults(format) {
    if (currentResults.length === 0) {
        showAlert('warning', 'No hay resultados para exportar.');
        return;
    }
    
    let content, filename, mimeType;
    
    if (format === 'csv') {
        content = 'Tarjeta,Estado,Mensaje,Tiempo,Gateway\n';
        content += currentResults.map(r => 
            `"${r.card}","${r.status}","${r.message}","${r.response_time}","${document.getElementById('gateway').value}"`
        ).join('\n');
        filename = 'checker_results.csv';
        mimeType = 'text/csv';
    } else {
        content = JSON.stringify(currentResults, null, 2);
        filename = 'checker_results.json';
        mimeType = 'application/json';
    }
    
    const blob = new Blob([content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alerts-container');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertContainer.appendChild(alert);
    
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>