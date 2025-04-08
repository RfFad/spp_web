<?php
include '../../config/db.php';
session_start();

$tahun = "";
$nominal = "";

if(!isset($_SESSION['username'])){
    header('Location: ../../auth/login.php');
}

if(isset($_GET['option'])){
    $option = $_GET['option'];
}else{
    $option = "";
}

//insert dan update data query
if(isset($_POST['simpan'])){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];
    if(!empty($id)){
        $updateQuery = $koneksi->prepare("UPDATE spp SET tahun = ?, nominal = ? WHERE id_spp = ?");
        $updateQuery->bind_param("iii", $tahun, $nominal, $id);
        if($updateQuery->execute()){
            echo "<script>alert('Data berhasil diupdate!'); window.location = 'index.php'</script>";
        }else{
            echo "<script>alert('Data gagal diupdate!'); window.location = 'index.php'</script>";
        }
    }else{
        $queryInsert = $koneksi->prepare("INSERT INTO spp(tahun, nominal)VALUES(?, ?)");
        $queryInsert->bind_param("ii", $tahun, $nominal);
        if($queryInsert->execute()){
            echo "<script>alert('Data berhasil ditambahkan!'); window.location = 'index.php'</script>";
        }else{
            echo "<script>alert('Data gagal ditambahkan!'); window.location = 'index.php'</script>";
        }
    }
}
//get data by id
if($option === 'edit'){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $editQuery = $koneksi->prepare("SELECT * FROM spp WHERE id_spp = ?");
    $editQuery->bind_param("i", $id);
    $editQuery->execute();
    $getEdit = $editQuery->get_result();
    $getResult = $getEdit->fetch_assoc();
    if($getResult){
        $tahun = $getResult['tahun'];
        $nominal = $getResult['nominal'];
    }
}
//hapus query data
if($option === "hapus"){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $hapusQuery = $koneksi->prepare("DELETE FROM spp WHERE id_spp = ?");
    $hapusQuery->bind_param("i", $id);
    if($hapusQuery->execute()){
        echo "<script>alert('Data berhasil dihapus!'); window.location = 'index.php'</script>";
    }else{
        echo "<script>alert('Data gagal dihapus!'); window.location = 'index.php'</script>";
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../asset/css/style.css">
    <link rel="stylesheet" href="../../asset/css/style-index.css">
</head>
<body>
    <div class="sidebar">
    <h2>Dashboard Admin</h2>
        <ul>
        <li><a href="#">Dashboard</a></li>
            <li><a href="../pembayaran/">Pembayaran Spp</a></li>
            <li><a href="../siswa/">Data Siswa</a></li>
            <li><a href="../kelas/">Data Kelas</a></li>
            <li><a href="../user/">Data User</a></li>
            <li><a href="index.php">Data Spp</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="form-input" style = "display: flex; justify-content: center;">
        <div class="card" style = "width: 50%">
            <div class="card-header">
                
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <input type="number" value="<?= $tahun ?>" name="tahun" placeholder = "Masukkan Tahun Contoh: 2024" min="1900" max="2100" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Nomimal</label>
                        <input type="number" value="<?= $nominal ?>" name="nominal" placeholder="Masukkan Nominal!" class="form-control">
                    </div>
                    <button class="btn btn-primary" name="simpan" type="submit">Simpan</button>
                </form>
            </div>
        </div>
        </div>
<!-- data table -->
 <div class="data-table">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tahun</th>
                <th>Nominal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            //data query
            $no = 1;
            $dataQuery = $koneksi->prepare("SELECT * FROM spp ORDER BY id_spp DESC");
            $dataQuery->execute();
            $resultData = $dataQuery->get_result();
            while($row = $resultData->fetch_array()){ ?>
             <tr>
                <td><?= $no ++ ?></td>
                <td><?= $row['tahun'] ?></td>
                <td><?= number_format($row['nominal']) ?></td>
                <td>
                    <a href="index.php?option=edit&id=<?= $row['id_spp'] ?>" class="btn btn-primary">Edit</a>
                    <a href="index.php?option=hapus&id=<?= $row['id_spp'] ?>" onclick = "return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')" class="btn btn-merah">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
 </div>
    </div>
</body>
</html>