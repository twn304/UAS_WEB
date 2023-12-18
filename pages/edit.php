<?php
require_once("../api/config.php");
require_once("../api/auth.php");

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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Toilet</title>
</head>

<body>
    <div class="container mt-5">
        <h4>Edit Toilet</h4>
        <form action="" method="POST">
            <div class="form-group">
                <label for="new_lokasi">New Lokasi </label>
                <input type="text" name="new_lokasi" value="<?php echo $row['lokasi']; ?>">
            </div>
            <div class="form-group">
                <label for="new_keterangan">New Keterangan </label>
                <input type="text" name="new_keterangan" value="<?php echo $row['keterangan']; ?>">
            </div>

            <input type="submit" class="btn btn-primary" name="update" value="Update">
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>
