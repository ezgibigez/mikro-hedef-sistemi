<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$id = (int)$_GET["id"];

$sql = "SELECT * FROM habits
        WHERE id = ?
        AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $id,
    $_SESSION["user_id"]
);

$stmt->execute();

$result = $stmt->get_result();

$habit = $result->fetch_assoc();

if (!$habit) {
    die("Kayıt bulunamadı.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $target_count = (int)$_POST["target_count"];

    $sql = "UPDATE habits
            SET title=?,
                description=?,
                target_count=?
            WHERE id=?
            AND user_id=?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssiii",
        $title,
        $description,
        $target_count,
        $id,
        $_SESSION["user_id"]
    );

    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alışkanlık Düzenle</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card">

        <div class="card-header">
            <h3>Alışkanlık Düzenle</h3>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label>Başlık</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="<?= htmlspecialchars($habit['title']) ?>">
                </div>

                <div class="mb-3">
                    <label>Açıklama</label>

                    <textarea
                        name="description"
                        class="form-control"><?= htmlspecialchars($habit['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Günlük Hedef</label>

                    <input
                        type="number"
                        name="target_count"
                        class="form-control"
                        value="<?= $habit['target_count'] ?>">
                </div>

                <button class="btn btn-success">
                    Güncelle
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>