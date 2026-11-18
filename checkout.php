<?php require 'config.php'; session_start();
$cart=$_SESSION['cart']??[]; if(!$cart){ header('Location: keranjang.php'); exit; }
$err=''; 
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nama=trim($_POST['nama']??''); $tel=trim($_POST['telepon']??''); $alm=trim($_POST['alamat']??'');
  if($nama===''||$tel===''||$alm==='') $err='Semua data wajib diisi.';
  else { $db->begin_transaction(); try{
    $rows=[]; $total=0;
    $s=$db->prepare("SELECT harga,stok FROM produk WHERE id_produk=? FOR UPDATE");
    foreach($cart as $id=>$q){ $s->bind_param('i',$id); $s->execute(); $p=$s->get_result()->fetch_assoc();
      if(!$p||$p['stok']<$q) throw new Exception("Stok produk ID $id tidak mencukupi.");
      $rows[]=[$id,$q,$p['harga']]; $total+=$q*$p['harga']; }
    $o=$db->prepare("INSERT INTO pesanan(nama_penerima,telepon,alamat,total) VALUES(?,?,?,?)");
    $o->bind_param('sssd',$nama,$tel,$alm,$total); $o->execute(); $oid=$db->insert_id;
    $d=$db->prepare("INSERT INTO detail_pesanan(id_pesanan,id_produk,jumlah,harga_satuan) VALUES(?,?,?,?)");
    $u=$db->prepare("UPDATE produk SET stok=stok-? WHERE id_produk=?");
    foreach($rows as [$id,$q,$h]){ $d->bind_param('iiid',$oid,$id,$q,$h); $d->execute(); $u->bind_param('ii',$q,$id); $u->execute(); }
    $db->commit(); unset($_SESSION['cart']); header("Location: sukses.php?id=$oid"); exit;
  }catch(Exception $ex){ $db->rollback(); $err=$ex->getMessage(); } } }
include 'header.php'; ?>
<h4>Checkout</h4><?php if($err) echo '<div class="alert alert-danger">'.e($err).'</div>'; ?>
<form method="post" class="bg-white p-3 rounded" style="max-width:520px">
<div class="mb-2"><label>Nama penerima</label><input name="nama" class="form-control" required></div>
<div class="mb-2"><label>Telepon</label><input name="telepon" class="form-control" required></div>
<div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" required></textarea></div>
<button class="btn btn-success">Buat Pesanan</button> <a href="keranjang.php" class="btn btn-link">Kembali</a></form>
<?php include 'footer.php'; ?>
