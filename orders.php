<?php
include('config.php');
include('fungsi.php');

// menjalankan perintah edit
if (isset($_POST['details'])) {
    $id = $_POST['id'];

    header('Location: order_details.php?id=' . $id);
    exit();
}

include('header.php');
?>

<section class="content">
    <h2 class="ui header">Pesanan</h2>

    <table class="ui celled table">
        <thead>
            <tr>
                <th>Tanggal Pesanan</th>
                <th>Qty</th>
                <th class="right aligned">Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Menampilkan list Pesanan
            $query = "SELECT id,qty,total,created_at FROM orders ORDER BY id";
            $result = mysqli_query($koneksi, $query);

            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;
            ?>
                <tr>
                    <td><?php echo $row['created_at'] ?></td>
                    <td><?php echo $row['qty'] ?> pcs</td>
                    <td class="right aligned">Rp <?php echo number_format($row['total'], 0, ',', '.') ?></td>
                    <td class="right aligned collapsing">
                        <a href="order_details.php?id=<?php echo $row['id'] ?>" class="ui mini teal left labeled icon button"><i class="right eye icon"></i>DETAILS</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<?php include('footer.php'); ?>