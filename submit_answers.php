<?php
$servername = "localhost";
$username = "rey";
$password = "12782305";
$database = "cuestionario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$participant_id = $_POST['participant_id'] ?? 1; 

$total_score = 0;

//bucle para martener la actualisacion de los resultados en simuktaneo de todas las personas que an dado \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
foreach ($_POST as $question_name => $selected_answer_id) {
    if (strpos($question_name, 'question_') === 0) {
        $question_id = str_replace('question_', '', $question_name);

        $sql = "SELECT is_correct, max_score FROM answers 
                JOIN questions ON answers.question_id = questions.id 
                WHERE answers.id = $selected_answer_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['is_correct']) {
                $total_score += $row['max_score'];  
            }
        }
    }
}

$sql_insert = "INSERT INTO results (participant_id, total_score) VALUES ('$participant_id', '$total_score')";
if ($conn->query($sql_insert) === TRUE) {
    $message = "Puntuación total obtenida: " . $total_score;
} else {
    $message = "Error al guardar el puntaje: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Cuestionario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .result {
            font-size: 18px;
            color: #333;
            margin: 20px 0;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resultado del Cuestionario</h1>
        <p class="result"><?php echo htmlspecialchars($message); ?></p>
        <a href="indx.php" class="back-button">Volver al Inicio</a>
    </div>
</body>
</html>
