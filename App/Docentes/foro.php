<?php
include_once __DIR__ . '/../Docentes/header.php';

// Asegúrate de que el usuario esté autenticado
if (!isset($_SESSION['identificacion_usuario'])) {
    die("Acceso denegado. Por favor, inicie sesión.");
}
?>

<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Crear un nuevo hilo en el foro</h2>
    <form action="procesar_foro" method="POST" class="shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título:</label>
            <input type="text" id="titulo" name="titulo" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>
        <input type="hidden" name="creador_id" value="<?php echo $_SESSION['identificacion_usuario']; ?>">
        <input type="hidden" name="tipo_creador" value="<?php echo $_SESSION['docente']; ?>">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Crear Hilo</button>
    </form>

    <h2 class="text-2xl font-bold mb-4">Publicaciones de los Docentes</h2>

    <?php
    include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

    // Consultar los hilos creados por los docentes
    $sql = "SELECT f.*, d.nombre AS nombre_docente 
            FROM foro f 
            JOIN docentes d ON f.creador_id = d.documento_identidad 
            WHERE f.tipo_creador = 'docente' 
            ORDER BY f.fecha_creacion DESC";

    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Consulta para contar el número de comentarios de cada hilo
            $comentarios_sql = "SELECT COUNT(*) AS total_comentarios FROM comentarios WHERE foro_id = ?";
            $comentarios_stmt = $conexion->prepare($comentarios_sql);
            $comentarios_stmt->bind_param("i", $row['id']);
            $comentarios_stmt->execute();
            $comentarios_result = $comentarios_stmt->get_result();
            $comentarios_row = $comentarios_result->fetch_assoc();
            $num_comentarios = $comentarios_row['total_comentarios'];

            echo '<div class="bg-white shadow-md rounded p-4 mb-4">';
            echo '<h3 class="text-lg font-semibold">' . htmlspecialchars($row['titulo']) . '</h3>';
            echo '<p class="text-gray-600 mb-2">Publicado por: ' . htmlspecialchars($row['nombre_docente']) . '</p>';
            echo '<p class="text-gray-800">' . nl2br(htmlspecialchars($row['descripcion'])) . '</p>';
            echo '<p class="text-gray-500 text-sm">Fecha: ' . $row['fecha_creacion'] . '</p>';

            // Mostrar número de comentarios con un icono
            echo '<div class="flex items-center mt-2 mb-4">';
            echo '<span class="mr-2">🗨️</span>'; // Icono de comentarios
            echo '<span class="text-gray-600">' . $num_comentarios . ' comentarios</span>';
            echo '</div>';

            echo '<label for="modal-' . $row['id'] . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer mt-4">Ver comentarios</label>';
            echo '</div>';

            // Modal DaisyUI
            echo '<input type="checkbox" id="modal-' . $row['id'] . '" class="modal-toggle">';
            echo '<div class="modal">';
            echo '<div class="modal-box relative">';
            echo '<label for="modal-' . $row['id'] . '" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>';
            echo '<h2 class="text-2xl font-bold mb-4">' . htmlspecialchars($row['titulo']) . '</h2>';
            echo '<p class="text-gray-700 mb-4">' . nl2br(htmlspecialchars($row['descripcion'])) . '</p>';

            // Mostrar comentarios en el modal
            echo '<div id="comments-container-' . $row['id'] . '">';
            echo '<p>Cargando comentarios...</p>';  // Mensaje de carga mientras se cargan los comentarios
            echo '</div>';

            // Formulario para agregar un comentario
            echo '<form action="procesar_comentario" method="POST" class="mt-4">';
            echo '<input type="hidden" name="foro_id" value="' . $row['id'] . '">';
            echo '<textarea name="comentario" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>';
            echo '<button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-2">Comentar</button>';
            echo '</form>';

            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p class="text-gray-500">No hay publicaciones disponibles.</p>';
    }

    $conexion->close();
    ?>
</div>

<script>
// Abrir el modal y cargar comentarios
document.addEventListener("DOMContentLoaded", function() {
    const modals = document.querySelectorAll('.modal-toggle');

    modals.forEach(modalToggle => {
        modalToggle.addEventListener('change', function() {
            if (this.checked) {
                const foroId = this.id.split('-')[1];
                const container = document.getElementById('comments-container-' + foroId);

                // Cargar comentarios del servidor
                fetch('load_comments?foro_id=' + foroId)
                    .then(response => response.text())
                    .then(data => {
                        container.innerHTML = data; // Añadir comentarios al contenedor del modal
                    })
                    .catch(error => {
                        console.error('Error loading comments:', error);
                        container.innerHTML = '<p>Error al cargar comentarios.</p>';
                    });
            }
        });
    });
});
</script>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
