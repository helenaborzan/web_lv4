<!DOCTYPE html>
<html lang="hr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Helena Borzan">
	<meta name="keywords" content="HTML, CSS, Web programming, galerija, slike, lightbox">
	<meta name="description" content="Galerija slika s lightbox funkcionalnosti bez JavaScripta">
	<link rel="stylesheet" href="style_slike.css">
	<title>Galerija slika</title>
</head>
<body>
	<?php
		session_start();
		include('includes/db.php');

		$status = "";
		$isLoggedIn = isset($_SESSION['username']);

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ocjena'], $_POST['id_slika']) && $isLoggedIn) {
			$username = $_SESSION['username'];
			$imageName = mysqli_real_escape_string($conn, $_POST['id_slika']);
			$rating = intval($_POST['ocjena']);

			$checkQuery = "SELECT * FROM ocjene_slika WHERE id_korisnik = '$username' AND id_slika = '$imageName'";
			$check = mysqli_query($conn, $checkQuery);

			if ($check) {
				if (mysqli_num_rows($check) === 0) {
					$query = "INSERT INTO ocjene_slika (id_korisnik, id_slika, ocjena) VALUES ('$username', '$imageName', $rating)";
				} else {
					$query = "UPDATE ocjene_slika SET ocjena = $rating, vrijeme_ocjene = CURRENT_TIMESTAMP WHERE id_korisnik = '$username' AND id_slika = '$imageName'";
				}

				if (mysqli_query($conn, $query)) {
					$status = "Ocjena je spremljena.";
				} else {
					$status = "Greška prilikom spremanja ocjene.";
				}
			} else {
				$status = "Greška prilikom provjere ocjene.";
			}
		}
		if (isset($_POST['uploadBtn']) && isset($_FILES['upload']) && $isLoggedIn) {
			$uploadDir = __DIR__ . 'images/';
			$allowedTypes = ['image/jpeg', 'image/png']; 
			$maxSize = 5 * 1024 * 1024; 

			$fileTmp  = $_FILES['upload']['tmp_name'];
			$fileName = basename($_FILES['upload']['name']);
			$fileSize = $_FILES['upload']['size'];
			$fileType = mime_content_type($fileTmp);

			if (!in_array($fileType, $allowedTypes)) {
				$status = "Dozvoljeni su samo JPG i PNG formati.";
			} elseif ($fileSize > $maxSize) {
				$status = "Veličina slike mora biti manja od 5MB.";
			} else {
				$destination = $uploadDir . $fileName;

				if (!file_exists($destination)) {
					if (move_uploaded_file($fileTmp, $destination)) {
						$status = "Slika je uspješno prenesena.";
					} else {
						$status = "Greška prilikom spremanja slike.";
					}
				} else {
					$status = "Slika s tim imenom već postoji.";
				}
			}
		}
		?>
	<nav aria-labelledby="prosirena-navigacija">
		<h2 id="prosirena-navigacija">Navigacija</h2>
		<ul>
			<li><a href="index.php">Početna</a></li>
			<li><a href="grafikon.html">Grafikon</a></li>
			<li><a href="slike.php">Slike</a></li>
		</ul>
	</nav>
	<?php if (isset($_SESSION['username'])): ?>
		<form method="post" action="export_ocjene.php" style="text-align:center; margin-bottom: 20px;">
			<button type="submit">Izvezi moje ocjene u CSV</button>
		</form>
	<?php endif; ?>
	<section class="galerija">
	<h1>Galerija slika</h1>
	<?php
	$folder = "images/";
	$images = array_values(array_diff(scandir($folder), ['.', '..']));
	foreach ($images as $index => $filename):
		$id = $index + 1;
	?>
		<figure class="galerija_slika">
			<a href="#lightbox-<?= $id ?>">
				<img src="<?= $folder . $filename ?>" alt="Slika <?= $id ?>" loading="lazy">
			</a>
			<figcaption>Slika <?= $id ?> (<?= htmlspecialchars($filename) ?>)</figcaption>

			<?php if (isset($_SESSION['username'])): ?>
				<form method="post" action="slike.php" class="rating-form">
					<input type="hidden" name="id_slika" value="<?= htmlspecialchars($filename) ?>">
					<label for="ocjena-<?= $id ?>">Ocijeni sliku (1-5):</label>
					<select name="ocjena" id="ocjena-<?= $id ?>" required>
						<option value="">-- Odaberi --</option>
						<?php for ($i = 1; $i <= 5; $i++): ?>
							<option value="<?= $i ?>"><?= $i ?></option>
						<?php endfor; ?>
					</select>
					<button type="submit">Pošalji</button>
				</form>
			<?php else: ?>
				<p><em>Morate biti prijavljeni za ocjenjivanje.</em></p>
			<?php endif; ?>

			<?php
				$escapedFile = mysqli_real_escape_string($conn, $filename);
				$res = mysqli_query($conn, "SELECT AVG(ocjena) AS avg_rating, COUNT(*) AS total FROM ocjene_slika WHERE id_slika = '$escapedFile'");
				$data = mysqli_fetch_assoc($res);
				if ($data['total'] > 0):
			?>
				<p>Prosječna ocjena: <?= number_format($data['avg_rating'], 2) ?> (<?= $data['total'] ?> glasova)</p>
			<?php else: ?>
				<p>Još nema ocjena.</p>
			<?php endif; ?>
		</figure>
	<?php endforeach; ?>
					</section>

	<?php
	foreach ($images as $index => $filename):
		$id = $index + 1;
		$prev = ($id == 1) ? count($images) : $id - 1;
		$next = ($id == count($images)) ? 1 : $id + 1;
	?>
		<div id="lightbox-<?= $id ?>" class="lightbox">
			<figure>
				<img src="<?= $folder . $filename ?>" alt="Slika <?= $id ?>">
				<figcaption>Slika <?= $id ?></figcaption>
			</figure>
			<a href="#lightbox-<?= $prev ?>" class="prev">&lt;</a>
			<a href="#lightbox-<?= $next ?>" class="next">&gt;</a>
			<a href="#" class="close">X</a>
		</div>
	<?php endforeach; ?>
	<?php if (isset($_SESSION['username'])): ?>
		<section class="upload-section">
			<h2>Dodaj novu sliku</h2>
			<form action="slike.php" method="post" enctype="multipart/form-data">
				<label for="upload">Odaberite sliku (JPG, PNG):</label>
				<input type="file" name="upload" id="upload" accept="image/*" required>
				<button type="submit" name="uploadBtn">Pošalji</button>
			</form>
		</section>
	<?php endif; ?>
</body>
</html>