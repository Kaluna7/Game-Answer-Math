<?php
// Koneksi
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

// Cek pengguna
$query = $dbh->prepare("SELECT * FROM users WHERE username = :username");
$query->bindParam(':username', $username);
$query->execute();

// Get data pengguna
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    die("Username atau password salah.");
}

// Show halo
echo "<h2 style='text-align: center; color: #007bff;'>HALO: " . htmlspecialchars($user['name']) . "</h2>";
echo "<h2 style='text-align: center; color: #007bff;'>Jawab Pertanyaan Berikut Ini.</h2>";
// CSS

echo '<style>
/* Mengatur gaya soal */
p {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    margin: 20px 0;
}

/* Gaya form untuk input jawaban */
form {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
    border: 2px solid #007bff;
    border-radius: 15px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

form label {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
}

form input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Tombol jawab */
form input[type="submit"] {
    display: block;
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
}

form input[type="submit"]:hover {
    background-color: #0056b3; /* Biru lebih gelap saat hover */
}

/* Gaya tambahan */
form input[type="number"]:focus,
form input[type="submit"]:focus {
    outline: none;
    border-color: #007bff;
}

form input[type="number"]:hover,
form input[type="submit"]:hover {
    border-color: #007bff;
}
</style>';

echo '<form method="POST" action="check_answers.php">';

// Soal
$operators = ['+', '-', '*', '/', '%'];
$questions = [];
$correct_answers = [];

for ($i = 0; $i < 5; $i++) {
    $num1 = rand(1, 100);
    $num2 = rand(1, 100);
    $operator = $operators[rand(0, 4)];

    $question = "$num1 $operator $num2";
    $questions[] = $question;

    // Hitung jawaban yg benar
    switch ($operator) {
        case '+':
            $correct_answer = $num1 + $num2;
            break;
        case '-':
            $correct_answer = $num1 - $num2;
            break;
        case '*':
            $correct_answer = $num1 * $num2;
            break;
        case '/':
            $correct_answer = round($num1 / $num2, 2);
            break;
        case '%':
            $correct_answer = $num1 % $num2;
            break;
    }
    $correct_answers[] = $correct_answer;

    // Show soal
    echo "<p>Soal " . ($i + 1) . ": $question</p>";
}

// Menambahkan input untuk soal
for ($i = 0; $i < 5; $i++) {
    echo '<label for="answer' . $i . '">Jawaban ' . ($i + 1) . ':</label>';
    echo '<input type="number" id="answer' . $i . '" name="answer' . $i . '" required><br>';
}

// Menambahkan data tersembunyi
echo '<input type="hidden" name="questions" value="' . htmlspecialchars(json_encode($questions)) . '">';
echo '<input type="hidden" name="correct_answers" value="' . htmlspecialchars(json_encode($correct_answers)) . '">';
echo '<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">';

// Button jawab
echo '<input type="submit" value="Jawab">';
echo '</form>';

$dbh = null;
?>