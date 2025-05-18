<!DOCTYPE html>
<html lang="hr">
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="loginStyle.css">
	<title>Prijava</title>
</head>
<body>
    <?php
        session_start();
        include('includes/db.php'); 

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = $_POST['password'];

            $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $error = "Neispravno korisničko ime ili lozinka.";
            }
        }
        ?>
	<div class="auth-container" id="login-container">
		<h2 class="auth-title">Prijava</h2>
		<form method="post" action="login.php" class="auth-form" id="login-form">
			<label for="username">Korisničko ime:</label>
			<input type="text" name="username" id="username" class="auth-input" required>

			<label for="password">Lozinka:</label>
			<input type="password" name="password" id="password" class="auth-input" required>

			<input type="submit" value="Prijavi se" class="auth-button">
		</form>
		<p class="auth-link">Nemate račun? <a href="register.php">Registrirajte se</a></p>
	</div>
</body>
</html>