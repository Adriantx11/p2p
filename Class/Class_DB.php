<?php

class DB {
    private $config;
    private $conn;
    private $bot;
    private $language;

    public function __construct($config, $bot) {
        $this->config = $config;
        $this->bot = $bot;
        $this->connect();
        
    }
    
    private function connect() {
        // Crear el directorio de la base de datos si no existe
        $dbDir = dirname($this->config['db']['path']);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        
        try {
            $this->conn = new SQLite3($this->config['db']['path']);
            $this->conn->enableExceptions(true);
            
            // Crear las tablas si no existen
            $this->createTables();
            
        } catch (Exception $e) {
            $message = "<b>🛑 SQLite DB connection Failed!\n\nError: " . $e->getMessage() . "\nPath: " . $this->config['db']['path'] . "</b>";
            $this->bot->callApi('sendmessage',[
                'chat_id'=>$this->config['adminID'],
                'text'=>$message,
                'parse_mode'=>'html']);
            $this->logSummary($message);
        }
    }
    
    private function createTables() {
        // Tabla users
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id TEXT UNIQUE NOT NULL,
                username TEXT,
                status TEXT DEFAULT 'Free User',
                creditos INTEGER DEFAULT 0,
                expiration_date TEXT,
                antispam INTEGER DEFAULT 60,
                language TEXT DEFAULT 'es',
                last_command_time INTEGER,
                command_count INTEGER DEFAULT 0,
                command_intervals TEXT,
                warns INTEGER DEFAULT 0,
                ban_expiration TEXT,
                last_commands TEXT,
                command_patterns TEXT
            )
        ");
        
        // Tabla gateway
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS gateway (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                gatecmd TEXT UNIQUE NOT NULL,
                gatename TEXT NOT NULL,
                status TEXT DEFAULT 'OFF',
                comment TEXT,
                auth INTEGER DEFAULT 0,
                charged INTEGER DEFAULT 0,
                ccn INTEGER DEFAULT 0,
                '3d' INTEGER DEFAULT 0,
                mass INTEGER DEFAULT 0,
                price INTEGER DEFAULT 0,
                premium INTEGER DEFAULT 0,
                credits INTEGER DEFAULT 0
            )
        ");
        
        // Tabla keyuser
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS keyuser (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                clave TEXT UNIQUE NOT NULL,
                status TEXT DEFAULT 'ACTIVE',
                plan TEXT,
                planexpiry TEXT,
                seller TEXT
            )
        ");
        
        // Tabla vSiesta
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS vSiesta (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                version_number TEXT NOT NULL,
                update_date TEXT NOT NULL,
                description TEXT,
                team_users TEXT,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Tabla rewards
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS rewards (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                availability_date TEXT,
                description TEXT
            )
        ");
        
        // Tabla groups
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS groups (
                id TEXT PRIMARY KEY,
                name TEXT,
                status TEXT,
                fecha_vencimiento TEXT,
                premium INTEGER DEFAULT 0
            )
        ");
        
        // Tabla admins
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id TEXT UNIQUE NOT NULL,
                username TEXT
            )
        ");
        
        // Tabla owners
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS owners (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id TEXT UNIQUE NOT NULL,
                username TEXT
            )
        ");
        
        // Tabla tools
        $this->conn->exec("
            CREATE TABLE IF NOT EXISTS tools (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tool_name TEXT UNIQUE NOT NULL,
                status TEXT DEFAULT 'inactive',
                comment TEXT
            )
        ");
    }

    private function countGatesByType($type) {
        $query = "SELECT COUNT(*) AS total FROM gateway WHERE $type = 1";
        $result = $this->conn->query($query);
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row['total'];
    }

    public function countTotalGates() {
        $query = "SELECT COUNT(*) AS total_gates FROM gateway";
        $result = $this->conn->query($query);
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row['total_gates'];
    }

    public function getGatesSummary() {
        $types = ['auth', 'charged', 'ccn', '3d', 'mass'];
        $summary = [];

        foreach ($types as $type) {
            $summary[$type] = $this->countGatesByType($type);
        }

        $summary['total'] = $this->countTotalGates();
        return $summary;
    }

    public function middlewareCheckUserRegistration($userID, $username, $firstname) {
        // Verificar si el usuario ya está registrado
        if (!$this->isUserRegistered($userID)) {
            $this->addUser($userID, $username, $firstname);
        }
    }

