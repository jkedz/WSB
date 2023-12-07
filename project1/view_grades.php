<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
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

$studentId = $_SESSION['id'];

$gradesQuery = "SELECT * FROM grades WHERE student_id = $studentId";
$gradesResult = $conn->query($gradesQuery);
?>

    <?php include 'header.html'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Twoje Oceny</h2>
        <?php if ($gradesResult->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Przedmiot</th>
                        <th>Ocena</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $gradesResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['subject'] ?></td>
                            <td><?= $row['grade'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Brak ocen do wyświetlenia.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.html'; ?>
