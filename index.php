<?php

include("koneksi.php");
// Variabel bantuan untuk menampung pesan sukses atau error di layar
$sukses = "";
$error  = "";
// ==========================================
// A. LOGIKA FITUR DELETE (HAPUS DATA)
// ==========================================
// Memeriksa apakah ada perintah hapus dari URL (ketika tombol Delete diklik)
if (isset($_GET['op']) && $_GET['op'] == 'delete') {
    $id = $_GET['id']; // Mengambil ID data yang mau dihapus
    
    // Kueri SQL untuk menghapus data berdasarkan ID spesifik agar tidak menghapus seluruh tabel
    $sql_delete = "DELETE FROM menu WHERE id = '$id'";
    $q_delete   = mysqli_query($koneksi, $sql_delete);
    
    if ($q_delete) {
        $sukses = "Data menu berhasil dihapus!";
    } else {
        $error  = "Gagal menghapus data menu.";
    }
}

// ==========================================
// B. LOGIKA FITUR UPDATE - TAHAP 1 (AMBIL DATA LAMA KETIKA TOMBOL EDIT DIKLIK)
// ==========================================
// Siapkan variabel kosong untuk menampung data lama di formulir
$kode_menu_lama = "";
$nama_menu_lama = "";
$kategori_lama  = "";
$harga_lama     = "";
$status_lama    = "";

