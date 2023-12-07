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

$userData = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST["newUsername"];
    $newEmail = $_POST["newEmail"];
    $newRole = $_POST["newRole"];

    $updateUserQuery = "UPDATE users SET username='$newUsername', email='$newEmail', role='$newRole' WHERE id=$userId";

    if ($conn->query($updateUserQuery) === TRUE) {
        header("Location: admin_view_users.php");
        exit();
    } else {
        echo "Błąd podczas edycji danych użytkownika: " . $conn->error;
    }

    if (!empty($_POST["newPassword"])) {
        $newPassword = $_POST["newPassword"];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE users SET password='$hashedPassword' WHERE id=$userId";

        if ($conn->query($updatePasswordQuery) !== TRUE) {
            echo "Błąd podczas aktualizacji hasła użytkownika: " . $conn->error;
        }
    }
}

$conn->close();
?>

    <?php include 'header.html'; ?>

    <div class="container">
        <h2 class="text-center mb-4">Edytuj Użytkownika</h2>
        <form id="editUserForm" action="admin_edit_user.php?id=<?php echo $userId; ?>" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="newUsername">Nowa nazwa użytkownika:</label>
                <input type="text" class="form-control" id="newUsername" name="newUsername" value="<?php echo $userData['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="newEmail">Nowy adres email:</label>
                <input type="email" class="form-control" id="newEmail" name="newEmail" value="<?php echo $userData['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="newPassword">Nowe hasło:</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword">
            </div>
            <div class="form-group">
                <label for="newRole">Nowa rola:</label>
                <select class="form-control" id="newRole" name="newRole">
                    <option value="student" <?php if ($userData['role'] == 'student') echo 'selected'; ?>>Student</option>
                    <option value="teacher" <?php if ($userData['role'] == 'teacher') echo 'selected'; ?>>Nauczyciel</option>
                    <option value="administrator" <?php if ($userData['role'] == 'administrator') echo 'selected'; ?>>Administrator</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Zapisz zmiany</button>
        </form>
    </div>

    <?php include 'footer.html'; ?>