<?php

$notification_add = "";
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

    if ($saved) {
        $notification_add = '<div class="alert alert-success" role="alert">
                            Toilet added successfully!
                        </div>';
    } else {
        $notification_add = '<div class="alert alert-danger" role="alert">
                            Error adding toilet. Please try again.
                        </div>';
    }
}
?>