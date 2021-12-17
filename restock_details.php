<?php
include('config.php');
include('fungsi.php');
include('header.php');

$stok_id = $_GET['id'];
$queryStok = "SELECT id,nama FROM stok WHERE id=$stok_id LIMIT 1";
$resultStok = mysqli_query($koneksi, $queryStok);
$stok = mysqli_fetch_assoc($resultStok);

?>

<section class="content">
    <h2 class="ui header">Riwayat Restock <?php echo $stok['nama'] ?></h2>

    <table class="ui celled table">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Vendor</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $query = "SELECT id,alternatif_id,created_at FROM restocks WHERE stok_id = '" . $stok_id . "' ORDER BY created_at DESC";
            $result = mysqli_query($koneksi, $query);

            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;

                $alternatif_id = $row['alternatif_id'];
                $queryAlternatif = "SELECT id,nama FROM alternatif WHERE id=$alternatif_id LIMIT 1";
                $resultAlternatif = mysqli_query($koneksi, $queryAlternatif);
                $alternatif = mysqli_fetch_assoc($resultAlternatif);
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $alternatif['nama'] ?></td>
                    <td><?php echo $row['created_at'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="bobot_kriteria.php?id=<?php echo $_GET['id'] ?>" class="ui small orange left labeled icon button"><i class="right plus icon"></i>RESTOCK</a>

</section>

<?php include('footer.php'); ?>