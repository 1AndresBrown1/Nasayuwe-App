<?php
session_start();
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Habilitar el reporte de errores para detectar cualquier problema
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de que se reciban todos los datos necesarios
    if (isset($_POST['foro_id'], $_POST['comentario'])) {
        $foro_id = $_POST['foro_id'];
        $contenido = $_POST['comentario'];
        $usuario_id = $_SESSION['identificacion_usuario'] ?? null; // Obtener el usuario desde la sesión
        
        // Validación adicional: Asegúrate de que el usuario esté autenticado
        if ($usuario_id === null) {
            die("Error: Usuario no autenticado.");
        }

        // Obtener el ID del comentario al que se responde, si existe
        $respuesta_a = isset($_POST['respuesta_a']) ? $_POST['respuesta_a'] : null;

        // Preparar la consulta SQL para insertar el comentario
        $sql = "INSERT INTO comentarios (foro_id, contenido, usuario_id, respuesta_a) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        // Verifica si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        // Verificar y enlazar los parámetros
        // bind_param utiliza tipos: s (string), i (integer)
        if ($stmt->bind_param("ssii", $foro_id, $contenido, $usuario_id, $respuesta_a) === false) {
            die("Error en bind_param: " . $stmt->error);
        }

        // Ejecutar la consulta y comprobar si fue exitosa
        if ($stmt->execute()) {
            // Redirigir al foro tras el éxito
            header("Location: foro");
            exit();
        } else {
            // Mostrar un error si la consulta falla
            echo "Error al añadir el comentario: " . $stmt->error;
        }

        // Cerrar la sentencia y la conexión
        $stmt->close();
        $conexion->close();
    } else {
        // Manejo de errores cuando no se reciben todos los datos
        die("Error: Todos los campos son obligatorios.");
    }
} else {
    // Si no se accede por el método POST, se muestra un error
    die("Método no permitido.");
}
?>
