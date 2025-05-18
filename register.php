<!DOCTYPE html>
<html lang="hr">
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="loginStyle.css">
	<title>Registracija</title>
</head>
<body>
    <?php
        session_start();
        include('includes/db.php'); 

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = $_POST['password'];

            $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Korisničko ime je već zauzeto.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
                if (mysqli_query($conn, $query)) {
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Greška prilikom registracije.";
                }
            }
        }
    ?>
	<div class="auth-container" id="register-container">
        <?php if (isset($error)): ?>
            <p class="auth-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
		<h2 class="auth-title">Registracija</h2>
		<form method="post" action="register.php" class="auth-form" id="register-form">
            <div class="form-group">
                <label for="username">Korisničko ime:</label>
                <input type="text" name="username" id="username" class="auth-input" required>
            </div>

            <div class="form-group">
                <label for="password">Lozinka:</label>
                <input type="password" name="password" id="password" class="auth-input" required>
            </div>

            <input type="submit" value="Registriraj se" class="auth-button">
        </form>
		<p class="auth-link">Već imate račun? <a href="login.php">Prijavite se</a></p>
	</div>
</body>
</html>

