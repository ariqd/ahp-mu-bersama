<?php
include('config.php');
include('fungsi.php');
include('header.php');
?>

<section class="content">
    <h2 class="ui header">Riwayat Restock</h2>

    <table class="ui celled table">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Barang</th>
                <th>Vendor</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Menampilkan list Pesanan
            $query = "SELECT id,stok_id,alternatif_id,created_at FROM restocks ORDER BY id";
            $result = mysqli_query($koneksi, $query);

            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;

                $stok_id = $row['stok_id'];
                $queryStok = "SELECT id,nama FROM stok WHERE id=$stok_id LIMIT 1";
                $resultStok = mysqli_query($koneksi, $queryStok);
                $stok = mysqli_fetch_assoc($resultStok);

                $alternatif_id = $row['alternatif_id'];
                $queryAlternatif = "SELECT id,nama FROM alternatif WHERE id=$alternatif_id LIMIT 1";
                $resultAlternatif = mysqli_query($koneksi, $queryAlternatif);
                $alternatif = mysqli_fetch_assoc($resultAlternatif);
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $stok['nama'] ?></td>
                    <td><?php echo $alternatif['nama'] ?></td>
                    <td><?php echo $row['created_at'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include('footer.php'); ?>