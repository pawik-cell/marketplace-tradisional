<?php require 'config.php'; include 'header.php';
$id=(int)($_GET['id']??0);
$st=$db->prepare("SELECT p.*,k.nama_kategori,u.nama AS penjual FROM produk p JOIN kategori k ON k.id_kategori=p.id_kategori JOIN pengguna u ON u.id_pengguna=p.id_penjual WHERE p.id_produk=?");
$st->bind_param('i',$id); $st->execute(); $p=$st->get_result()->fetch_assoc();
if(!$p){ echo '<div class="alert alert-warning">Produk tidak ditemukan.</div>'; include 'footer.php'; exit; } ?>
<div class="row"><div class="col-md-5"><img class="img-fluid rounded" src="<?=e($p['gambar']?:'https://placehold.co/500x350?text=Produk')?>"></div>
<div class="col-md-7"><h3><?=e($p['nama'])?></h3><span class="badge bg-secondary"><?=e($p['nama_kategori'])?></span>
<h4 class="text-success my-3"><?=rp($p['harga'])?></h4><p><?=nl2br(e($p['deskripsi']))?></p>
<p>Penjual: <b><?=e($p['penjual'])?></b> | Stok: <b><?=$p['stok']?></b></p>
<?php if($p['stok']>0): ?><form method="post" action="keranjang.php" class="d-flex gap-2"><input type="hidden" name="aksi" value="tambah"><input type="hidden" name="id" value="<?=$p['id_produk']?>">
<input type="number" name="qty" value="1" min="1" max="<?=$p['stok']?>" class="form-control w-25"><button class="btn btn-success">+ Keranjang</button></form>
<?php else: ?><div class="alert alert-danger">Stok habis</div><?php endif; ?></div></div>
<?php include 'footer.php'; ?>
