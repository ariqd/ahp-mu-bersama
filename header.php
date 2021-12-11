<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Sistem Pendukung Keputusan Pemilihan Vendor</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
</head>

<body>
	<header>
		<h1><img src="image/logo.jpeg" alt="logo Mutiara Indah"><br>Sistem Pendukung Keputusan Pemilihan Vendor <br> Toko Mutiara Indah</h1>
	</header>

	<div class="wrapper">
		<nav id="navigation" role="navigation">
			<ul>
				<li><a class="item" href="home.php">Home</a></li>
				<li><a class="item" href="stok.php">Stok</a></li>
				<li>
					<a class="item" href="kriteria.php">Kriteria
						<div class="ui blue tiny label" style="float: right;"><?php echo getJumlahKriteria(); ?></div>
					</a>
				</li>
				<li>
					<a class="item" href="alternatif.php">Alternatif
						<div class="ui blue tiny label" style="float: right;"><?php echo getJumlahAlternatif(); ?></div>
					</a>
				</li>
				<li><a class="item" href="bobot_kriteria.php">Perbandingan Kriteria</a></li>
				<li><a class="item" href="bobot.php?c=1">Perbandingan Alternatif</a></li>
				<ul>
					<?php

					if (getJumlahKriteria() > 0) {
						for ($i = 0; $i <= (getJumlahKriteria() - 1); $i++) {
							echo "<li><a class='item' href='bobot.php?c=" . ($i + 1) . "'>" . getKriteriaNama($i) . "</a></li>";
						}
					}

					?>
				</ul>
				<li><a class="item" href="hasil.php">Hasil</a></li>
				<li><a class="item" href="index.php">Logout</a></li>
			</ul>
		</nav>