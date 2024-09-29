<?php
include_once __DIR__ . '/../Docentes/header.php';
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Obtener el ID de la lección de la URL
if (isset($_GET['leccion_id'])) {
    $leccion_id = $_GET['leccion_id'];

    // Consulta para obtener las preguntas asociadas a la lección
    $sql = "SELECT pregunta, opcion1, opcion2, opcion3, opcion4, correcta 
            FROM preguntas_leccion 
            WHERE leccion_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $leccion_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un array de preguntas
    $preguntas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }
    }

    // Obtener la calificación del usuario de la base de datos
    $usuario_id = $_SESSION['identificacion_usuario']; // Suponemos que el usuario está autenticado y el ID está en la sesión
    $sql_calificacion = "SELECT nota FROM calificaciones WHERE usuario_id = ? AND leccion_id = ?";
    $stmt_calificacion = $conexion->prepare($sql_calificacion);
    $stmt_calificacion->bind_param("ii", $usuario_id, $leccion_id);
    $stmt_calificacion->execute();
    $result_calificacion = $stmt_calificacion->get_result();

    $nota = null;
    $calificacion_existe = false;
    if ($result_calificacion->num_rows > 0) {
        $row_calificacion = $result_calificacion->fetch_assoc();
        $nota = $row_calificacion['nota'];
        $calificacion_existe = true; // Indicar que ya existe una calificación para esta lección
    }
} else {
    echo "<p>Error: No se especificó ninguna lección.</p>";
    exit;
}
?>

<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Examen de la Lección</h2>

    <?php if ($calificacion_existe): ?>
        <!-- Mostrar la calificación si ya fue realizada -->
        <div class="mt-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
            <h3 class="text-lg font-bold">Calificación obtenida:</h3>
            <p>Tu calificación es: <?php echo htmlspecialchars($nota, ENT_QUOTES, 'UTF-8'); ?>%</p>
        </div>
    <?php elseif (!empty($preguntas)) : ?>
        <!-- Mostrar el formulario si la calificación no existe -->
        <form action="procesar_examen.php" method="POST" class="shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <?php foreach ($preguntas as $index => $pregunta) : ?>
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">Pregunta <?php echo $index + 1; ?>:</h3>
                    <p><?php echo htmlspecialchars($pregunta['pregunta'], ENT_QUOTES, 'UTF-8'); ?></p>

                    <div class="mb-2">
                        <label>
                            <input type="radio" name="respuesta[<?php echo $index; ?>]" value="1" required>
                            <?php echo htmlspecialchars($pregunta['opcion1'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                    </div>
                    <div class="mb-2">
                        <label>
                            <input type="radio" name="respuesta[<?php echo $index; ?>]" value="2" required>
                            <?php echo htmlspecialchars($pregunta['opcion2'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                    </div>
                    <div class="mb-2">
                        <label>
                            <input type="radio" name="respuesta[<?php echo $index; ?>]" value="3" required>
                            <?php echo htmlspecialchars($pregunta['opcion3'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                    </div>
                    <div class="mb-2">
                        <label>
                            <input type="radio" name="respuesta[<?php echo $index; ?>]" value="4" required>
                            <?php echo htmlspecialchars($pregunta['opcion4'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>

            <input type="hidden" name="leccion_id" value="<?php echo $leccion_id; ?>">
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Enviar Respuestas</button>
        </form>
    <?php else: ?>
        <p>No hay preguntas disponibles para esta lección.</p>
    <?php endif; ?>
</div>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
