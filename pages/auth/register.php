<?php 
include("../../api/config.php");

if(isset($_POST['register'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    $sql = "INSERT INTO users (name, username, email, password) 
            VALUES (:name, :username, :email, :password)";
    $stmt = $db->prepare($sql);

    $params = array(
        ":name" => $name,
        ":username" => $username,
        ":password" => $password,
        ":email" => $email
    );

    $saved = $stmt->execute($params);

    // Notifikasi
    if ($saved) {
        $notification = '<div class="alert alert-success" role="alert">
                            Registrasi berhasil! Silakan <a href="login.php" class="alert-link">login</a>.
                          </div>';
    } else {
        $notification = '<div class="alert alert-danger" role="alert">
                            Gagal melakukan registrasi. Silakan coba lagi.
                          </div>';
    }
}

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../../style/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        /* REGISTER CONTAINER  */
        .register__container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Menetapkan tinggi ke 100% dari tinggi viewport */
            margin: 70px 0;
        }
        .register__content {
            width: 300px;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(135,123,123,0.61);; /* Menambahkan box shadow */
            padding : 20px;
        }

        .register__content-title {
            color:#333;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
            padding : 5px;
        }
    </style>
</head>
<body>
    <div class="register__container">
        <div class="register__content">
            <div class="register__content-title">
                <h1>Register</h1>
            </div>
            <div class="register__content-form">
                <?php
                if (isset($notification)) {
                    echo $notification;
                }
                ?>
                <form action="" method="POST">
                    <div class="form-group"> 
                        <label for="name">Nama Lengkap</label>
                        <input class="form-control" type="text" name="name" placeholder="Nama Anda">
                    </div>
                    <div class="form-group"> 
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" placeholder="Email">
                    </div>
                    <div class="form-group"> 
                        <label for="username">Username</label>
                        <input class="form-control" type="text" name="username" placeholder="Username">
                    </div>
                    <div class="form-group"> 
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>

                    <input type="submit" class="btn btn-primary btn-block" name="register" value="Daftar" />
                </form>
                <p class="text-center mt-3"><a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
    
</body>
</html>
