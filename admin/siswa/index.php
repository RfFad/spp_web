<?php
include '../../config/db.php';
$nisn = "";
$nis = "";
$nama = "";
$id_kelas = "";
$alamat = "";
$no_telp = "";

if(isset($_GET['option'])){
    $option = $_GET['option'];
}else{
    $option = "";
}
//insert dan update data
if(isset($_POST['simpan'])){
    //untuk post
    $nisn = $_POST['nisn'];
    $nisn_lama = $_GET['nisn'] ?? '';
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];

    if(!empty($nisn_lama)){
        $queryUpdate = $koneksi->prepare("UPDATE siswa SET nisn = ?, nis = ?, nama = ?, id_kelas = ?, alamat = ?, no_telp =? WHERE nisn = ? ");
        $queryUpdate->bind_param("sssisss", $nisn, $nis, $nama, $id_kelas, $alamat, $no_telp, $nisn_lama);
        if($queryUpdate->execute()){
            echo '<script>alert("Berhasil mengupdate data!"); window.location = "index.php"</script>';
        }else{
            echo '<script>alert("Gagal mengupdate data!"); window.location = "index.php"</script>';
        }
    }else{
        $queryInsert = $koneksi->prepare("INSERT INTO siswa (nisn, nis, nama, id_kelas, alamat, no_telp) VALUES(?, ?, ?, ?, ?, ?)");
        $queryInsert->bind_param("sssiss", $nisn, $nis, $nama, $id_kelas, $alamat, $no_telp);
        if($queryInsert->execute()){
            echo '<script>alert("Berhasil menambahkan data!"); window.location = "index.php"</script>';
        }else{
            echo '<script>alert("Gagal menambahkan data!"); window.location = "index.php"</script>';
        }
    }
}
//get data edit
if($option === 'edit'){
    $nisn = $_GET['nisn'];
    $getQuery = $koneksi->prepare("SELECT * FROM siswa WHERE nisn = ?");
    $getQuery->bind_param('s', $nisn);
    $getQuery->execute();
    $getData = $getQuery->get_result();
    $rowData = $getData->fetch_assoc();
    if($rowData){
        $nisn = $rowData['nisn'];
        $nis = $rowData['nis'];
        $nama = $rowData['nama'];
        $id_kelas = $rowData['id_kelas'];
        $no_telp = $rowData['no_telp'];
        $alamat = $rowData['alamat'];
    }
}
//hapus query
if($option === 'hapus'){
    $nisn = $_GET['nisn'];
    $queryHapus = $koneksi->prepare("DELETE FROM siswa WHERE nisn = ?");
    $queryHapus->bind_param("s", $nisn);
    if($queryHapus->execute()){
        echo '<script>alert("Berhasil menghapus data!"); window.location = "index.php";</script>';
    }else{
        echo '<script>alert("Gagal menghapus data!"); window.location = "index.php";</script>';
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
            <li><a href="index.php">Data Siswa</a></li>
            <li><a href="../kelas/">Data Kelas</a></li>
            <li><a href="../siswa/">Data User</a></li>
            <li><a href="../spp/">Data Spp</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="content" style="margin-bottom: 50px;">
        <div class="form-input">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="">Nisn</label>
                            <input type="text" value="<?= $nisn ?>" name= "nisn" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Nis</label>
                            <input type="text" value = "<?= $nis ?>" name = "nis" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Siswa</label>
                            <input type="text" value = "<?= $nama ?>" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Kelas</label>
                            <select name="id_kelas" required id="" class="form-control">
                                <option selected disabled>-- Pilih Kelas --</option>
                                <?php 
                                $queryKelas = $koneksi->prepare("SELECT * FROM kelas");
                                $queryKelas->execute();
                                $resultKelas = $queryKelas->get_result();
                                while($rowK = $resultKelas->fetch_array()){ ?>
                                <option value="<?= $rowK['id_kelas'] ?>" <?= $id_kelas === $rowK['id_kelas'] ? 'selected' : '' ?>><?= $rowK['nama_kelas'] ?> <?= $rowK['kompetensi_keahlian'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">No Telp</label>
                            <input type="text" value= "<?= $no_telp ?>" name = "no_telp" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                                <textarea name="alamat" style = "height: 150px" name="" id="" class="form-control" required><?= $alamat ?></textarea>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="data-siswa">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nisn</th>
                        <th>Nis</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $queryData = $koneksi->prepare("SELECT siswa.*, kelas.nama_kelas, kelas.kompetensi_keahlian AS jurusan FROM siswa JOIN kelas ON siswa.id_kelas = kelas.id_kelas ORDER BY siswa.nisn");
                    $queryData->execute();
                    $resultData = $queryData->get_result();
                    while($row = $resultData->fetch_array()){ ?>
                    <tr>
                        <td><?= $no ++ ?></td>
                        <td><?= $row['nisn'] ?></td>
                        <td><?= $row['nis'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['nama_kelas'] ?> <?= $row['jurusan'] ?></td>
                        <td><?= $row['alamat']?></td>
                        <td><?= $row['no_telp'] ?></td>
                        <td>
                            <a href="index.php?option=edit&nisn=<?= $row['nisn'] ?>" class="btn btn-primary">Edit</a>
                            <a href="index.php?option=hapus&nisn=<?= $row['nisn'] ?>" onclick = "return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-merah">Hapus</a>
                        </td>
                    </tr>
                   <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>