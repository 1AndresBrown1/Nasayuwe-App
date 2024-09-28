<?php
session_start();
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foro_id = $_POST['foro_id'];
    $contenido = $_POST['comentario'];
    $usuario_id = $_SESSION['identificacion_usuario'];
    
    // Obtener el ID del comentario al que se responde
    $respuesta_a = isset($_POST['respuesta_a']) ? $_POST['respuesta_a'] : null;

    // Preparar la consulta
    $sql = "INSERT INTO comentarios (foro_id, contenido, usuario_id, respuesta_a) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssii", $foro_id, $contenido, $usuario_id, $respuesta_a);

    if ($stmt->execute()) {
        header("Location: foro");
        exit();
    } else {
        echo "Error al añadir el comentario: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
} else {
    die("Método no permitido.");
}
?>
