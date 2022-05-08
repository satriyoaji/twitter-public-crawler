

## Laravel Project - Twitter API public access

### Cara Menjalankan Project
- pastikan sudah menginstall Composer
- jalankan command `composer install` pada root directory
- copy `.env.example` ke file `.env` kemudian sesuaikan semua env variable di dalamnya termasuk setting Twitter environment & token
- Setting twitter environment bisa dilakukan dengan menambah apps baru pada [Twitter Developer Portal](https://developer.twitter.com/en/portal/dashboard) -> bisa register terlebih dahulu kemudian menambahkan project apps
- Aktifkan issue SSL certificate untuk keperluan akses public API seperti [pada link ini](https://noorsplugin.com/how-to-fix-curl-error-60-ssl-certificate-problem-unable-to-get-local-issuer-certificate/)
#### setelah dipastikan setting twitter environment dan issue SSL certificate aman, jalankan command dibawah ini pada root directory project laravel 
- jalankan command `php artisan key:generate`
- jalankan command `php artisan jwt:secret`
- jalankan command `php artisan cache:clear`
- jalankan command `php artisan config:clear`
- jalankan command `php artisan serve` untuk menjalankan local server misal pada `http://localhost:8000/`
- list endpoint ada pada file `/routes/api.php`

### List Endpoints 
- [GET] Twitter search queries `/twitter-query/:search`
- [GET] Twitter search user by id `/search-user-by-id/:id`
- [GET] Twitter search user by username `/search-user-by-username/:username`
