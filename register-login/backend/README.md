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
  "username": "kaan",
  "email": "kaan@gmail.com",
  "password": "123"
}
```

Gelen Response:

```json
{
  "success": true,
  "message": "User registered successfully",
  "user_id": 1
}
```

### 2. Kullanıcı Girişi (Login)

```json
{
  "method": "login",
  "username": "kaan",
  "password": "123"
}
```

Gelen Response:

```json
{
  "success": true,
  "message": "Logged in successfully",
  "user": {
    "id": 1,
    "username": "kaan"
  }
}
```

### 3. Tüm Kullanıcıları Listele

```json
{
  "method": "get-users"
}
```

Gelen Response:

```json
{
  "success": true,
  "users": [
    {
      "id": 1,
      "username": "kaan",
      "email": "kaan@gmail.com"
    },
    {
      "id": 2,
      "username": "ahmet",
      "email": "ahmet@gmail.com"
    }
  ]
}
```

### 4. Tek Kullanıcı Getir

```json
{
  "method": "get-user",
  "id": 1
}
```

Gelen Response:

```json
{
  "success": true,
  "user": {
    "id": 1,
    "username": "kaan",
    "email": "kaan@gmail.com"
  }
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

Gelen Response:

```json
{
  "success": true,
  "message": "User updated successfully"
}
```

### 6. Kullanıcı Sil

```json
{
  "method": "delete-user",
  "id": 1
}
```

Gelen Response:

```json
{
  "success": true,
  "message": "User deleted successfully"
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
