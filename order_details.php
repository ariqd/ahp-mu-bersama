<?php
include('config.php');
include('fungsi.php');

// menjalankan perintah edit
if (isset($_POST['orders'])) {
    $id = $_POST['id'];

    header('Location: order_details.php?id=' . $id);
    exit();
}

include('header.php');
?>

<section class="content">
    <h2 class="ui header">Detail Pesanan</h2>

    <table class="ui celled table">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Nama Barang</th>
                <th>Qty Dibeli</th>
                <th class="right aligned">Harga</th>
                <th class="right aligned">Subtotal</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $total = 0;
            $order_id = $_GET['id'];
            // Menampilkan list Detail Pesanan
            $query = "SELECT id,stok_id,nama,qty_beli,harga,kategori_id FROM order_details WHERE order_id = $order_id ORDER BY id";
            $result = mysqli_query($koneksi, $query);
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;
                $subtotal = $row['harga'] * $row['qty_beli'];
                $total = $total + $subtotal;
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row['nama'] ?></td>
                    <td><?php echo $row['qty_beli'] ?> pcs</td>
                    <td class="right aligned">Rp <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="right aligned">Rp <?php echo number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot class="full-width">
            <tr>
                <th colspan="4" class="right aligned">
                    Total
                </th>
                <th class="right aligned">
                    <h3>Rp <?php echo number_format($total, 0, ',', '.') ?></h3>
                </th>
            </tr>
        </tfoot>
    </table>
</section>

<?php include('footer.php'); ?>