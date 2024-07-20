<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('location: login.php');
    exit;
}
?>

<?php include 'templates/header.php'; ?>

<div class="page-header">
    <h1>Hi, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. Welcome to our site.</h1>
</div>
<p>
    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
</p>

<?php include 'templates/footer.php'; ?>