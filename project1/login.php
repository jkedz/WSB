<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "dziennik_lekcyjny";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $row["role"];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Błędne hasło!";
        }
    } else {
        $error_message = "Użytkownik nie istnieje!";
    }

    $conn->close();
}
?>

<?php include 'header.html'; ?>

<div class="container">
    <h2 class="text-center mb-4">Logowanie</h2>
    <?php
    if (isset($error_message)) {
        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
    }
    ?>
    <form id="loginForm" action="login.php" method="post">
        <div class="form-group">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Hasło:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Zaloguj się</button>
    </form>
</div>

<?php include 'footer.html'; ?>