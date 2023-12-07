<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrator') {
    header("Location: index.php");
    exit();
}

include 'header.html';
?>

<div class="container mt-4">
    <h2>Panel Administratora</h2>

    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="admin_add_user.php" class="btn btn-primary btn-block">Dodaj użytkownika</a>
        </div>
        <div class="col-md-12">
            <a href="admin_view_users.php" class="btn btn-secondary btn-block">Wyświetl wszystkich użytkowników</a>
        </div>
    </div>
</div>

<?php include 'footer.html'; ?>