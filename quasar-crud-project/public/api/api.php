<?php
require 'db.php'; // Veritabanı bağlantısını dahil ediyoruz
// cors: bir web sayfasının kendi domaini dışındaki bir kaynağa (API, resim, CSS dosyası vb.) erişim yapmasına izin vermek için kullanılan bir güvenlik mekanizmasıdır. 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// Preflight (OPTIONS) isteğine yanıt verin
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}


switch ($method) {
    case 'GET':
        // Fetch all users
        try {
            $stmt = $DB->prepare("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        // Create a new user
        try {
        // HTTP isteğinin gövdesini okur.
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->name) && isset($data->age)) {
                $stmt = $DB->prepare("INSERT INTO users (user_name, user_age) VALUES (:name, :age)");
                $stmt->bindParam(':name', $data->name);
                $stmt->bindParam(':age', $data->age);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'User created']);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'User creation failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid input']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        // Update an existing user
        try {
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->id) && isset($data->name) && isset($data->age)) {
                $stmt = $DB->prepare("UPDATE users SET user_name = :name, user_age = :age WHERE id = :id");
                $stmt->bindParam(':name', $data->name);
                $stmt->bindParam(':age', $data->age);
                $stmt->bindParam(':id', $data->id);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'User updated']);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'User update failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid input']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        // Delete a user
        try {
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->id)) {
                $stmt = $DB->prepare("DELETE FROM users WHERE id = :id");
                $stmt->bindParam(':id', $data->id);
                if ($stmt->execute()) {
                    echo json_encode(['message' => 'User deleted']);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'User deletion failed']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid input']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
?>

