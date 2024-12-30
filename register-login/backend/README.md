# PHP Kullanıcı Yönetim API

## Gereksinimler

- PHP 7.4+
- MySQL/MariaDB
- Web Server (Apache/Nginx)

---

## Veritabanı Kurulumu

1. `database_setup.sql` dosyasını veritabanınıza import edin
2. `db_connection.php` dosyasındaki veritabanı bilgilerini güncelleyin

---

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

---

### 2. Kullanıcı Girişi (Login)

```json
{
  "method": "login",
  "email": "kaan@gmail.com",
  "password": "123"
}
```

Gelen Response:

```json
{
  "success": true,
  "message": "Logged in successfully",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MzU1Nzc4OTAsImV4cCI6MTczNTU4MTQ5MCwidXNlcl9pZCI6MSwidXNlcm5hbWUiOiJrYWFuIiwiZW1haWwiOiJrYWFuQGdtYWlsLmNvbSJ9.qwB9BjD0jCzVLyFTy41DgfFkndj82i387Jbl6SjBZ5Y=",
  "user": {
    "id": 1,
    "username": "kaan",
    "email": "kaan@gmail.com"
  }
}
```

---

### 3. Tüm Kullanıcıları Listele

`Authorization`: Bearer your-jwt-token

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
      "username": "veli",
      "email": "veli@gmail.com"
    }
  ]
}
```

---

### 4. Tek Kullanıcı Getir

`Authorization`: Bearer your-jwt-token

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

---

### 5. Kullanıcı Güncelle

`Authorization`: Bearer your-jwt-token

```json
{
  "method": "update-user",
  "id": 2,
  "username": "update_veli",
  "email": "updateveli@gmail.com"
}
```

Gelen Response:

```json
{
  "success": true,
  "message": "User updated successfully"
}
```

---

### 6. Kullanıcı Sil

`Authorization`: Bearer your-jwt-token

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

---

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

---

## Güvenlik Notları

- Şifreler bcrypt ile hashlenir
- CORS headers eklenmiştir
- Prepared statements kullanılarak SQL injection önlenir.

---

## Kurulum

1. Dosyaları web sunucunuzun PHP dizinine yerleştirin
2. Veritabanı bağlantı ayarlarını yapılandırın
3. database_setup.sql dosyasını import edin
4. API'yi kullanmaya başlayın
