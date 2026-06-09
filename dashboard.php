<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
require "db.php";
$totalSql = "SELECT COUNT(*) as total
             FROM habits
             WHERE user_id = ?";

$stmt = $conn->prepare($totalSql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$totalResult = $stmt->get_result();
$totalHabits = $totalResult->fetch_assoc()["total"];


$completedSql = "SELECT COUNT(*) as completed
                 FROM habits
                 WHERE user_id = ?
                 AND completed = 1";

$stmt = $conn->prepare($completedSql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$completedResult = $stmt->get_result();
$completedHabits = $completedResult->fetch_assoc()["completed"];


$percentage = 0;

if ($totalHabits > 0) {
    $percentage = round(
        ($completedHabits / $totalHabits) * 100
    );
}
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM habits WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1>
        Hoş geldin,
        <?= $_SESSION["username"] ?>
    </h1>
    <div class="row mt-4">

    <div class="col-md-4">

        <div class="card text-bg-primary mb-3">

            <div class="card-body">
                <h5 class="card-title">
                    Toplam Alışkanlık
                </h5>

                <h2>
                    <?= $totalHabits ?>
                </h2>
            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card text-bg-success mb-3">

            <div class="card-body">
                <h5 class="card-title">
                    Tamamlanan
                </h5>

                <h2>
                    <?= $completedHabits ?>
                </h2>
            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card text-bg-warning mb-3">

            <div class="card-body">
                <h5 class="card-title">
                    Başarı Oranı
                </h5>

                <h2>
                    %<?= $percentage ?>
                </h2>
            </div>

        </div>

    </div>

</div>
    <h3 class="mt-4">Alışkanlıklarım</h3>

<table class="table table-bordered mt-3">

    <thead>
        <tr>
            <th>ID</th>
            <th>Başlık</th>
            <th>Açıklama</th>
            <th>Günlük Hedef</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
    </thead>

    <tbody>

        <?php while($habit = $result->fetch_assoc()): ?>

        <tr>
            <td><?= $habit["id"] ?></td>
            <td><?= $habit["title"] ?></td>
            <td><?= $habit["description"] ?></td>
            <td><?= $habit["target_count"] ?></td>
            <td>
    <?php if($habit["completed"] == 1): ?>
        <span class="badge bg-success">Tamamlandı</span>
    <?php else: ?>
        <span class="badge bg-secondary">Tamamlanmadı</span>
    <?php endif; ?>
</td>

    <td>

    <a
        href="habit_complete.php?id=<?= $habit['id'] ?>"
        class="btn btn-success btn-sm">
        Tamamla
    </a>

    <a
        href="habit_edit.php?id=<?= $habit['id'] ?>"
        class="btn btn-warning btn-sm">
        Düzenle
    </a>

    <a
        href="habit_delete.php?id=<?= $habit['id'] ?>"
        class="btn btn-danger btn-sm">
        Sil
    </a>

</td>
        </tr>
       

        <?php endwhile; ?>

    </tbody>

</table>

    <a href="habit_add.php" class="btn btn-primary mt-3">
    Yeni Alışkanlık Ekle
</a>
    <a href="logout.php" class="btn btn-danger mt-3">
    Çıkış Yap
</a>

</div>

</body>
</html>