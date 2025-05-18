<!DOCTYPE html>
	<html lang="hr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta name="author" content="Helena Borzan">
		<meta name="keywords" content="HTML, CSS, Web programming">
		<link rel="stylesheet" href="style.css">

		<title>Pocetna stranica</title> 
	</head>
    <script src="https://cdn.jsdelivr.net/npm/papaparse@5.4.1/papaparse.min.js"></script>
    <script src="script.js"></script>
	<body>
		<?php
			session_start();
			include('includes/db.php'); 

			$status = "";
			$isLoggedIn = isset($_SESSION['username']);
			if (isset($_POST['id']) && $_POST['id'] != "") {
				$id = test_input($_POST['id']);

				if (!is_numeric($id)) {
					$status = "<div class='box' style='color:red;'>Invalid movie ID.</div>";
				}
			}

			$title = $year = $genres = $duration = $country = $rating = "";

			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}

			if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_movie"])) {
				if (isset($_SESSION['username'])) {
					$title = test_input($_POST["title"]);
					$year = test_input($_POST["year"]);
					$genres = test_input($_POST["genres"]);
					$duration = test_input($_POST["duration"]);
					$country = test_input($_POST["country"]);
					$rating = test_input($_POST["rating"]);

					$stmt = $conn->prepare("INSERT INTO movies (title, year, genres, duration, country, rating, total_votes, directors) VALUES (?, ?, ?, ?, ?, ?, 0, '')");
					$stmt->bind_param("sisssd", $title, $year, $genres, $duration, $country, $rating);

					if ($stmt->execute()) {
						$status = "<div class='box'>Film je uspješno dodan!</div>";
					} else {
						$status = "<div class='box' style='color:red;'>Greška pri dodavanju filma.</div>";
					}
				}
			}
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
				if (!isset($_SESSION['username'])) {
					echo "<script>alert('Morate biti prijavljeni da biste dodali film.');</script>";
				} else {
					$movieId = intval($_POST['id']);
					$username = $_SESSION['username'];

					$check = mysqli_query($conn, "SELECT * FROM liked_movies WHERE username = '$username' AND movie_id = $movieId");
					if (mysqli_num_rows($check) == 0) {
						mysqli_query($conn, "INSERT INTO liked_movies (username, movie_id) VALUES ('$username', $movieId)");
					}
				}
			}
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie_id']) && isset($_SESSION['username'])) {
				$movieId = intval($_POST['delete_movie_id']);
				$username = $_SESSION['username'];

				mysqli_query($conn, "DELETE FROM liked_movies WHERE username = '$username' AND movie_id = $movieId");
			}
		?>
		<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
			<h1>
				<?php if (isset($_SESSION['username'])): ?>
					Dobrodošli <?= htmlspecialchars($_SESSION['username']) ?> na moju web stranicu
				<?php else: ?>
					Dobrodošli na moju web stranicu
				<?php endif; ?>
			</h1>
			<nav>
				<?php if (isset($_SESSION['username'])): ?>
					<a href="logout.php">Odjava</a>
				<?php else: ?>
					<a href="login.php" style="margin-right: 10px;">Prijava</a>
					<a href="register.php">Registracija</a>
				<?php endif; ?>
			</nav>
		</header>
		<nav aria-labelledby="prosirena-navigacija">
			<h2 id="prosirena-navigacija">Navigacija</h2> 
			<ul>				
				<li><a href="index.php">Pocetna</a></li>
				<li><a href="grafikon.html">Grafikon</a></li>
				<li><a href="slike.php">Slike</a></li>
			</ul>
		</nav>
		<h1>Popis Filmova</h1>
		<main class="flex-container">
			<table>
				<tr>
					<th>ID</th>
					<th>Naslov</th>
					<th>Godina</th>
					<th>Žanr</th>
					<th>Trajanje</th>
					<th>Država</th>
					<th>Ocjena</th>
				</tr>
				<tr><td>2</td><td>Bugs Bunny's Third Movie</td><td>1982</td><td>Animacija</td><td>76</td><td>SAD</td><td>7.7</td></tr>
				<tr><td>3</td><td>18 anni tra una settimana</td><td>1991</td><td>Drama</td><td>98</td><td>Italija</td><td>6.5</td></tr>
				<tr><td>17</td><td>Ride a Wild Pony</td><td>1976</td><td>Romantični</td><td>91</td><td>SAD</td><td>5.7</td></tr>
				<tr><td>18</td><td>Diner</td><td>1982</td><td>Komedija</td><td>95</td><td>SAD</td><td>7.0</td></tr>
				<tr><td>20</td><td>A che servono questi quattrini?</td><td>1942</td><td>Komedija</td><td>85</td><td>Italija</td><td>5.9</td></tr>
				<tr><td>22</td><td>A ciascuno il suo</td><td>1967</td><td>Drama</td><td>93</td><td>Italija</td><td>7.6</td></tr>
				<tr><td>23</td><td>Dead-Bang</td><td>1989</td><td>Krimić</td><td>109</td><td>SAD</td><td>6.0</td></tr>
				<tr><td>24</td><td>A... come assassino</td><td>1966</td><td>Triler</td><td>80</td><td>Italija</td><td>5.2</td></tr>
				<tr><td>26</td><td>At Close Range</td><td>1986</td><td>Drama</td><td>115</td><td>SAD</td><td>7.5</td></tr>
				<tr><td>30</td><td>A Ghentar si muore facile</td><td>1968</td><td>Avantura</td><td>101</td><td>Italija</td><td>4.8</td></tr>
				<tr><td>32</td><td>Sleeping with the Enemy</td><td>1990</td><td>Drama</td><td>96</td><td>SAD</td><td>5.0</td></tr>
				<tr><td>34</td><td>In Bed With Madonna</td><td>1990</td><td>Dokumentarni</td><td>111</td><td>SAD</td><td>5.3</td></tr>
				<tr><td>36</td><td>Bowery at Midnight</td><td>1942</td><td>Horor</td><td>62</td><td>SAD</td><td>5.1</td></tr>
				<tr><td>37</td><td>A mezzanotte va la ronda del piacere</td><td>1975</td><td>Komedija</td><td>100</td><td>Italija</td><td>5.8</td></tr>
				<tr><td>38</td><td>Mr. Majestyk</td><td>1974</td><td>Akcija</td><td>105</td><td>SAD</td><td>6.2</td></tr>
				<tr><td>45</td><td>Warning Sign</td><td>1985</td><td>Akcija</td><td>99</td><td>SAD</td><td>4.8</td></tr>
				<tr><td>47</td><td>About Last Night</td><td>1986</td><td>Komedija</td><td>113</td><td>SAD</td><td>5.9</td></tr>
				<tr><td>49</td><td>Fail-Safe</td><td>1964</td><td>Drama</td><td>110</td><td>SAD</td><td>8.2</td></tr>
				<tr><td>51</td><td>Some Like It Hot</td><td>1959</td><td>Komedija</td><td>120</td><td>SAD</td><td>9.1</td></tr>
				<tr><td>Inception</td><td>2010</td><td>Action;Sci-Fi</td><td>148</td><td>United States</td><td>200</td>
					<td>
						<form method="post" action="index.php">
						<input type="hidden" name="code" value="INCEPTION123">
						<input type="submit" value="Dodaj">
						</form>
					</td>
				</tr>
			</table>

			<!-- dodati jedan aside na desnoj strani s ugrađenom google mapom/filmom ili slično-->
			<aside>
				<img src="images.jpg">
			</aside>
		</main>
		
		<main class="flex-container">
            <div id="filteri">
                <select id="filter-genre">
                <option value="">-- Odaberi zanr --</option>
                <option value="Action">Action</option>
                <option value="Drama">Drama</option>
                <option value="Sci-Fi">Sci-Fi</option>
                <option value="Romance">Romance</option>
                <!-- Ostali zanrovi -->
                </select>

                <input type="number" id="filter-year-from" placeholder="Godina od">
                <input type="number" id="filter-year-to" placeholder="Godina do">

                <select id="filter-country">
                <option value="">-- Odaberi drzavu --</option>
                <option value="United States">United States</option>
                <option value="Italy">Italy</option>
                <!-- Ostale drzave -->
                </select>
                <label for="filter-votes">Minimalni broj glasova:</label>
                <input type="range" id="filter-votes" min="0" max="1000" step="50" value="0">
                <span id="votes-value">0</span>
                <button id="primijeni-filtere">Filtriraj</button>
			
			<table id="filmovi-tablica">
                <thead>
                  <tr>
                    <th>Naslov</th>
                    <th>Godina</th>
                    <th>Žanr</th>
                    <th>Trajanje</th>
                    <th>Država</th>
                    <th>Broj glasova</th>
                    <th>Dodaj</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>

			<aside id="kosarica">
                <h2 id="toggle-kosarica">Moja košarica ▼</h2>
                <div id="kosarica-sadrzaj" style="display: none;">
				  <ul id="lista-kosarice">
					<?php
					if (isset($_SESSION['username'])) {
						$username = $_SESSION['username'];
						$resultLiked = mysqli_query($conn, "
							SELECT m.id, m.title FROM liked_movies lm
							JOIN movies m ON lm.movie_id = m.id
							WHERE lm.username = '$username'
						");
						while ($liked = mysqli_fetch_assoc($resultLiked)) {
							echo '<li>' . htmlspecialchars($liked['title']) . '
								<form method="post" action="index.php" style="display:inline;">
									<input type="hidden" name="delete_movie_id" value="' . $liked['id'] . '">
									<button type="submit" title="Ukloni" style="border:none; background:none; cursor:pointer;">🗑️</button>
								</form>
							</li>';
						}
					} else {
						echo '<li>Morate biti prijavljeni za pregled košarice.</li>';
					}
					?>
				</ul>
                  <button id="potvrdi-kosaricu">Potvrdi odabir</button>
                </div>
            </aside>
		</main>
		<section id="unos-filma">
		<h2>Dodaj ili Uredi Film</h2>
			<form method="POST" action="index.php">
				<label for="title">Naslov:</label>
				<input type="text" name="title" required><br>

				<label for="year">Godina:</label>
				<input type="number" name="year" min="1900" max="2099" required><br>

				<label for="genres">Žanr:</label>
				<input type="text" name="genres" required><br>

				<label for="duration">Trajanje (min):</label>
				<input type="number" name="duration" min="30" max="300" required><br>

				<label for="country">Država:</label>
				<input type="text" name="country" required><br>

				<label for="rating">Ocjena (1-10):</label>
				<input type="number" step="0.1" min="1" max="10" name="rating" required><br>

				<input type="submit" name="submit_movie" value="Spremi Film">
			</form>
		</section>
		<section id="filtri">
			<h2>Filtriraj i sortiraj filmove</h2>
			<form method="get" action="index.php">
				<label for="year">Godina:</label>
				<input type="number" name="year" id="year" value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : '' ?>">

				<label for="genre">Žanr:</label>
				<input type="text" name="genre" id="genre" value="<?= isset($_GET['genre']) ? htmlspecialchars($_GET['genre']) : '' ?>">

				<label for="country">Država:</label>
				<input type="text" name="country" id="country" value="<?= isset($_GET['country']) ? htmlspecialchars($_GET['country']) : '' ?>">

				<label for="sort">Sortiraj po:</label>
				<select name="sort" id="sort">
					<option value="">-- Odaberi --</option>
					<option value="year" <?= isset($_GET['sort']) && $_GET['sort'] == 'year' ? 'selected' : '' ?>>Godina</option>
					<option value="rating" <?= isset($_GET['sort']) && $_GET['sort'] == 'rating' ? 'selected' : '' ?>>Ocjena</option>
					<option value="title" <?= isset($_GET['sort']) && $_GET['sort'] == 'title' ? 'selected' : '' ?>>Naslov</option>
				</select>

				<input type="submit" value="Primijeni">
			</form>
		</section>
		<section id="tablica-filmova">
			<h2>Filmovi iz baze podataka</h2>
			<table>
				<thead>
				<tr>
					<th>ID</th>
					<th>Naslov</th>
					<th>Godina</th>
					<th>Žanr</th>
					<th>Trajanje</th>
					<th>Država</th>
					<th>Ocjena</th>
					<th>Broj glasova</th>
					<th>Dodaj</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$query = "SELECT * FROM movies WHERE 1=1";
					if (!empty($_GET['year'])) {
						$year = intval($_GET['year']);
						$query .= " AND year = $year";
					}
					if (!empty($_GET['genre'])) {
						$genre = mysqli_real_escape_string($conn, $_GET['genre']);
						$query .= " AND genres LIKE '%$genre%'";
					}
					if (!empty($_GET['country'])) {
						$country = mysqli_real_escape_string($conn, $_GET['country']);
						$query .= " AND country LIKE '%$country%'";
					}
					if (!empty($_GET['sort'])) {
						$allowedSorts = ['year', 'rating', 'title'];
						if (in_array($_GET['sort'], $allowedSorts)) {
							$query .= " ORDER BY " . $_GET['sort'];
						}
					}
					$result = mysqli_query($conn, $query);
					while ($row = mysqli_fetch_assoc($result)): ?>
						<tr>
						<td><?= $row['id'] ?></td>
						<td><?= htmlspecialchars($row['title']) ?></td>
						<td><?= $row['year'] ?></td>
						<td><?= htmlspecialchars($row['genres']) ?></td>
						<td><?= $row['duration'] ?></td>
						<td><?= htmlspecialchars($row['country']) ?></td>
						<td><?= $row['rating'] ?></td>
						<td><?= $row['total_votes'] ?></td>
						<td>
						<form method="post" action="index.php" class="dodaj-form" data-rating="<?= htmlspecialchars($row['rating']) ?>"> 
							<input type="hidden" name="id" value="<?= $row['id'] ?>">
							<input type="submit" value="Dodaj">
						</form>
					</td>
					</tr>
				<?php endwhile; ?>
				</tbody>
			</table>
		</section>
		<footer>
		<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
	</body>
	</html>
	<script>
document.addEventListener('DOMContentLoaded', function () {
	const isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

	document.querySelectorAll('.dodaj-form').forEach(form => {
		form.addEventListener('submit', function (e) {
			if (!isLoggedIn) {
				e.preventDefault();
				alert('Morate biti prijavljeni da biste dodali film u košaricu.');
				return;
			}

			const rating = parseFloat(form.getAttribute('data-rating'));
			console.log("Rating from form:", rating);

			if (!isNaN(rating) && rating < 5.0) {
				const potvrda = confirm('Ovaj film ima nisku prosječnu ocjenu. Jeste li sigurni da ga želite dodati?');
				if (!potvrda) {
					e.preventDefault();
				}
			}
		});
	});
});
</script>