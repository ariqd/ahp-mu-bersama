<?php
session_start();

function d($data)
{
	if (is_null($data)) {
		$str = "<i>NULL</i>";
	} elseif ($data == "") {
		$str = "<i>Empty</i>";
	} elseif (is_array($data)) {
		if (count($data) == 0) {
			$str = "<i>Empty array.</i>";
		} else {
			$str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
			foreach ($data as $key => $value) {
				$str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">" . $key . "</td><td style=\"border:1px solid #000;\">" . d($value) . "</td></tr>";
			}
			$str .= "</table>";
		}
	} elseif (is_resource($data)) {
		while ($arr = mysqli_fetch_array($data)) {
			$data_array[] = $arr;
		}
		$str = d($data_array);
	} elseif (is_object($data)) {
		$str = d(get_object_vars($data));
	} elseif (is_bool($data)) {
		$str = "<i>" . ($data ? "True" : "False") . "</i>";
	} else {
		$str = $data;
		$str = preg_replace("/\n/", "<br>\n", $str);
	}
	return $str;
}

function dnl($data)
{
	echo d($data) . "<br>\n";
}

function dd($data)
{
	echo dnl($data);
	exit;
}

// mencari ID kriteria
// berdasarkan urutan ke berapa (C1, C2, C3)
function getKriteriaID($no_urut)
{
	include('config.php');
	$query  = "SELECT id FROM kriteria ORDER BY id";
	$result = mysqli_query($koneksi, $query);

	while ($row = mysqli_fetch_array($result)) {
		$listID[] = $row['id'];
	}

	return $listID[($no_urut)];
}

// mencari ID alternatif
// berdasarkan urutan ke berapa (A1, A2, A3)
function getAlternatifID($no_urut)
{
	include('config.php');
	$query  = "SELECT id FROM alternatif ORDER BY id";
	$result = mysqli_query($koneksi, $query);

	while ($row = mysqli_fetch_array($result)) {
		$listID[] = $row['id'];
	}

	return $listID[($no_urut)];
}

// mencari nama kriteria
function getKriteriaNama($no_urut)
{
	include('config.php');
	$query  = "SELECT nama FROM kriteria ORDER BY id";
	$result = mysqli_query($koneksi, $query);

	while ($row = mysqli_fetch_array($result)) {
		$nama[] = $row['nama'];
	}

	return $nama[($no_urut)];
}

// mencari nama alternatif
function getAlternatifNama($no_urut)
{
	include('config.php');
	$query  = "SELECT nama FROM alternatif ORDER BY id";
	$result = mysqli_query($koneksi, $query);

	while ($row = mysqli_fetch_array($result)) {
		$nama[] = $row['nama'];
	}

	return $nama[($no_urut)];
}

// mencari priority vector alternatif
function getAlternatifPV($id_alternatif, $id_kriteria)
{
	include('config.php');
	$query = "SELECT nilai FROM pv_alternatif WHERE id_alternatif=$id_alternatif AND id_kriteria=$id_kriteria";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$pv = $row['nilai'];
	}

	return $pv;
}

// mencari priority vector kriteria
function getKriteriaPV($id_kriteria)
{
	include('config.php');
	$query = "SELECT nilai FROM pv_kriteria WHERE id_kriteria=$id_kriteria";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$pv = $row['nilai'];
	}

	return $pv;
}

function getJumlahKategori()
{
	include('config.php');
	$query  = "SELECT count(*) FROM kategori";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jmlData = $row[0];
	}

	return $jmlData;
}

// mencari jumlah alternatif
function getJumlahAlternatif()
{
	include('config.php');
	$query  = "SELECT count(*) FROM alternatif";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jmlData = $row[0];
	}

	return $jmlData;
}

// mencari jumlah kriteria
function getJumlahKriteria()
{
	include('config.php');
	$query  = "SELECT count(*) FROM kriteria";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jmlData = $row[0];
	}

	return $jmlData;
}

