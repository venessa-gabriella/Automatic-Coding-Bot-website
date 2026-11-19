
<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"]) && ctype_digit($_GET["id"])) {

    $project_id = (int) $_GET["id"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare(
        "DELETE FROM projects WHERE id=? AND user_id=?"
    );

    $stmt->bind_param("ii", $project_id, $user_id);
    $stmt->execute();
}

header("Location: history.php");
exit;
?>
