# Deploy ke InfinityFree (tanpa XAMPP)

1. Daftar di infinityfree.com → Create Account → pilih subdomain gratis → tunggu aktif.
2. Client Area → Control Panel → **MySQL Databases** → buat database `marketplace`. Catat: MySQL Hostname, Username, Password, nama DB lengkap (berawalan `if0_...`).
3. Control Panel → **phpMyAdmin** (Connect) → pilih database tadi → tab **Import** → pilih `database_infinityfree.sql`.
4. **File Manager** (atau FTP, mis. FileZilla) → buka folder `htdocs` → hapus `index2.html` bawaan → unggah SEMUA isi folder `marketplace` (index.php langsung di dalam `htdocs`, bukan di subfolder).
5. Di `htdocs`, ganti nama/buat file `config.local.php` dari `config.sample.php`, isi kredensial dari langkah 2 dan password admin yang kuat.
6. Buka `https://SUBDOMAINMU.infinityfreeapp.com/` dan `/api/produk.php`.

## Pengujian API di InfinityFree
- GET lewat **browser** biasanya langsung jalan.
- InfinityFree memasang proteksi anti-bot JavaScript; request dari Postman/curl sering dibalas halaman HTML ("requires JavaScript"), terutama POST. Jika terjadi: (a) uji POST lewat halaman form/JS di browser, atau (b) uji Postman ke server lokal (`php -S localhost:8000`) dan tunjukkan di laporan bahwa kode yang sama berjalan di InfinityFree. Soal 12 tidak mewajibkan deploy API.
- Kalau muncul error koneksi: cek host/user/password/nama DB di `config.local.php` (bukan `localhost`).
