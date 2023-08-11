<?php
// "config.php" dosyasını dahil edin
require_once 'config.php';
session_start(); 
try {
    $username =$_SESSION["username"];
    // Formdan dosya seçildi mi diye kontrol edin
    if (isset($_POST["submit"])) {
        $targetDir = "./uploads/"; // Yüklenen dosyanın nereye kaydedileceği dizini belirtin
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Dosyanın resim mi olup olmadığını kontrol edin
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                
    
                $uploadOk = 0;
            }
        }

        // Dosyayı sunucuya yükleyin
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";

                // Dosyanın yüklenme başarılı olduğu durumda veritabanına kaydedin
                $sql = "UPDATE users SET image = :image WHERE username = '$username'";
                // $sql = "INSERT INTO users (image) VALUES (:image)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':image', $targetFile);
                if ($stmt->execute()) {
                    echo " Record added to the database successfully.";
                    
                } else {
                    echo " Errordd: " . $sql . "<br>" . $stmt->errorInfo();
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