    public function consultaClaim($username, $userId, $find) {
        $query = "SELECT * FROM users WHERE user_id=?";
        $stmt1 = $this->conn->prepare($query);
        $stmt1->bindValue(1, $userId, SQLITE3_TEXT);
        $result = $stmt1->execute();
    
        if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
            $stmt1->close();
    
            $keyQuery = "SELECT * FROM keyuser WHERE clave=? AND status='ACTIVE'";
            $stmt2 = $this->conn->prepare($keyQuery);
            $stmt2->bindValue(1, $find, SQLITE3_TEXT);
            $result = $stmt2->execute();
    
            if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
                $result->reset();
                $keyData = $result->fetchArray(SQLITE3_ASSOC);
                $plan = $keyData['plan'];
                $planexpiry = $keyData['planexpiry'];
                $seller = $keyData['seller'];
                $stmt2->close();
    
                // Obtener la fecha de expiración actual del usuario
                $currentExpirationQuery = "SELECT expiration_date FROM users WHERE user_id=?";
                $stmt4 = $this->conn->prepare($currentExpirationQuery);
                $stmt4->bindValue(1, $userId, SQLITE3_TEXT);
                $currentExpirationResult = $stmt4->execute();
                $currentExpirationData = $currentExpirationResult->fetchArray(SQLITE3_ASSOC);
                $currentExpirationDate = $currentExpirationData['expiration_date'];
                $stmt4->close();
    
                // Calcular la nueva fecha de expiración
                $currentDate = new DateTime();
                $expirationDate = new DateTime($planexpiry);
                $daysToAdd = $currentDate->diff($expirationDate)->days + 1; // Ajustar para incluir el día actual
    
                if ($currentExpirationDate) {
                    $currentExpiration = new DateTime($currentExpirationDate);
                    $currentExpiration->modify("+$daysToAdd days");
                    $newExpirationDate = $currentExpiration->format('Y-m-d H:i:s');
                } else {
                    $newExpirationDate = $planexpiry;
                }
    
                $updateUserQuery = "UPDATE users SET status='premium', expiration_date=? WHERE user_id=?";
                $stmt3 = $this->conn->prepare($updateUserQuery);
                $stmt3->bindValue(1, $newExpirationDate, SQLITE3_TEXT);
                $stmt3->bindValue(2, $userId, SQLITE3_TEXT);
                $stmt3->execute();
                $stmt3->close();
    
                $updateKeyQuery = "UPDATE keyuser SET status='In Use' WHERE clave=?";
                $stmt6 = $this->conn->prepare($updateKeyQuery);
                $stmt6->bindValue(1, $find, SQLITE3_TEXT);
                $stmt6->execute();
                $stmt6->close();
    
                // Calcular los días restantes hasta la nueva expiración del plan
                $daysRemaining = $currentDate->diff(new DateTime($newExpirationDate))->days + 1; // Ajustar para incluir el día actual
    
                // Verificar si el plan ha expirado
                $planStatus = ($daysRemaining > 0) ? 'active' : 'expired';
    
                $this->logSummary("
[❃]━━━━━━━━━━━━[❃]
𝐊𝐄𝐘 𝐑𝐄𝐃𝐄𝐌𝐏𝐓𝐈𝐎𝐍: 𝐒𝐔𝐂𝐂𝐄𝐒𝐒
[❃]━━━━━━━━━━━━[❃]
🔑 Clave: <code>$find</code>
👤 Usuario: <code>@$username</code>
👤 id: <code>$userId</code>
📦 Plan: <code>$plan</code>
📆 Expiración: <code>$newExpirationDate</code>
🛒 Vendedor: <code>$seller</code>
[❃]━━━━━━━━━━━━[❃]");
    
                return [
                    'plan' => $plan,
                    'planexpiry' => $newExpirationDate,
                    'daysRemaining' => $daysRemaining,
                    'planStatus' => $planStatus
                ];
            } else {
                $stmt2->close();
                return false;
            }
        }
        return "error";
    }
    
    
    
    
    
    public function RandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function generateUniqueKey($expiry, $plantype, $sellerUsername, $keyNumber = null)
    {
        // Si no se pasa el número de claves, se establece por defecto a 1
        $keyNumber = $keyNumber ?? 1;
        $keys = [];
    
        try {
            // Calcular la diferencia de días entre la fecha actual y la fecha de vencimiento
            $expiryDate = date('Y-m-d', $expiry);
            $fechaActual = date('Y-m-d');
            $date1 = date("Y-m-d H:i:s");
            $date2 = new DateTime($expiryDate);
            $diff = date_diff(new DateTime($fechaActual), $date2);
            $dias = $diff->format('%R%a días'); // Este valor será el mismo para todas las claves
    
            // Generar un conjunto de claves únicas
            $generatedKeys = [];
            for ($i = 0; $i < $keyNumber; $i++) {
                // Generar la clave única
                $key = 'SiestaValidBot-' . $this->RandomString(4) . $this->RandomString(4) . $this->RandomString(4);
                $generatedKeys[] = $key;
            }
    
            // Verificar todas las claves generadas de una vez en la base de datos
            $placeholders = implode(',', array_fill(0, count($generatedKeys), '?'));
            $query = "SELECT clave FROM keyuser WHERE clave IN ($placeholders)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param(str_repeat('s', count($generatedKeys)), ...$generatedKeys);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Almacenar las claves existentes en un arreglo
            $existingKeys = [];
            while ($row = $result->fetch_assoc()) {
                $existingKeys[] = $row['clave'];
            }
            $stmt->close();
    
            // Filtrar las claves existentes
            $keysToInsert = array_diff($generatedKeys, $existingKeys);
    
            // Si no hay claves disponibles para insertar, lanzar un error
            if (empty($keysToInsert)) {
                throw new Exception("No se pueden generar claves únicas.");
            }
    
            // Insertar las claves únicas en la base de datos
            $expiryDateTime = date('Y-m-d H:i:s', $expiry);
            $SQL = "INSERT INTO keyuser (clave, status, plan, planexpiry, seller) VALUES (?, 'ACTIVE', ?, ?, ?)";
            $stmt = $this->conn->prepare($SQL);
            
            $insertedKeys = []; // Almacena las claves insertadas para el registro
            foreach ($keysToInsert as $key) {
                $stmt->bind_param("ssss", $key, $plantype, $expiryDateTime, $sellerUsername);
                $stmt->execute();
    
                if ($stmt->affected_rows === 0) {
                    // Error al insertar en la base de datos
                    throw new Exception("Error al insertar clave en la base de datos: " . $this->conn->error);
                }
    
                // Agregar la clave generada a un array
                $keys[] = [
                    'key' => $key,
                    'dias' => $dias // El mismo valor de días para todas las claves
                ];
    
                // Almacenar las claves insertadas para hacer el log después
                $insertedKeys[] = $key;
            }
    
            // Realizar el log de todas las claves generadas después de la inserción
            if (!empty($insertedKeys)) {
                $this->logSummary("
[❃]━━━━━━━━━━━━[❃]
𝑮𝒆𝒏𝒆𝒓𝒂𝒄𝒊ó𝒏 𝑫𝒆 𝑪𝒍𝒂𝒗𝒆 𝑮𝑬𝑵𝑬𝑹𝑨𝑫𝑨
[❃]━━━━━━━━━━━━[❃]
会 | 𝑫𝒂𝒕𝒆:  <code>$date1</code>
会 | 𝑮𝒆𝒏𝒆𝒓𝒂𝒅𝒐𝒓:  <code>$sellerUsername</code>
会 | 𝑪𝒍𝒂𝒗𝒆 𝑮𝒆𝒏𝒆𝒓𝒂𝒅𝒐𝒔:  <code>" . implode("\n", array_map(fn($key): string => "<code>$key</code>", $insertedKeys)). "</code>
会 | 𝑹𝒂𝒏𝒈𝒐: <code>$plantype</code>
会 | 𝑭𝒆𝒄𝒉𝒂 𝒅𝒆 𝑽𝒆𝒏𝒄𝒊𝒎𝒊𝒆𝒏𝒕𝒐: <code>$expiryDate</code>
会 | 𝑫𝒖𝒓𝒂𝒄𝒊ó𝒏: <code>$dias</code>
[❃]━━━━━━━━━━━━[❃]");
            }
    
            // Si se han generado claves, devolverlas
            return [
                'status' => 'success',
                'keys' => $keys,
                'selectedKey' => reset($keys)['key'] // Seleccionar la primera clave generada
            ];
        } catch (Exception $e) {
            // Manejar errores inesperados
            return [
                'status' => 'error',
                'message' => "Error inesperado: " . $e->getMessage()
            ];
        }
    }
    
    
    


    
    
    public function isUserRegistered($userID) {
        $query = "SELECT * FROM users WHERE user_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $userID, SQLITE3_TEXT);
        $result = $stmt->execute();
    
        return $result && $result->fetchArray(SQLITE3_ASSOC) !== false;
    }
    
    public function addUser($userID, $username, $firstname) {
        // Verificar si el usuario ya está registrado
        $query = "SELECT user_id FROM users WHERE user_id = ?";
        $stmt1 = $this->conn->prepare($query);
        $stmt1->bindValue(1, $userID, SQLITE3_TEXT);
        $result = $stmt1->execute();
        
        if ($result && !$result->fetchArray(SQLITE3_ASSOC)) {
            // El usuario no está registrado, lo registramos
            $displayName = !empty($username) ? "@$username" : $firstname;
            $insertQuery = "INSERT INTO users (user_id, username, status) VALUES (?, ?, 'Free User')";
            $stmt2 = $this->conn->prepare($insertQuery);
            $stmt2->bindValue(1, $userID, SQLITE3_TEXT);
            $stmt2->bindValue(2, $displayName, SQLITE3_TEXT);
            
            if ($stmt2->execute()) {
                // Registro exitoso
                $displayNameEscaped = htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); // Evitar interpretar como HTML
                $date1 = date("Y-m-d");
                $time = date("H:i:s");

                $this->logsummary("
[❃]━━━━━━━━━━━━[❃]
🆕 Usuario Registrado
[❃]━━━━━━━━━━━━[❃]
会 | Nuevo usuario: <code>$displayNameEscaped</code>
会 | ID: <code>$userID</code>
[❃]━━━━━━━━━━━━[❃]");

                $stmt1->close();
                $stmt2->close();
                return "new_registration";
            } else {
                // Error al ejecutar la inserción
                $error = $this->conn->lastErrorMsg();
                $stmt1->close();
                $stmt2->close();
                return "error: $error";
            }
        } else {
            // El usuario ya está registrado
            $stmt1->close();
            return "already_registered";
        }
    }
    

    public function logSummary($summary) {
        $this->bot->callApi('sendmessage',[
            'chat_id'=>$this->config['logsID'],
            'text'=>$summary,
            'parse_mode'=>'html']);
    }
    // Agrega una nueva actualización con descripción
// Añadir una nueva actualización con versión, fecha, descripción y usuarios de equipo
public function addUpdate($version, $date, $description, $team_users) {
    $stmt = $this->conn->prepare("INSERT INTO vSiesta (version_number, update_date, description, team_users, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
    if (!$stmt) {
        error_log("Error preparando consulta: " . $this->conn->lastErrorMsg());
        return false;
    }
    $stmt->bindValue(1, $version, SQLITE3_TEXT);
    $stmt->bindValue(2, $date, SQLITE3_TEXT);
    $stmt->bindValue(3, $description, SQLITE3_TEXT);
    $stmt->bindValue(4, $team_users, SQLITE3_TEXT);
    return $stmt->execute();
}

// Obtener las actualizaciones más recientes, con descripción y un límite opcional
public function getRecentUpdates($limit = 5) {
    $stmt = $this->conn->prepare("SELECT version_number, update_date, description FROM vSiesta ORDER BY update_date DESC LIMIT ?");
    if (!$stmt) {
        error_log("Error preparando consulta: " . $this->conn->lastErrorMsg());
        return [];
    }
    $stmt->bindValue(1, $limit, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $updates = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $updates[] = $row;
    }
    return $updates;
}

public function getGatewayInfo($gatewayName)
{
    $sql = "SELECT * FROM gateway WHERE gatecmd = ?";
    $stmt = $this->conn->prepare($sql);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $this->conn->lastErrorMsg());
    }

    $stmt->bindValue(1, $gatewayName, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
        $result->reset();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return null;
    }
}

