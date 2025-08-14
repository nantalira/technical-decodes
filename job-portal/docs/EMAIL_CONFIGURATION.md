# 📧 Konfigurasi Email untuk Fitur Lupa Password

## Pengaturan Email Provider

### 1. Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@golekgawe.com"
MAIL_FROM_NAME="Golek Gawe Job Portal"
```

**Cara Setup Gmail:**

1. Login ke Gmail
2. Aktifkan 2-Factor Authentication
3. Generate App Password di Security Settings
4. Gunakan App Password sebagai MAIL_PASSWORD

### 2. Mailtrap (Testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="test@golekgawe.com"
MAIL_FROM_NAME="Golek Gawe Job Portal"
```

### 3. Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=key-xxxxxxxxxxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="Golek Gawe Job Portal"
```

### 4. SendGrid

```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="Golek Gawe Job Portal"
```

## Mode Demo vs Production

### Demo Mode

```env
PASSWORD_RESET_DEMO_MODE=true
```

-   User langsung diarahkan ke halaman reset password
-   Tidak mengirim email (untuk testing/demo)
-   Token tetap tersimpan di database

### Production Mode

```env
PASSWORD_RESET_DEMO_MODE=false
```

-   Email dikirim ke user
-   User harus mengklik link di email
-   Logging error jika gagal kirim email

## Testing Email

### 1. Menggunakan Artisan Command

```bash
# Test kirim email ke user tertentu
php artisan email:test-reset user@example.com
```

### 2. Menggunakan Tinker

```bash
php artisan tinker

# Test manual
$user = App\Models\User::where('email', 'test@example.com')->first();
$token = Str::random(64);
Mail::to('test@example.com')->send(new App\Mail\PasswordResetMail($token, 'test@example.com', $user));
```

### 3. Log Driver (untuk debugging)

```env
MAIL_MAILER=log
```

Email akan disimpan di `storage/logs/laravel.log`

## Troubleshooting

### Error: Connection refused

-   Periksa MAIL_HOST dan MAIL_PORT
-   Pastikan firewall tidak memblokir koneksi
-   Test dengan telnet: `telnet smtp.gmail.com 587`

### Error: Authentication failed

-   Periksa MAIL_USERNAME dan MAIL_PASSWORD
-   Untuk Gmail, pastikan menggunakan App Password
-   Periksa 2FA sudah aktif

### Error: SSL/TLS issues

-   Coba ubah MAIL_ENCRYPTION dari tls ke ssl atau null
-   Update sertifikat SSL sistem

### Email masuk ke Spam

-   Setup SPF record: `v=spf1 include:_spf.google.com ~all`
-   Setup DKIM di provider email
-   Setup DMARC policy
-   Gunakan domain profesional untuk MAIL_FROM_ADDRESS

## Queue untuk Performa

### Setup Queue

```env
QUEUE_CONNECTION=database
```

### Update PasswordResetMail

```php
class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable;
    // ...
}
```

### Jalankan Queue Worker

```bash
php artisan queue:work
```

## Monitoring

### Log Email Activity

Semua aktivitas email di-log di `storage/logs/laravel.log`

### Database Monitoring

Cek tabel `password_reset_tokens` untuk melihat token aktif:

```sql
SELECT email, created_at FROM password_reset_tokens
WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR);
```

## Security Best Practices

1. **Token Expiration**: Default 24 jam, bisa diubah di `PASSWORD_RESET_TOKEN_EXPIRE_HOURS`
2. **Rate Limiting**: Implementasi rate limiting untuk prevent spam
3. **HTTPS Only**: Pastikan link reset password menggunakan HTTPS
4. **Secure Headers**: Implementasi CSRF protection
5. **Log Monitoring**: Monitor logs untuk aktivitas mencurigakan

## Customization

### Custom Email Template

Edit file: `resources/views/emails/password-reset.blade.php`

### Custom Email Subject

Edit di: `app/Mail/PasswordResetMail.php`

### Custom Token Length

Edit di: `AuthController@forgotPassword()` - ubah `Str::random(64)`

### Custom Expiration Time

```env
PASSWORD_RESET_TOKEN_EXPIRE_HOURS=48  # 2 hari
```
