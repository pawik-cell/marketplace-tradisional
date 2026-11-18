<?php require 'config.php'; session_start();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['aksi']??''; $id=(int)($_POST['id']??0);
  if($a==='tambah'){ $q=max(1,(int)($_POST['qty']??1));
    $s=$db->prepare("SELECT stok FROM produk WHERE id_produk=?"); $s->bind_param('i',$id); $s->execute(); $row=$s->get_result()->fetch_assoc();
    if($row){ $_SESSION['cart'][$id]=min($row['stok'],($_SESSION['cart'][$id]??0)+$q); } }
  if($a==='ubah'){ foreach(($_POST['qty']??[]) as $pid=>$q){ $q=(int)$q; if($q<=0) unset($_SESSION['cart'][(int)$pid]); else $_SESSION['cart'][(int)$pid]=$q; } }
  if($a==='hapus') unset($_SESSION['cart'][$id]);
  header('Location: keranjang.php'); exit; }
include 'header.php'; $items=[]; $total=0;
$s=$db->prepare("SELECT * FROM produk WHERE id_produk=?");
foreach(($_SESSION['cart']??[]) as $id=>$q){ $s->bind_param('i',$id); $s->execute(); if($p=$s->get_result()->fetch_assoc()){ $p['qty']=min($q,$p['stok']); $p['sub']=$p['qty']*$p['harga']; $total+=$p['sub']; $items[]=$p; } } ?>
<h4>Keranjang Belanja</h4>
<?php if(!$items): ?><div class="alert alert-info">Keranjang kosong. <a href="index.php">Belanja dulu</a></div><?php else: ?>
<form method="post"><input type="hidden" name="aksi" value="ubah"><table class="table bg-white align-middle"><thead><tr><th>Produk</th><th>Harga</th><th width="110">Jumlah</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach($items as $i): ?><tr><td><?=e($i['nama'])?></td><td><?=rp($i['harga'])?></td>
<td><input type="number" name="qty[<?=$i['id_produk']?>]" value="<?=$i['qty']?>" min="0" max="<?=$i['stok']?>" class="form-control form-control-sm"></td>
<td><?=rp($i['sub'])?></td><td><button form="h<?=$i['id_produk']?>" class="btn btn-sm btn-outline-danger">Hapus</button></td></tr><?php endforeach; ?>
<tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td colspan="2"><?=rp($total)?></td></tr></tbody></table>
<button class="btn btn-secondary">Perbarui</button> <a href="checkout.php" class="btn btn-success">Checkout</a></form>
<?php foreach($items as $i): ?><form id="h<?=$i['id_produk']?>" method="post"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?=$i['id_produk']?>"></form><?php endforeach; endif; ?>
<?php include 'footer.php'; ?>
