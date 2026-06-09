<?php

require "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $sql = "INSERT INTO users(username,email,password)
            VALUES(?,?,?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sss",
        $username,
        $email,
        $hashedPassword
    );

    if($stmt->execute()){

    header("Location: login.php");
    exit;

}else{
    $message = "Hata oluştu.";
}

}
?>

?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card">

                <div class="card-header">
                    <h3>Kayıt Ol</h3>
                </div>

                <div class="card-body">

                <?php if($message): ?>
<div class="alert alert-info">
    <?= $message ?>
</div>
<?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Kullanıcı Adı</label>
                            <input type="text" name="username" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>E-Posta</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Şifre</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Kayıt Ol
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>