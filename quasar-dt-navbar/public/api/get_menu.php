<?php
/*header Fonksiyonları: sunucunun JSON formatında bir yanıt göndereceği belirtilir. Access-Control-Allow-Origin başlığı ise tüm domainlerden gelen istekleri kabul edeceğini belirtir.*/
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "navbar";

try {
    $DB = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // set the PDO error mode to exception
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; // Bağlantı başarılı
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

$sql = "SELECT * FROM my_web_pages"; // Tablo adını belirtin
$result = $DB->query($sql);

$menus = array(); //menüleri tutacak dizi
if ($result->rowCount() > 0) { // Eğer sonuçlar varsa
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $menus[] = $row;
    }
}

echo json_encode($menus); //json formatına çevirir
