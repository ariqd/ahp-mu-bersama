<?php
session_start();
include('config.php');
include('fungsi.php');

if (isset($_POST['addToCart'])) {
    $id = $_POST['id'];

    addToCart($id);

    $_SESSION['message'] = "Barang berhasil ditambahkan ke Keranjang";
    // header('Location: stok.php');
}

if (isset($_POST['restock'])) {
    $id = $_POST['id'];

    header('Location: bobot_kriteria.php');
    exit();
}

// menjalankan perintah edit
if (isset($_POST['edit'])) {
    $id = $_POST['id'];

    header('Location: editStok.php?id=' . $id);
    exit();
}

// menjalankan perintah delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    deleteStok($id);
}

// menjalankan perintah tambah
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    tambahData('stok', $nama);
}

include('header.php');
?>

<section class="content">
    <div class="ui grid">
        <div class="two wide column">
            <h2 class="ui header">Stok</h2>
        </div>
        <div class="two wide right floated column">
            <a href="tambahStok.php">
                <div class="ui right floated small primary labeled icon button">
                    <i class="plus icon"></i>Tambah
                </div>
            </a>
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

    <table class="ui celled table">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Restock Point</th>
                <th>Harga</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Menampilkan list stok
            $query = "SELECT id,nama,qty,restock_point,harga FROM stok ORDER BY id";
            $result = mysqli_query($koneksi, $query);
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $i++;
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row['nama'] ?></td>
                    <td><?php echo $row['qty'] ?> pcs</td>
                    <td><?php echo $row['restock_point'] ?> pcs</td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="right aligned collapsing">
                        <form method="post" action="stok.php">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <button type="submit" name="addToCart" class="ui mini green left labeled icon button"><i class="right plus icon"></i>KERANJANG</button>
                            <button type="submit" name="restock" class="ui mini orange left labeled icon button"><i class="right plus icon"></i>RESTOCK</button>
                            <button type="submit" name="edit" class="ui mini teal left labeled icon button"><i class="right edit icon"></i>EDIT</button>
                            <!-- <button type="submit" name="delete" class="ui mini red left labeled icon button"><i class="right remove icon"></i>DELETE</button> -->
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

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>