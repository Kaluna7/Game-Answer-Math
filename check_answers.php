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
$questions = json_decode($_POST['questions']);
$correct_answers = json_decode($_POST['correct_answers']);

// skor
$score = 0;
$user_answers = [];
$statuses = [];

for ($i = 0; $i < 5; $i++) {
    $user_answer = isset($_POST['answer' . $i]) ? floatval($_POST['answer' . $i]) : 0;
    $user_answers[] = $user_answer;

    // correction
    if (round($user_answer, 2) === round($correct_answers[$i], 2)) {
        $score++;
        $statuses[] = "Benar";
    } else {
        $statuses[] = "Salah";
    }
}

// Save ke database
$query = $dbh->prepare("INSERT INTO progress (user_id, date, score, questions, answers, status) VALUES (
    (SELECT id FROM users WHERE username = :username),
    NOW(),
    :score,
    :questions,
    :answers,
    :status
)");
$query->bindParam(':username', $username);
$query->bindParam(':score', $score);

// Membuat new variabel
$questions_json = json_encode($questions);
$query->bindParam(':questions', $questions_json, PDO::PARAM_STR);

$user_answers_json = json_encode($user_answers);
$query->bindParam(':answers', $user_answers_json, PDO::PARAM_STR);
$statuses_json = json_encode($statuses);
$query->bindParam(':status', $statuses_json, PDO::PARAM_STR);

$query->execute();

// Show hasil
echo "Skor Anda: $score/5<br><br>";
echo "Detail Jawaban:<br>";
for ($i = 0; $i < 5; $i++) {
    echo "Soal: $questions[$i]<br>";
    echo "Jawaban Anda: $user_answers[$i] - $statuses[$i]<br>";
    echo "Jawaban Benar: $correct_answers[$i]<br><br>";
}

$dbh = null;
?>