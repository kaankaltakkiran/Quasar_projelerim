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

  case 'get-menus':
    ################################### get all menu ###################################
    $sql = "SELECT * FROM menu";
    $SORGU = $DB->prepare($sql);
    $SORGU->execute();
    $rows = $SORGU->fetchAll(PDO::FETCH_ASSOC);
    $response['success'] = true; // İşlem başarılı
    $response['menus'] = $rows;
    break;
    
         case 'get-page':
    ################################### Route with page slug###################################
    $sql = "SELECT * FROM menu WHERE page_slug = :page_slug";
    $SORGU = $DB->prepare($sql);
    $SORGU->bindParam(':page_slug', $data['page_slug']);
    $SORGU->execute();
    $row = $SORGU->fetch(PDO::FETCH_ASSOC);
    $response['success'] = true; // İşlem başarılı
    $response['page'] = $row;
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
