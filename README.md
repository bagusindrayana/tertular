## **TERTULAR**

Sistem informasi berbasis web untuk pendataan dan pelacakan lokasi pasien positif covid-19/virus corona

**menjalankan aplikasi**
download/clone repo ini dan masuk ke direktori aplikasi
 1. jalankan perintah composer install
 2. jalankan perintah npm install
 3. buat file .env lalu isi dengan kode yang ada di .env.example
 4. isi semua parameter yang ada di .env seperti host,username,database,password
 5. buat akun [https://www.mapbox.com/](https://www.mapbox.com/) untuk mengisi MAPBOX_TOKEN
 6. buat akun [https://opencagedata.com/](https://opencagedata.com/) untuk mengisi GEOCODE_TOKEN
 7. jalankan perintah php artisan key:generate
 8. jalankan perintah php artisan migrate
 9. jalankan perintah php artisan db:seed
 10.dan jalankan php artisan serve lalu buka [http://127.0.0.1:8000/](http://127.0.0.1:8000/) di browser 
