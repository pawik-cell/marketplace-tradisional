<?php require 'config.php'; include 'header.php'; $id=(int)($_GET['id']??0);
$s=$db->prepare("SELECT * FROM pesanan WHERE id_pesanan=?"); $s->bind_param('i',$id); $s->execute(); $o=$s->get_result()->fetch_assoc();
if(!$o){ echo '<div class="alert alert-warning">Pesanan tidak ditemukan.</div>'; include 'footer.php'; exit; }
$d=$db->prepare("SELECT d.*,p.nama FROM detail_pesanan d JOIN produk p ON p.id_produk=d.id_produk WHERE d.id_pesanan=?"); $d->bind_param('i',$id); $d->execute(); $r=$d->get_result(); ?>
<div class="alert alert-success">Pesanan #<?=$o['id_pesanan']?> berhasil dibuat!</div>
<p>Penerima: <b><?=e($o['nama_penerima'])?></b> (<?=e($o['telepon'])?>)<br>Alamat: <?=e($o['alamat'])?></p>
<table class="table bg-white"><tr><th>Produk</th><th>Jml</th><th>Harga</th></tr>
<?php while($i=$r->fetch_assoc()): ?><tr><td><?=e($i['nama'])?></td><td><?=$i['jumlah']?></td><td><?=rp($i['harga_satuan'])?></td></tr><?php endwhile; ?>
<tr class="fw-bold"><td colspan="2">Total</td><td><?=rp($o['total'])?></td></tr></table><a href="index.php" class="btn btn-success">Kembali Belanja</a>
<?php include 'footer.php'; ?>
