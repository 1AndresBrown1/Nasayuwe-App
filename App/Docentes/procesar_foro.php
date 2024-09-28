<?php
session_start();
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $creador_id = $_POST['creador_id'];
    $tipo_creador = $_POST['tipo_creador'];

    // Preparar la consulta
    $sql = "INSERT INTO foro (titulo, descripcion, creador_id, tipo_creador) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    // Comprobar si la preparación fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("ssis", $titulo, $descripcion, $creador_id, $tipo_creador);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a una página de éxito o al foro
        header("Location: foro"); // Cambia esto si necesitas redirigir a otra página
        exit();
    } else {
        echo "Error al crear el hilo: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conexion->close();
} else {
    die("Método no permitido.");
}
?>
