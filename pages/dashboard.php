<?php

require_once("../api/config.php");
require_once("../api/auth.php");
require_once("../api/toilet/delete_toilet.php");
require_once("../api/toilet/tambah_toilet.php");

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa; /* Bootstrap background color */
        }

        .container {
            background-color: #ffffff; /* White background for container */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff; /* Bootstrap primary color */
            color: black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Alternate row background color */
        }
        hr {
            margin-top: 30px;
            color: black;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="display-5">Dashboard</h2>
        <h4>Nama: <?php echo $_SESSION["user"]["name"]?></h4>
        <h4>Email: <?php echo $_SESSION["user"]["email"] ?></h4>
        <hr>

        <h2 class="mt-4">Tambah Daftar Toilet</h2>
        <?php echo $notification_add; // Display the notification ?>

        
        <form action="" method="POST">
            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" class="form-control" placeholder="Lokasi">
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
            </div>

            <input type="submit" class="btn btn-primary btn-block" name="submit" value="Submit" />
        </form>
        <hr>

        <h2>Data Toilet</h2>
        <?php echo $notification_delete; // Display the notification ?>

        <?php if (isset($rows) && is_array($rows) && count($rows) > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $row['lokasi']; ?></td>
                            <td><?php echo $row['keterangan']; ?></td>
                            <td class="align-middle">
                                <div class="d-flex justify-content-center">
                                    <a href="/api/toilet/edit_toilet.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mx-2">Edit</a>
                                    <a href="checklist.php?id=<?php echo $row['id']; ?>" class="btn btn-info mx-2">Checklist</a>
                                </div>   
                            </td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No data available.</p>
        <?php endif; ?>

        <p class="mt-3"><a href="auth/logout.php" class="btn btn-secondary">Logout</a></p>
    </div>
</body>

</html>