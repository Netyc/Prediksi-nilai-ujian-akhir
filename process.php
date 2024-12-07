<?php
session_start();

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
}

// Initialize predictor in session
if (!isset($_SESSION['predictor'])) {
    $_SESSION['predictor'] = new SubjectPredictor();
}
$predictor = $_SESSION['predictor'];

// Handle form submission
$subjects = $_POST['subjects'] ?? [];
$predictedScores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($subjects as $subject) {
        $predictedScores[$subject] = $predictor->predictScore($subject);
    }
    header('Location: index.php?scores=' . urlencode(json_encode($predictedScores)));
    exit;
}
?>