// Método para devolver la información del gateway en variables específicas
public function gateway($gatewayName)
{
    $gatewayInfo = $this->getGatewayInfo($gatewayName);

    if ($gatewayInfo) {
        return [
            'id' => $gatewayInfo['id'] ?? null,
            'gatecmd' => $gatewayInfo['gatecmd'] ?? null,
            'gatename' => $gatewayInfo['gatename'] ?? null,
            'status' => $gatewayInfo['status'] ?? null,
            'comment' => $gatewayInfo['comment'] ?? null,
            'auth' => $gatewayInfo['auth'] ?? null,
            'charged' => $gatewayInfo['charged'] ?? null,
            'ccn' => $gatewayInfo['ccn'] ?? null,
            '3d' => $gatewayInfo['3d'] ?? null,
            'mass' => $gatewayInfo['mass'] ?? null,
            'price' => $gatewayInfo['price'] ?? null,
            'premium' => $gatewayInfo['premium'] ?? null,
            'credits' => $gatewayInfo['credits'] ?? null
        ];
    } else {
        return null;
    }
}
public function deductCredits($userId, $creditsToDeduct)
{
    try {

        $sqlGetUserCredits = "SELECT creditos FROM users WHERE user_id = ?";
        $stmtGetUserCredits = $this->conn->prepare($sqlGetUserCredits);
        if (!$stmtGetUserCredits) {
            throw new Exception($this->conn->lastErrorMsg());
        }
        $stmtGetUserCredits->bindValue(1, $userId, SQLITE3_TEXT);

        $result = $stmtGetUserCredits->execute();

        $response = [];

        if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
            $result->reset();
            $user = $result->fetchArray(SQLITE3_ASSOC);
            $currentCredits = (int)$user['creditos'];

            if ($currentCredits >= $creditsToDeduct) {
                $newCredits = $currentCredits - $creditsToDeduct;

                $sqlUpdateCredits = "UPDATE users SET creditos = ? WHERE user_id = ?";
                $stmtUpdateCredits = $this->conn->prepare($sqlUpdateCredits);
                if (!$stmtUpdateCredits) {
                    throw new Exception($this->conn->lastErrorMsg());
                }
                $stmtUpdateCredits->bindValue(1, $newCredits, SQLITE3_INTEGER);
                $stmtUpdateCredits->bindValue(2, $userId, SQLITE3_TEXT);

                $stmtUpdateCredits->execute();
                $stmtUpdateCredits->close();

                $response = "Credits deducted successfully. Remaining credits: $newCredits";
            } else {
                $response = "Insufficient credits. User has $currentCredits credits, but $creditsToDeduct are required.";
            }
        } else {
            $response = "User not found.";
        }

        $stmtGetUserCredits->close();
        return $response;
    } catch (Exception $e) {
        return "Database error: " . $e->getMessage();
    } catch (Error $e) {
        return "A fatal error occurred: " . $e->getMessage();
    }
}

