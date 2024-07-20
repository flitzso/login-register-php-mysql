<?php
require 'config/database.php';

$username = $password = $confirm_password = '';
$username_err = $password_err = $confirm_password_err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar nome de usuário
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter a username.';
    } else {
        $sql = 'SELECT id FROM users WHERE username = :username';
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST['username']);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = 'This username is already taken.';
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo 'Oops! Something went wrong. Please try again later.';
            }
            unset($stmt);
        }
    }

    // Validar senha
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = 'Password must have at least 6 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validar confirmação de senha
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Verificar erros de entrada antes de inserir no banco de dados
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = 'INSERT INTO users (username, password) VALUES (:username, :password)';
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $param_password, PDO::PARAM_STR);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if ($stmt->execute()) {
                header('location: login.php');
            } else {
                echo 'Something went wrong. Please try again later.';
            }

            unset($stmt);
        }
    }

    unset($pdo);
}
?>

<?php include 'templates/header.php'; ?>

<div class="wrapper">
    <h2>Register</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Submit">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>

<?php include 'templates/footer.php'; ?>