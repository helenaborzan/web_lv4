<?php
include('includes/db.php');

$message = "";

if (isset($_POST['submit_movie'])) {
    $title = trim($_POST['title']);
    $year = (int)$_POST['year'];
    $genres = trim($_POST['genres']);
    $duration = (int)$_POST['duration'];
    $country = trim($_POST['country']);
    $rating = (float)$_POST['rating'];

    $errors = [];

    if ($year < 1900 || $year > 2099) {
        $errors[] = "Godina mora biti između 1900 i 2099.";
    }
    if ($duration < 30 || $duration > 300) {
        $errors[] = "Trajanje filma mora biti između 30 i 300 minuta.";
    }
    if ($rating < 1 || $rating > 10) {
        $errors[] = "Ocjena mora biti između 1 i 10.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO movies (title, year, genres, duration, country, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisisi", $title, $year, $genres, $duration, $country, $rating);

        if ($stmt->execute()) {
            $message = "Film uspješno dodan!";
        } else {
            $message = "Greška pri unosu filma: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    } else {
        foreach ($errors as $error) {
            $message .= $error . "<br>";
        }
    }

    mysqli_close($conn);

    header("Location: index.html?message=" . urlencode($message));
    exit();
}
