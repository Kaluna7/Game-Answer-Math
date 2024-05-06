<?php
// koneksi database
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

// Mengecek pengguna
$query = $dbh->prepare("SELECT * FROM users WHERE username = :username");
$query->bindParam(':username', $username);
$query->execute();

// Get data pengguna
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    die("Username atau password salah.");
}

// CSS
echo '<style>
/* Mengatur gaya elemen */
body {
    font-family: Arial, sans-serif;
    color: #333;
}

/* Mengatur tabel */
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    overflow: hidden;
}

table, th, td {
    border: 1px solid #007bff;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Mengatur judul */
h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 20px;
}

/* Mengatur nama pengguna */
p {
    font-size: 20px;
    font-weight: bold;
    color: #007bff;
    text-align: center;
    margin-bottom: 10px;
}

/* Mengatur total skor */
.total-score {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-top: 20px;
}
</style>';


// Show nama
echo "<h2>Record Progress</h2>";
echo "<p>Nama: " . htmlspecialchars($user['name']) . "</p>";

// Take record progress
$query = $dbh->prepare("SELECT date, score FROM progress WHERE user_id = :user_id");
$query->bindParam(':user_id', $user['id']);
$query->execute();

// Show record
$total_score = 0;
echo "<table>";
echo "<tr><th>Tanggal</th><th>Skor</th></tr>";
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . date("d-m-Y H:i", strtotime($row['date'])) . "</td>";
    echo "<td>" . $row['score'] . "</td>";
    echo "</tr>";
    $total_score += $row['score'];
}
echo "</table>";

// Score
echo "<br>Total score: $total_score";
?>