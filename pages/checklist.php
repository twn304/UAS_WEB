<?php
require_once("../api/config.php");
require_once("../api/auth.php");

if (isset($_GET['id'])) {
    $toilet_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Fetch toilet data for the selected ID
    $sql = "SELECT * FROM toilet WHERE id = :toilet_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':toilet_id', $toilet_id, PDO::PARAM_INT);
    $stmt->execute();
    $toilet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$toilet) {
        // Handle invalid ID (redirect or display an error message)
        header("Location: checklist.php"); // Redirect to the main page
        exit();
    }
} else {
    // Handle missing ID (redirect or display an error message)
    header("Location: checklist.php"); // Redirect to the main page
    exit();
}

// Handle form submission for checklist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kloset = isset($_POST['kloset']) ? 1 : 0;
    $wastafel = isset($_POST['wastafel']) ? 1 : 0;
    $lantai = isset($_POST['lantai']) ? 1 : 0;
    $dinding = isset($_POST['dinding']) ? 1 : 0;
    $kaca = isset($_POST['kaca']) ? 1 : 0;
    $bau = isset($_POST['bau']) ? 1 : 0;
    $sabun = isset($_POST['sabun']) ? 1 : 0;

    $insert_sql = "INSERT INTO checklist (tanggal, toilet_id, kloset, wastafel, lantai, dinding, kaca, bau, sabun, users_id)
                   VALUES (NOW(), :toilet_id, :kloset, :wastafel, :lantai, :dinding, :kaca, :bau, :sabun, :users_id)";
    $insert_stmt = $db->prepare($insert_sql);
    $insert_params = array(
        ":toilet_id" => $toilet_id,
        ":kloset" => $kloset,
        ":wastafel" => $wastafel,
        ":lantai" => $lantai,
        ":dinding" => $dinding,
        ":kaca" => $kaca,
        ":bau" => $bau,
        ":sabun" => $sabun,
        ":users_id" => $_SESSION["user"]["id"]
    );
    $inserted = $insert_stmt->execute($insert_params);

    if ($inserted) {
        echo "Checklist submitted successfully!";
    } else {
        echo "Error submitting checklist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checklist</title>
</head>

<body>
    <div class="container mt-5">
        <h4>Checklist for <?php echo $toilet['lokasi']; ?></h4>
        <form action="" method="POST">
            <div class="form-group">
                <label><input type="checkbox" name="kloset"> Kloset</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="wastafel"> Wastafel</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="lantai"> Lantai</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="dinding"> Dinding</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="kaca"> Kaca</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="bau"> Bau</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="sabun"> Sabun</label>
            </div>

            <input type="submit" class="btn btn-primary" value="Submit Checklist">
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>
