<?php

include('config.php');
include('fungsi.php');

if (isset($_POST['restock'])) {
	$stok_id = $_POST['stok_id'];
	$alternatif_id = $_POST['alternatif_id'];
	// $qty_restock = $_POST['qty_restock'];
	$created_at = date('Y-m-d H:i:s');

	// $query = "INSERT INTO restocks (created_at, alternatif_id, stok_id, qty_restock) VALUES ('$created_at', '$alternatif_id', '$stok_id', '$qty_restock')";
	$query = "INSERT INTO restocks (created_at, alternatif_id, stok_id) VALUES ('$created_at', '$alternatif_id', '$stok_id')";
	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal mengupdate restocks";
		exit();
	}

	// $queryStok = "SELECT qty FROM stok WHERE id=$stok_id LIMIT 1";
	// $resultStok = mysqli_query($koneksi, $queryStok);
	// $stok = mysqli_fetch_assoc($resultStok);

	// $newStok = $stok['qty'] + $qty_restock;

	// $query = "UPDATE stok SET qty=$newStok WHERE id=$stok_id";
	// $update	= mysqli_query($koneksi, $query);
	// if (!$update) {
	// 	echo "Gagal update data stok";
	// 	exit();
	// }

	header('Location: stok.php');
}

// menghitung perangkingan
$jmlKriteria 	= getJumlahKriteria();
$jmlAlternatif	= getJumlahAlternatif();
$nilai			= array();

// mendapatkan nilai tiap alternatif
for ($x = 0; $x <= ($jmlAlternatif - 1); $x++) {
	// inisialisasi
	$nilai[$x] = 0;

	for ($y = 0; $y <= ($jmlKriteria - 1); $y++) {
		$id_alternatif 	= getAlternatifID($x);
		$id_kriteria	= getKriteriaID($y);

		$pv_alternatif	= getAlternatifPV($id_alternatif, $id_kriteria);
		$pv_kriteria	= getKriteriaPV($id_kriteria);

		$nilai[$x]	 	+= ($pv_alternatif * $pv_kriteria);
	}
}

// update nilai ranking
for ($i = 0; $i <= ($jmlAlternatif - 1); $i++) {
	$id_alternatif = getAlternatifID($i);
	$query = "INSERT INTO ranking VALUES ($id_alternatif,$nilai[$i]) ON DUPLICATE KEY UPDATE nilai=$nilai[$i]";
	$result = mysqli_query($koneksi, $query);
	if (!$result) {
		echo "Gagal mengupdate ranking";
		exit();
	}
}

include('header.php');

?>

<section class="content">
	<h2 class="ui header">Hasil Perhitungan</h2>
	<table class="ui celled table">
		<thead>
			<tr>
				<th>Overall Composite Height</th>
				<th>Priority Vector (rata-rata)</th>
				<?php
				for ($i = 0; $i <= (getJumlahAlternatif() - 1); $i++) {
					echo "<th>" . getAlternatifNama($i) . "</th>\n";
				}
				?>
			</tr>
		</thead>
		<tbody>

			<?php
			for ($x = 0; $x <= (getJumlahKriteria() - 1); $x++) {
				echo "<tr>";
				echo "<td>" . getKriteriaNama($x) . "</td>";
				echo "<td>" . round(getKriteriaPV(getKriteriaID($x)), 5) . "</td>";

				for ($y = 0; $y <= (getJumlahAlternatif() - 1); $y++) {
					echo "<td>" . round(getAlternatifPV(getAlternatifID($y), getKriteriaID($x)), 5) . "</td>";
				}


				echo "</tr>";
			}
			?>
		</tbody>

		<tfoot>
			<tr>
				<th colspan="2">Total</th>
				<?php
				for ($i = 0; $i <= ($jmlAlternatif - 1); $i++) {
					echo "<th>" . round($nilai[$i], 5) . "</th>";
				}
				?>
			</tr>
		</tfoot>

	</table>


	<div class="ui grid">
		<div class="six wide column">
			<h2 class="ui header">Perangkingan</h2>
			<table class="ui celled table">
				<thead>
					<tr>
						<th>Peringkat</th>
						<th>Alternatif</th>
						<th>Nilai</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query  = "SELECT id,nama,id_alternatif,nilai FROM alternatif,ranking WHERE alternatif.id = ranking.id_alternatif ORDER BY nilai DESC";
					$result = mysqli_query($koneksi, $query);

					$i = 0;
					while ($row = mysqli_fetch_array($result)) {
						$i++;
					?>
						<tr>
							<?php if ($i == 1) {
								$firstId = $row['id'];
								echo "<td><div class=\"ui ribbon label\">Pertama</div></td>";
							} else {
								echo "<td>" . $i . "</td>";
							}

							?>

							<td><?php echo $row['nama'] ?></td>
							<td><?php echo $row['nilai'] ?></td>
						</tr>

					<?php
					}


					?>
				</tbody>
			</table>
		</div>
		<div class="ten wide column">
			<h2 class="ui header">Restock</h2>

			<form class="ui form" action="hasil.php" method="POST">
				<div class="inline field">
					<!-- <label>Qty Restock</label> -->
					<!-- <input type="number" name="qty_restock" placeholder="Qty Restock" required min="0" value="1"> -->
					<input type="hidden" name="stok_id" value="<?php echo $_GET['stok_id']; ?>">
					<input type="hidden" name="alternatif_id" value="<?php echo $firstId; ?>">

					<button type="submit" class="ui right labeled green icon button" name="restock">
						<i class="right check icon"></i>
						Simpan Hasil Restock
					</button>
				</div>
			</form>

		</div>
	</div>

</section>

<?php include('footer.php'); ?>