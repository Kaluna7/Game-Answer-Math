<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'math_game';
$user = 'root';
$pass = '';
try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Get data dari form
$username = $_POST['username'];
$password = $_POST['password'];
$retype_password = $_POST['retype_password'];
$name = $_POST['name'];

// Memeriksa pasword
if ($password !== $retype_password) {
    die("Password tidak cocok.");
}

// Cek username apakah ada atau tidak
$query = $dbh->prepare("SELECT * FROM users WHERE username = :username");
$query->bindParam(':username', $username);
$query->execute();

if ($query->rowCount() > 0) {
    die("Username sudah digunakan.");
}
// hash pasword
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Menyimpan data pengguna ke database
$query = $dbh->prepare("INSERT INTO users (username, password, name) VALUES (:username, :password, :name)");
$query->bindParam(':username', $username);
$query->bindParam(':password', $hashed_password);
$query->bindParam(':name', $name);
$query->execute();

// show ketika berhasil
echo "Registrasi berhasil. Silakan kembali ke halaman utama.";
?>