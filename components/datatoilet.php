<?php
include("./api/config.php");

// Fetch data for toilets
$toiletSql = "SELECT * FROM toilet";
$toiletStmt = $db->query($toiletSql);
$toilets = $toiletStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data for users
$usersSql = "SELECT * FROM users";
$usersStmt = $db->query($usersSql);
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

// Function to get toilets without checklist
function getToiletsWithoutChecklist($db) {
    $toiletSqlUnchecked = "SELECT * FROM toilet t
                           LEFT JOIN checklist c ON t.id = c.toilet_id
                           WHERE c.toilet_id IS NULL";

    $toiletStmtUnchecked = $db->query($toiletSqlUnchecked);
    return $toiletStmtUnchecked->fetchAll(PDO::FETCH_ASSOC);
}

// Function to display toilets without checklist
function displayToiletsWithoutChecklist($toiletsWithoutChecklist) {
    if (!empty($toiletsWithoutChecklist)) {
        echo '<h2>Daftar Toilet Belum Dichecklist</h2>';
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Lokasi</th>';
        echo '<th>Keterangan</th>';
        echo '</tr>';

        foreach ($toiletsWithoutChecklist as $toilet) {
            echo '<tr>';
            echo "<td>{$toilet['lokasi']}</td>";
            echo "<td>{$toilet['keterangan']}</td>";
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo "All toilets have corresponding entries in the checklist.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["show_unchecked"])) {
        // Get toilets without checklist
        $toiletsWithoutChecklist = getToiletsWithoutChecklist($db);

        // Display toilets without checklist
        displayToiletsWithoutChecklist($toiletsWithoutChecklist);
    }

    // Add other conditions as needed for different actions
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Checklist Toilet</title>

    <style>
        h2 {
            margin: 20px;
        }

        form label {
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            background-color: #FBF9F1;
            width: 97%;
            margin: 10px 20px;
            overflow-x: auto; /* Enable horizontal scrolling on small screens */
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #cbcccb;
        }

        li {
            list-style-type: none;
        }

        @media only screen and (max-width: 600px) {
            table {
                width: 100%; /* Make the table take up full width on small screens */
            }

            th, td {
                display: block; /* Stack cells vertically on small screens */
                width: 100%; /* Make cells take up full width on small screens */
                box-sizing: border-box; /* Include padding and border in cell width */
            }
        }
    </style>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Process the form submission
        $selectedDate = $_POST["selected_date"];
        // Validate and sanitize the date (you may want to enhance this validation)
        $selectedDate = filter_var($selectedDate, FILTER_SANITIZE_STRING);

        // Fetch data for checklists based on the selected date
        $checklistSql = "SELECT c.*, t.lokasi, u.name as name_users FROM checklist c
                        JOIN toilet t ON c.toilet_id = t.id
                        JOIN users u ON c.users_id = u.id
                        WHERE DATE(c.tanggal) = :selectedDate
                        ORDER BY c.tanggal DESC";

        $checklistStmt = $db->prepare($checklistSql);
        $checklistStmt->bindParam(":selectedDate", $selectedDate);
        $checklistStmt->execute();

        // Display the results
        $checklists = $checklistStmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($checklists)) {
            echo'<h2>Data Toilet Sudah Dichecklist</h2>';

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>Data Toilet</th>';
            echo '<th>User Id</th>';
            echo '<th>Checked on</th>';
            echo '<th>Kloset</th>';
            echo '<th>Wastafel</th>';
            echo '<th>Lantai</th>';
            echo '<th>Dinding</th>';
            echo '<th>Kaca</th>';
            echo '<th>Bau</th>';
            echo '<th>Sabun</th>';
            echo '</tr>';
            foreach ($checklists as $checklistData) {
                // Display checklist data as before
                echo '<tr>';

                // Filter toilets based on toilet_id in the current checklist data
                $filteredToilets = array_filter($toilets, function($toilet) use ($checklistData) {
                    return $toilet['id'] == $checklistData['toilet_id'];
                });

                if (!empty($filteredToilets)) {
                    $toilet = reset($filteredToilets); // Get the first element of the $filteredToilets array
                    echo "<td>{$toilet['lokasi']} - {$toilet['keterangan']}</td>";
                } else {
                    echo "No toilet data available.";
                }

                echo "<td>{$checklistData['users_id']}</td>";
                echo "<td> " . (isset($checklistData['tanggal']) ? $checklistData['tanggal'] : 'No date available.') . "</td>";

                // Display other checklist data as before
                $fields = ['kloset', 'wastafel', 'lantai', 'dinding', 'kaca'];
                
                foreach ($fields as $field) {
                    echo "<td>";
                    switch ($checklistData[$field]) {
                        case 1:
                            echo 'Bersih';
                            break;
                        case 2:
                            echo 'Kotor';
                            break;
                        case 3:
                            echo 'Rusak';
                            break;
                        default:
                            echo 'Unknown Option';
                    }
                    echo "</td>";
                }
                $alat = ['bau', 'sabun'];
                foreach ($alat as $alat){
                    echo "<td>";
                    switch ($checklistData[$alat]) {
                        case 1:
                            echo 'Ya';
                            break;
                        case 2:
                            echo 'Tidak';
                            break;
                        default:
                            echo 'Unknown Option';
                    }
                    echo "</td>";         
                }
            }
            echo '</table>';
        }
    } else {
        // Display the form to input the date
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
                <label for="selected_date">Cari Data Berdasarkan Tanggal:</label>
                <input type="date" id="selected_date" name="selected_date">
                <button type="submit">Show Checklist</button>
                <button type="submit" name="show_unchecked">Show Data Unchecked</button> <!-- Added button for showing unchecked data -->
              </form>';

        // Fetch data for the latest checklist
        $latestChecklistSql = "SELECT c.*, t.lokasi, u.name as name_users FROM checklist c
                               JOIN toilet t ON c.toilet_id = t.id
                               JOIN users u ON c.users_id = u.id
                               WHERE (toilet_id, tanggal) IN (
                                SELECT toilet_id, MAX(tanggal) as max_date
                                FROM checklist
                                GROUP BY toilet_id
                               )
                               ORDER BY c.tanggal DESC";


        $latestChecklistStmt = $db->query($latestChecklistSql);
        $latestChecklists = $latestChecklistStmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($latestChecklists)) {
            echo '<h2>Daftar Checklist Toilet Terbaru </h2>';

            echo '<table border="1">';
            echo '<tr>';
            echo '<th>Data Toilet</th>';
            echo '<th>User Id</th>';
            echo '<th>Checked on</th>';
            echo '<th>Kloset</th>';
            echo '<th>Wastafel</th>';
            echo '<th>Lantai</th>';
            echo '<th>Dinding</th>';
            echo '<th>Kaca</th>';
            echo '<th>Bau</th>';
            echo '<th>Sabun</th>';
            echo '</tr>';

            foreach ($latestChecklists as $latestChecklistData) {
                echo '<tr>';
                // Display latest checklist data as before
                $toiletInfo = '';
                $filteredToilets = array_filter($toilets, function($toilet) use ($latestChecklistData) {
                    return $toilet['id'] == $latestChecklistData['toilet_id'];
                });
                if (!empty($filteredToilets)) {
                    $toilet = reset($filteredToilets);
                    $toiletInfo = "<li>{$toilet['lokasi']} - {$toilet['keterangan']}</li>";
                } else {
                    $toiletInfo = "No toilet data available.";
                }
                echo "<td>{$toiletInfo}</td>";

                echo "<td>{$latestChecklistData['users_id']}</td>";
                echo "<td>" . (isset($latestChecklistData['tanggal']) ? $latestChecklistData['tanggal'] : 'No date available.') . "</td>";

                $fields = ['kloset', 'wastafel', 'lantai', 'dinding', 'kaca'];
                foreach ($fields as $field) {
                    echo "<td>";
                    switch ($latestChecklistData[$field]) {
                        case 1:
                            echo 'Bersih';
                            break;
                        case 2:
                            echo 'Kotor';
                            break;
                        case 3:
                            echo 'Rusak';
                            break;
                        default:
                            echo 'Unknown Option';
                    }
                    echo "</td>";
                }

                $alat = ['bau', 'sabun'];
                foreach ($alat as $alat){
                    echo "<td>";
                    switch ($latestChecklistData[$alat]) {
                        case 1:
                            echo 'Ya';
                            break;
                        case 2:
                            echo 'Tidak';
                            break;
                        default:
                            echo 'Unknown Option';
                    }
                    echo "</td>";
                }
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "No checklist data available.";
        }
    }
    ?>
</body>
</html>
