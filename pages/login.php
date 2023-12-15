<?php
include("../api/config.php");

if(isset($_POST['login'])){ 
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $sql = "SELECT * FROM users WHERE username=:username OR email=:email";
    $stmt = $db->prepare($sql);

    $params = array(
        ":username" => $username,
        ":email" => $username
    );

    $stmt->execute($params);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        if (password_verify($password, $user["password"])){ 
            session_start();
            $_SESSION["user"] = $user;
            header("Location: dashboard.php");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="POST"> 
        <div>
            <label for="username">Username</label>
            <input class="form-control" type="text" name="username" placeholder="Username atau email" />
        </div>
        <div> 
            <label for="password">Password</label>
            <input class="form-control" type="password" name="password" placeholder="Password" />
        </div>

        <input type="submit" class="btn btn-success btn-block" name="login" value="Masuk" />
    </form>
</body>
</html>
