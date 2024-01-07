<?php
require_once("../config.php");
require_once("../auth.php");

$notification = ""; // Initialize notification variable

if (isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Fetch the data for the selected ID
    $sql = "SELECT * FROM toilet WHERE id = :edit_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':edit_id', $edit_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Handle form submission for updating
if (isset($_POST["update"])) {
    $new_lokasi = filter_input(INPUT_POST, 'new_lokasi', FILTER_SANITIZE_STRING);
    $new_keterangan = filter_input(INPUT_POST, 'new_keterangan', FILTER_SANITIZE_STRING);

    // Perform update
    $update_sql = "UPDATE toilet SET lokasi = :new_lokasi, keterangan = :new_keterangan WHERE id = :edit_id";
    $update_stmt = $db->prepare($update_sql);
    $update_params = array(
        ":edit_id" => $edit_id,
        ":new_lokasi" => $new_lokasi,
        ":new_keterangan" => $new_keterangan
    );
    $updated = $update_stmt->execute($update_params);

    if ($updated) {
        // Fetch the updated data from the database
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $notification = '<div class="alert alert-success" role="alert">
                            Toilet information updated successfully!
                        </div>';
    } else {
        $notification = '<div class="alert alert-danger" role="alert">
                            Error updating toilet information. Please try again.
                        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Toilet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(135,123,123,0.61);
            border-radius: 10px;
            padding: 20px;
            margin-top: 100px;
            max-width: 300px;
        }

        form {
            max-width: 300px;
            margin: 0 auto;
        }

        h3 {
            padding: 20px;
        }

        button {
            width: 100%;
        }

        p {
            margin-top: 20px;
            text-align: center;
        }

        .form-label {
            padding-bottom: 10px;
            font-weight: bold;
        }

        input[type="submit"] {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center">Edit Toilet</h3>

        <?php echo $notification; // Display the notification ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="new_lokasi" class="form-label">New Lokasi</label>
                <input type="text" class="form-control" id="new_lokasi" name="new_lokasi" value="<?php echo $row['lokasi']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="new_keterangan" class="form-label">New Keterangan</label>
                <input type="text" class="form-control" id="new_keterangan" name="new_keterangan" value="<?php echo $row['keterangan']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>

        <p><a href="../../pages/dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>
