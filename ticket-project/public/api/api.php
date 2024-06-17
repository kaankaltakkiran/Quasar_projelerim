<?php
//cors tanımları
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept, Expires, Pragma");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

// Preflight isteklerini işleme
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

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
        ################################### Get Users ###################################

        $response = fetchUsers($DB); // kullanıcıları getirme fonksiyonu
        break;
    case 'add-user':
        ################################### Add User ###################################
        $response = addUser($DB, $data);
        break;

    default:
        ################################### default ###################################
        $response['error'] = "`method` adı geçerli değil";
        if (!isset($data['methode'])) {
            $response['error'] = "`method` parametresi bulunamadı";
        }
} // switch
header('Content-Type: application/json');
//Veriyi daha okunaklı göster
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

function fetchUsers($DB)
{
    ################################### get Users ###################################
    $sql = "SELECT * FROM users";
    $SORGU = $DB->prepare($sql);
    $SORGU->execute();
    $rows = $SORGU->fetchAll(PDO::FETCH_ASSOC);
    $response['success'] = true; // İşlem başarılı
    $response['users'] = $rows;
    return $response;
}

function addUser($DB, $data)
{
    $sql = "INSERT INTO users (user_name, user_email, user_status) VALUES (:name, :email, :status)";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':name', $data['name']);
    $SORGU->bindParam(':email', $data['email']);
    // status alanının value değerini doğrudan kaydet
    $statusValue = $data['status']['value']; //value değerini kaydet
    $SORGU->bindParam(':status', $statusValue);
    $SORGU->execute();
    $response['success'] = true; // İşlem başarılı
    return $response;
}
