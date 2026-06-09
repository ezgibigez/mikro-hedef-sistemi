<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $target_count = (int)$_POST["target_count"];

    $sql = "INSERT INTO habits
            (user_id,title,description,target_count)
            VALUES (?,?,?,?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "issi",
        $_SESSION["user_id"],
        $title,
        $description,
        $target_count
    );

    if ($stmt->execute()) {

    header("Location: dashboard.php");
    exit;

}
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alışkanlık Ekle</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card">

        <div class="card-header">
            <h3>Yeni Alışkanlık</h3>
        </div>

        <div class="card-body">

            <?php if($message): ?>
                <div class="alert alert-success">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label>Başlık</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Açıklama</label>
                    <textarea
                        name="description"
                        class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Günlük Hedef</label>
                    <input
                        type="number"
                        name="target_count"
                        class="form-control"
                        value="1">
                </div>

                <button class="btn btn-success">
                    Kaydet
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>