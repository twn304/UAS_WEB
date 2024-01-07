<?php

$notification_delete = "";
if (isset($_POST["delete"])) {
    $delete_id = filter_input(INPUT_POST, 'delete_id', FILTER_SANITIZE_NUMBER_INT);

    // Perform deletion
    $delete_sql = "DELETE FROM toilet WHERE id = :delete_id";
    $delete_stmt = $db->prepare($delete_sql);
    $delete_params = array(":delete_id" => $delete_id);
    $deleted = $delete_stmt->execute($delete_params);

    if ($deleted) {
        $notification_delete = '<div class="alert alert-success" role="alert">
                            Toilet deleted successfully!
                        </div>';
    } else {
        $notification_delete = '<div class="alert alert-danger" role="alert">
                            Error deleting toilet. Please try again.
                        </div>';
    }
}
?>