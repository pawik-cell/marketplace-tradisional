<?php require 'config.php'; include 'header.php';
$q=trim($_GET['q']??''); $k=(int)($_GET['k']??0); $like="%$q%";
$st=$db->prepare("SELECT p.*,k.nama_kategori FROM produk p JOIN kategori k ON k.id_kategori=p.id_kategori WHERE p.nama LIKE ? AND (?=0 OR p.id_kategori=?) ORDER BY p.id_produk DESC");
$st->bind_param('sii',$like,$k,$k); $st->execute(); $r=$st->get_result();
$kat=$db->query("SELECT * FROM kategori ORDER BY nama_kategori"); ?>
<form class="row g-2 mb-4"><div class="col-md-6"><input name="q" value="<?=e($q)?>" class="form-control" placeholder="Cari produk..."></div>
<div class="col-md-4"><select name="k" class="form-select"><option value="0">Semua kategori</option>
<?php while($c=$kat->fetch_assoc()): ?><option value="<?=$c['id_kategori']?>" <?=$k==$c['id_kategori']?'selected':''?>><?=e($c['nama_kategori'])?></option><?php endwhile; ?></select></div>
<div class="col-md-2"><button class="btn btn-success w-100">Cari</button></div></form>
<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
<?php while($p=$r->fetch_assoc()): ?><div class="col"><div class="card h-100 shadow-sm">
<img src="<?=e($p['gambar']?:'https://placehold.co/400x250?text=Produk')?>" class="card-img-top" alt="">
<div class="card-body"><span class="badge bg-secondary"><?=e($p['nama_kategori'])?></span>
<h6 class="mt-2"><?=e($p['nama'])?></h6><p class="fw-bold text-success mb-1"><?=rp($p['harga'])?></p><small>Stok: <?=$p['stok']?></small></div>
<div class="card-footer bg-white"><a href="produk_detail.php?id=<?=$p['id_produk']?>" class="btn btn-outline-success btn-sm w-100">Lihat Detail</a></div>
</div></div><?php endwhile; if($r->num_rows==0) echo '<p class="text-muted">Produk tidak ditemukan.</p>'; ?></div>
<?php include 'footer.php'; ?>
