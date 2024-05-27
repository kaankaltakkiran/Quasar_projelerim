<?php
/*header Fonksiyonları: sunucunun JSON formatında bir yanıt göndereceği belirtilir. Access-Control-Allow-Origin başlığı ise tüm domainlerden gelen istekleri kabul edeceğini belirtir.*/
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'db.php'; // Veritabanı bağlantısını sağlar

$sql = "SELECT * from  FROM my_web_pages";
$result = $conn->query($sql);

$menus = array(); //menüleri tutacak dizi
if ($result->num_rows > 0) { // Eğer sonuçlar varsa
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}

echo json_encode($menus); //json formatına çevirir

$conn->close();
?>
