# PHP Kullanıcı Yönetim API

## Gereksinimler
- PHP 7.4+
- MySQL/MariaDB
- Web Server (Apache/Nginx)

## Veritabanı Kurulumu
1. `database_setup.sql` dosyasını veritabanınıza import edin
2. `db_connection.php` dosyasındaki veritabanı bilgilerini güncelleyin

## API Endpoint'leri

### 1. Kullanıcı Kaydı (Register)
```json
{
    "method": "register",
    "username": "test_user",
    "email": "test@example.com",
    "password": "123456"
}
```

### 2. Kullanıcı Girişi (Login)
```json
{
    "method": "login",
    "username": "test_user",
    "password": "123456"
}
```

### 3. Tüm Kullanıcıları Listele
```json
{
    "method": "get-users"
}
```

### 4. Tek Kullanıcı Getir
```json
{
    "method": "get-user",
    "id": 1
}
```

### 5. Kullanıcı Güncelle
```json
{
    "method": "update-user",
    "id": 1,
    "username": "yeni_kullanici",
    "email": "yeni@email.com"
}
```

### 6. Kullanıcı Sil
```json
{
    "method": "delete-user",
    "id": 1
}
```

## Yanıt Formatı

Başarılı yanıt örneği:
```json
{
    "success": true,
    "message": "İşlem başarılı",
    "data": { ... }
}
```

Hata yanıt örneği:
```json
{
    "success": false,
    "error": "Hata mesajı"
}
```

## Güvenlik Notları
- Şifreler bcrypt ile hashlenir
- CORS headers eklenmiştir
- Prepared statements kullanılarak SQL injection önlenir

## Kurulum
1. Dosyaları web sunucunuzun PHP dizinine yerleştirin
2. Veritabanı bağlantı ayarlarını yapılandırın
3. database_setup.sql dosyasını import edin
4. API'yi kullanmaya başlayın
