<?php
include_once __DIR__ . '/../Estudiantes/header.php';
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

        // Si la respuesta del usuario es correcta, incrementar el contador de respuestas correctas
        if (isset($respuestas[$total_preguntas]) && $respuestas[$total_preguntas] == $respuesta_correcta) {
            $respuestas_correctas++;
        }
        $total_preguntas++;
    }

    // Calcular la nota como un porcentaje
    $nota = ($respuestas_correctas / $total_preguntas) * 100;

    // Insertar la calificación en la tabla `calificaciones`
    $usuario_id = $_SESSION['identificacion_usuario']; // Asumimos que el usuario está autenticado y su ID está en la sesión

    // Verificar si ya existe una calificación para evitar duplicados
    $sql_verificar = "SELECT id FROM calificaciones WHERE usuario_id = ? AND leccion_id = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("ii", $usuario_id, $leccion_id);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows > 0) {
        // Si ya existe una calificación, actualizarla
        $sql_actualizar = "UPDATE calificaciones SET nota = ?, fecha = NOW() WHERE usuario_id = ? AND leccion_id = ?";
        $stmt_actualizar = $conexion->prepare($sql_actualizar);
        $stmt_actualizar->bind_param("dii", $nota, $usuario_id, $leccion_id);
        $stmt_actualizar->execute();
    } else {
        // Si no existe una calificación, insertarla
        $sql_calificacion = "INSERT INTO calificaciones (usuario_id, leccion_id, nota) VALUES (?, ?, ?)";
        $stmt_calificacion = $conexion->prepare($sql_calificacion);
        $stmt_calificacion->bind_param("iid", $usuario_id, $leccion_id, $nota);
        $stmt_calificacion->execute();
    }

    // Mostrar la calificación al usuario
    echo "<div class='container mx-auto p-4'>";
    echo "<h2 class='text-2xl font-bold mb-4'>Resultado del Examen</h2>";
    echo "<p>Examen completado. Tu calificación es: " . round($nota, 2) . "%.</p>";
    echo "<a href='preguntas?leccion_id=" . $leccion_id . "' class='btn btn-primary mt-4'>Volver a la lección</a>";
    echo "</div>";

} else {
    echo "<p>Error: No se enviaron respuestas válidas.</p>";
}

include_once __DIR__ . '/../Estudiantes/footer.php';
?>
