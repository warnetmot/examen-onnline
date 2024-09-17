<?php
$servername = "localhost";
$username = "rey";
$password = "12782305";
$database = "cuestionario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM questions";
$result = $conn->query($sql);

// Asegúrate de tener el valor de $participant_id aquí
$participant_id = 1; // Ejemplo estático, reemplazar con tu lógica de sesión o base de datos.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario en Línea</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .question {
            margin-bottom: 20px;
        }
        .question h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .question div {
            margin-bottom: 5px;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .submit-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Examen en Línea</h1>
        <form method="POST" action="submit_answers.php">
            <input type="hidden" name="participant_id" value="<?php echo htmlspecialchars($participant_id); ?>">

            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="question">
                        <h3><?php echo htmlspecialchars($row['question_text']); ?> (Puntuación: <?php echo htmlspecialchars($row['max_score']); ?>)</h3>

                        <?php
                        $question_id = $row['id'];
                        $sql_answers = "SELECT * FROM answers WHERE question_id = $question_id";
                        $result_answers = $conn->query($sql_answers);
                        ?>

                        <?php if ($result_answers->num_rows > 0): ?>
                            <?php while ($answer = $result_answers->fetch_assoc()): ?>
                                <div>
                                    <input type="radio" name="question_<?php echo htmlspecialchars($question_id); ?>" value="<?php echo htmlspecialchars($answer['id']); ?>" required>
                                    <?php echo htmlspecialchars($answer['answer_text']); ?>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No hay respuestas disponibles para esta pregunta.</p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay preguntas disponibles.</p>
            <?php endif; ?>
            
            <input type="submit" value="Enviar respuestas" class="submit-button">
        </form>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