// mencari jumlah cart
function getJumlahCart()
{
	include('config.php');
	$query  = "SELECT sum(qty) FROM cart";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$jmlData = $row[0];
	}

	return $jmlData;
}

// menambah data kriteria / alternatif
function tambahData($tabel, $nama)
{
	include('config.php');

	$query 	= "INSERT INTO $tabel (nama) VALUES ('$nama')";
	$tambah	= mysqli_query($koneksi, $query);

	if (!$tambah) {
		echo "Gagal mmenambah data" . $tabel;
		exit();
	}
}

function tambahStok($nama, $qty, $restock_point, $harga, $kategori_id)
{
	include('config.php');

	$query 	= "INSERT INTO stok (nama, qty, restock_point, harga, kategori_id) VALUES ('$nama', '$qty', '$restock_point', $harga, $kategori_id)";
	$tambah	= mysqli_query($koneksi, $query);

	if (!$tambah) {
		echo "Gagal mmenambah data stok";
		exit();
	}
}

function addToCart($id)
{
	// $id == id stok
	include('config.php');

	// Get data stok
	$queryStok = "SELECT nama, qty, restock_point FROM stok WHERE id=$id LIMIT 1";
	$resultStok = mysqli_query($koneksi, $queryStok);
	$stok = mysqli_fetch_assoc($resultStok);

	// Check if stok exist
	$result = mysqli_query($koneksi, "SELECT stok_id, qty FROM cart WHERE stok_id='$id' LIMIT 1");
	$row = mysqli_fetch_assoc($result);

	if ($row) {
		// Update Stok
		// Check if stok habis
		if ($row['qty'] >= $stok['qty']) {
			$_SESSION['message_warning'] = "Qty " . $stok['nama'] . " telah mencapai batas maksimum";
		} else {
			$newQty = $row['qty'] + 1;
			$query = "UPDATE cart SET qty=$newQty WHERE stok_id=$id";
			$update	= mysqli_query($koneksi, $query);

			if (!$update) {
				echo "Gagal update data cart";
				exit();
			}

			$_SESSION['message'] = "Qty " . $stok['nama'] . " berhasil ditambahkan";
		}
	} else {
		// Tambah Stok
		$query 	= "INSERT INTO cart (stok_id, qty) VALUES ('$id', '1')";
		$tambah	= mysqli_query($koneksi, $query);

		if (!$tambah) {
			echo "Gagal mmenambah data cart";
			exit();
		}

		$_SESSION['message'] =  $stok['nama'] . " berhasil ditambahkan ke Keranjang";
	}
}

function updateCart($cart_id, $stok_id, $updated_qty)
{
	include('config.php');

	$queryStok = "SELECT nama, qty FROM stok WHERE id=$stok_id LIMIT 1";
	$resultStok = mysqli_query($koneksi, $queryStok);
	$stok = mysqli_fetch_assoc($resultStok);

	if ($updated_qty >= $stok['qty']) {
		$_SESSION['message_warning'] = "Qty baru " . $stok['nama'] . " melebihi batas maksimum";
	} else if ($updated_qty <= 0) {
		// Delete from cart
		$query 	= "DELETE FROM cart WHERE id=$cart_id";
		mysqli_query($koneksi, $query);

		$_SESSION['message'] =  $stok['nama'] . " berhasil dihapus dari Keranjang";
	} else {
		$query = "UPDATE cart SET qty=$updated_qty WHERE id=$cart_id";
		$update	= mysqli_query($koneksi, $query);

		if (!$update) {
			echo "Gagal update data cart";
			exit();
		}

		$_SESSION['message'] = "Qty " . $stok['nama'] . " berhasil di-update";
	}
}

function deleteStok($id)
{
	include('config.php');

	// hapus record dari tabel stok
	$query 	= "DELETE FROM stok WHERE id=$id";
	mysqli_query($koneksi, $query);
}

function deleteFromCart($id)
{
	include('config.php');

	// hapus record dari tabel cart
	$query 	= "DELETE FROM cart WHERE id=$id";
	mysqli_query($koneksi, $query);

	$_SESSION['message'] =  "barang berhasil dihapus dari Keranjang";
}

