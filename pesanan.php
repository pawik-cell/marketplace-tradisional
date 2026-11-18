<?php require __DIR__.'/_init.php';
if($_SERVER['REQUEST_METHOD']==='GET'){
  if(!isset($_GET['id'])||!ctype_digit((string)$_GET['id'])) out(400,['status'=>'error','message'=>'Parameter id wajib (angka)']);
  $id=(int)$_GET['id']; $s=$db->prepare("SELECT id_pesanan,nama_penerima,telepon,alamat,total,status,tanggal FROM pesanan WHERE id_pesanan=?");
  $s->bind_param('i',$id); $s->execute(); $o=$s->get_result()->fetch_assoc();
  if(!$o) out(404,['status'=>'error','message'=>"Pesanan $id tidak ditemukan"]);
  $d=$db->prepare("SELECT id_produk,jumlah,harga_satuan FROM detail_pesanan WHERE id_pesanan=?"); $d->bind_param('i',$id); $d->execute();
  $o['items']=$d->get_result()->fetch_all(MYSQLI_ASSOC); out(200,['status'=>'success','data'=>$o]);
}
allow('POST');
$in=json_decode(file_get_contents('php://input'),true);
if(!is_array($in)) out(400,['status'=>'error','message'=>'Body harus JSON valid']);
$nama=trim($in['nama_penerima']??''); $tel=trim($in['telepon']??''); $alm=trim($in['alamat']??''); $items=$in['items']??[];
if($nama===''||$tel===''||$alm===''||!is_array($items)||!$items) out(422,['status'=>'error','message'=>'nama_penerima, telepon, alamat, dan items wajib diisi']);
$db->begin_transaction();
try{
  $rows=[]; $total=0; $s=$db->prepare("SELECT harga,stok FROM produk WHERE id_produk=? FOR UPDATE");
  foreach($items as $it){ $pid=(int)($it['id_produk']??0); $q=(int)($it['jumlah']??0);
    if($pid<=0||$q<=0) throw new Exception('Item tidak valid',422);
    $s->bind_param('i',$pid); $s->execute(); $p=$s->get_result()->fetch_assoc();
    if(!$p) throw new Exception("Produk $pid tidak ditemukan",404);
    if($p['stok']<$q) throw new Exception("Stok produk $pid tidak mencukupi",409);
    $rows[]=[$pid,$q,$p['harga']]; $total+=$q*$p['harga']; }
  $o=$db->prepare("INSERT INTO pesanan(nama_penerima,telepon,alamat,total) VALUES(?,?,?,?)"); $o->bind_param('sssd',$nama,$tel,$alm,$total); $o->execute(); $oid=$db->insert_id;
  $d=$db->prepare("INSERT INTO detail_pesanan(id_pesanan,id_produk,jumlah,harga_satuan) VALUES(?,?,?,?)"); $u=$db->prepare("UPDATE produk SET stok=stok-? WHERE id_produk=?");
  foreach($rows as [$pid,$q,$h]){ $d->bind_param('iiid',$oid,$pid,$q,$h); $d->execute(); $u->bind_param('ii',$q,$pid); $u->execute(); }
  $db->commit(); out(201,['status'=>'success','message'=>'Pesanan berhasil dibuat','data'=>['id_pesanan'=>$oid,'total'=>(float)$total]]);
}catch(Exception $ex){ $db->rollback(); $c=$ex->getCode(); out(in_array($c,[404,409,422])?$c:500,['status'=>'error','message'=>$ex->getMessage()]); }
