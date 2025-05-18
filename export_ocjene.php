<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['username'])) {
	die("Morate biti prijavljeni za izvoz ocjena.");
}

$username = $_SESSION['username'];
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="moje_ocjene.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Naziv slike', 'Ocjena', 'Vrijeme ocjene']);

// Get ratings from database
$query = "SELECT id_slika, ocjena, vrijeme_ocjene FROM ocjene_slika WHERE id_korisnik = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
	fputcsv($output, [$row['id_slika'], $row['ocjena'], $row['vrijeme_ocjene']]);
}

fclose($output);
exit;
