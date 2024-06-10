<?php
//cors tanımları
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');

// HTTP isteğinin gövdesindeki ham verileri okur.
$temp = file_get_contents("php://input");
$data = json_decode($temp, true); //json verisi php dizinine çevrildi.
$METHOD = $data['method']; //gelen mthoda göre işlem yapma


require_once 'db.php'; //veritabanı bağlantısı

$response = []; // gelen datayı dizi olarak al

//$response['GELENDATA'] = $data;

$response['success'] = false; // veri gelme durumu defalut olarak false olsun


switch ($METHOD) {

  case 'get-users':
    ################################### get Users ###################################
    $sql = "SELECT * FROM users";
    $SORGU = $DB->prepare($sql);
    $SORGU->execute();
    $rows = $SORGU->fetchAll(PDO::FETCH_ASSOC);
    $response['success'] = true; // İşlem başarılı
    $response['users'] = $rows;
    //$response['methodname'] = $METHOD; 
    break;
    
      case 'get-user':
    ################################### Get User ###################################
    $sql = "SELECT * FROM users WHERE id = :id";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':id', $data['id']);
    $SORGU->execute();
    $row = $SORGU->fetch(PDO::FETCH_ASSOC);
    $response['success'] = true; // İşlem başarılı
    $response['user'] = $row;
    break;
    
      case 'get-users-by-status':
       ################################### Get Users by Status ###################################
            if (isset($data['status'])) {
           $sql = "SELECT * FROM users WHERE user_status = :status";
           $SORGU = $DB->prepare($sql);
           $SORGU->bindParam(':status', $data['status']);
           $SORGU->execute();
           $rows = $SORGU->fetchAll(PDO::FETCH_ASSOC);
           $response['success'] = true; // İşlem başarılı
           $response['users'] = $rows;
            } else {
                $response['error'] = "Status parameter is missing";
            }
            break;

      case 'add-user':
    ################################### Add User ###################################
    $sql = "INSERT INTO users (user_name,user_email, user_status) VALUES (:name, :email,:status)";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':name', $data['name']);
    $SORGU->bindParam(':email', $data['email']);
    //status değer girilmezse defalut 0 olsun
    $status = isset($data['status']) ? $data['status'] : 0;
    $SORGU->bindParam(':status', $status);
    $SORGU->execute();
    $response['success'] = true; // İşlem başarılı
    break;
    
case 'delete-user':
    ################################### Delete User Or Users ###################################
    if (isset($data['ids']) && is_array($data['ids']) && count($data['ids']) > 0) {
        $successCount = 0; // Başarılı silme sayacı
        $failedCount = 0;  // Başarısız silme sayacı
        
        foreach ($data['ids'] as $id) {
            $sql = "DELETE FROM users WHERE id = :id";
            $SORGU = $DB->prepare($sql);
            $SORGU->bindParam(':id', $id);
            if ($SORGU->execute()) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }
        
        // Başarılı ve başarısız silme sayılarına göre cevap oluştur
        if ($successCount > 0) {
            $response['success'] = true;
        }
        if ($failedCount > 0) {
            $response['error'] = "Some users couldn't be deleted";
        }
    } else {
        $response['error'] = "IDs parameter is missing or empty";
    }
    break;
   
case 'update-user':
    ################################### Update User ###################################
    if (isset($data['id'])) {
        $sql = "UPDATE users SET user_name = :name, user_email = :email, user_status = :status WHERE id = :id";
        $SORGU = $DB->prepare($sql);
        $SORGU->bindParam(':id', $data['id']);
        $SORGU->bindParam(':name', $data['name']);
        $SORGU->bindParam(':email', $data['email']);
        $SORGU->bindParam(':status', $data['status']);
        $SORGU->execute();
        $response['success'] = true; 
        $response['user'] = [
            'id' => $data['id'],
            'user_name' => $data['name'],
            'user_email' => $data['email'],
            'user_status' => $data['status'], 
        ];
    } else {
        $response['error'] = "ID parameter is missing";
    }
    break;


    
  default:
    ################################### Default ###################################
    $response['error'] = "`method` adı geçerli değil";
    if (!isset($data['methode'])) {
      $response['error'] = "`method` parametresi bulunamadı";
    }
} // switch

// http_response_code(200);

//Veriyi daha okunaklı göster
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