public function addCredits($userId, $creditsToAdd)
{
    try {
        // Consulta para obtener los créditos actuales del usuario
        $sqlGetUserCredits = "SELECT creditos FROM users WHERE user_id = ?";
        $stmtGetUserCredits = $this->conn->prepare($sqlGetUserCredits);
        if (!$stmtGetUserCredits) {
            throw new Exception($this->conn->lastErrorMsg());
        }
        $stmtGetUserCredits->bindValue(1, $userId, SQLITE3_TEXT);

        $result = $stmtGetUserCredits->execute();

        $response = [];

        if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
            $result->reset();
            $user = $result->fetchArray(SQLITE3_ASSOC);
            $currentCredits = (int)$user['creditos'];

            // Sumar los créditos
            $newCredits = $currentCredits + $creditsToAdd;

            // Actualizar los créditos del usuario
            $sqlUpdateCredits = "UPDATE users SET creditos = ? WHERE user_id = ?";
            $stmtUpdateCredits = $this->conn->prepare($sqlUpdateCredits);
            if (!$stmtUpdateCredits) {
                throw new Exception($this->conn->lastErrorMsg());
            }
            $stmtUpdateCredits->bindValue(1, $newCredits, SQLITE3_INTEGER);
            $stmtUpdateCredits->bindValue(2, $userId, SQLITE3_TEXT);

            $stmtUpdateCredits->execute();
            $stmtUpdateCredits->close();

            $response = "Credits added successfully. New total credits: $newCredits";
        } else {
            $response = "User not found.";
        }

        $stmtGetUserCredits->close();
        return $response;
    } catch (Exception $e) {
        return "Database error: " . $e->getMessage();
    } catch (Error $e) {
        return "A fatal error occurred: " . $e->getMessage();
    }
}





// Método para obtener todos los gateways de una categoría específica
public function getGatewaysByCategory($category)
{
    $allowedCategories = ['auth', 'charged', 'ccn', '3d', 'mass'];
    if (!in_array($category, $allowedCategories)) {
        die("Categoría inválida: " . $category);
    }

    $sql = "SELECT * FROM gateway WHERE $category = 1";
    $result = $this->conn->query($sql);

    if ($result === false) {
        die('Error en la consulta de categoría: ' . $this->conn->lastErrorMsg());
    }

    $gateways = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $gateways[] = [
            'id' => $row['id'],
            'gatecmd' => $row['gatecmd'],
            'gatename' => $row['gatename'],
            'status' => $row['status'],
            'comment' => $row['comment'],
            'auth' => $row['auth'],
            'charged' => $row['charged'],
            'ccn' => $row['ccn'],
            '3d' => $row['3d'],
            'mass' => $row['mass'],
            'price' => $row['price']
        ];
    }

    return $gateways;
}

public function getLatestUpdate() {
    $query = "SELECT version_number, update_date, description, team_users, created_at FROM vSiesta ORDER BY update_date DESC LIMIT 1";
    $result = $this->conn->query($query);

    if (!$result) {
        error_log("Error en la consulta: " . $this->conn->lastErrorMsg());
        return null;
    }

    $data = $result->fetchArray(SQLITE3_ASSOC);
    
    // Mostrar cada campo

    return $data;
}
public function getRecentRewards($limit = 5) {
    $stmt = $this->conn->prepare("SELECT title, availability_date, description FROM rewards ORDER BY availability_date DESC LIMIT ?");
    $stmt->bindValue(1, $limit, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    $rewards = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rewards[] = $row;
    }
    return $rewards;
}

public function getGroupInfo($chatId)
{
    // Consulta a la tabla groups
    $sqlGroup = "SELECT * FROM groups WHERE id = ?";
    $stmtGroup = $this->conn->prepare($sqlGroup);
    $stmtGroup->bindValue(1, $chatId, SQLITE3_TEXT);
    $resultGroup = $stmtGroup->execute();

    if ($resultGroup && $resultGroup->fetchArray(SQLITE3_ASSOC)) {
        $resultGroup->reset();
        $groupData = $resultGroup->fetchArray(SQLITE3_ASSOC);
        return [
            'name' => $groupData['name'],
            'fecha_vencimiento' => $groupData['fecha_vencimiento'],
            'status' => $groupData['status']
        ];
    }

    // Si el grupo no está registrado en la tabla
    return null;
}


public function verifyUserAccess($userId, $chatId, $gatewayName)
{
    global $bot;

    $messages = $bot->loadPlantillas();

    // Verificar el estado del gateway
    $gatewayInfo = $this->gateway($gatewayName);
    if (!$gatewayInfo || $gatewayInfo['status'] !== 'ON') {
        // Retornar mensaje si el gateway no está activo
        return sprintf($messages['restricted_gateway'], $gatewayName);
    }

    // Verificar si el gateway es premium
    $isPremium = $gatewayInfo['premium'] ?? 0; // Suponiendo que "premium" está en el gateway

    // Verificar si el gateway requiere créditos
    $gatewayCredits = $gatewayInfo['credits'] ?? 0;

    // Obtener la información del usuario
    $userInfo = $this->obtenerInformacionUsuario($userId);
    $userStatus = $userInfo['status'];
    $userCredits = $userInfo['creditos'] ?? 0;

    // Obtener información del grupo
    $groupInfo = $this->getGroupInfo($chatId);
    $groupStatus = $groupInfo['status'] ?? null;
    $groupIsPremium = $groupInfo['premium'] ?? 0;

    // Si el usuario es "Free User" y el gateway es premium, restringir acceso
    if ($userStatus === 'Free User' && $isPremium==1) {
        return $messages['restricted'] ?: 'Este gateway premium no está disponible para usuarios gratuitos.';
    }

    // Si el usuario es "Free User" y el grupo no es premium, restringir acceso
    
    // Si el gateway no requiere créditos y el usuario es "Free User"
    if ($userStatus === 'Free User' && $gatewayCredits === 0) {
        // Permitir acceso solo si el grupo está activado
        if ($groupStatus) {
            return null;
        }
        return $messages['restricted'] ?: 'Este gateway no permite acceso a usuarios gratuitos sin grupo activo.';
    }

    // Si el usuario tiene suficientes créditos, permitir acceso
    if ($userCredits >= $gatewayCredits) {
        return null;
    }

    // Retornar mensaje si no tiene suficientes créditos
    return $messages['insufficient_user_credits'] ?: 'No tienes suficientes créditos para usar este gateway.';
}




private function registrarError($mensaje)
{
    $fecha = date("Y-m-d H:i:s");
    $mensajeCompleto = "[{$fecha}] {$mensaje}\n";
    file_put_contents('errores.log', $mensajeCompleto, FILE_APPEND);
}







