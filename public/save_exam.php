<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_dino";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$examName = $_POST['examName'];
$questions = json_decode($_POST['questions'], true); // Decodificar el JSON a un array
$classId = $_POST['classId'];

$sql = "INSERT INTO examenes (nombre, clase_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $examName, $classId);

if ($stmt->execute()) {
    $examId = $stmt->insert_id;

    foreach ($questions as $question) {
        $questionText = $question['question'];
        $options = $question['options'];

        $sql = "INSERT INTO preguntas (examen_id, pregunta) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $examId, $questionText);
        $stmt->execute();
        $questionId = $stmt->insert_id;

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
