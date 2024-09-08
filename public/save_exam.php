<?php
// Configura tu conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_dino";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recoger los datos del formulario
$examName = $_POST['examName'];
$questions = json_decode($_POST['questions'], true); // Decodificar el JSON a un array
$classId = $_POST['classId'];

// Insertar el examen en la base de datos
$sql = "INSERT INTO examenes (nombre, clase_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $examName, $classId);

if ($stmt->execute()) {
    $examId = $stmt->insert_id;

    // Insertar las preguntas y respuestas
    foreach ($questions as $question) {
        $questionText = $question['question'];
        $options = $question['options'];

        // Insertar la pregunta
        $sql = "INSERT INTO preguntas (examen_id, pregunta) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $examId, $questionText);
        $stmt->execute();
        $questionId = $stmt->insert_id;

        // Insertar las respuestas
        foreach ($options as $option) {
            $optionText = $option['text'];
            $isCorrect = $option['isCorrect'] ? 1 : 0;

            $sql = "INSERT INTO respuestas (pregunta_id, respuesta, es_correcta) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $questionId, $optionText, $isCorrect);
            $stmt->execute();
        }
    }
    
    echo "Examen guardado con éxito.";
} else {
    echo "Error al guardar el examen: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
