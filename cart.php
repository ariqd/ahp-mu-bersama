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
                <th>Harga</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Menampilkan list stok
            $queryCart = "SELECT id,stok_id,qty FROM cart ORDER BY id";
            $resultCart = mysqli_query($koneksi, $queryCart);
            $i = 0;
            while ($cart = mysqli_fetch_array($resultCart)) {
                $cart_stok_id = $cart['stok_id'];
                $queryStok = "SELECT id, nama, harga FROM stok WHERE id=$cart_stok_id LIMIT 1";
                $resultStok = mysqli_query($koneksi, $queryStok);
                $stok = mysqli_fetch_assoc($resultStok);
                $i++;
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $stok['nama'] ?></td>
                    <td><?php echo $cart['qty'] ?> pcs</td>
                    <td>Rp <?php echo number_format($stok['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?php echo number_format($stok['harga'] * $cart['qty'], 0, ',', '.') ?></td>
                    <td class="right aligned collapsing">
                        <form method="post" action="stok.php">
                            <input type="hidden" name="id" value="<?php echo $cart['id'] ?>">
                            <!-- <button type="submit" name="addToCart" class="ui mini orange left labeled icon button"><i class="right plus icon"></i>ADD TO CART</button>
                            <button type="submit" name="restock" class="ui mini green left labeled icon button"><i class="right plus icon"></i>RESTOCK</button>
                            <button type="submit" name="edit" class="ui mini teal left labeled icon button"><i class="right edit icon"></i>EDIT</button> -->
                            <button type="submit" name="delete" class="ui mini red left labeled icon button"><i class="right remove icon"></i>REMOVE</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
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