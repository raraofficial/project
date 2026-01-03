<?php
require_once 'koneksi.php';
require_once 'action.php';
session_start();

$db = new Database();
$conn = $db->getConnection();
$userObj = new User($conn);

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $userObj->login($username, $password);

    if ($user) {
        $_SESSION['user'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="login-page">

        <div class="login-card">

            <h2>Login</h2>

            <?php if (!empty($error)) : ?>
            <div class="alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post" style="background-color: #ffe5e5; padding:20px; border-radius:8px;">

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autocomplete="off"
                        value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required autocomplete="off">
                </div>

                <button type="submit" name="login" class="btn-login">
                    Login
                </button>

            </form>

        </div>

    </div>

</body>

</html>