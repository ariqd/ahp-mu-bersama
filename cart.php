<?php
include('config.php');
include('fungsi.php');

include('header.php');
?>

<section class="content">
    <div class="ui grid">
        <div class="two wide column">
            <h2 class="ui header">Cart</h2>
        </div>
    </div>

    <table class="ui celled table">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Nama Barang</th>
                <th>Qty di Keranjang</th>
                <th class="right aligned">Harga</th>
                <th class="right aligned">Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $queryCart = "SELECT id,stok_id,qty FROM cart ORDER BY id";
            $resultCart = mysqli_query($koneksi, $queryCart);
            $i = 0;
            $total = 0;
            while ($cart = mysqli_fetch_array($resultCart)) {
                $cart_stok_id = $cart['stok_id'];
                $queryStok = "SELECT id, nama, harga FROM stok WHERE id=$cart_stok_id LIMIT 1";
                $resultStok = mysqli_query($koneksi, $queryStok);
                $stok = mysqli_fetch_assoc($resultStok);
                $subtotal = $stok['harga'] * $cart['qty'];
                $total = $total + $subtotal;
                $i++;
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $stok['nama'] ?></td>
                    <td><?php echo $cart['qty'] ?> pcs</td>
                    <td class="right aligned">Rp <?php echo number_format($stok['harga'], 0, ',', '.') ?></td>
                    <td class="right aligned">Rp <?php echo number_format($subtotal, 0, ',', '.') ?></td>
                    <td class="right aligned collapsing">
                        <form method="post" action="stok.php">
                            <input type="hidden" name="id" value="<?php echo $cart['id'] ?>">
                            <button type="submit" name="delete" class="ui mini red left labeled icon button"><i class="right remove icon"></i>REMOVE</button>
                        </form>
                    </td>
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
                <th></th>
            </tr>
        </tfoot>
    </table>

    <!-- <br> -->

    <!-- <form action="alternatif.php">
        <button class="ui right labeled icon button" style="float: right;">
            <i class="right arrow icon"></i>
            Lanjut
        </button>
    </form> -->

</section>

<?php include('footer.php'); ?>