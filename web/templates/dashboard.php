<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                    Dashboard
                </h1>
                <p class="text-muted mb-0">Bienvenido de vuelta, <?= $this->escape($user['username']) ?></p>
            </div>
            <div>
                <span class="badge bg-success fs-6">
                    <i class="fas fa-circle me-1"></i>
                    Sistema Online
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Gateways</h5>
                        <h2 class="mb-0">32</h2>
                        <small class="opacity-75">Disponibles</small>
                    </div>
                    <div class="display-4 opacity-50">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Verificaciones</h5>
                        <h2 class="mb-0">1,247</h2>
                        <small class="opacity-75">Este mes</small>
                    </div>
                    <div class="display-4 opacity-50">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Herramientas</h5>
                        <h2 class="mb-0">4</h2>
                        <small class="opacity-75">Activas</small>
                    </div>
                    <div class="display-4 opacity-50">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Usuarios</h5>
                        <h2 class="mb-0">156</h2>
                        <small class="opacity-75">Registrados</small>
                    </div>
                    <div class="display-4 opacity-50">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="/checker" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-4 text-decoration-none">
                            <i class="fas fa-search fa-2x mb-3"></i>
                            <h6 class="mb-1">Verificar Tarjetas</h6>
                            <small class="text-muted">Checker de CCs</small>
                        </a>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="/tools" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center align-items-center py-4 text-decoration-none">
                            <i class="fas fa-tools fa-2x mb-3"></i>
                            <h6 class="mb-1">Herramientas</h6>
                            <small class="text-muted">BIN, Gen, etc.</small>
                        </a>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <button class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center align-items-center py-4" onclick="showBinLookup()">
                            <i class="fas fa-search-plus fa-2x mb-3"></i>
                            <h6 class="mb-1">BIN Lookup</h6>
                            <small class="text-muted">Consulta rápida</small>
                        </button>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <button class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center py-4" onclick="generateAddress()">
                            <i class="fas fa-map-marker-alt fa-2x mb-3"></i>
                            <h6 class="mb-1">Generar Dirección</h6>
                            <small class="text-muted">Address Gen</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & System Status -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Actividad Reciente
                </h5>
                <small class="text-muted">Últimas 24 horas</small>
            </div>
            <div class="card-body">
                <div class="activity-timeline">
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="activity-icon bg-success text-white rounded-circle me-3">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Verificación completada</h6>
                            <p class="text-muted mb-0 small">50 tarjetas verificadas con gateway PayPal</p>
                            <small class="text-muted">hace 2 horas</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="activity-icon bg-info text-white rounded-circle me-3">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">BIN Lookup realizado</h6>
                            <p class="text-muted mb-0 small">Consultado BIN 411111</p>
                            <small class="text-muted">hace 3 horas</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="activity-icon bg-warning text-white rounded-circle me-3">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Dirección generada</h6>
                            <p class="text-muted mb-0 small">Generada dirección para US</p>
                            <small class="text-muted">hace 5 horas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-server me-2"></i>
                    Estado del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span>Base de Datos</span>
                    <span class="badge bg-success">Online</span>
                </div>
                
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span>Gateways</span>
                    <span class="badge bg-success">32/32</span>
                </div>
                
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span>API Status</span>
                    <span class="badge bg-success">Operativo</span>
                </div>
                
                <div class="status-item d-flex justify-content-between align-items-center mb-3">
                    <span>Última actualización</span>
                    <span class="text-muted small">hace 1 min</span>
                </div>
            </div>
        </div>
        
        <!-- Quick BIN Lookup -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-search me-2"></i>
                    BIN Lookup Rápido
                </h6>
            </div>
            <div class="card-body">
                <form id="quickBinForm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="quickBin" placeholder="Ingresa BIN..." maxlength="8">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <div id="binResult" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- BIN Lookup Modal -->
<div class="modal fade" id="binModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search me-2"></i>
                    BIN Lookup
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="binLookupForm">
                    <div class="mb-3">
                        <label for="binInput" class="form-label">BIN (6-8 dígitos)</label>
                        <input type="text" class="form-control" id="binInput" maxlength="8" placeholder="411111">
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                </form>
                <div id="binModalResult"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="performBinLookup()">
                    <i class="fas fa-search me-2"></i>Consultar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Quick BIN lookup
document.getElementById('quickBinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const bin = document.getElementById('quickBin').value.trim();
    if (bin.length >= 6) {
        await performQuickBinLookup(bin);
    }
});

async function performQuickBinLookup(bin) {
    const resultDiv = document.getElementById('binResult');
    resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Consultando...</div>';
    
    try {
        const formData = new FormData();
        formData.append('bin', bin);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch('/api/tools/bin', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <strong>${data.brand}</strong> ${data.type}<br>
                    <small>${data.bank} - ${data.country}</small>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (error) {
        resultDiv.innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
    }
}

function showBinLookup() {
    new bootstrap.Modal(document.getElementById('binModal')).show();
}

async function performBinLookup() {
    const bin = document.getElementById('binInput').value.trim();
    const resultDiv = document.getElementById('binModalResult');
    
    if (bin.length < 6) {
        resultDiv.innerHTML = '<div class="alert alert-warning">BIN debe tener al menos 6 dígitos</div>';
        return;
    }
    
    resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Consultando...</div>';
    
    try {
        const formData = new FormData();
        formData.append('bin', bin);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch('/api/tools/bin', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            resultDiv.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">${data.brand} ${data.type}</h6>
                        <p class="card-text">
                            <strong>Banco:</strong> ${data.bank}<br>
                            <strong>País:</strong> ${data.country} (${data.country_code})<br>
                            <strong>Nivel:</strong> ${data.level}
                        </p>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (error) {
        resultDiv.innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
    }
}

async function generateAddress() {
    try {
        const formData = new FormData();
        formData.append('country', 'US');
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const response = await fetch('/api/tools/gen', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            alert(`Dirección generada:\n${data.address}\n${data.city}, ${data.state} ${data.zip}`);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error de conexión');
    }
}
</script>