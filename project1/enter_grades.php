<?php
session_start();

if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'administrator')) {
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

$studentsQuery = "SELECT id, username FROM users WHERE role = 'student'";
$studentsResult = $conn->query($studentsQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST["studentId"];
    $grade = $_POST["grade"];
    $subject = $_POST["subject"];
    $teacherId = $_SESSION['id'];

    $insertGradeQuery = "INSERT INTO grades (student_id, teacher_id, grade, subject) VALUES ($studentId, $teacherId, $grade, '$subject')";

    if ($conn->query($insertGradeQuery) === TRUE) {
        $insertModificationQuery = "INSERT INTO grade_modifications (student_id, teacher_id, new_grade, subject) VALUES ($studentId, $teacherId, $grade, '$subject')";

        if ($conn->query($insertModificationQuery) !== TRUE) {
            echo "Błąd podczas dodawania modyfikacji oceny: " . $conn->error;
        }

        echo "Dodano ocenę!";
    } else {
        echo "Błąd podczas dodawania oceny: " . $conn->error;
    }
}

$conn->close();
?>

    <?php include 'header.html'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Wprowadź Ocenę</h2>
        <form id="enterGradesForm" action="enter_grades.php" method="post">
            <div class="form-group">
                <label for="studentId">Wybierz ucznia:</label>
                <select class="form-control" id="studentId" name="studentId" required>
                    <?php while ($row = $studentsResult->fetch_assoc()) : ?>
                        <option value="<?= $row['id'] ?>"><?= $row['username'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
			<div class="form-group">
				<label for="grade">Ocena:</label>
				<input type="number" class="form-control" id="grade" name="grade" required min="1" max="6">
			</div>
			<div class="form-group">
				<label for="subject">Przedmiot:</label>
				<select class="form-control" id="subject" name="subject" required>
					<option value="biologia">Biologia</option>
					<option value="chemia">Chemia</option>
					<option value="edukacja_dla_bezpieczenstwa">Edukacja dla Bezpieczeństwa</option>
					<option value="edukacja_wczesnoszkolna">Edukacja Wczesnoszkolna</option>
					<option value="fizyka">Fizyka</option>
					<option value="geografia">Geografia</option>
					<option value="historia">Historia</option>
					<option value="informatyka">Informatyka</option>
					<option value="jezyk_obcy_nowozytny">Język Obcy Nowożytny</option>
					<option value="drugi_jezyk_obcy_nowozytny">Drugi Język Obcy Nowożytny</option>
					<option value="jezyk_polski">Język Polski</option>
					<option value="matematyka">Matematyka</option>
					<option value="muzyka">Muzyka</option>
					<option value="plastyka">Plastyka</option>
					<option value="przyroda">Przyroda</option>
					<option value="technika">Technika</option>
					<option value="wiedza_o_spoleczenstwie">Wiedza o Społeczeństwie</option>
					<option value="wychowanie_fizyczne">Wychowanie Fizyczne</option>
				</select>
			</div>
            <button type="submit" class="btn btn-primary btn-block">Dodaj Ocenę</button>
        </form>
    </div>

    <?php include 'footer.html'; ?>
