<?php
$servername = "localhost";
$username = "rey";
$password = "12782305";
$database = "cuestionario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = $_POST['question_text']; // Tipo de dato: cadena de texto
    $max_score = (int) $_POST['max_score']; // Tipo de dato: entero
    $answers = $_POST['answers'];    // Tipo de dato: array de cadenas
    $correct_answer = (int) $_POST['correct_answer']; // Tipo de dato: entero

    $sql_question = "INSERT INTO questions (question_text, max_score) VALUES ('$question_text', $max_score)";

    if ($conn->query($sql_question) === TRUE) {
        $question_id = $conn->insert_id;

        foreach ($answers as $index => $answer_text) {
            $is_correct = ($index == $correct_answer) ? 1 : 0;
            $sql_answer = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES ('$question_id', '$answer_text', $is_correct)";
            if (!$conn->query($sql_answer)) {
                echo "Error al insertar la respuesta: " . $conn->error;
                $conn->close();
                exit; // Termina el script si ocurre un error al insertar respuestas
            }
        }

        header('Location: indx.php');
        exit; 
    } else {
        echo "Error: " . $sql_question . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
