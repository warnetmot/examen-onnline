<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cuestionario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $sql_check = "SELECT * FROM participants WHERE email = '$email'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "El correo ya está registrado. Por favor, use otro correo.";
    } else {
        $sql = "INSERT INTO participants (first_name, last_name, email) VALUES ('$first_name', '$last_name', '$email')";

        if ($conn->query($sql) === TRUE) {
            header("Location: preguntas.php");
            exit(); 
        } else {
            echo "Error al registrar: " . $conn->error;
        }
    }
    
    $conn->close();
}
?>
