<?php
session_start();
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Habilitar el reporte de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si el ID del usuario está establecido en la sesión
    if (!isset($_SESSION['identificacion_usuario'])) {
        die("No tienes permiso para crear un hilo."); // Este mensaje es opcional
    }

    // Recibir datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $creador_id = $_SESSION['identificacion_usuario']; // Obtener el ID del creador desde la sesión

    // Validar entradas
    if (empty($titulo) || empty($descripcion)) {
        die("El título y la descripción son obligatorios.");
    }

    // Establecer un tipo_creador predeterminado (opcional)
    $tipo_creador = 'general'; // Puedes cambiar esto a cualquier valor que desees

    // Preparar la consulta
    $sql = "INSERT INTO foro (titulo, descripcion, creador_id) VALUES (?,  ?, ?)";
    $stmt = $conexion->prepare($sql);

    // Comprobar si la preparación fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("ssi", $titulo, $descripcion, $creador_id);

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
