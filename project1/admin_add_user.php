<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrator') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["newUsername"];
    $newEmail = $_POST["newEmail"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];
    $selectedRole = $_POST["selectedRole"];

    if (empty($newUsername) || empty($newEmail) || empty($newPassword) || empty($confirmPassword) || $newPassword !== $confirmPassword) {
        die("Błąd: Wprowadzone dane są niepoprawne.");
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $conn = new mysqli("localhost", "root", "", "dziennik_lekcyjny");

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $checkUserQuery = "SELECT * FROM users WHERE username='$newUsername'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        die("Błąd: Użytkownik o podanej nazwie już istnieje.");
    }

    $insertUserQuery = "INSERT INTO users (username, email, password, role) VALUES ('$newUsername', '$newEmail', '$hashedPassword', '$selectedRole')";

    if ($conn->query($insertUserQuery) === TRUE) {
        echo "Dodano nowego użytkownika!";
    } else {
        echo "Błąd podczas dodawania użytkownika: " . $conn->error;
    }

    $conn->close();
}
?>

<body>
    <?php include 'header.html'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Dodaj nowego użytkownika</h2>
        <form id="addUserForm" action="admin_add_user.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="newUsername">Nowa nazwa użytkownika:</label>
                <input type="text" class="form-control" id="newUsername" name="newUsername" required>
            </div>
            <div class="form-group">
                <label for="newEmail">Nowy adres email:</label>
                <input type="email" class="form-control" id="newEmail" name="newEmail" required>
            </div>
            <div class="form-group">
                <label for="newPassword">Nowe hasło:</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Potwierdź hasło:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <label for="selectedRole">Wybierz rolę:</label>
                <select class="form-control" id="selectedRole" name="selectedRole" required>
                    <option value="student">Student</option>
                    <option value="teacher">Nauczyciel</option>
                    <option value="administrator">Administrator</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Dodaj użytkownika</button>
        </form>
    </div>

    <?php include 'footer.html'; ?>