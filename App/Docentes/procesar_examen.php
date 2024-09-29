<?php
include_once __DIR__ . '/../Docentes/header.php';
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Verificar si se ha enviado el formulario y si hay respuestas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['respuesta']) && isset($_POST['leccion_id'])) {
    $leccion_id = $_POST['leccion_id'];
    $respuestas = $_POST['respuesta'];

    // Obtener las preguntas y respuestas correctas de la base de datos
    $sql_preguntas = "SELECT id, correcta FROM preguntas_leccion WHERE leccion_id = ?";
    $stmt_preguntas = $conexion->prepare($sql_preguntas);
    $stmt_preguntas->bind_param("i", $leccion_id);
    $stmt_preguntas->execute();
    $result_preguntas = $stmt_preguntas->get_result();

    $total_preguntas = 0;
    $respuestas_correctas = 0;

    // Comparar las respuestas del usuario con las respuestas correctas
    while ($pregunta = $result_preguntas->fetch_assoc()) {
        $pregunta_id = $pregunta['id'];
        $respuesta_correcta = $pregunta['correcta'];

        if (isset($respuestas[$total_preguntas]) && $respuestas[$total_preguntas] == $respuesta_correcta) {
            $respuestas_correctas++;
        }
        $total_preguntas++;
    }

    // Calcular la nota como un porcentaje
    $nota = ($respuestas_correctas / $total_preguntas) * 100;

    // Insertar la calificación en la tabla `calificaciones`
    $usuario_id = $_SESSION['identificacion_usuario']; // Asumimos que el usuario está autenticado y su ID está en la sesión

    $sql_calificacion = "INSERT INTO calificaciones (usuario_id, leccion_id, nota) VALUES (?, ?, ?)";
    $stmt_calificacion = $conexion->prepare($sql_calificacion);
    $stmt_calificacion->bind_param("iid", $usuario_id, $leccion_id, $nota);

    if ($stmt_calificacion->execute()) {
        echo "<p>Examen completado. Tu calificación es: " . round($nota, 2) . "%.</p>";
    } else {
        echo "<p>Error al guardar la calificación: " . $conexion->error . "</p>";
    }
} else {
    echo "<p>Error: No se enviaron respuestas válidas.</p>";
}
?>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
