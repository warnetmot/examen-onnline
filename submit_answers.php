<?php
$servername = "localhost";
$username = "rey";
$password = "12782305";
$database = "cuestionario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$participant_id = $_POST['participant_id']; 

if (!isset($participant_id) || empty($participant_id)) {
    die("Error: ID de participante no válido.");
}

$total_score = 0;

foreach ($_POST as $question_name => $selected_answer_id) {
    if (strpos($question_name, 'question_') === 0) {
        $question_id = str_replace('question_', '', $question_name);

        // Consulta preparada para proteger contra inyecciones SQL
        $stmt = $conn->prepare("SELECT is_correct, max_score FROM answers 
                                JOIN questions ON answers.question_id = questions.id 
                                WHERE answers.id = ?");
        $stmt->bind_param("i", $selected_answer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['is_correct']) {
                $total_score += $row['max_score'];
            }
        }
        $stmt->close();
    }
}

// Inserción del resultado utilizando consulta preparada
$stmt_insert = $conn->prepare("INSERT INTO results (participant_id, total_score) VALUES (?, ?)");
$stmt_insert->bind_param("ii", $participant_id, $total_score);

if ($stmt_insert->execute()) {
    $message = "Puntuación total obtenida: " . $total_score;
} else {
    $message = "Error al guardar el puntaje: " . $stmt_insert->error;
}

$stmt_insert->close();
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