public function deleteGroup($groupId, $sellerUsername)
{
    // Verificar si el grupo existe en la base de datos
    $sqlCheckGroup = "SELECT * FROM `groups` WHERE id = ?";
    $stmtCheckGroup = $this->conn->prepare($sqlCheckGroup);
    $stmtCheckGroup->bind_param("s", $groupId);
    $stmtCheckGroup->execute();

    $resultCheckGroup = $stmtCheckGroup->get_result();

    if ($resultCheckGroup->num_rows > 0) {
        // El grupo existe, proceder con la eliminación
        $sqlDeleteGroup = "DELETE FROM `groups` WHERE id = ?";
        $stmtDeleteGroup = $this->conn->prepare($sqlDeleteGroup);
        $stmtDeleteGroup->bind_param("s", $groupId);

        if ($stmtDeleteGroup->execute()) {
            // Éxito al eliminar el grupo
            $date1 = date("Y-m-d");
            $time = date("h:i:sa");

            $this->logsummary("
[❃]━━━━━━━━━━━━[❃]
𝑮𝒓𝒐𝒖𝒑 𝑫𝒆𝒍𝒆𝒕𝒆𝒅
[❃]━━━━━━━━━━━━[❃]
会 | 𝑫𝒂𝒕𝒆:  <code>$date1 $time</code>
会 | 𝑮𝒓𝒐𝒖𝒑 𝑰𝑫:  <code>$groupId</code>
会 | 𝑺𝒆𝒍𝒍𝒆𝒓: @$sellerUsername
[❃]━━━━━━━━━━━━[❃]");

            return "El grupo con ID $groupId fue eliminado correctamente.";
        } else {
            // Error al eliminar el grupo
            return "Error al intentar eliminar el grupo con ID $groupId.";
        }
    } else {
        // El grupo no existe en la base de datos
        return "No se encontró ningún grupo con el ID $groupId en la base de datos.";
    }
}

public function changeGroupStatus($groupId, $newName, $newStatus, $newDate, $sellerUsername)
{
    // Verificar si el grupo ya está registrado en la base de datos
    $sqlCheckGroup = "SELECT * FROM `groups` WHERE id = ?";
    $stmtCheckGroup = $this->conn->prepare($sqlCheckGroup);
    $stmtCheckGroup->bind_param("s", $groupId);
    $stmtCheckGroup->execute();

    $resultCheckGroup = $stmtCheckGroup->get_result();

    if ($resultCheckGroup->num_rows > 0) {
        // El grupo ya está registrado
        return "El grupo con ID $groupId ya está registrado en la base de datos.";
    } else {
        // El grupo no está registrado, proceder a la actualización
        $expiryTimestamp = strtotime("+$newDate days");
        $expiryDate = date('Y-m-d', $expiryTimestamp);

        $sqlUpdateGroup = "INSERT INTO `groups` (id, name, status, fecha_vencimiento) VALUES (?, ?, ?, ?) 
                           ON DUPLICATE KEY UPDATE name = VALUES(name), status = VALUES(status), fecha_vencimiento = VALUES(fecha_vencimiento)";
        $stmtUpdateGroup = $this->conn->prepare($sqlUpdateGroup);
        $stmtUpdateGroup->bind_param("ssss", $groupId, $newName, $newStatus, $expiryDate);

        if ($stmtUpdateGroup->execute()) {
            // Éxito al cambiar el nombre, el status y la fecha de vencimiento

            // Notificar a logsummary
            $date1 = date("Y-m-d");
            $time = date("h:i:sa");

            $this->logsummary("
[❃]━━━━━━━━━━━━[❃]
𝑮𝒓𝒐𝒖𝒑 𝑪𝒉𝒂𝒏𝒈𝒆𝒅
[❃]━━━━━━━━━━━━[❃]
会 | 𝑫𝒂𝒕𝒆:  <code>$date1 $time</code>
会 | 𝑮𝒓𝒐𝒖𝒑 𝑰𝑫:  <code>$groupId</code>
会 | 𝑵𝒖𝒆𝒗𝒐 𝑵𝒐𝒎𝒃𝒓𝒆: <code>$newName</code>
会 | 𝑵𝒖𝒆𝒗𝒐 𝑺𝒕𝒂𝒕𝒖𝒔: <code>$newStatus</code>
会 | 𝑭𝒆𝒄𝒉𝒂 𝒅𝒆 𝑽𝒆𝒏𝒄𝒊𝒎𝒊𝒆𝒏𝒕𝒐: <code>$expiryDate</code>
会 | 𝑺𝒆𝒍𝒍𝒆𝒓: @$sellerUsername
[❃]━━━━━━━━━━━━[❃]");

            return "Nombre, status y fecha de vencimiento actualizados correctamente para el grupo con ID $groupId y nombre: $newName.";
        } else {
            // Error al cambiar el nombre, el status y la fecha de vencimiento
            return "Error al actualizar el nombre, el status y la fecha de vencimiento para el grupo con ID $groupId.";
        }
    }
}

public function changeUserToFree($userId)
{
    // Ajustar el antispam a 60, eliminar expiration_date y cambiar el status a 'Free User'
    $antispamValue = 60;
    $newStatus = 'Free User';

    $sqlUpdateUser = "UPDATE `users` SET status = ?, expiration_date = NULL, antispam = ? WHERE user_id = ?";
    $stmtUpdateUser = $this->conn->prepare($sqlUpdateUser);
    $stmtUpdateUser->bind_param("sis", $newStatus, $antispamValue, $userId);

    $stmtUpdateUser->execute();
    $stmtUpdateUser->close();
}
public function changeUserStatus($userId, $newStatus, $sellerUsername, $newExpirationDate,$antispamValue=10)
{
    // Verificar si el usuario existe
    $sqlCheckUser = "SELECT * FROM `users` WHERE user_id = ?";
    $stmtCheckUser = $this->conn->prepare($sqlCheckUser);
    $stmtCheckUser->bind_param("s", $userId);
    $stmtCheckUser->execute();

    $resultCheckUser = $stmtCheckUser->get_result();

    if ($resultCheckUser->num_rows > 0) {
        // El usuario existe, actualizar el status y la fecha de vencimiento
        $expiryTimestamp = strtotime("+$newExpirationDate days");
        $expiryDate = date('Y-m-d', $expiryTimestamp);
        
        // Actualizar el estado y la fecha de expiración
        $sqlUpdateStatus = "UPDATE `users` SET status = ?, expiration_date = ? WHERE user_id = ?";
        $stmtUpdateStatus = $this->conn->prepare($sqlUpdateStatus);
        $stmtUpdateStatus->bind_param("sss", $newStatus, $expiryDate, $userId);

        if ($stmtUpdateStatus->execute()) {
            // Éxito al cambiar el estado y la fecha de vencimiento

            // Actualizar el antispam a 10
     
            $sqlUpdateAntispam = "UPDATE `users` SET antispam = ? WHERE user_id = ?";
            $stmtUpdateAntispam = $this->conn->prepare($sqlUpdateAntispam);
            $stmtUpdateAntispam->bind_param("is", $antispamValue, $userId);
            $stmtUpdateAntispam->execute();

            // Notificar a logsummary
            $date1 = date("Y-m-d");
            $time = date("h:i:sa");

            $this->logsummary(" 
[❃]━━━━━━━━━━━━[❃]
𝑺𝒕𝒂𝒕𝒖𝒔 𝑪𝒉𝒂𝒏𝒈𝒆𝒅
[❃]━━━━━━━━━━━━[❃]
会 | 𝑫𝒂𝒕𝒆:  <code>$date1 $time</code>
会 | 𝑼𝒔𝒆𝒓 𝑰𝑫:  <code>$userId</code>
会 | 𝑵𝒖𝒆𝒗𝒐 𝑺𝒕𝒂𝒕𝒖𝒔: <code>$newStatus</code>
会 | 𝑺𝒆𝒍𝒍𝒆𝒓: @$sellerUsername
会 | 𝑵𝒖𝒆𝒗𝒂 𝑭𝒆𝒄𝒉𝒂 𝒅𝒆 𝑽𝒆𝒏𝒄𝒊𝒎𝒊𝒆𝒏𝒕𝒐: <code>$expiryDate</code>
[❃]━━━━━━━━━━━━[❃]");

            echo "Status, fecha de vencimiento y antispam actualizados correctamente para el usuario con ID $userId.";
        } else {
            // Error al cambiar el status y la fecha de vencimiento
            echo "Error al actualizar el status y la fecha de vencimiento para el usuario con ID $userId.";
        }
    } else {
        // El usuario no existe
        echo "Lo siento, este usuario no está en mi base de datos.";
    }
}



public function obtenerInformacionUsuario($userId)
{
    $sqlUser = "SELECT * FROM `users` WHERE user_id = ?";
    $stmtUser = $this->conn->prepare($sqlUser);
    $stmtUser->bind_param("s", $userId);
    $stmtUser->execute();

    $resultUser = $stmtUser->get_result();

    if ($resultUser->num_rows > 0) {
        $userData = $resultUser->fetch_assoc();
        $status = $userData['status'];
        $creditos = $userData['creditos'];
        $expirationDate = $userData['expiration_date'];
        $antispam = $userData['antispam'];
        $language = $userData['language'];

        // Verificar si el usuario es admin
        if ($this->isAdmin($userId)) {
            $status = 'admin';
        }
        // Verificar si el usuario es owner
        elseif ($this->isOwner($userId)) {
            $status = 'owner';
        }

        // Verificar si la fecha de expiración es igual o anterior a la fecha actual
        if ($expirationDate !== null && strtotime($expirationDate) <= strtotime('today')) {
            $status = 'Free User';
            $expirationDate = null;

            // Actualizar el estado y expiration_date en la base de datos
            $updateStatusSql = "UPDATE `users` SET status = ?, expiration_date = NULL WHERE user_id = ?";
            $stmtUpdate = $this->conn->prepare($updateStatusSql);
            $stmtUpdate->bind_param("ss", $status, $userId);
            $stmtUpdate->execute();
            $stmtUpdate->close();

            // Actualizar el valor de antispam a 60 si la fecha ya pasó
            if ($antispam < 60) {
                $antispam = 60;
                $updateAntispamSql = "UPDATE `users` SET antispam = ? WHERE user_id = ?";
                $stmtUpdateAntispam = $this->conn->prepare($updateAntispamSql);
                $stmtUpdateAntispam->bind_param("is", $antispam, $userId);
                $stmtUpdateAntispam->execute();
                $stmtUpdateAntispam->close();
            }
        }

        // Calcular los días restantes hasta la fecha de expiración
        $daysExpiration = null;
        if ($expirationDate !== null) {
            $currentDate = new DateTime();
            $expirationDateTime = new DateTime($expirationDate);
            $daysExpiration = $currentDate->diff($expirationDateTime)->days + 1; // Ajustar para incluir el día actual
        }

        // Agregar más datos según sea necesario
        $userInfo = [
            'user_id' => $userData['user_id'],
            'username' => $userData['username'],
            'status' => $status,
            'creditos' => $creditos !== null ? $creditos : "null",
            'expiration_date' => $expirationDate !== null ? $expirationDate : "null",
            'antispam' => $antispam !== null ? $antispam : "null",
            'language' => $language !== null ? $language : "null",
            'days_expiration' => $daysExpiration !== null ? $daysExpiration : "null",
            // Agrega más campos según sea necesario
        ];

        return $userInfo;
    } else {
        // El usuario no se encontró en la tabla users
        return null;
    }
}




public function getUserLanguage($chat_id) {
    $default_language = "es"; // Idioma predeterminado

    $stmt = $this->conn->prepare("SELECT language FROM users WHERE user_id = ?");
    if (!$stmt) {
        // Error al preparar la consulta
        // Puedes registrar el error si lo deseas
        return $default_language;
    }

    $stmt->bind_param("s", $chat_id);
    if (!$stmt->execute()) {
        // Error al ejecutar la consulta
        // Puedes registrar el error si lo deseas
        $stmt->close();
        return $default_language;
    }

    // Utilizar bind_result y fetch en lugar de get_result()
    $stmt->bind_result($language);
    if ($stmt->fetch()) {
        // Se encontró el idioma del usuario
        $stmt->close();
        return $language ? $language : $default_language;
    } else {
        // No se encontró el usuario o no tiene idioma asignado
        $stmt->close();
        return $default_language;
    }
}
public function getUserLanguage2($chat_id) {
    $default_language = null; // Idioma predeterminado

    $stmt = $this->conn->prepare("SELECT language FROM users WHERE user_id = ?");
    if (!$stmt) {
        // Error al preparar la consulta
        // Puedes registrar el error si lo deseas
        return $default_language;
    }

    $stmt->bind_param("s", $chat_id);
    if (!$stmt->execute()) {
        // Error al ejecutar la consulta
        // Puedes registrar el error si lo deseas
        $stmt->close();
        return $default_language;
    }

    // Utilizar bind_result y fetch en lugar de get_result()
    $stmt->bind_result($language);
    if ($stmt->fetch()) {
        // Se encontró el idioma del usuario
        $stmt->close();
        return $language ? $language : $default_language;
    } else {
        // No se encontró el usuario o no tiene idioma asignado
        $stmt->close();
        return $default_language;
    }
}


    
    public function setUserLanguage($chat_id, $lang) {
        $stmt = $this->conn->prepare("UPDATE users SET language = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $lang, $chat_id);
        $stmt->execute();
    }
    
    public function changeLanguage($lang) {
        // Validar el idioma, podrías tener una lista de idiomas permitidos
        $allowedLanguages = ['es', 'en','de','pt', 'fr', 'it'];
        if (in_array($lang, $allowedLanguages)) {
            $this->language = $lang;
        } else {
            // Si el idioma no es válido, puedes establecer un valor predeterminado
            $this->language = 'es';
        }
    }

    public function isAdmin($userId) {
        $sql = "SELECT * FROM admins WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $userId, SQLITE3_TEXT);
        $result = $stmt->execute();

        return $result && $result->fetchArray(SQLITE3_ASSOC) !== false;
    }

    public function isOwner($userId) {
        $sql = "SELECT * FROM owners WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $userId, SQLITE3_TEXT);
        $result = $stmt->execute();

        return $result && $result->fetchArray(SQLITE3_ASSOC) !== false;
    }

    public function registerAdmin($userId, $username) {
        if ($this->isAdmin($userId)) {
            return "El usuario ya es admin.";
        }

        $sql = "INSERT INTO admins (user_id, username) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $userId, SQLITE3_TEXT);
        $stmt->bindValue(2, $username, SQLITE3_TEXT);

        if ($stmt->execute()) {
            return "Registro exitoso como admin.";
        } else {
            return "Error al registrar como admin.";
        }
    }

    public function getAntiSpamTime($userRank) {
        switch ($userRank) {
            case 1:
                return 50; 
            case 2:
                return 20;
            case 3:
                return 10; 
            case 4:
                return 10; 
            default:
                return 0; 
        }
    }

    public function existsAntispam($userID) {
        $stmt = $this->conn->prepare("SELECT antispam FROM users WHERE user_id=?");
        $stmt->bind_param("s", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return false;
        }

        $userData = $result->fetch_assoc();
        return $userData['antispam'];
    }
    public function getSiestaInfo() {
        $query = "SELECT version_number, update_date, team_users FROM vSiesta ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($query);
    
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            // Formatear el campo de usuarios en la forma deseada
            $teamMembers = explode(", ", $data['team_users']);
            $formattedTeam = "";
    
            foreach ($teamMembers as $member) {
                // Separar el @usuario y el ID
                preg_match('/(@\w+)\sID:\s(\d+)/', $member, $matches);
                if (!empty($matches)) {
                    $formattedTeam .= "<b>🛡 {$matches[1]}</b> | <i>ID: {$matches[2]}</i>\n";
                }
            }
            
            $data['formatted_team'] = $formattedTeam;
            return $data;
        } else {
            return null;
        }
    }
    
    public function antispamCheck($userID, $username) {
        try {
            // Configuración más específica para bot de Telegram
            $config = [
                'cooldownSeconds' => 2,        // Tiempo mínimo entre comandos (más permisivo)
                'maxCommands' => 8,            // Ventana de comandos a analizar
                'commandLimit' => 30,          // Límite de comandos por período
                'periodSeconds' => 60,         // Período de análisis
                'maxWarns' => 3,              // Advertencias antes del ban
                'banDurationDays' => 30,       // Duración del ban
                
                // Nuevos parámetros para detección avanzada
                'patternThreshold' => 0.85,    // Similitud en patrones de tiempo
                'minTimeVariation' => 1.0,     // Variación mínima entre comandos
                'maxIdenticalCommands' => 5,   // Máximo de comandos idénticos seguidos
                'complexityScore' => 3         // Puntuación mínima de complejidad
            ];
    
            // Obtener datos del usuario y su historial
            $stmt = $this->conn->prepare("
                SELECT u.antispam, u.antispam_s, u.command_count, u.last_command_time,
                       u.command_intervals, u.warns, u.ban_expiration, u.last_commands,
                       u.command_patterns
                FROM users u
                WHERE u.user_id = ?
            ");
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
    
            $currentTime = time();
    
            // Verificar si se excede el tiempo mínimo configurado por el usuario
            $userCooldown = isset($userData['antispam']) ? (int)$userData['antispam'] : $config['cooldownSeconds'];
            if ($userData['last_command_time'] !== null && $currentTime - $userData['last_command_time'] < $userCooldown) {
                $remainingTime = $userCooldown - ($currentTime - $userData['last_command_time']);
                return sprintf(
                    "❗ Estás enviando comandos demasiado rápido. Por favor, espera <b>%d</b> segundos.",
                    $remainingTime
                );
            }
    
            // Verificar ban existente
            if ($userData['ban_expiration'] !== null && strtotime($userData['ban_expiration']) > $currentTime) {
                $remainingTime = strtotime($userData['ban_expiration']) - $currentTime;
                $remainingDays = ceil($remainingTime / (60 * 60 * 24));
                return sprintf(
                    "[<u>BAN</u>] Tu cuenta está baneada por uso de scripts.\nTiempo restante: <b>%d</b> día(s).",
                    $remainingDays
                );
            }
    
            // Inicializar o decodificar el historial de comandos
            $lastCommands = isset($userData['last_commands']) ? 
                json_decode($userData['last_commands'], true) : [];
            
            // Añadir comando actual al historial
            $lastCommands[] = [
                'time' => $currentTime,
                'command' => debug_backtrace()[1]['function'] ?? 'unknown'
            ];
            
            // Mantener solo los últimos N comandos
            if (count($lastCommands) > $config['maxCommands']) {
                array_shift($lastCommands);
            }
    
            // Sistema avanzado de detección de scripts
            $scriptDetected = false;
            $detectionReason = '';
            
            if (count($lastCommands) >= 3) {
                // 1. Análisis de intervalos de tiempo
                $intervals = [];
                for ($i = 1; $i < count($lastCommands); $i++) {
                    $intervals[] = $lastCommands[$i]['time'] - $lastCommands[$i-1]['time'];
                }
                
                // Calcular variación en intervalos
                $stdDev = $this->calculateStandardDeviation($intervals);
                $mean = array_sum($intervals) / count($intervals);
                $variation = $stdDev / $mean;
    
                // 2. Detección de patrones repetitivos
                $commandSequence = array_column($lastCommands, 'command');
                $patternScore = $this->detectPatterns($commandSequence);
    
                // 3. Verificar comandos idénticos consecutivos
                $consecutiveCount = $this->countConsecutiveCommands($commandSequence);
    
                // 4. Análisis de complejidad de interacción
                $complexityScore = $this->calculateComplexityScore($lastCommands);
    
                // Determinar si es un script basado en múltiples factores
                if ($variation < $config['minTimeVariation'] && count($intervals) > 3) {
                    $scriptDetected = true;
                    $detectionReason = "Intervalos de tiempo demasiado regulares";
                }
                elseif ($patternScore > $config['patternThreshold']) {
                    $scriptDetected = true;
                    $detectionReason = "Patrones de comando repetitivos";
                }
                elseif ($consecutiveCount > $config['maxIdenticalCommands']) {
                    $scriptDetected = true;
                    $detectionReason = "Demasiados comandos idénticos consecutivos";
                }
                elseif ($complexityScore < $config['complexityScore']) {
                    $scriptDetected = true;
                    $detectionReason = "Patrón de uso poco natural";
                }
            }
    
            // Actualizar datos del usuario
            $commandsJson = json_encode($lastCommands);
            $stmt = $this->conn->prepare("
                UPDATE users 
                SET last_commands = ?,
                    last_command_time = ?,
                    command_count = command_count + 1
                WHERE user_id = ?
            ");
            $stmt->bind_param("sis", $commandsJson, $currentTime, $userID);
            $stmt->execute();
    
            // Manejar detección de script
            if ($scriptDetected) {
                // Manejo de advertencias y baneo como en el código original...
            }
    
            return false;
        } catch (Exception $e) {
            error_log("AntiSpam Error: " . $e->getMessage());
            return false; // Permitir el comando en caso de error
        }
    }
    
    
    // Funciones auxiliares
    private function calculateStandardDeviation($array) {
        $count = count($array);
        if ($count < 2) return 0;
        
        $mean = array_sum($array) / $count;
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $array)) / ($count - 1);
        
        return sqrt($variance);
    }
    
    private function detectPatterns($commands) {
        $length = count($commands);
        if ($length < 4) return 0;
        
        $patterns = [];
        // Buscar patrones de 2-4 comandos
        for ($size = 2; $size <= 4; $size++) {
            for ($i = 0; $i < $length - $size; $i++) {
                $pattern = array_slice($commands, $i, $size);
                $patternStr = implode(',', $pattern);
                $patterns[$patternStr] = ($patterns[$patternStr] ?? 0) + 1;
            }
        }
        
        $maxRepetitions = max($patterns);
        return $maxRepetitions / ($length / 2);
    }
    
    private function countConsecutiveCommands($commands) {
        $maxCount = 1;
        $currentCount = 1;
        $lastCommand = reset($commands);
        
        foreach (array_slice($commands, 1) as $command) {
            if ($command === $lastCommand) {
                $currentCount++;
                $maxCount = max($maxCount, $currentCount);
            } else {
                $currentCount = 1;
            }
            $lastCommand = $command;
        }
        
        return $maxCount;
    }
    
    private function calculateComplexityScore($commands) {
        $score = 0;
        
        // Analizar variación en tiempos
        $intervals = [];
        for ($i = 1; $i < count($commands); $i++) {
            $intervals[] = $commands[$i]['time'] - $commands[$i-1]['time'];
        }
        
        // Variación en intervalos (más natural)
        $score += (count(array_unique($intervals)) / count($intervals)) * 2;
        
        // Variación en comandos (más natural)
        $uniqueCommands = count(array_unique(array_column($commands, 'command')));
        $score += ($uniqueCommands / count($commands)) * 2;
        
        return $score;
    }
    
    private function logViolation($userID, $username, $warns, $reason, $isBan) {
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $type = $isBan ? "⛔ 𝑩𝒂𝒏 𝑪𝒐𝒍𝒐𝒄𝒂𝒅𝒐" : "⚠️ 𝑨𝒅𝒗𝒆𝒓𝒕𝒆𝒏𝒄𝒊𝒂 𝑨𝒔𝒊𝒈𝒏𝒂𝒅𝒂";
        
        $this->logsummary("
    [❃]━━━━━━━━━━━━[❃]
    $type
    [❃]━━━━━━━━━━━━[❃]
    会 | 𝑫𝒂𝒕𝒆: <code>$date $time</code>
    会 | 𝑼𝒔𝒆𝒓 𝑰𝑫: <code>$userID</code>
    会 | 𝑼𝒔𝒆𝒓𝒏𝒂𝒎𝒆: <code>$username</code>
    会 | 𝑵𝒖𝒎𝒆𝒓𝒐 𝒅𝒆 𝑨𝒅𝒗𝒆𝒓𝒕𝒆𝒏𝒄𝒊𝒂𝒔: <code>$warns</code>
    会 | 𝑹𝒂𝒛ó𝒏: $reason
    [❃]━━━━━━━━━━━━[❃]");
    }
    
    // Función para calcular la desviación estándar
    private function standardDeviation($array) {
        $mean = array_sum($array) / count($array);
        $sumSquaredDiffs = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $array));
        return sqrt($sumSquaredDiffs / count($array));
    }
    
    
    
    
    
    
    public function addTool($tool_name, $status = 'inactive', $comment = '') { 
        $query = "INSERT INTO tools (tool_name, status, comment) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $tool_name, SQLITE3_TEXT);
        $stmt->bindValue(2, $status, SQLITE3_TEXT);
        $stmt->bindValue(3, $comment, SQLITE3_TEXT);
    
        if ($stmt->execute()) {
            return "Tool added successfully.";
        } else {
            return "Error adding tool.";
        }
    }
    
    
    public function removeTool($tool_name) {
        $query = "DELETE FROM tools WHERE tool_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $tool_name, SQLITE3_TEXT);
    
        if ($stmt->execute()) {
            return "Tool removed successfully.";
        } else {
            return "Error removing tool.";
        }
    }
    
    public function updateToolStatus($tool_name, $status) {
        $query = "UPDATE tools SET status = ? WHERE tool_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $status, SQLITE3_TEXT);
        $stmt->bindValue(2, $tool_name, SQLITE3_TEXT);
    
        if ($stmt->execute()) {
            return "Tool status updated successfully.";
        } else {
            return "Error updating tool status.";
        }
    }

    public function getToolStatus($tool_name) {
        $query = "SELECT status FROM tools WHERE tool_name = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            error_log("Error en la preparación de la declaración: " . $this->conn->lastErrorMsg());
            return false; // O lanza una excepción
        }
    
        $stmt->bindValue(1, $tool_name, SQLITE3_TEXT);
        
        $result = $stmt->execute(); // Obtener el resultado de la consulta
    
        if ($result && $result->fetchArray(SQLITE3_ASSOC)) {
            $result->reset();
            $row = $result->fetchArray(SQLITE3_ASSOC); // Obtener la fila como un array asociativo
            $stmt->close(); // Cierra la declaración
            return $row['status'] === 'active'; // Devuelve true si está activa, false si no
        }
    
        $stmt->close(); // Cierra la declaración
        return false; // Retorna false si no se encontró
    }
    
    
    public function getAllToolsStatus() {
        $query = "SELECT tool_name, status, comment FROM tools";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute();
    
        $tools = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $tools[] = [
                'tool_name' => $row['tool_name'],
                'status' => $row['status'],
                'comment' => $row['comment'] ?: 'No comment' // Si el comentario es NULL, devuelve 'No comment'
            ];
        }
        return $tools;
    }
    
    public function getAllUserIDs() {
        $query = "SELECT user_id FROM users";
        $result = $this->conn->query($query);

        $userIDs = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $userIDs[] = $row['user_id'];
        }

        return $userIDs;
    }
}

?>
