<?php
include '../../config/db.php';
session_start();


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
            <li><a href="index.php">Pembayaran Spp</a></li>
            <li><a href="../siswa/">Data Siswa</a></li>
            <li><a href="../kelas/">Data Kelas</a></li>
            <li><a href="../user/">Data User</a></li>
            <li><a href="../spp/">Data Spp</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="form-input">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="">Pilih Siswa</label>
                            <select name="nisn" id="" class="form-control">
                                <option disabled selected>-- Pilih Siswa --</option>
                                <?php
                                $no = 1;
                                $querySiswa = $koneksi->prepare("SELECT siswa.*, kelas.kompetensi_keahlian AS keahlian, kelas.nama_kelas FROM siswa JOIN kelas ON siswa.id_kelas = kelas.id_kelas ORDER BY siswa.nisn DESC");
                                $querySiswa->execute();
                                $resultSiswa = $querySiswa->get_result();
                                while($rowS = $resultSiswa->fetch_array()){ ?>
                                
                                <option value="<?= $rowS['nisn'] ?>"><?= $rowS['nis'] ?> - <?= $rowS['nama'] ?> - <?= $rowS['nama_kelas'] ?> <?= $rowS['keahlian'] ?></option>
                            
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Bulan</label>
                            <select class="form-control" name="bulan_bayar" id="">
                                <option value="Januari">Januari</option>
                                <option value="Februari">Februari</option>
                                <option value="Maret">Maret</option>
                                <option value="April">April</option>
                                <option value="Mei">Mei</option>
                                <option value="Juni">Juni</option>
                                <option value="Juli">Juli</option>
                                <option value="Agustus">Agustus</option>
                                <option value="September">September</option>
                                <option value="Oktober">Oktober</option>
                                <option value="November">November</option>
                                <option value="Desember">Desember</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tahun Bayar</label>
                            <input type="number" name="tahun_bayar" placeholder="Masukkan Tahun Pembayaran" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Spp</label>
                            <select name="id_spp" class="form-control" id="">
                                <option selected disabled>-- Select Spp --</option>
                                <?php 
                                $querySpp = $koneksi->prepare("SELECT * FROM spp");
                                $querySpp->execute();
                                $resultSpp = $querySpp->get_result();
                                while($rowSp = $resultSpp->fetch_array()){ ?> 
                                
                                <option value=""><?= $rowSp['tahun'] ?> - Rp. <?= number_format($rowSp['nominal']) ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Pembayaran</label>
                            <input type="number" placeholder="Masukkan Jumlah Pembayaran" name="jumlah_bayar" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>