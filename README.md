# Subject Predictor Web Application

## Description
The Subject Predictor is a web application designed to predict exam scores for various subjects. Users can input subjects, and the app generates random predicted scores. It also calculates the average score and displays a motivational message based on the results. This project is built with PHP for backend logic and HTML, CSS, and JavaScript for the frontend.

---

## Features
- Predicts scores for input subjects with random values between 0 and 100.
- Calculates the average score of all predicted subjects.
- Displays motivational or constructive feedback messages based on the average score.
- Allows users to dynamically add multiple subjects to predict scores for.
- Fully responsive design for mobile and desktop devices.

---

## Requirements
- PHP 7.4 or higher
- Web server (e.g., Apache, Nginx, or PHP built-in server)
- Browser with JavaScript enabled

---

## Installation
1. Clone the repository or download the source code:
    ```bash
    git clone https://github.com/yourusername/subject-predictor.git
    ```

2. Place the files in your web server's root directory.

3. Start your PHP server:
    ```bash
    php -S localhost:8000
    ```

4. Open your browser and navigate to:
    ```
    http://localhost:8000
    ```

---

## Usage
1. Enter a subject name in the input field.
2. Click **Tambahkan Mapel Lainnya** to add more subjects if needed.
3. Click **Prediksi Hasil** to generate scores.
4. View the predicted scores and average score in the results section.
5. Read the motivational message based on the average score.

---

## File Structure
- `index.php`: Main PHP script containing backend logic and frontend rendering.
- `styles`: Embedded CSS within the HTML for styling the web application.
- `scripts`: JavaScript for dynamic addition of subject input fields.

---

## Key Code Highlights
- **Random Score Prediction:**
    ```php
    $score = rand(0, 100);
    ```
    Generates a random score for each subject input.

- **Average Score Calculation:**
    ```php
    return array_sum($this->predictedScores) / count($this->predictedScores);
    ```
    Computes the average of all predicted scores.

- **Responsive Design:**
    CSS is styled with media queries to ensure responsiveness for desktop and mobile devices.

---

## Customization
- **Feedback Messages:**
  You can edit the success and error messages in the `$successMessages` and `$errorMessages` arrays within the PHP code.

- **Styling:**
  Modify the embedded CSS in the `<style>` section to change the appearance of the application.

---

## Contributing
1. Fork the repository.
2. Create a new branch:
    ```bash
    git checkout -b feature-branch
    ```
3. Commit your changes:
    ```bash
    git commit -m "Add new feature"
    ```
4. Push to the branch:
    ```bash
    git push origin feature-branch
    ```
5. Open a pull request.

---

## License
This project is licensed under the MIT License. See the LICENSE file for details.

---

## Acknowledgments
- Built by unfnitystudiosimagines team
- Special thanks to contributors and testers
- Support us at: [Saweria](https://saweria.co/mhammadnaufal)

---

## Contact
For questions, suggestions, or contributions, please reach out to:
- Email: naufalmushaddiq@gmail.com
- Website: [unfnitystudiosimagines.com](https://unfnitystudiosimagines.com)

