<?php
// session_start();
include('config.php');
include('fungsi.php');

if (isset($_POST['addToCart'])) {
    $id = $_POST['id'];

    addToCart($id);
}

if (isset($_POST['restock'])) {
    $id = $_POST['id'];

    // $query = "SELECT id FROM restocks WHERE stok_id=$id";
    $query  = "SELECT count(*) as restock_count FROM restocks WHERE stok_id=$id";
    $select = mysqli_query($koneksi, $query);
    $restock_count = mysqli_fetch_array($select)['restock_count'];

    // echo $restock_count;

    if ($restock_count <= 0) {
        header('Location: bobot_kriteria.php?id=' . $id);
    } else {
        header('Location: restock_details.php?id=' . $id);
    }
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
        <div class="four wide column">
            <div style="display: flex; align-items:baseline">
                <h2 class="ui header">Stok</h2>
                <div id="orange" style="margin-left: 20px;"></div>&nbsp;Perlu Restock
            </div>
        </div>
        <div class="twelve wide column">
            <form action="stok.php" class="right floated ui form">
                <div class="inline fields">
                    <div class="field">
                        <label for="kategori">Filter Kategori</label>
                        <select class="ui dropdown" name="kategori_id" required id="kategori_id">
                            <option value="0" <?php echo isset($_GET['kategori_id']) && $_GET['kategori_id'] == 0 ? 'selected' : '' ?>>Semua Kategori</option>
                            <?php
                            $query = "SELECT id,nama FROM kategori ORDER BY id";
                            $result = mysqli_query($koneksi, $query);
                            $i = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                $i++;
                            ?>
                                <option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['kategori_id']) && $_GET['kategori_id'] == $row['id'] ? 'selected' : '' ?>><?php echo $row['nama'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="field">
                        <a href="tambahStok.php">
                            <div class="ui primary labeled icon button">
                                <i class="plus icon"></i>Tambah Stok
                            </div>
                        </a>
                    </div>
                </div>
            </form>
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

    <table class="ui celled table" id="myTable">
        <thead>
            <tr>
                <th class="collapsing">No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Qty</th>
                <th>Restock Point</th>
                <th class="right aligned">Harga</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menampilkan list stok
            if (isset($_GET['kategori_id']) && $_GET['kategori_id'] != 0) {
                $kategori_id = $_GET['kategori_id'];
                $query = "SELECT id,nama,qty,restock_point,harga,kategori_id FROM stok WHERE kategori_id = '$kategori_id' ORDER BY id";
            } else {
                $query = "SELECT id,nama,qty,restock_point,harga,kategori_id FROM stok ORDER BY id";
            }
            $result = mysqli_query($koneksi, $query);
            $i = 0;
            while ($row = mysqli_fetch_array($result)) {
                $kategori_id = $row['kategori_id'];
                $queryKategori = "SELECT nama FROM kategori WHERE id=$kategori_id LIMIT 1";
                $resultKategori = mysqli_query($koneksi, $queryKategori);
                $kategori = mysqli_fetch_assoc($resultKategori);
                $i++;
            ?>
                <tr style="background-color: <?php echo $row['qty'] < $row['restock_point'] ? '#FFE57F' : 'none' ?>">
                    <td><?php echo $i ?></td>
                    <td><?php echo $row['nama'] ?></td>
                    <td><?php echo $kategori['nama'] ?></td>
                    <td><?php echo $row['qty'] ?> pcs</td>
                    <td><?php echo $row['restock_point'] ?> pcs</td>
                    <td class="right aligned">Rp <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
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
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.11.3/sorting/currency.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.11.3/sorting/natural.js"></script>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    $(document).ready(function() {
        $('#myTable').DataTable({
            columnDefs: [{
                    type: 'natural',
                    targets: 3
                },
                {
                    type: 'natural',
                    targets: 4
                },
                {
                    type: 'currency',
                    targets: 5
                },
                {
                    orderable: false,
                    targets: 6
                }
            ]
        });

        $('#kategori_id').change(function() {
            // $('#select_date').click();
            this.form.submit();
        });
    });
</script>