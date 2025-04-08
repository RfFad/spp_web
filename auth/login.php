<?php
include '../config/db.php';
session_start();
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
   
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($password,$row['password'])){
            $_SESSION['username'] = $row['username'];

            header('Location: ../admin/kelas/index.php');
            exit;
        }else{
            echo '<script>alert("Password salah!")</script>'; 
        }
    }else{
        echo '<script>alert("Username tidak ditemukan!")</script>';
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/css/login.css">
    <link rel="stylesheet" href="../asset/css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="login-form">
        <form method="post" action="" class="form-custom">
            <h4>Login Form</h4>
            <div class="form-group">
                <label for="">Username</label>
                <input type="text" name="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" name= "password" class="form-control">
            </div>
            <button type="submit" name="login" class="btn btn-merah">Submit</button>
        </form>
    </div>
</body>
</html>