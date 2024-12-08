<?php
require_once 'vendor/autoload.php'; // Pastikan Anda sudah menginstal library dompdf
use Dompdf\Dompdf;

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subjects'])) {
    $subjects = $_POST['subjects'];
    foreach ($subjects as $subject) {
        $predictedScores[$subject] = $predictor->predictScore($subject);
    }
    $averageScore = $predictor->calculateAverageScore();

    if (isset($_POST['download'])) {
        $html = "<h1>Laporan Prediksi Nilai</h1><table border='1' style='width:100%; border-collapse:collapse; text-align:left;'><tr><th>Mata Pelajaran</th><th>Nilai Prediksi</th></tr>";
        foreach ($predictedScores as $subject => $score) {
            $html .= "<tr><td>" . htmlspecialchars($subject) . "</td><td>" . $score . "</td></tr>";
        }
        $html .= "</table><p><strong>Rata-rata Nilai:</strong> " . $averageScore . "</p>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Laporan_Nilai.pdf");
        exit;
    }
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
            font-family: 'Times New Roman', Times, serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid #333;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .results {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
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
            <button type="button" onclick="addSubjectField()">Tambah Mata Pelajaran</button>
            <button type="submit">Prediksi Nilai</button>
            <button type="submit" name="download">Download PDF</button>
        </form>

<?php if (!empty($predictedScores)): ?>
        <div class="results">
            <h2>Hasil Prediksi:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Nilai Prediksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($predictedScores as $subject => $score): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject) ?></td>
                            <td><?= $score ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><strong>Rata-rata Nilai:</strong> <?= $averageScore ?></p>
        </div>
<?php endif; ?>
    </div>

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
