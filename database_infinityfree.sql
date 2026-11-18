DROP TABLE IF EXISTS detail_pesanan, pesanan, produk, kategori, pengguna;
CREATE TABLE pengguna(
 id_pengguna INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE, role ENUM('penjual','pembeli') NOT NULL DEFAULT 'pembeli');
CREATE TABLE kategori(
 id_kategori INT AUTO_INCREMENT PRIMARY KEY, nama_kategori VARCHAR(60) NOT NULL UNIQUE);
CREATE TABLE produk(
 id_produk INT AUTO_INCREMENT PRIMARY KEY, id_kategori INT NOT NULL, id_penjual INT NOT NULL,
 nama VARCHAR(120) NOT NULL, deskripsi TEXT, harga DECIMAL(12,2) NOT NULL CHECK(harga>=0),
 stok INT NOT NULL DEFAULT 0 CHECK(stok>=0), gambar VARCHAR(255),
 FOREIGN KEY(id_kategori) REFERENCES kategori(id_kategori) ON UPDATE CASCADE,
 FOREIGN KEY(id_penjual) REFERENCES pengguna(id_pengguna) ON UPDATE CASCADE);
CREATE TABLE pesanan(
 id_pesanan INT AUTO_INCREMENT PRIMARY KEY, nama_penerima VARCHAR(100) NOT NULL, telepon VARCHAR(20) NOT NULL,
 alamat TEXT NOT NULL, total DECIMAL(14,2) NOT NULL, status ENUM('baru','diproses','selesai','batal') NOT NULL DEFAULT 'baru',
 tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE detail_pesanan(
 id_detail INT AUTO_INCREMENT PRIMARY KEY, id_pesanan INT NOT NULL, id_produk INT NOT NULL,
 jumlah INT NOT NULL CHECK(jumlah>0), harga_satuan DECIMAL(12,2) NOT NULL,
 FOREIGN KEY(id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
 FOREIGN KEY(id_produk) REFERENCES produk(id_produk));
INSERT INTO pengguna(nama,email,role) VALUES('Toko Bu Sari','sari@mail.com','penjual'),('Andi','andi@mail.com','pembeli');
INSERT INTO kategori(nama_kategori) VALUES('Sayuran'),('Buah'),('Bumbu Dapur'),('Kerajinan');
INSERT INTO produk(id_kategori,id_penjual,nama,deskripsi,harga,stok) VALUES
(1,1,'Bayam Segar 1 Ikat','Bayam organik dari petani lokal.',3000,50),
(2,1,'Pisang Kepok 1 Sisir','Pisang kepok matang pohon.',15000,30),
(3,1,'Cabai Merah 250 gr','Cabai merah keriting segar.',12000,40),
(3,1,'Bawang Merah 500 gr','Bawang merah pilihan.',18000,25),
(4,1,'Tikar Pandan','Tikar anyaman tangan.',65000,10);
