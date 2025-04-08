<?php 
include '../../config/db.php';
session_start();
$username = "";
$nama = "";
$passwordNew = "";

if(!isset($_SESSION['username'])){
    echo '<script>window.location = "../../auth/login.php"</script>';
}

if(isset($_GET['option'])){
    $option = $_GET['option'];
}else{
    $option = "";
}

if(isset($_POST['simpan'])){
    //
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    //search password
    if(empty($password)){
    $dataPwd = $koneksi->prepare("SELECT * FROM user WHERE id_user = ?");
    $dataPwd->bind_param("i", $id);
    $dataPwd->execute();
    $resultPwd = $dataPwd->get_result();
    $getPwd = $resultPwd->fetch_assoc();
    $password = $getPwd['password'];
    }else{
    $password = password_hash($password, PASSWORD_BCRYPT);
    }
    if(!empty($id)){
        $updateQuery = $koneksi->prepare("UPDATE user SET username = ?, nama = ?, password = ? WHERE id_user = ?");
        $updateQuery->bind_param("sssi", $username, $nama, $password, $id);
        if($updateQuery->execute()){
           echo '<script>alert("Berhasil Mengupdate Data User!"); window.location = "index.php"</script>';
        }else{
            echo '<script>alert("Gagal Mengupdate Data User!"); window.location = "index.php"</script>';
        }
    }else{
        $insertQuery = $koneksi->prepare("INSERT INTO user(username, nama, password)VALUES(?, ?, ?)");
        $insertQuery->bind_param("sss", $username, $nama, $password);
        if($insertQuery->execute()){
           echo '<script>alert("Berhasil Menambahkan Data User!"); window.location = "index.php"</script>';
        }else{
            echo '<script>alert("Gagal Menambahkan Data User!"); window.location = "index.php"</script>';
        }
    }
    
}
if($option === 'hapus'){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $deleteQuery = $koneksi->prepare("DELETE FROM user WHERE id_user = ?");
    $deleteQuery->bind_param("i", $id);
    if($deleteQuery->execute()){
        echo '<script>alert("Berhasil Menghapus Data User!"); window.location = "index.php"</script>';
    }else{
        echo '<script>alert("Gagal Menghapus Data User!"); window.location = "index.php"</script>';
    }
}
if($option === 'edit'){
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $dataEdit = $koneksi->prepare("SELECT * FROM user WHERE id_user = ?");
    $dataEdit->bind_param("i", $id);
    $dataEdit->execute();
    $rowEdit = $dataEdit->get_result();
    $rowData = $rowEdit->fetch_assoc();
    if($rowData){
        $username = $rowData['username'];
        $nama = $rowData['nama'];
    }else{
        echo 'Data Tidak Ditemukan';
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
    <style>
        .btn-warning{background-color: #ffd900; color: white;}
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Admin</h2>
        <ul>
        <li><a href="#">Dashboard</a></li>
            <li><a href="../pembayaran/">Pembayaran Spp</a></li>
            <li><a href="../siswa/">Data Siswa</a></li>
            <li><a href="../kelas/">Data Kelas</a></li>
            <li><a href="index.php">Data User</a></li>
            <li><a href="../spp/">Data Spp</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="content" >
        <div class="form-input" style="display: flex; justify-content: center">
            <div class="card" style="width: 50%;">
                <div class="card-header">
                    
                </div>
                <div class="card-body">
                    <form action="" method = "post">
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" value="<?= $username ?>" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" value="<?= $nama ?>" name="nama" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <button class="btn btn-primary" name="simpan">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="data-table">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $queryData = $koneksi->prepare("SELECT * FROM user ORDER BY id_user DESC");
                            $queryData->execute();
                            $resultData = $queryData->get_result();
                            while($row = $resultData->fetch_array()) { ?>
                            
                            <tr>
                                <td><?= $no ++ ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td>
                                    <a href="index.php?option=edit&id=<?= $row['id_user']?>" class="btn btn-warning">Edit</a>
                                    <a href="index.php?option=hapus&id=<?= $row['id_user'] ?>" onclick = "return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')" class = "btn btn-merah">Hapus</a>
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