// hapus kriteria
function deleteKriteria($id)
{
	include('config.php');

	// hapus record dari tabel kriteria
	$query 	= "DELETE FROM kriteria WHERE id=$id";
	mysqli_query($koneksi, $query);

	// hapus record dari tabel pv_kriteria
	$query 	= "DELETE FROM pv_kriteria WHERE id_kriteria=$id";
	mysqli_query($koneksi, $query);

	// hapus record dari tabel pv_alternatif
	$query 	= "DELETE FROM pv_alternatif WHERE id_kriteria=$id";
	mysqli_query($koneksi, $query);

	$query 	= "DELETE FROM perbandingan_kriteria WHERE kriteria1=$id OR kriteria2=$id";
	mysqli_query($koneksi, $query);

	$query 	= "DELETE FROM perbandingan_alternatif WHERE pembanding=$id";
	mysqli_query($koneksi, $query);
}

// hapus alternatif
function deleteAlternatif($id)
{
	include('config.php');

	// hapus record dari tabel alternatif
	$query 	= "DELETE FROM alternatif WHERE id=$id";
	mysqli_query($koneksi, $query);

	// hapus record dari tabel pv_alternatif
	$query 	= "DELETE FROM pv_alternatif WHERE id_alternatif=$id";
	mysqli_query($koneksi, $query);

	// hapus record dari tabel ranking
	$query 	= "DELETE FROM ranking WHERE id_alternatif=$id";
	mysqli_query($koneksi, $query);

	$query 	= "DELETE FROM perbandingan_alternatif WHERE alternatif1=$id OR alternatif2=$id";
	mysqli_query($koneksi, $query);
}

// memasukkan nilai priority vektor kriteria
function inputKriteriaPV($id_kriteria, $pv)
{
	include('config.php');

	$query = "SELECT * FROM pv_kriteria WHERE id_kriteria=$id_kriteria";
	$result = mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Error !!!";
		exit();
	}

	// jika result kosong maka masukkan data baru
	// jika telah ada maka diupdate
	if (mysqli_num_rows($result) == 0) {
		$query = "INSERT INTO pv_kriteria (id_kriteria, nilai) VALUES ($id_kriteria, $pv)";
	} else {
		$query = "UPDATE pv_kriteria SET nilai=$pv WHERE id_kriteria=$id_kriteria";
	}


	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal memasukkan / update nilai priority vector kriteria";
		exit();
	}
}

// memasukkan nilai priority vektor alternatif
function inputAlternatifPV($id_alternatif, $id_kriteria, $pv)
{
	include('config.php');

	$query  = "SELECT * FROM pv_alternatif WHERE id_alternatif = $id_alternatif AND id_kriteria = $id_kriteria";
	$result = mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Error !!!";
		exit();
	}

	// jika result kosong maka masukkan data baru
	// jika telah ada maka diupdate
	if (mysqli_num_rows($result) == 0) {
		$query = "INSERT INTO pv_alternatif (id_alternatif,id_kriteria,nilai) VALUES ($id_alternatif,$id_kriteria,$pv)";
	} else {
		$query = "UPDATE pv_alternatif SET nilai=$pv WHERE id_alternatif=$id_alternatif AND id_kriteria=$id_kriteria";
	}

	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal memasukkan / update nilai priority vector alternatif";
		exit();
	}
}


