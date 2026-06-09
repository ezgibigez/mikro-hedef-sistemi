<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$id = (int)$_GET["id"];

$sql = "UPDATE habits
        SET completed = 1
        WHERE id = ?
        AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $id,
    $_SESSION["user_id"]
);

$stmt->execute();

header("Location: dashboard.php");
exit;