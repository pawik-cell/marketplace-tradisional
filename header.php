<?php if(session_status()===PHP_SESSION_NONE) session_start(); $jml=array_sum($_SESSION['cart']??[]); ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pasar Tradisional Online</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<nav class="navbar navbar-expand navbar-dark bg-success mb-4"><div class="container">
<a class="navbar-brand" href="index.php">Pasar Tradisional</a>
<div class="navbar-nav ms-auto"><a class="nav-link" href="keranjang.php">Keranjang <span class="badge bg-warning text-dark"><?= $jml ?></span></a>
<a class="nav-link" href="admin/produk.php">Admin</a></div></div></nav><div class="container">
