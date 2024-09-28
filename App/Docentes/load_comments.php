<?php
include_once __DIR__ . '/../db.php';

$foro_id = $_GET['foro_id'];

// Consultar los comentarios del foro específico
$comentarios_sql = "SELECT c.*, e.nombre AS nombre_estudiante 
                    FROM comentarios c 
                    JOIN estudiantes e ON c.usuario_id = e.documento_identidad 
                    WHERE c.foro_id = ? AND c.respuesta_a IS NULL";

$comentarios_stmt = $conexion->prepare($comentarios_sql);
$comentarios_stmt->bind_param("i", $foro_id);
$comentarios_stmt->execute();
$comentarios_result = $comentarios_stmt->get_result();

if ($comentarios_result->num_rows > 0) {
    while ($comentario_row = $comentarios_result->fetch_assoc()) {
        echo '<div class="bg-gray-100 rounded p-2 mt-2">';
        echo '<p class="text-gray-600">' . htmlspecialchars($comentario_row['contenido']) . '</p>';
        echo '<p class="text-gray-500 text-sm">Comentado por: ' . htmlspecialchars($comentario_row['nombre_estudiante']) . '</p>';
        echo '<p class="text-gray-500 text-xs">Fecha: ' . $comentario_row['fecha_creacion'] . '</p>';
        
        // Formulario para responder al comentario
        echo '<form action="procesar_comentario" method="POST" class="mt-2">';
        echo '<input type="hidden" name="foro_id" value="' . $foro_id . '">';
        echo '<input type="hidden" name="respuesta_a" value="' . $comentario_row['id'] . '">'; // ID del comentario al que se responde
        echo '<textarea name="comentario" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>';
        echo '<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-2">Responder</button>';
        echo '</form>';
        
        // Mostrar respuestas
        $respuestas_sql = "SELECT c.*, e.nombre AS nombre_estudiante 
                           FROM comentarios c 
                           JOIN estudiantes e ON c.usuario_id = e.documento_identidad 
                           WHERE c.respuesta_a = ?";
        $respuestas_stmt = $conexion->prepare($respuestas_sql);
        $respuestas_stmt->bind_param("i", $comentario_row['id']);
        $respuestas_stmt->execute();
        $respuestas_result = $respuestas_stmt->get_result();

        if ($respuestas_result->num_rows > 0) {
            while ($respuesta_row = $respuestas_result->fetch_assoc()) {
                echo '<div class="bg-gray-200 rounded p-2 mt-2 ml-4">';
                echo '<p class="text-gray-600">' . htmlspecialchars($respuesta_row['contenido']) . '</p>';
                echo '<p class="text-gray-500 text-sm">Respondido por: ' . htmlspecialchars($respuesta_row['nombre_estudiante']) . '</p>';
                echo '<p class="text-gray-500 text-xs">Fecha: ' . $respuesta_row['fecha_creacion'] . '</p>';
                echo '</div>';
            }
        }

        echo '</div>'; // Cierre del div del comentario
    }
} else {
    echo '<p class="text-gray-500">No hay comentarios aún.</p>';
}

$conexion->close();
?>
