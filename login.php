<?php
session_start();
require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: dashboard.php");
            exit;

        } else {
            $message = "Şifre yanlış.";
        }

    } else {
        $message = "Kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card">

                <div class="card-header">
                    <h3>Giriş Yap</h3>
                </div>

                <div class="card-body">

                    <?php if($message): ?>
                    <div class="alert alert-danger">
                        <?= $message ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <button class="btn btn-success">
                            Giriş Yap
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>