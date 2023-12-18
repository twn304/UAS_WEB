<?php

require_once("../api/config.php");
require_once("../api/auth.php");

// Check if the delete button is clicked
if (isset($_POST["delete"])) {
    $delete_id = filter_input(INPUT_POST, 'delete_id', FILTER_SANITIZE_NUMBER_INT);

    // Perform deletion
    $delete_sql = "DELETE FROM toilet WHERE id = :delete_id";
    $delete_stmt = $db->prepare($delete_sql);
    $delete_params = array(":delete_id" => $delete_id);
    $deleted = $delete_stmt->execute($delete_params);
}

if (isset($_POST["submit"])) {
    $lokasi = filter_input(INPUT_POST, 'lokasi', FILTER_SANITIZE_STRING);
    $keterangan = filter_input(INPUT_POST, 'keterangan', FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO toilet (lokasi, keterangan) 
            VALUES (:lokasi, :keterangan)";

    $stmt = $db->prepare($sql);

    $params = array(
        ":lokasi" => $lokasi,
        ":keterangan" => $keterangan
    );

    $saved = $stmt->execute($params);
}

// Fetch data from the database
$sql = "SELECT * FROM toilet";
$stmt = $db->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>

<body>
    <div class="container mt-5">
        <h3><?php echo $_SESSION["user"]["name"] ?></h3>
        <p><?php echo $_SESSION["user"]["email"] ?></p>

        <form action="" method="POST">
            <div class="form-group">
                <label for="lokasi">Lokasi </label>
                <input type="text" name="lokasi" placeholder="Lokasi">
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan </label>
                <input type="text" name="keterangan" placeholder="Keterangan">
            </div>

            <input type="submit" class="btn btn-success btn-block" name="submit" value="Poke" />
        </form>

        <h4>Data Toilet</h4>
        <?php if (isset($rows) && is_array($rows) && count($rows) > 0): ?>
            <table border="1">
                <tr>
                    <th>Lokasi</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                    <th>Edit</th>
                    <th>Checklist</th> <!-- New column for the checklist button -->
                </tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['lokasi']; ?></td>
                        <td><?php echo $row['keterangan']; ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                            </form>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                        </td>
                        <td>
                            <a href="checklist.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Checklist</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No data available.</p>
        <?php endif; ?>

        <p><a href="logout.php">Logout</a></p>
    </div>
</body>

</html>
