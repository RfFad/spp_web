<?php
include '../config/db.php';
insert('refan', 'refan');
function insert($username, $password){
    global $koneksi; // Pastikan koneksi global digunakan

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Gunakan prepared statement dengan bind_param untuk keamanan
    $query = $koneksi->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $query->bind_param("ss", $username, $password_hash);

    if($query->execute()){
        echo "✅ Berhasil Menambahkan Data!\n";
    } else {
        echo "❌ Gagal Menambahkan Data: " . $query->error . "\n";
    }
}


?>