// Memeriksa apakah ada perintah edit dari URL (ketika tombol Edit diklik)
if (isset($_GET['op']) && $_GET['op'] == 'edit') {
    $id = $_GET['id']; // Mengambil ID data yang mau diedit
    
    // Mencari data menu lama berdasarkan ID tersebut
    $sql_edit = "SELECT * FROM menu WHERE id = '$id'";
    $q_edit   = mysqli_query($koneksi, $sql_edit);
    $r_edit   = mysqli_fetch_array($q_edit);
    
    if ($r_edit) {
        // Memasukkan data lama dari database ke dalam variabel
        $kode_menu_lama = $r_edit['kode_menu'];
        $nama_menu_lama = $r_edit['nama_menu'];
        $kategori_lama  = $r_edit['kategori'];
        $harga_lama     = $r_edit['harga'];
        $status_lama    = $r_edit['status'];
    } else {
        $error = "Data tidak ditemukan!";
    }
}
// 1. Memeriksa apakah tombol "Simpan Data" sudah diklik oleh pengguna
if (isset($_POST['simpan'])) {
    
    // Ambil ID dari input hidden untuk mendeteksi apakah ini mode edit atau tambah baru
    $id        = $_POST['id']; 
    
    // Mengambil data ketikan dari form
    $kode_menu = $_POST['kode_menu'];
    $nama_menu = $_POST['nama_menu'];
    $kategori  = $_POST['kategori'];
    $harga     = $_POST['harga'];
    $status    = $_POST['status'];

    // 2. VALIDASI 1: Memeriksa apakah ada field/kolom yang dibiarkan kosong
    if ($kode_menu == "" || $nama_menu == "" || $kategori == "" || $harga == "" || $status == "") {
        $error = "Gagal menyimpan! Silakan isi semua kolom data terlebih dahulu.";
    } else {
        
        // 3. VALIDASI 2: Memeriksa apakah input harga berupa angka dan di atas 0 (Ketentuan Tugas)
        if (!is_numeric($harga) || $harga <= 0) {
            $error = "Gagal menyimpan! Kolom Harga harus diisi dengan angka yang valid.";
        } else {
            
            // PERCABANGAN UTAMA: Cek apakah data ini mau di-UPDATE atau di-INSERT
            if ($id != "") {
                // A. Jika ID ada possesses nilai, lakukan UPDATE data lama berdasarkan ID tersebut
                $sql1 = "UPDATE menu SET 
                            kode_menu = '$kode_menu', 
                            nama_menu = '$nama_menu', 
                            kategori  = '$kategori', 
                            harga     = '$harga', 
                            status    = '$status' 
                         WHERE id = '$id'";
                $q1   = mysqli_query($koneksi, $sql1);

                if ($q1) {
                    $sukses = "Data menu berhasil diperbarui!";
                } else {
                    $error  = "Gagal memperbarui data menu di database.";
                }
            } else {
                // B. Jika ID kosong, lakukan INSERT data sebagai menu baru
                $sql1 = "INSERT INTO menu (kode_menu, nama_menu, kategori, harga, status) 
                         VALUES ('$kode_menu', '$nama_menu', '$kategori', '$harga', '$status')";
                $q1   = mysqli_query($koneksi, $sql1);

                if ($q1) {
                    $sukses = "Yey! Data menu baru berhasil ditambahkan ke database.";
                } else {
                    $error  = "Duh, gagal memasukkan data baru. Cek apakah Kode Menu sudah pernah dipakai!";
                }
            }
            
        }
    }
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD Manajemen Menu Kafe</title>

    <link href="https://jsdelivr.net" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto { width: 800px; }
        .card { margin-top: 20px: }
    </style>
</head>
<body>
    <div class="container mx-auto">


        <div class="card">
            <div class="card-header bg-dark text-white">
                Tambah / Edit Data Menu Kafe
            </div>
            <div class="card-body">
                <!-- Menampilkan kotak peringatan merah jika terjadi error/validasi gagal -->
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <!-- Menampilkan kotak peringatan hijau jika data berhasil disimpan -->
                <?php if ($sukses): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST">
                
                    <!-- Input tersembunyi untuk menyimpan ID data yang sedang diedit -->
                    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                    <input type="hidden" name="op" value="<?php echo $op; ?>">

                    <!-- Input field untuk Kode Menu -->
                    <div class="mb-3 row">
                        <label for="kode_menu" class="col-sm-2 col-form-label">Kode Menu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="kode_menu" name="kode_menu" value="<?php echo $kode_menu_lama; ?>" placeholder="Contoh: MN001">
                        </div>
                        </div>

                    <!-- Input field untuk Nama Menu -->
                    <div class="mb-3 row">
                        <label for="nama_menu" class="col-sm-2 col-form-label">Nama Menu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu" value="<?php echo $nama_menu_lama; ?>" placeholder="Contoh: Es Kopi Susu">
                        </div>
                        </div>

                    <!-- Input pilihan (Dropdown) untuk Kategori Menu -->
                    <div class="mb-3 row">
                        <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih Kategori -</option>
                                <option value="Makanan" <?php if ($kategori_lama == "Makanan") echo "selected"; ?>>Makanan</option>
                                <option value="Minuman" <?php if ($kategori_lama == "Minuman") echo "selected"; ?>>Minuman</option>
                                <option value="Camilan" <?php if ($kategori_lama == "Camilan") echo "selected"; ?>>Camilan</option>
                            </select>
                        </div>
                        </div>

                    <!-- Input field untuk Harga -->
                    <div class="mb-3 row">
                        <label for="harga" class="col-sm-2 col-form-label">Harga (Rp)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="harga" name="harga" value="<?php echo $harga_lama; ?>" placeholder="Contoh: 15000">
                        </div>
                        </div>

                    <!-- Input pilihan (Dropdown) untuk Status Ketersediaan -->
                    <div class="mb-3 row">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="status" name="status">
                                <option value="">- Pilih Status -</option>
                                <option value="Tersedia" <?php if ($status_lama == "Tersedia") echo "selected"; ?>>Tersedia</option>
                                <option value="Habis" <?php if ($status_lama == "Habis") echo "selected"; ?>>Habis</option>
                            </select>
                        </div>
                        </div>

                    <!-- Tombol Simpan Data -->
                    <div class="col-12 text-end">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
            </div>

        <!-- Bagian 3: TABEL UNTUK MENAMPILKAN DATA DARI DATABASE -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Daftar Menu Kafe
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Kode Menu</th>
                            <th scope="col">Nama Menu</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 1. Kueri SQL untuk mengambil semua data dari tabel menu
                        // Menggunakan ORDER BY id DESC agar data yang paling baru diinput otomatis muncul paling atas
                        $sql2   = "SELECT * FROM menu ORDER BY id DESC";
                        $q2     = mysqli_query($koneksi, $sql2);

                        // Variabel penomoran otomatis untuk tabel di layar
                        $nomor  = 1;

                        // 2. Melakukan perulangan (loop) selama data di database masih ada
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id        = $r2['id'];
                            $kode_menu = $r2['kode_menu'];
                            $nama_menu = $r2['nama_menu'];
                            $kategori  = $r2['kategori'];
                            $harga     = $r2['harga'];
                            $status    = $r2['status'];
                            ?>
                            <tr>
                                <th scope="row"><?php echo $nomor++; ?></th>
                                <td><?php echo $kode_menu; ?></td>
                                <td><?php echo $nama_menu; ?></td>
                                <td><?php echo $kategori; ?></td>
                                <!-- Format harga menggunakan number_format agar rapi ada titik ribuannya -->
                                <td>Rp <?php echo number_format($harga, 0, ',', '.'); ?></td>
                                <td>
                                    <!-- Memberikan warna badge yang berbeda berdasarkan status biar keren -->
                                    <span class="badge <?php echo ($status == 'Tersedia') ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- Tombol Aksi Edit dan Delete dengan parameter ID unik masing-masing menu -->
                                    <a href="index.php?op=edit&id=<?php echo $id; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="index.php?op=delete&id=<?php echo $id; ?>" onclick="return confirm('Apakah kamu yakin ingin menghapus menu ini?')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <?php
}
?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>