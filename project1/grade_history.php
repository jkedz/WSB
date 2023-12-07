<?php
session_start();

if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'student')) {
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

$userId = $_SESSION['id'];
$role = $_SESSION['role'];
$historyQuery = ($role === 'teacher') ? "SELECT * FROM grade_modifications WHERE teacher_id = $userId" : "SELECT * FROM grade_modifications WHERE student_id = $userId";
$historyResult = $conn->query($historyQuery);
?>

    <?php include 'header.html'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Historia Modyfikacji Oceny</h2>
        <?php if ($historyResult->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Data Modyfikacji</th>
                        <th>Ocena</th>
                        <th>Przedmiot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $historyResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['modification_date'] ?></td>
                            <td><?= $row['new_grade'] ?></td>
                            <td><?= $row['subject'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Brak historii modyfikacji ocen.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.html'; ?>
