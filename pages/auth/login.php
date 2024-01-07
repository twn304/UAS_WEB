<?php
include("../../api/config.php");

if (isset($_POST['login'])) {
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

    if ($user) {
        if (password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["user"] = $user;
            header("Location: ../dashboard.php");
        } else {
            // Incorrect password
            $alertMessage = '<div class="alert alert-danger" role="alert">
                                Incorrect password. Please try again.
                            </div>';
        }
    } else {
        // Username or email not found
        $alertMessage = '<div class="alert alert-danger" role="alert">
                            Username or email not found. Please try again.
                        </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../../style/index.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>/* LOGIN CONTAINER  */

        .login__container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Menetapkan tinggi ke 100% dari tinggi viewport */
            margin: 50px 0;
        }
        .login__content {
            width: 300px;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(135,123,123,0.61);; /* Menambahkan box shadow */
            padding : 20px;
        }

        .login__content-title {
            color:#333;
            text-align: center;
            padding-bottom : 20px;
        }
        .form-group {
            padding-bottom: 10px;
        }
        .form-group label {
            padding-bottom: 10px;
            font-weight: bold;
        }
        input[type="submit"]{
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login__container">
        <div class="login__content">
            <div class="login__content-title">
                <h1>Login</h1>
            </div>
            <div class="login__content-form">
                <?php
                if (isset($alertMessage)) {
                    echo $alertMessage;
                }
                ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input class="form-control" type="text" name="username" placeholder="Username atau email" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" />
                    </div>

                    <input type="submit" class="btn btn-primary btn-block" name="login" value="Masuk" />
                </form>
                <p class="mt-3"><a href="../index.php">Back</a></p>
            </div>
        </div>
    </div>

</body>

</html>