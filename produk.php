<?php require __DIR__.'/_init.php'; allow('GET');
$sql="SELECT p.id_produk,p.nama,p.deskripsi,p.harga,p.stok,p.gambar,p.id_kategori,k.nama_kategori FROM produk p JOIN kategori k ON k.id_kategori=p.id_kategori";
$fix=function($r){ $r['id_produk']=(int)$r['id_produk']; $r['id_kategori']=(int)$r['id_kategori']; $r['harga']=(float)$r['harga']; $r['stok']=(int)$r['stok']; return $r; };
if(isset($_GET['id'])){
  if(!ctype_digit((string)$_GET['id'])) out(400,['status'=>'error','message'=>'Parameter id harus angka']);
  $id=(int)$_GET['id']; $s=$db->prepare("$sql WHERE p.id_produk=?"); $s->bind_param('i',$id); $s->execute();
  $r=$s->get_result()->fetch_assoc();
  if(!$r) out(404,['status'=>'error','message'=>"Produk dengan id $id tidak ditemukan"]);
  out(200,['status'=>'success','data'=>$fix($r)]);
}
$rows=[]; $res=$db->query("$sql ORDER BY p.id_produk");
while($r=$res->fetch_assoc()) $rows[]=$fix($r);
out(200,['status'=>'success','total'=>count($rows),'data'=>$rows]);
