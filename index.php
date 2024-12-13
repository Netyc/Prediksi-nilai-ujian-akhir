<?php
// Class SubjectPredictor
class SubjectPredictor {
    private $predictedScores = [];

    public function predictScore($subject, $averageReportScore) {
        $normalizedSubject = strtolower(trim($subject));

        // Tentukan range nilai berdasarkan rata-rata nilai lapor
        $minScore = $averageReportScore >= 75 ? 75 : 25;
        $maxScore = $averageReportScore >= 75 ? 100 : 74;

        if (isset($this->predictedScores[$normalizedSubject])) {
            return $this->predictedScores[$normalizedSubject];
        } else {
            $score = rand($minScore, $maxScore);
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
$averageReportScore = 0;
$message = '';
$username = '';

$successMessages = [
    "Wow.. si karbit sa ae diatas rata rata ga tuh🫵😂🫵",
    "Hei sepuh, anda ini jawir yah, hebat hebat orang jawir yah, selamatt❤️‍🔥",
    "Lumayan juga lu, beruntung amat hidup lu. selamat ya sayang, muchh🥰",
    "Selamat anda telah selamat dari yang namanya kehidupan ujian, silahkan coba lagi ujian tahun depan yah..",
    "Gila, lu emang beda kelas bro, tangan dewa kali ya? 👏😂",
    "Wih, warna hijau lagi nih, kapan-kapan ajarin tips and trick dong! 😎",
    "Kok bisa sih, orang biasa ga bakal sanggup kek gini!🔥",
    "Beruntung banget lu, dapet jackpot kehidupan nih. Selamat bro! ✨",
    "Kamu tuh kandidat orang jawir beneran deh, fix ga ada lawan! ❤️‍🔥",
    "Ayo-ayo siapa lagi yang bisa sekeren ini? Kamu hebat banget! 👏",
    "Astaga, bakat lu di atas rata-rata! Apakah ini cheat? 🤔😂",
    "Hebatnya kayak pesulap deh, sukses mulus gitu aja! 🎩✨",
    "Lu bikin iri orang sekampung nih, congrats ya bos! 💪😂",
    "Bro, lu ini aktor utamanya sukses, kita cuma cameo. 👀",
    "Hei, udah daftar jadi superhero belum? Kemampuan lu super sih! 🦸‍♂️🔥",
    "Wah hebat banget, jarang banget liat yang bisa kayak gini! 💯",
    "Ternyata keberuntungan ada yang bisa disewa, dan lu nyewa full set! 🤑",
    "Mantap banget bang, warna hijau itu langka kayak unicorn tau! 🦄",
    "Lu lagi di level dewa nih, ajarin caranya dong master! 🙏😂",
    "Wih, apa rahasianya bisa ngehajar probabilitas 0.001%? 😲",
    "Cie yang berhasil, boleh dong traktiran dikit. 🎉😂",
    "Luar biasa sih, orang kayak lu tuh bikin hidup lebih seru! 🥳",
    "Lu bikin kita yang lain iri, selamat ya jawara! 💪",
    "Waduh, lu kayak cheat engine hidup ya, semudah itu bro! 😂",
    "Congrats, hidup lu ini bener-bener kayak mimpi deh! ✨",
    "Pas banget, ini momen ‘kok bisa sih?’, kagum banget deh! 😅",
    "Selamat bang, ini pasti ada campur tangan semesta buat bantu lu! 🌌",
    "Hoki lu kayaknya gak ada habisnya, sukses terus ya bos! 💸",
    "Sumpah, gua gak percaya kalo ini cuma kebetulan. Lu keren banget! 👏",
    "Lu bikin probabilitas gak masuk akal jadi kenyataan. Nice! 🤯",
    "Lu ini kayak alien di tengah manusia, luar biasa banget! 👽",
    "Lu ini kayak pemenang lotre kehidupan, hoki lu parah! 🤑",
    "Sumpah, ini epic banget sih, ga ada kata lain. Respect! 👏",
    "Lu bikin yang mustahil jadi mungkin. Ini sih level legenda! 🔥",
    "Kayaknya lu harus jadi pembicara motivasi deh, inspirasi banget! 🎤",
    "Lu ini bukti nyata kalo keberuntungan itu nyata. GG! 💯",
    "Selamat ya, ini momen yang bikin kita semua jadi kagum! 🎉"
];

$errorMessages = [
    "Bangke, nyesel gw hitung rata rata lu, rendah amat nilai lu dek dek",
    "Tuhkan rendah nilai lu, mampusss awowkwok, makanya disuruh belajar ya belajar, scroll teros tuh sosmed sampai jadi bodoh lu kan",
    "Semangat putus asa yah, tetap lah menyerah dan jangan hidup lagi yah🥰❤️‍🔥",
    "Kamu jujur dalam ujian, gapapa kok, semua itu hanya angka, yang penting bagaimana sikap kamu dalam mengerjakanya dari dalam ❤️‍🔥",
    "Hadeh udah beban keluarga, belajar enggan, bodoh enggan. tapi kerjaannya cuma ngeluh doang. belajar dari kesalahan lu kocak😌.",
    "Dek, kalau malas belajar, nanti suksesnya cuma jadi wacana. Bangun dong, jangan rebahan terus!",
    "Wow, nilai lu ya... kayak suhu di Kutub Utara, dingin banget.",
    "Tenang aja, gagal itu biasa kok... tapi kalau terus-terusan ya aneh juga sih.",
    "Belajar itu bukan beban, dek, yang berat itu ekspektasi keluarga kamu!",
    "Nilai rendah itu bukan akhir dunia, tapi ya jangan dijadiin kebiasaan juga, bro.",
    "Kamu belajar dari kesalahan? Bagus... Tapi kenapa kesalahannya masih sama terus?",
    "Santai aja, dek, nilai itu cuma angka... Tapi kalau 0 terus ya tetap bikin malu.",
    "Gak apa-apa kalau salah, yang penting jangan terus-terusan jadi konten komedi hidup.",
    "Dek, kamu hebat banget! Hebat bikin orang mikir dua kali buat bantuin lu belajar.",
    "Jangan takut salah, takutlah kalau terus jadi bahan lelucon teman-teman.",
    "Hasil kamu itu membuktikan, kalau rebahan lebih sering daripada belajar, hasilnya ya gini.",
    "Semangat ya, dek! Meskipun kayaknya bakat kamu bukan di akademis, tapi di komedi situasi hidup.",
    "Gagal sekali, itu biasa. Gagal terus-terusan, itu perlu dipertanyakan sih usahanya.",
    "Kamu tahu? Einstein pernah gagal. Tapi dia belajar... Nah, kamu kapan?",
    "Nilai lu kayak sinyal HP di hutan, hilang timbul gak jelas.",
    "Dek, kalau malas terus, sukses hanya akan jadi nama Wifi tetangga.",
    "Tenang, gak perlu merasa beban keluarga... yang penting tetap menghibur mereka dengan nilai kamu.",
    "Lu bisa kok jadi motivator... motivator buat orang lain biar gak kayak lu.",
    "Nyesel gak belajar dari awal? Atau masih santai-santai aja?",
    "Jangan khawatir soal nilai rendah, khawatirkan kalau guru sampai kasih bonus nilai kasihan.",
    "Dek, nilai kamu bagus kok... kalau standarnya dari dasar palung Mariana.",
    "Bangun! Ini bukan drama, nilai kamu nyata dan itu... menyedihkan.",
    "Dek, hidup itu pilihan. Dan kamu selalu memilih yang salah, ya?",
    "Teruslah mencoba, dek! Karena kalau menyerah, ya... malu banget sih.",
    "Kamu harus yakin. Yakin bahwa belajar itu penting, bukan cuma rebahan.",
    "Dek, kalau kamu terus begini, masa depan bakal kasih surat pengunduran diri.",
    "Gak apa-apa kok gagal, asalkan bukan jadi pekerjaan tetap.",
    "Kalau nilai lu manusia, dia pasti udah capek hidup.",
    "Belajar itu memang berat, tapi masa depan yang suram lebih berat lagi.",
    "Dek, kalau lu mau sukses, ya minimal bangun dulu dari kasur.",
    "Semangat terus ya... walau hasilnya kayaknya gak semangat.",
    "Hidup itu kayak ujian, kalau malas, hasilnya nihil.",
    "Gagal itu cuma sementara... kecuali kalau kamu memang gak mau berubah.",
    "Nilai lu tuh inspirasi banget, dek. Inspirasi buat guru pensiun dini.",
    "Ingat, belajar itu investasi. Kalau gak belajar, ya bangkrut masa depan lu.",
    "Tenang, nilai kecil itu cuma angka... Tapi malu sih tetap aja.",
    "Mungkin kamu gak bodoh, dek... mungkin cuma belum nemu cara ngakalinnya.",
    "Setidaknya kalau gak pinter, usahain jadi rajin. Jangan dua-duanya gak ada.",
    "Jangan menyerah, kecuali kalau emang udah niat bikin orang tua kecewa.",
    "Nilai itu relatif, tapi usaha itu mutlak. Mana usaha lu?",
    "Kalau nilai kamu itu meme, pasti viral banget karena bikin ngakak.",
    "Dek, kalau rebahan itu menghasilkan nilai, kamu pasti juara kelas.",
    "Hasil gak akan mengkhianati usaha. Nah, hasil kamu jelas nunjukin usaha kamu mana?",
    "Tenang aja, nilai kecil itu bukan akhir dunia... tapi bisa jadi awalnya.",
    "Lu tau gak? Kegagalan adalah guru terbaik. Tapi kok kayaknya kamu gak lulus juga ya?",
    "Semangat putus asa boleh, tapi jangan lupa coba bangkit dulu.",
    "Nilai lu tuh membuktikan bahwa malas adalah jalan ninja yang salah.",
    "Hidup itu penuh kejutan, tapi nilai lu tuh gak ada yang mengejutkan sih... konsisten rendah.",
    "Dek, masa depan yang cerah nunggu kamu... di sisi lain layar HP kamu.",
    "Dek, kalau malas belajar, nanti suksesnya cuma jadi wacana. Bangun dong, jangan rebahan terus!",
    "Wow, nilai lu ya... kayak suhu di Kutub Utara, dingin banget.",
    "Tenang aja, gagal itu biasa kok... tapi kalau terus-terusan ya aneh juga sih.",
    "Kamu harus yakin. Yakin bahwa belajar itu penting, bukan cuma rebahan.",
    "Dek, kalau kamu terus begini, masa depan bakal kasih surat pengunduran diri.",
    "Gak apa-apa kok gagal, asalkan bukan jadi pekerjaan tetap.",
    "Kalau nilai lu manusia, dia pasti udah capek hidup.",
    "Belajar itu memang berat, tapi masa depan yang suram lebih berat lagi.",
    "Dek, kalau lu mau sukses, ya minimal bangun dulu dari kasur.",
    "Semangat terus ya... walau hasilnya kayaknya gak semangat.",
    "Hidup itu kayak ujian, kalau malas, hasilnya nihil.",
    "Gagal itu cuma sementara... kecuali kalau kamu memang gak mau berubah.",
    "Nilai lu tuh inspirasi banget, dek. Inspirasi buat guru pensiun dini.",
    "Ingat, belajar itu investasi. Kalau gak belajar, ya bangkrut masa depan lu.",
    "Tenang, nilai kecil itu cuma angka... Tapi malu sih tetap aja.",
    "Mungkin kamu gak bodoh, dek... mungkin cuma belum nemu cara ngakalinnya.",
    "Setidaknya kalau gak pinter, usahain jadi rajin. Jangan dua-duanya gak ada.",
    "Jangan menyerah, kecuali kalau emang udah niat bikin orang tua kecewa.",
    "Nilai itu relatif, tapi usaha itu mutlak. Mana usaha lu?",
    "Kalau nilai kamu itu meme, pasti viral banget karena bikin ngakak.",
    "Dek, kalau rebahan itu menghasilkan nilai, kamu pasti juara kelas.",
    "Hasil gak akan mengkhianati usaha. Nah, hasil kamu jelas nunjukin usaha kamu mana?",
    "Tenang aja, nilai kecil itu bukan akhir dunia... tapi bisa jadi awalnya.",
    "Lu tau gak? Kegagalan adalah guru terbaik. Tapi kok kayaknya kamu gak lulus juga ya?",
    "Semangat putus asa boleh, tapi jangan lupa coba bangkit dulu.",
    "Nilai lu tuh membuktikan bahwa malas adalah jalan ninja yang salah.",
    "Hidup itu penuh kejutan, tapi nilai lu tuh gak ada yang mengejutkan sih... konsisten rendah.",
    "Dek, masa depan yang cerah nunggu kamu... di sisi lain layar HP kamu."
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjects = $_POST['subjects'] ?? [];
    $username = htmlspecialchars($_POST['username'] ?? 'Pengguna');
         $averageReportScore = (float) $_POST['average_report_score'];
         foreach ($subjects as $subject) {
        $predictedScores[$subject] = $predictor->predictScore($subject, $averageReportScore);
    }

    // Jika jumlah pelajaran kurang dari 5
    if (count($subjects) < 5) {
        $message = "Harap masukkan minimal 5 mata pelajaran untuk melihat hasil!";
    } else {
        // Hitung rata-rata jika ada 5 atau lebih pelajaran
        $averageScore = $predictor->calculateAverageScore();

        // Pastikan pesan hasil hanya dihasilkan sekali untuk sesi saat ini
        if (!isset($_SESSION['resultMessage'])) {
            if ($averageScore >= 75) {
                $_SESSION['resultMessage'] = $successMessages[array_rand($successMessages)];
            } else {
                $_SESSION['resultMessage'] = $errorMessages[array_rand($errorMessages)];
            }
        }
        $message = $_SESSION['resultMessage'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Nilai Ujian Akhir</title>
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
            font-size: 30px;
            color: #212240;
            text-align: center;
        }

        h5 {
            margin-top: 30px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h6 {
            font-size: 25px;
            color: #555;
            text-align: left;
            margin-lower: 35px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            font-size: 16px;
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
            font-size: 25px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="number"] {
            margin: 2px;
            width: 100%;
            padding: 20px 20px;
            font-size: 25px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            margin: 7px 5px;
            padding: 20px;
            font-size: 25px;
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
            font-size: 14px;
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
            margin-top: 60px;
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
            font-size: 25px;
        }
        input[type="number"] {
            font-size: 25px;
        }
 
        button {
             font-size: 25px;
             padding: 10px 8px;
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
            h5 {
               font-size: 14px;
            }
            h6 {
               font-size: 14px;
            }

            input[type="text"] {
                font-size: 14px;
                padding: 8px 4px;
            }
            input[type="number"] {
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
        <h6>Hasil prediksi adalah nilai asli dari soal yang kamu jawab saat ujian, tidak ada penambahan nilai dari tugas harian.</h6>
        <div id="subjects-container">
            <label for="username">Nama kamu</label>
            <input type="text" name="username" placeholder="Masukkan nama kamu" required value="<?= htmlspecialchars($username) ?>">
            
            
            <label for="average_report_score">Rata-rata Nilai Lapor</label>
            <input type="number" name="average_report_score" step="0.01" placeholder="Masukkan rata-rata nilai lapor" required>
            
            <label for="subjects[]">Mata pelajaran</label>
            <input type="text" name="subjects[]" placeholder="Masukkan mata pelajaran" required>
        </div>
        <div class="buttons">
            <button type="button" onclick="addSubjectField()"> ➕ </button>
            <button type="submit">Prediksi Hasil</button>
        </div>
    </form>

    <?php if (!empty($predictedScores) && count($subjects) >= 5): ?>
        <div class="results">
            <h5>Selamat yah,...<strong><?= $username ?></strong>! 🥳<br>Ini adalah hasil dari kerja keras kamu selama masa menjalani ujian dengan jujur.</h5>
            <ul>
                <?php foreach ($predictedScores as $subject => $score): ?>
                    <li><strong><?= htmlspecialchars($subject) ?>:</strong> <?= $score ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Rata-Rata :</strong> <?= $averageScore ?></p>
            <div class="message <?= $averageScore >= 75 ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="message error">
            <?= $message ?>
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
            input.placeholder = 'Masukkan mata pelajaran';
            input.required = true;
            container.appendChild(input);
        }
    </script>
</body>
</html>
