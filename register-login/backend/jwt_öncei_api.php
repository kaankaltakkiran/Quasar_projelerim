<?php
// CORS tanımları
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

// Preflight (OPTIONS) isteğine yanıt verin
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Veritabanı baglantısı
require_once 'db_connection.php';

// Gelen ham veriyi okuma
$data = json_decode(file_get_contents("php://input"), true);
$METHOD = $data['method'] ?? '';

$response = ['success' => false];

// Veritabanı bağlantısı
$db = new Database();
$DB = $db->connection;

// Metodlara göre işlemleri yönlendirme
switch ($METHOD) {
    case 'register':
        $response = registerUser($DB, $data);
        break;

    case 'login':
        $response = loginUser($DB, $data);
        break;

    case 'get-users':
        $response = getUsers($DB);
        break;

    case 'get-user':
        $response = getUser($DB, $data);
        break;

    case 'update-user':
        $response = updateUser($DB, $data);
        break;

    case 'delete-user':
        $response = deleteUser($DB, $data);
        break;

    default:
        $response['error'] = "invalid method";
}

// JSON formatında yanıt döndürme
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$db->closeConnection();

// Kullanıcı kaydı oluşturma
function registerUser($DB, $data) {
    $response = ['success' => false];

    try {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $DB->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['username'], $data['email'], $hash);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "User registered successfully";
            $response['user_id'] = $stmt->insert_id;
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}

// Kullanıcı giriş işlemi
function loginUser($DB, $data) {
    $response = ['success' => false];

    try {
        $stmt = $DB->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $data['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kullanıcı varsa sifreyi kontrol et
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($data['password'], $user['password'])) {
                $response['success'] = true;
                $response['message'] = "Logged in successfully";
                $response['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email']
                ];
            } else {
                $response['error'] = "Wrong password or email";
            }
        } else {
            $response['error'] = "User not found";
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}

// Tüm kullanıcıları listeleme
function getUsers($DB) {
    $response = ['success' => false];

    try {
        $stmt = $DB->prepare("SELECT id, username, email FROM users");
        $stmt->execute();
        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $response['success'] = true;
        $response['users'] = $users;
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}

// Belirli bir kullanıcıyı getirme
function getUser($DB, $data) {
    $response = ['success' => false];

    try {
        $stmt = $DB->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $response['success'] = true;
            $response['user'] = $result->fetch_assoc();
        } else {
            $response['error'] = "User not found";
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}

// Kullanıcı bilgilerini güncelleme
function updateUser($DB, $data) {
    $response = ['success' => false];

    try {
        $stmt = $DB->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $data['username'], $data['email'], $data['id']);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "User updated successfully";
        } else {
            $response['error'] = "User update failed";
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}

// Kullanıcıyı silme
function deleteUser($DB, $data) {
    $response = ['success' => false];

    try {
        $stmt = $DB->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $data['id']);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "User deleted successfully";
        } else {
            $response['error'] = "User delete failed";
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}
