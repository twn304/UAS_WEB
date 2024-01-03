<?php 
include("../api/config.php");

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

    if($saved) header("Location: login.php");
}

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../style/index.css">
</head>
<body>
    <div class="register__container">
        <div class="register__content">
            <div class="register__content-title">
                <h1>Register</h1>
            </div>
            <div class="register__content-form">
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

                    <input type="submit" class="btn btn-success btn-block" name="register" value="Daftar" />
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