// memasukkan bobot nilai perbandingan kriteria
function inputDataPerbandinganKriteria($kriteria1, $kriteria2, $nilai)
{
	include('config.php');

	$id_kriteria1 = getKriteriaID($kriteria1);
	$id_kriteria2 = getKriteriaID($kriteria2);

	$query  = "SELECT * FROM perbandingan_kriteria WHERE kriteria1 = $id_kriteria1 AND kriteria2 = $id_kriteria2";
	$result = mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Error !!!";
		exit();
	}

	// jika result kosong maka masukkan data baru
	// jika telah ada maka diupdate
	if (mysqli_num_rows($result) == 0) {
		$query = "INSERT INTO perbandingan_kriteria (kriteria1,kriteria2,nilai) VALUES ($id_kriteria1,$id_kriteria2,$nilai)";
	} else {
		$query = "UPDATE perbandingan_kriteria SET nilai=$nilai WHERE kriteria1=$id_kriteria1 AND kriteria2=$id_kriteria2";
	}

	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal memasukkan data perbandingan";
		exit();
	}
}

// memasukkan bobot nilai perbandingan alternatif
function inputDataPerbandinganAlternatif($alternatif1, $alternatif2, $pembanding, $nilai)
{
	include('config.php');


	$id_alternatif1 = getAlternatifID($alternatif1);
	$id_alternatif2 = getAlternatifID($alternatif2);
	$id_pembanding  = getKriteriaID($pembanding);

	$query  = "SELECT * FROM perbandingan_alternatif WHERE alternatif1 = $id_alternatif1 AND alternatif2 = $id_alternatif2 AND pembanding = $id_pembanding";
	$result = mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Error !!!";
		exit();
	}

	// jika result kosong maka masukkan data baru
	// jika telah ada maka diupdate
	if (mysqli_num_rows($result) == 0) {
		$query = "INSERT INTO perbandingan_alternatif (alternatif1,alternatif2,pembanding,nilai) VALUES ($id_alternatif1,$id_alternatif2,$id_pembanding,$nilai)";
	} else {
		$query = "UPDATE perbandingan_alternatif SET nilai=$nilai WHERE alternatif1=$id_alternatif1 AND alternatif2=$id_alternatif2 AND pembanding=$id_pembanding";
	}

	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal memasukkan data perbandingan";
		exit();
	}
}

// mencari nilai bobot perbandingan kriteria
function getNilaiPerbandinganKriteria($kriteria1, $kriteria2)
{
	include('config.php');

	$id_kriteria1 = getKriteriaID($kriteria1);
	$id_kriteria2 = getKriteriaID($kriteria2);

	$query  = "SELECT nilai FROM perbandingan_kriteria WHERE kriteria1 = $id_kriteria1 AND kriteria2 = $id_kriteria2";
	$result = mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Error !!!";
		exit();
	}

	if (mysqli_num_rows($result) == 0) {
		$nilai = 1;
	} else {
		while ($row = mysqli_fetch_array($result)) {
			$nilai = $row['nilai'];
		}
	}

	return $nilai;
}

// mencari nilai bobot perbandingan alternatif
function getNilaiPerbandinganAlternatif($alternatif1, $alternatif2, $pembanding)
{
	include('config.php');

	$id_alternatif1 = getAlternatifID($alternatif1);
	$id_alternatif2 = getAlternatifID($alternatif2);
	$id_pembanding  = getKriteriaID($pembanding);

	$query  = "SELECT nilai FROM perbandingan_alternatif WHERE alternatif1 = $id_alternatif1 AND alternatif2 = $id_alternatif2 AND pembanding = $id_pembanding";
	$result = mysqli_query($koneksi, $query);

	if (!$result) {
		echo "Error !!!";
		exit();
	}
	if (mysqli_num_rows($result) == 0) {
		$nilai = 1;
	} else {
		while ($row = mysqli_fetch_array($result)) {
			$nilai = $row['nilai'];
		}
	}

	return $nilai;
}

// menampilkan nilai IR
function getNilaiIR($jmlKriteria)
{
	include('config.php');
	$query  = "SELECT nilai FROM ir WHERE jumlah=$jmlKriteria";
	$result = mysqli_query($koneksi, $query);
	while ($row = mysqli_fetch_array($result)) {
		$nilaiIR = $row['nilai'];
	}

	return $nilaiIR;
}

