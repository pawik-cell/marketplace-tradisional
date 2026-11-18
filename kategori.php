<?php require __DIR__.'/_init.php'; allow('GET');
$rows=[]; $res=$db->query("SELECT id_kategori,nama_kategori FROM kategori ORDER BY nama_kategori");
while($r=$res->fetch_assoc()){ $r['id_kategori']=(int)$r['id_kategori']; $rows[]=$r; }
out(200,['status'=>'success','total'=>count($rows),'data'=>$rows]);
