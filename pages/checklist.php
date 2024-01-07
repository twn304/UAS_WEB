<?php
require_once("../api/config.php");
require_once("../api/auth.php");

$kloset = 0;
$wastafel = 0;
$lantai = 0;
$dinding = 0;
$kaca = 0;
$bau = 0;
$sabun = 0;

$notification = ""; // Initialize notification variable

if (isset($_GET['id'])) {
    $toilet_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Fetch toilet data for the selected ID
    $sql = "SELECT * FROM toilet WHERE id = :toilet_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':toilet_id', $toilet_id, PDO::PARAM_INT);
    $stmt->execute();
    $toilet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$toilet) {
        header("Location: checklist.php");
        exit();
    }

    // Fetch checklist data for the selected toilet ID
    $checklistSql = "SELECT * FROM checklist WHERE toilet_id = :toilet_id ORDER BY tanggal DESC LIMIT 1";
    $checklistStmt = $db->prepare($checklistSql);
    $checklistStmt->bindParam(':toilet_id', $toilet_id, PDO::PARAM_INT);
    $checklistStmt->execute();
    $checklistData = $checklistStmt->fetch(PDO::FETCH_ASSOC);

    // Set variabel dengan nilai dari database
    if ($checklistData) {
        $kloset = $checklistData['kloset'];
        $wastafel = $checklistData['wastafel'];
        $lantai = $checklistData['lantai'];
        $dinding = $checklistData['dinding'];
        $kaca = $checklistData['kaca'];
        $bau = $checklistData['bau'];
        $sabun = $checklistData['sabun'];
    }
} else {
    header("Location: checklist.php");
    exit();
}

// Handle form submission for checklist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kloset = isset($_POST['kloset']) ? intval($_POST['kloset']) : 0;
    $wastafel = isset($_POST['wastafel']) ? intval($_POST['wastafel']) : 0;
    $lantai = isset($_POST['lantai']) ? intval($_POST['lantai']) : 0;
    $dinding = isset($_POST['dinding']) ? intval($_POST['dinding']) : 0;
    $kaca = isset($_POST['kaca']) ? intval($_POST['kaca']) : 0;
    $bau = isset($_POST['bau']) ? intval($_POST['bau']) : 0;
    $sabun = isset($_POST['sabun']) ? intval($_POST['sabun']) : 0;

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
        $notification = '<div class="alert alert-success" role="alert">
                            Checklist submitted successfully!
                        </div>';
    } else {
        $notification = '<div class="alert alert-danger" role="alert">
                            Error submitting checklist. Please try again.
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
    <title>Checklist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .form-group {
            margin: 10px;
            display : block;
        }
        label {
            font-weight: bold;
            padding-right: 10px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5 border border-primary rounded col-5" id="container">
    <h3 class="mt-3 mb-3">Checklist for <?php echo $toilet['lokasi']; ?></h3>

<?php echo $notification; // Display the notification ?>
        </h3>
        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Kloset</label>
                <select name="kloset" class="form-select">
                    <option value="1" <?php echo $kloset == 1 ? 'selected' : ''; ?>>Bersih</option>
                    <option value="2" <?php echo $kloset == 2 ? 'selected' : ''; ?>>Kotor</option>
                    <option value="3" <?php echo $kloset == 3 ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-group">Wastafel</label>
                <select name="wastafel" class="form-select">
                    <option value="1" <?php echo $wastafel == 1 ? 'selected' : ''; ?>>Bersih</option>
                    <option value="2" <?php echo $wastafel == 2 ? 'selected' : ''; ?>>Kotor</option>
                    <option value="3" <?php echo $wastafel == 3 ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Lantai</label>
                <select name="lantai" class="form-select">
                    <option value="1" <?php echo $lantai == 1 ? 'selected' : ''; ?>>Bersih</option>
                    <option value="2" <?php echo $lantai == 2 ? 'selected' : ''; ?>>Kotor</option>
                    <option value="3" <?php echo $lantai == 3 ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Dinding</label>
                <select name="dinding" class= "form-select">
                    <option value="1" <?php echo $dinding == 1 ? 'selected' : ''; ?>>Bersih</option>
                    <option value="2" <?php echo $dinding == 2 ? 'selected' : ''; ?>>Kotor</option>
                    <option value="3" <?php echo $dinding == 3 ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kaca</label>
                <select name="kaca" class="form-select">
                    <option value="1" <?php echo $kaca == 1 ? 'selected' : ''; ?>>Bersih</option>
                    <option value="2" <?php echo $kaca == 2 ? 'selected' : ''; ?>>Kotor</option>
                    <option value="3" <?php echo $kaca == 3 ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Bau</label>
                <select name="bau" class="form-select">
                    <option value="1" <?php echo $bau == 1 ? 'selected' : ''; ?>>Ya</option>
                    <option value="2" <?php echo $bau == 2 ? 'selected' : ''; ?>>Tidak</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Sabun</label>
                <select name="sabun" class="form-select">
                    <option value="1" <?php echo $sabun == 1 ? 'selected' : ''; ?>>Ya</option>
                    <option value="2" <?php echo $sabun == 2 ? 'selected' : ''; ?>>Tidak</option>
                </select>
            </div>

            <input type="submit" class="btn btn-primary mt-3" value="Submit Checklist">
        </form>

        <p class="text-center mt-3"><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>