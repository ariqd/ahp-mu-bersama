<?php
include('config.php');
include('fungsi.php');

if (isset($_POST['checkout'])) {
    // Save to orders table
    $query_sum_qty = "SELECT sum(qty) FROM cart";
    $result_sum_qty = mysqli_query($koneksi, $query_sum_qty);
    while ($row_sum_qty = mysqli_fetch_array($result_sum_qty)) {
        $qty = $row_sum_qty[0];
    }

    $total = $_POST['total'];
    $created_at = date('Y-m-d H:i:s');

    $query_insert_orders = "INSERT INTO orders (qty, total, created_at) VALUES ('$qty', '$total', '$created_at')";
    $insert_orders = mysqli_query($koneksi, $query_insert_orders);

    if (!$insert_orders) {
        echo "Gagal mmenambah data orders";
        exit();
    }

    // Save to order details table
    $order_id = mysqli_insert_id($koneksi);

    $query_cart = "SELECT id,stok_id,qty FROM cart ORDER BY id";
    $result_cart = mysqli_query($koneksi, $query_cart);
    $i = 0;
    $total = 0;
    while ($cart = mysqli_fetch_array($result_cart)) {
        $stok_id = $cart['stok_id'];
        $qty_beli = $cart['qty'];

        $query_stok = "SELECT nama, harga, qty, kategori_id FROM stok WHERE id=$stok_id LIMIT 1";
        $result_stok = mysqli_query($koneksi, $query_stok);
        $stok = mysqli_fetch_assoc($result_stok);
        $nama = $stok['nama'];
        $harga = $stok['harga'];
        $qty_now = $stok['qty'];
        $kategori_id = $stok['kategori_id'];

        // Insert into order details table
        $query_order_details = "INSERT INTO order_details (order_id, stok_id, nama, qty_beli, harga, kategori_id) VALUES ('$order_id', '$stok_id', '$nama', '$qty_beli', '$harga', '$kategori_id')";
        $insert_order_details = mysqli_query($koneksi, $query_order_details);

        if (!$insert_order_details) {
            echo "Gagal mmenambah data order details";
            exit();
        }

        // Decrease qty according to qty sold
        $updated_qty = $qty_now - $qty_beli;
        $query_update_stok = "UPDATE stok SET qty=$updated_qty WHERE id=$stok_id";
        $update_stok = mysqli_query($koneksi, $query_update_stok);

        if (!$update_stok) {
            echo "Gagal update data cart";
            exit();
        }
    }

    // Clear cart table
    $query_truncate_cart = "TRUNCATE TABLE cart";
    $truncate_cart = mysqli_query($koneksi, $query_truncate_cart);

    if (!$truncate_cart) {
        echo "Gagal TRUNCATE cart";
        exit();
    }

    // Redirect to orders page
    header('Location: stok.php');
}

if (isset($_POST['updatedQty'])) {
    $id = $_POST['id']; // CART ID
    $stok_id = $_POST['stok_id']; // STOK ID
    $updated_qty = $_POST['updatedQty']; // NEW QTY
    // $previous_qty = $_POST['previous_qty']; // NEW QTY

    // echo $id;

    updateCart($id, $stok_id, $updated_qty);
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];  // CART ID

    deleteFromCart($id);
}

include('header.php');
?>

<section class="content">
    <div class="ui grid">
        <div class="two wide column">
            <h2 class="ui header">Cart</h2>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])) : ?>
        <div class="ui positive message">
            <i class="close icon"></i>
            <div class="header">
                Success!
            </div>
            <?php echo $_SESSION['message']; ?>
        </div>
    <?php endif; ?>
    <?php unset($_SESSION['message']); ?>

    <?php if (isset($_SESSION['message_warning'])) : ?>
        <div class="ui negative message">
            <i class="close icon"></i>
            <div class="header">
                Failed!
            </div>
            <?php echo $_SESSION['message_warning']; ?>
        </div>
    <?php endif; ?>
    <?php unset($_SESSION['message_warning']); ?>

    <table class="ui celled table">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Nama Barang</th>
                <th class="collapsing">Qty di Keranjang</th>
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
                    <td>
                        <form method="post" action="cart.php" id="updateQtyForm">
                            <div class="ui transparent input" style="border-bottom: 1px solid #eee;padding-bottom: 5px;">
                                <input type="hidden" name="id" value="<?php echo $cart['id'] ?>">
                                <input type="hidden" name="stok_id" value="<?php echo $stok['id'] ?>">
                                <input type="number" placeholder="Qty" value="<?php echo $cart['qty'] ?>" style="width: 70px;text-align:center;" name="updatedQty" id="updateQty<?php echo $cart['id'] ?>">
                            </div>
                            &nbsp;pcs
                        </form>
                    </td>
                    <td class="right aligned">Rp <?php echo number_format($stok['harga'], 0, ',', '.') ?></td>
                    <td class="right aligned">Rp <?php echo number_format($subtotal, 0, ',', '.') ?></td>
                    <td class="right aligned collapsing">
                        <form method="post" action="cart.php">
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

    <br>

    <button class="ui right labeled green icon button" style="float: right;" id="checkout">
        <i class="right arrow icon"></i>
        Checkout
    </button>

    <div class="ui small modal checkout">
        <i class="close icon"></i>
        <div class="header">
            Konfirmasi Pesanan
        </div>
        <div class="image content">
            <div class="description">
                <div class="ui header">Stok akan dikurangi sesuai pesanan</div>
                <p>Yakin akan melanjutkan?</p>
            </div>
        </div>
        <div class="actions">
            <form action="cart.php" method="POST">
                <div class="ui black deny button">
                    Tidak
                </div>
                <input type="hidden" value="<?php echo $total ?>" name="total">
                <button class="ui positive right labeled icon button" name="checkout">
                    Ya, Selesaikan Pesanan
                    <i class="checkmark icon"></i>
                </button>
            </form>
        </div>
    </div>

</section>

<?php include('footer.php'); ?>



<script>
    $(function() {
        $("#checkout").click(function() {
            $(".checkout").modal('show');
        });
        $(".checkout").modal({
            closable: true
        });
    });
</script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    $(document).ready(function() {
        $('[id^=updateQty]').change(function() {
            // $('#select_date').click();
            this.form.submit();
        });
    });
</script>