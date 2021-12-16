<?php
include('config.php');
include('fungsi.php');

if (isset($_POST['tambah'])) {
	$nama = $_POST['nama'];
	$qty = $_POST['qty'];
	$restock_point = $_POST['restock_point'];
	$harga = $_POST['harga'];
	$kategori_id = $_POST['kategori_id'];

	tambahStok($nama, $qty, $restock_point, $harga, $kategori_id);

	header('Location: stok.php');
}

include('header.php');
?>

<section class="content">
	<h2>Tambah Stok</h2>

	<form class="ui form" method="post" action="tambahStok.php">
		<div class="field">
			<label for="nama">Nama Barang</label>
			<input type="text" name="nama" id="nama" required>
		</div>
		<div class="five wide field">
			<label for="kategori">Kategori</label>
			<select class="ui dropdown" name="kategori_id" required>
				<option value="">Pilih Kategori</option>
				<?php
				$query = "SELECT id,nama FROM kategori ORDER BY id";
				$result = mysqli_query($koneksi, $query);
				$i = 0;
				while ($row = mysqli_fetch_array($result)) {
					$i++;
				?>
					<option value="<?php echo $row['id'] ?>"><?php echo $row['nama'] ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="three wide field">
			<label for="qty">Qty</label>
			<input type="number" min="0" name="qty" id="qty" required>
		</div>
		<div class="three wide field">
			<label for="restock_point">Restock Point</label>
			<input type="number" min="0" name="restock_point" id="restock_point" required>
		</div>
		<div class="three wide field">
			<label for="harga">Harga (Rp)</label>
			<input type="number" name="harga" id="harga" min="0" required>
		</div>
		<input type="hidden" name="jenis" value="stok">
		<br>
		<input class="ui green button" type="submit" name="tambah" value="SIMPAN">
	</form>
</section>

<?php include('footer.php'); ?>