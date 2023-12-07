<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrator') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "dziennik_lekcyjny";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
} else {
    header("Location: admin_view_users.php");
    exit();
}

$selectUserQuery = "SELECT * FROM users WHERE id = $userId";
$result = $conn->query($selectUserQuery);

if ($result->num_rows == 0) {
    header("Location: admin_view_users.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deleteUserQuery = "DELETE FROM users WHERE id = $userId";

    if ($conn->query($deleteUserQuery) === TRUE) {
        header("Location: admin_view_users.php");
        exit();
    } else {
        echo "Błąd podczas usuwania użytkownika: " . $conn->error;
    }
}

$conn->close();
?>

    <?php include 'header.html'; ?>

    <div class="container">
        <h2 class="text-center mb-4">Potwierdź Usunięcie Użytkownika</h2>
        <p>Czy na pewno chcesz usunąć tego użytkownika?</p>
        <form action="admin_delete_user.php?id=<?php echo $userId; ?>" method="post">
            <button type="submit" class="btn btn-danger">Usuń</button>
            <a href="admin_view_users.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>

    <?php include 'footer.html'; ?>
