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
$username = '';
$message = '';

$successMessages = [
    "Wow.. si karbit sa ae diatas rata rata ga tuh🫵😂🫵",
    "Waduh bang, hebat banget luh cik, untuk dapat warna hijau ni sulit loh bang, probalitas 0,001%💀",
    "Hei sepuh, anda ini jawir yah, hebat hebat orang jawir yah, selamatt❤️‍🔥",
    "Lumayan juga lu, beruntung amat hidup lu. selamat ya sayang, muchh🥰",
    "Selamat anda telah selamat dari yang namanya kehidupan ujian, silahkan coba lagi ujian tahun depan yah.."
];

$errorMessages = [
    "Bangke, nyesel gw hitung rata rata lu, rendah amat nilai lu dek dek",
    "Tuhkan rendah nilai lu, mampusss awowkwok, makanya disuruh belajar ya belajar, scroll teros tuh sosmed sampai jadi bodoh lu kan",
    "Semangat putus asa yah, tetap lah menyerah dan jangan hidup lagi yah🥰❤️‍🔥",
    "Kamu jujur dalam ujian, gapapa kok, semua itu hanya angka, yang penting bagaimana sikap kamu dalam mengerjakanya dari dalam ❤️‍🔥",
    "Hadeh udah beban keluarga, belajar enggan, bodoh enggan. tapi kerjaannya cuma ngeluh doang. belajar dari kesalahan lu kocak😌."
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjects = $_POST['subjects'] ?? [];
    $username = htmlspecialchars($_POST['username'] ?? 'Pengguna');
    foreach ($subjects as $subject) {
        $predictedScores[$subject] = $predictor->predictScore($subject);
    }
    $averageScore = $predictor->calculateAverageScore();

    // Generate message only if not already set
    if (!isset($_SESSION['resultMessage'])) {
        if ($averageScore >= 75) {
            $_SESSION['resultMessage'] = $successMessages[array_rand($successMessages)];
        } else {
            $_SESSION['resultMessage'] = $errorMessages[array_rand($errorMessages)];
        }
    }
    $message = $_SESSION['resultMessage'];
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
            background-color: #F5F2FC;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
       
        }
        
        h2 {
            font-size: 29px;
            color: #212240;
            text-align: center;
        }

        h5 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h6 {
            font-size: 16px;
            color: #555;
            text-align: left;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
          }

        form {
            display: inline-block;
            flex-direction: column-reverse;
            gap: 10px;
        }

        input[type="text"] {
            margin: 2px;
            width: 100%;
            padding: 20px 20px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            margin: 9px 7px;
            padding: 20px;
            font-size: 18px;
            color: white;
            background-color: #212240;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #333;
        }

        .results {
            margin-top: 20px;
        }
        .results h5 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
         }

        .results ul {
            list-style: none;
            padding: 0;
        }

        .results li {
            background: #f9f9f9;
            margin: 5px 0;
            font-size: 16px;
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
            margin-top: 30px;
            padding: 10px;
            font-size: 12px;
            color: #555;
        }

        footer .watermark {
            font-style: italic;
        }
        
        @media (min-width: 1024px) {
        .container {
            padding: 40px;
        }
  
        h2 {
            font-size: 30px;
        }

        input[type="text"] {
            font-size: 18px;
        }
 
        button {
             font-size: 18px;
             padding: 12px 20px;
        }
    }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 2px 6px;
            }

            h2 {
                font-size: 25px;
            }
            h6 {
               font-size: 14px;
            }

            input[type="text"] {
                font-size: 14px;
                padding: 8px 4px;
            }

            button {
                padding: 8px 50px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 16px;
            }

            button {
                font-size: 12px;
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Prediksi Nilai Ujian Akhir</h2>
        <form method="POST">
             <h6>Hasil prediksi adalah nilai asli dari soal yang kamu jawab saat ujian, tidak ada penambahan nilai dari tugas harian ataupun PR (tugas rumah).<br>𝘒𝘓𝘐𝘒 + 𝘜𝘕𝘛𝘜𝘒 𝘔𝘌𝘕𝘈𝘔𝘉𝘈𝘏𝘒𝘈𝘕 𝘔𝘈𝘛𝘈 𝘗𝘌𝘓𝘈𝘑𝘈𝘙𝘈𝘕 𝘓𝘈𝘐𝘕𝘕𝘠𝘈.</br></h6>
            <div id="subjects-container">
                 <label for="username">Masukkan nama kamu</label>
                <input type="text" name="username" placeholder="Masukkan nama kamu" required value="<?= htmlspecialchars($username) ?>">
                
                <label for="subjects[]">Masukkan mata pelajaran</label>
                <input type="text" name="subjects[]" placeholder="Masukkan mata pelajaran" required>
            </div>
            <div class="buttons">
                <button type="button" onclick="addSubjectField()"> ➕ </button>
                <button type="submit">Prediksi Hasil</button>
            </div>
        </form>

        <?php if (!empty($predictedScores)): ?>
            <div class="results">
                <h5>Selamat yah, <strong><?= $username ?></strong>! 🥳<br>Ini adalah hasil dari kerja keras kamu selama masa menjalani ujian dengan jujur.</h5>
                <ul>
                    <?php foreach ($predictedScores as $subject => $score): ?>
                        <li><strong><?= htmlspecialchars($subject) ?>:</strong> <?= $score ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Rata-Rata Nilai Anda:</strong> <?= $averageScore ?></p>
                <div class="message <?= $averageScore >= 75 ? 'success' : 'error' ?>">
                    <?= $message ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>Dukung Kami: <a href="https://saweria.co/mhammadnaufal">Belikan kopi dong hehe ☕</a></p>
        <p class="watermark">&copy; 2024 kode by @naufalshdq</p>
    </footer>

    <script>
        function addSubjectField() {
            const container = document.getElementById('subjects-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'subjects[]';
            input.placeholder = 'Masukan nama kamu';
            input.placeholder = 'Masukkan mata pelajaran';
            input.required = true;
            container.appendChild(input);
        }
    </script>
</body>
</html>
