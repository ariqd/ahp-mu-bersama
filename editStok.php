<?php
include('config.php');
include('fungsi.php');

// mendapatkan data edit
if (isset($_GET['id'])) {
	$id = $_GET['id'];

	// hapus record
	$query 	= "SELECT nama, qty, restock_point FROM stok WHERE id=$id";
	$result	= mysqli_query($koneksi, $query);

	while ($row = mysqli_fetch_array($result)) {
		$nama = $row['nama'];
		$qty = $row['qty'];
		$restock_point = $row['restock_point'];
	}
}

if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$nama = $_POST['nama'];
	$qty = $_POST['qty'];
	$restock_point = $_POST['restock_point'];

	$query 	= "UPDATE stok SET nama='$nama', qty='$qty', restock_point='$restock_point' WHERE id=$id";
	$result	= mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Update gagal";
		exit();
	} else {
		header('Location: stok.php');
		exit();
	}
}

include('header.php');
?>

<section class="content">
	<h2>Edit Stok</h2>

	<form class="ui form" method="post" action="editStok.php">
		<input type="hidden" name="id" value="<?php echo $id ?>">
		<div class="field">
			<label>Nama Barang</label>
			<input type="text" name="nama" value="<?php echo $nama ?>">
		</div>
		<div class="three wide field">
			<label>Qty</label>
			<input type="number" name="qty" value="<?php echo $qty ?>">
		</div>
		<div class="three wide field">
			<label>Restock Point</label>
			<input type="number" name="restock_point" value="<?php echo $restock_point ?>">
		</div>
		<br>
		<input class="ui green button" type="submit" name="update" value="UPDATE">
	</form>
</section>

<?php include('footer.php'); ?>