// mencari Principe Eigen Vector (λ maks)
function getEigenVector($matrik_a, $matrik_b, $n)
{
	$eigenvektor = 0;
	for ($i = 0; $i <= ($n - 1); $i++) {
		$eigenvektor += ($matrik_a[$i] * (($matrik_b[$i]) / $n));
	}

	return $eigenvektor;
}

// mencari Cons Index
function getConsIndex($matrik_a, $matrik_b, $n)
{
	$eigenvektor = getEigenVector($matrik_a, $matrik_b, $n);
	$consindex = ($eigenvektor - $n) / ($n - 1);

	return $consindex;
}

// Mencari Consistency Ratio
function getConsRatio($matrik_a, $matrik_b, $n)
{
	$consindex = getConsIndex($matrik_a, $matrik_b, $n);
	$consratio = $consindex / getNilaiIR($n);

	return $consratio;
}

// menampilkan tabel perbandingan bobot
function showTabelPerbandingan($jenis, $kriteria)
{
	include('config.php');

	if ($kriteria == 'kriteria') {
		$n = getJumlahKriteria();
	} else {
		$n = getJumlahAlternatif();
	}

	$query = "SELECT nama FROM $kriteria ORDER BY id";
	$result	= mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Error koneksi database!!!";
		exit();
	}

	// buat list nama pilihan
	while ($row = mysqli_fetch_array($result)) {
		$pilihan[] = $row['nama'];
	}

	// tampilkan tabel
?>

	<form class="ui form" action="proses.php" method="post">
		<table class="ui celled selectable collapsing table">
			<thead>
				<tr>
					<th colspan="2">pilih yang lebih penting</th>
					<th>nilai perbandingan</th>
				</tr>
			</thead>
			<tbody>

				<?php

				//inisialisasi
				$urut = 0;

				for ($x = 0; $x <= ($n - 2); $x++) {
					for ($y = ($x + 1); $y <= ($n - 1); $y++) {

						$urut++;

				?>
						<tr>
							<td>
								<div class="field">
									<div class="ui radio checkbox">
										<input name="pilih<?php echo $urut ?>" value="1" checked="" class="hidden" type="radio">
										<label><?php echo $pilihan[$x]; ?></label>
									</div>
								</div>
							</td>
							<td>
								<div class="field">
									<div class="ui radio checkbox">
										<input name="pilih<?php echo $urut ?>" value="2" class="hidden" type="radio">
										<label><?php echo $pilihan[$y]; ?></label>
									</div>
								</div>
							</td>
							<td>
								<div class="field">

									<?php
									if ($kriteria == 'kriteria') {
										$nilai = getNilaiPerbandinganKriteria($x, $y);
									} else {
										$nilai = getNilaiPerbandinganAlternatif($x, $y, ($jenis - 1));
									}

									?>
									<select name="bobot<?php echo $urut ?>" required>
										<option value="">--pilih satu--</option>
										<option value="1" <?php echo $nilai ?>>(1) Sama pentingnya</option>
										<option value="2" <?php echo $nilai ?>>(2) Sama hingga sedikit lebih penting</option>
										<option value="3" <?php echo $nilai ?>>(3) Sedikit lebih penting</option>
										<option value="4" <?php echo $nilai ?>>(4) Sedikit lebih hingga jelas lebih penting</option>
										<option value="5" <?php echo $nilai ?>>(5) Jelas lebih penting</option>
										<option value="6" <?php echo $nilai ?>>(6) Jelas hingga sangat jelas lebih penting</option>
										<option value="7" <?php echo $nilai ?>>(7) Sangat jelas lebih penting</option>
										<option value="8" <?php echo $nilai ?>>(8) Sangat jelas hingga mutlak lebih penting</option>
										<option value="9" <?php echo $nilai ?>>(9) Mutlak lebih penting </option>
								</div>
							</td>
						</tr>
				<?php
					}
				}

				?>
			</tbody>
		</table>
		<input type="text" name="jenis" value="<?php echo $jenis; ?>" hidden>
		<br><br><input class="ui submit button" type="submit" name="submit" value="SUBMIT">
	</form>

<?php
}

?>