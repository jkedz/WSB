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

$selectUsersQuery = "SELECT * FROM users";
$result = $conn->query($selectUsersQuery);

$conn->close();
?>

    <?php include 'header.html'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Wszyscy Użytkownicy</h2>

        <?php
        if ($result->num_rows > 0) {
            echo "<table class='table'>
                    <thead><tr><th>ID</th><th>Nazwa Użytkownika</th><th>Email</th><th>Rola</th><th>Akcje</th></tr></thead>
                    <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <a href='admin_edit_user.php?id={$row['id']}' class='btn btn-warning'>Edytuj</a>
                            <a href='admin_delete_user.php?id={$row['id']}' class='btn btn-danger'>Usuń</a>
                        </td>
                      </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "Brak użytkowników.";
        }
        ?>
    </div>

    <?php include 'footer.html'; ?>
