<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || $password !== $confirmPassword) {
        die("Błąd: Wprowadzone dane są niepoprawne.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "dziennik_lekcyjny";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'student')");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Rejestracja zakończona sukcesem!";
    } else {
        echo "Błąd podczas rejestracji: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>