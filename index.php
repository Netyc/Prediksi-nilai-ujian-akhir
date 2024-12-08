<?php
// Class SubjectPredictor
class SubjectPredictor {
    private $predictedScores = [];

    public function predictScore($subject) {
        $normalizedSubject = strtolower(trim($subject));
        if (isset($this->predictedScores[$normalizedSubject])) {
            return $this->predictedScores[$normalizedSubject];
        } else {
            $score = rand(0, 100);
            $this->predictedScores[$normalizedSubject] = $score;
            return $score;
        }
    }

    public function getScores() {
        return $this->predictedScores;
    }

    public function calculateAverageScore() {
        if (empty($this->predictedScores)) {
            return 0;
        }
        return array_sum($this->predictedScores) / count($this->predictedScores);
    }
}

// Static variable to preserve scores between requests
session_start();
if (!isset($_SESSION['predictor'])) {
    $_SESSION['predictor'] = new SubjectPredictor();
}
$predictor = $_SESSION['predictor'];

// Handle form submission
$subjects = [];
$predictedScores = [];
$averageScore = 0;
$successMessages = [
    "Selamat! Nilai rata-rata Anda luar biasa.",
    "Kerja keras Anda terbayar! Rata-rata nilai Anda sangat baik.",
    "Hebat! Anda menunjukkan hasil yang mengagumkan.",
    "Luar biasa! Pertahankan kinerja Anda.",
    "Anda telah mencapai hasil yang sangat baik, teruskan!"
];

$errorMessages = [
    "Nilai rata-rata Anda kurang memuaskan. Jangan menyerah!",
    "Masih banyak ruang untuk meningkatkan usaha Anda.",
    "Jangan berkecil hati, tetap semangat belajar!",
    "Hasil ini adalah peluang untuk belajar lebih giat lagi.",
    "Tingkatkan usaha Anda untuk hasil yang lebih baik di masa depan."
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjects = $_POST['subjects'] ?? [];
    foreach ($subjects as $subject) {
        $predictedScores[$subject] = $predictor->predictScore($subject);
    }
    $averageScore = $predictor->calculateAverageScore();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Prediksi Nilai</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #eef2f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        #subjects-container {
            flex: 1 1 100%;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        button {
            padding: 10px 15px;
            font-size: 14px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .results {
            margin-top: 20px;
        }
        .results ul {
            list-style: none;
            padding: 0;
        }
        .results li {
            background: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            font-size: 12px;
            color: #555;
        }
        footer .watermark {
            font-style: italic;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            input[type="text"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Prediksi Nilai</h1>
        <form method="POST">
            <div id="subjects-container">
                <input type="text" name="subjects[]" placeholder="Masukkan mata pelajaran" required>
            </div>
            <div class="buttons">
                <button type="button" onclick="addSubjectField()">Tambah Mata Pelajaran</button>
                <button type="submit">Prediksi Nilai</button>
            </div>
        </form>

        <?php if (!empty($predictedScores)): ?>
            <div class="results">
                <h2>Hasil Prediksi:</h2>
                <ul>
                    <?php foreach ($predictedScores as $subject => $score): ?>
                        <li><strong><?= htmlspecialchars($subject) ?>:</strong> <?= $score ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Rata-rata Nilai:</strong> <?= $averageScore ?></p>
                <?php if ($averageScore >= 75): ?>
                    <div class="message success">
                        <?= $successMessages[array_rand($successMessages)] ?>
                    </div>
                <?php else: ?>
                    <div class="message error">
                        <?= $errorMessages[array_rand($errorMessages)] ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>Hubungi Pembuat: <a href="mailto:naufalshdq@gmail.com">naufalshdq@gmail.com</a></p>
        <p class="watermark">&copy; 2024 Naufalshdq</p>
    </footer>

    <script>
        function addSubjectField() {
            const container = document.getElementById('subjects-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'subjects[]';
            input.placeholder = 'Masukkan mata pelajaran';
            input.required = true;
            container.appendChild(input);
        }
    </script>
</body>
</html>
