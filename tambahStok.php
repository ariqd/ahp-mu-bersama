<?php
include('config.php');
include('fungsi.php');

if (isset($_POST['tambah'])) {
	$nama = $_POST['nama'];
	$qty = $_POST['qty'];
	$restock_point = $_POST['restock_point'];

	tambahStok($nama, $qty, $restock_point);

	header('Location: stok.php');
}

include('header.php');
?>

<section class="content">
	<h2>Tambah Stok</h2>

	<form class="ui form" method="post" action="tambahStok.php">
		<div class="field">
			<label for="nama">Nama Barang</label>
			<input type="text" name="nama" id="nama">
		</div>
		<div class="three wide field">
			<label for="qty">Qty</label>
			<input type="number" name="qty" id="qty">
		</div>
		<div class="three wide field">
			<label for="restock_point">Restock Point</label>
			<input type="number" name="restock_point" id="restock_point">
		</div>
		<input type="hidden" name="jenis" value="stok">
		<br>
		<input class="ui green button" type="submit" name="tambah" value="SIMPAN">
	</form>
</section>

<?php include('footer.php'); ?>