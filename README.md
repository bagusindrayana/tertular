## **TERTULAR**

Sistem informasi berbasis web untuk pendataan dan pelacakan lokasi pasien positif covid-19/virus corona

![halaman depan](https://i.ibb.co/ZWVGLdj/Fire-Shot-Capture-122-Tertular-tertular-dev-com.png)

![tambah pasien](https://i.ibb.co/2sR34SQ/Fire-Shot-Capture-119-Tertular-tertular-dev-com.png)

**menjalankan aplikasi**

 1. download/clone repo ini lalu dan masuk ke direktorinya/foldernya
 2. jalankan perintah `composer install`
 3. jalankan perintah `npm install`
 4. copy file `.env.example` lalu rename jadi `.env`
 5. isi semua parameter yang ada di `.env` seperti host,username,database,password,tb_prefix dll
 6. buat akun [https://www.mapbox.com/](https://www.mapbox.com/) untuk mengisi `MAPBOX_TOKEN`
 7. buat akun [https://opencagedata.com/](https://opencagedata.com/) untuk mengisi `GEOCODE_TOKEN`
 8. jalankan perintah `php artisan key:generate`
 9. jalankan perintah `php artisan migrate`
 10. jalankan perintah `php artisan db:seed` **(OPTIONAL jika ingin menambahkan data user provinsi,kota,kecamatan,dan kelurahan indonesia serta data dummy pasien untuk wilayah kaltim,bisa di custom di bagain database seeder (total baris ada 80000+))**
  11. dan jalankan `php artisan serve` lalu buka [http://127.0.0.1:8000/](http://127.0.0.1:8000/) di browser
