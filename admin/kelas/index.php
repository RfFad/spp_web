<?php
include '../../config/db.php';
session_start();
$nama = "";
$keahlian = "";
$id = "";
$nama_kelas= "";
$kompetensi = "";

if(isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "";
}

if(isset($_POST['simpan'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $nama_kelas = $_POST['nama_kelas'];
    $kompetensi = $_POST['kompetensi'];
    if(!empty($id)){
        $updateQuery = $koneksi->prepare("UPDATE kelas SET nama_kelas = ?, kompetensi_keahlian = ? WHERE id_kelas = ?");
        $updateQuery->bind_param("ssi", $nama_kelas, $kompetensi, $id);
        if($updateQuery->execute()){
            echo "<script>alert('Berhasil Mengupdate Data!'); window.location = 'index.php'</script>";
        }else{
            echo "<script>alert('Gagal Mengupdate Data!'); window.location = 'index.php'</script>";
        }
    }else{
        $insertQuery = $koneksi->prepare("INSERT INTO kelas (nama_kelas, kompetensi_keahlian)VALUES(?, ?)");
        $insertQuery->bind_param("ss", $nama_kelas, $kompetensi);
        if($insertQuery->execute()){
            echo "<script>alert('Berhasil Menambahkan Data!'); </script>";
        }else{
            echo "<script>alert('Gagal Menambahkan Data!'); window.location = 'index.php'</script>";
        }
    }
}
if($action === 'edit'){
    $id = $_GET['id'];
    $queryGet = $koneksi->prepare("SELECT * FROM kelas WHERE id_kelas= ?");
    $queryGet->bind_param("i", $id);
    $queryGet->execute();
    $resultGet = $queryGet->get_result();
    $getRow = $resultGet->fetch_assoc();
    if($getRow){
    $nama = $getRow['nama_kelas'];
    $keahlian = $getRow['kompetensi_keahlian'];
    }else{
        echo "<script>alert('Gagal Menemukan Data!'); window.location = 'index.php'</script>";
        
    }
}
if($action === 'hapus'){
    $id = $_GET['id'];
    $queryHapus = $koneksi->prepare("DELETE FROM kelas WHERE id_kelas = ?");
    $queryHapus->bind_param("i", $id);
    if($queryHapus->execute()){
        echo "<script>alert('Berhasil Menghapus Data!'); window.location = 'index.php'</script>";
    }else{
        echo "<script>alert('Berhasil Menghapus Data!'); window.location = 'index.php'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Sederhana</title>
    <link rel="stylesheet" href="../../asset/css/style.css">
    <link rel="stylesheet" href="../../asset/css/style-index.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <ul>
        <li><a href="#">Dashboard</a></li>
            <li><a href="../pembayaran/">Pembayaran Spp</a></li>
            <li><a href="../siswa/">Data Siswa</a></li>
            <li><a href="index.php">Data Kelas</a></li>
            <li><a href="../user/">Data User</a></li>
            <li><a href="../spp/">Data Spp</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="form-input" style="display: flex; justify-content: center;">
       <div class="card" style="width: 300px;">
        <div class="card-header">

        </div>
        <div class="card-body">
            <form method = "post">
                <div class="form-group">
                    <label for="">Nama Kelas</label>
                    <input type="text" value="<?= $nama ?>" name="nama_kelas" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="">Kompetensi Keahlian</label>
                    <input type="text" value="<?= $keahlian ?>" name="kompetensi" class="form-control" required>
                </div>
                <button type="submit"  name="simpan" class="btn btn-primary">Simpan</button>
            </form>
        </div>
       </div>
        </div>

        <div class="data-table">
            <div class="card">
                <div class="card-header">
                    <p>Data Kelas</p>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kelas</th>
                                <th>Kompetensi Keahlian</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no =1;
                            $queryData = $koneksi->prepare("SELECT * FROM kelas");
                            $queryData->execute();
                            $resultData = $queryData->get_result();
                            while($row = $resultData->fetch_array()){ ?>
                            <tr>
                                <td><?= $no ++ ?></td>
                                <td><?= $row['nama_kelas'] ?></td>
                                <td><?= $row['kompetensi_keahlian'] ?></td>
                                <td>
                                    <a href="index.php?action=edit&id=<?= $row['id_kelas'] ?>" style= "text-decoration: none;" class="btn btn-primary">Edit</a>
                                    <a href="index.php?action=hapus&id=<?= $row['id_kelas'] ?>" style= "text-decoration: none;" onclick="return confirm('Apakah Anda Ingin Menghaous Data Ini?')" class="btn btn-merah">Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
