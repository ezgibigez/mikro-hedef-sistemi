<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "habit_tracker"
);

if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası!");
}
?>