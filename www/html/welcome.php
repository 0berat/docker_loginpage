<?php
// Initialize the session
require_once 'config.php';

session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$username = $_SESSION["username"];

// SQL sorgusunu oluşturun ve kullanıcı adına göre fotoğrafı seçin
// $sql = "SELECT image FROM users WHERE username = ?";
$sql = "SELECT image FROM users WHERE username = '$username'";
$stmt = $pdo->prepare($sql);
// $stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();

// Fotoğrafı saklayacak bir değişken tanımlayın ve veriyi alın
$image_data = $stmt->fetchColumn();

$photo_path = $image_data; // Örneğin "uploads/siberkumefoto.jpeg"

// Dosyanın var olduğunu kontrol edin ve varsa Base64'e dönüştürerek görüntüleyin
if (file_exists($photo_path)) {
    $image_data = file_get_contents($photo_path);
} else {
    echo 'Fotoğraf bulunamadı.';
}


?>
 



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
        <a href="upload.php" class="btn btn-danger ml-3">Do you want a have a pp?</a>
        <form action="upload.php" method="post" enctype="multipart/form-data" ">
        <label for="image">Select Image:</label>
        <input type="file" value="Fotograf sec" name="image"  id="image" class="btn btn-danger ml-3">
        <input type="submit" value="Upload" name="submit" class="btn btn-danger ml-3">
        <h2>Profil Fotoğrafınız:</h2>
        <?php

        // Veritabanından alınan fotoğraf verisini gösterme
        echo '<img src="data:image/jpeg;base64,' . base64_encode($image_data) . '" alt="Profil Fotoğrafı" width="250" height="300">';
        
        ?>
    </form>


    </p>

</body>
</html>
