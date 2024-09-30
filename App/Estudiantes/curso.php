<?php
include_once __DIR__ . '/../Estudiantes/header.php';
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Obtener el ID del usuario
$usuario_id = $_SESSION['identificacion_usuario']; // Supone que el usuario está autenticado
// Consulta para obtener todos los cursos disponibles
$sql_cursos = "SELECT id, titulo FROM cursos";
$result_cursos = $conexion->query($sql_cursos);

// Verificar si se ha seleccionado un curso
$curso_seleccionado = isset($_GET['curso']) ? $_GET['curso'] : null;

// Consulta para obtener los niveles, lecciones y preguntas del curso seleccionado (o de todos si no se ha seleccionado ninguno)
$sql = "
    SELECT c.id AS curso_id, c.titulo AS curso_titulo, n.id AS nivel_id, n.titulo AS nivel_titulo, 
        l.id AS leccion_id, l.titulo AS leccion_titulo, l.descripcion, l.imagen_url, l.audio_url, l.video_url, l.duracion,
        (SELECT COUNT(*) FROM calificaciones WHERE calificaciones.leccion_id = l.id AND calificaciones.usuario_id = ?) AS leccion_completada
    FROM cursos c
    JOIN niveles n ON n.curso_id = c.id
    JOIN lecciones l ON l.nivel_id = n.id ";

if ($curso_seleccionado) {
    $sql .= " WHERE c.id = ?";
}

$sql .= " ORDER BY c.id, n.id, l.id";

$stmt = $conexion->prepare($sql);

if ($curso_seleccionado) {
    $stmt->bind_param("ii", $usuario_id, $curso_seleccionado);
} else {
    $stmt->bind_param("i", $usuario_id);
}
$stmt->execute();
$result = $stmt->get_result();

// Crear un array de cursos con sus niveles y lecciones
$cursos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $curso_id = $row['curso_id'];
        $nivel_id = $row['nivel_id'];
        $leccion_id = $row['leccion_id'];

        // Verificar si el curso ya está en el array
        if (!isset($cursos[$curso_id])) {
            $cursos[$curso_id] = [
                'curso_titulo' => $row['curso_titulo'],
                'niveles' => []
            ];
        }

        // Verificar si el nivel ya está en el curso
        if (!isset($cursos[$curso_id]['niveles'][$nivel_id])) {
            $cursos[$curso_id]['niveles'][$nivel_id] = [
                'nivel_titulo' => $row['nivel_titulo'],
                'lecciones' => []
            ];
        }

        // Verificar si la lección ya está en el nivel
        if (!isset($cursos[$curso_id]['niveles'][$nivel_id]['lecciones'][$leccion_id])) {
            $imagen_url = !empty($row['imagen_url']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/edu-alex/App/Docentes/' . htmlspecialchars($row['imagen_url'], ENT_QUOTES, 'UTF-8') : null;
            $video_url = !empty($row['video_url']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/edu-alex/App/Docentes/' . htmlspecialchars($row['video_url'], ENT_QUOTES, 'UTF-8') : null;
            $audio_url = 'http://' . $_SERVER['HTTP_HOST'] . '/edu-alex/App/Docentes/' . htmlspecialchars($row['audio_url'], ENT_QUOTES, 'UTF-8');

            $cursos[$curso_id]['niveles'][$nivel_id]['lecciones'][$leccion_id] = [
                'leccion_id' => $leccion_id,
                'leccion_titulo' => htmlspecialchars($row['leccion_titulo'], ENT_QUOTES, 'UTF-8'),
                'descripcion' => htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8'),
                'imagen_url' => $imagen_url,
                'video_url' => $video_url,
                'audio_url' => $audio_url,
                'duracion' => htmlspecialchars($row['duracion'], ENT_QUOTES, 'UTF-8'),
                'leccion_completada' => $row['leccion_completada']
            ];
        }
    }
}
?>

<form method="get">
    <label for="curso">Selecciona un curso:</label>
    <select class="select select-bordered w-full max-w-xs mx-3" id="curso" name="curso">
        <?php
        while ($row_curso = $result_cursos->fetch_assoc()) {
            $selected = ($curso_seleccionado == $row_curso['id']) ? 'selected' : '';
            echo "<option value='{$row_curso['id']}' $selected>{$row_curso['titulo']}</option>";
        }
        ?>
    </select>
    <button class="btn" type="submit">Filtrar</button>
</form>

<div class="p-4">
    <!-- Sección para mostrar los niveles como pasos (steps) -->
    <div class="niveles-steps mb-6">
        <h2 class="text-lg font-bold mb-4">Niveles del Curso</h2>
        <ul id="course-steps" class="steps">
            <!-- Aquí se generarán dinámicamente los niveles -->
        </ul>
    </div>

    <!-- Sección para mostrar las lecciones del nivel seleccionado -->
    <div class="lecciones-list mb-6" id="lesson-list-container" style="display: none;">
        <h3 class="text-lg font-bold">Lecciones del Nivel</h3>
        <p>Selecciona una lección</p>
        <div id="lesson-list" class="mt-4 flex flex-wrap gap-4">
            <!-- Aquí se generarán dinámicamente las lecciones -->
        </div>
    </div>

    <!-- Sección de reproducción y tabs -->
    <div class="video-section">
        <div style="height: 660px;" class="mockup-window bg-base-300  border">
            <div id="media-container">
                <img id="section-image" src="" alt="Imagen de la lección" class="w-full  object-contain bg-gray-200" style="display: none;">
                <video id="video-player" controls class="video-player" style="display: none; height: 600px;
    display: block;
    width: 100%;">
                    <source id="video-source" type="video/mp4">
                    Tu navegador no soporta la reproducción de video.
                </video>
            </div>
        </div>
        <div class="mt-4">
            <p>Pronunciación</p>
            <audio id="audio-player" controls class="w-full" style="display: none;">
                <source id="audio-source" src="" type="audio/mp3">
                Tu navegador no soporta la reproducción de audio.
            </audio>

            <!-- Tabs para contenido, resolver preguntas y comentarios -->
            <div role="tablist" class="tabs tabs-lifted mt-8">
                <input type="radio" name="question_tabs" role="tab" class="tab" aria-label="Lección" checked />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    <h4 class="font-bold">Contenido de la Lección</h4>
                    <p class="hidden" id="lesson-content"></p>
                    <p id="lesson-description" class="description rounded-lg p-4 my-5 bg-base-200"></p>
                </div>

                <input type="radio" name="question_tabs" role="tab" class="tab" aria-label="Preguntas" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    <a id="resolver-preguntas-btn" href="#" class="btn btn-primary">Responder Cuestionario</a>
                </div>

                <input type="radio" name="question_tabs" role="tab" class="tab" aria-label="Comentarios" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    <h4 class="font-bold mb-4">Comentarios</h4>
                    <!-- Lógica de los comentarios como en el ejemplo anterior -->
                    <div id="comments-container"></div> <!-- Contenedor para los comentarios -->



                    <div class="container mx-auto p-4">
                        <h2 class="text-2xl font-bold mb-4">Crear un nuevo hilo en el foro</h2>
                        <form action="procesar_foro_e" method="POST" class="shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const data = <?php echo json_encode($cursos, JSON_UNESCAPED_UNICODE); ?>;

    function loadCourseContent() {

        const courseSelect = document.getElementById('curso');
        const selectedCourseId = courseSelect.value;

        const courseSteps = document.getElementById('course-steps');
        const lessonListContainer = document.getElementById('lesson-list-container');
        const lessonList = document.getElementById('lesson-list');

        courseSteps.innerHTML = '';
        lessonListContainer.style.display = 'none';

        // Obtener el primer curso de los datos
        const firstCourseId = Object.keys(data)[0];
        const filteredData = selectedCourseId ? data[selectedCourseId] : data[firstCourseId];

        Object.values(data).forEach((curso) => {
            Object.values(curso.niveles).forEach((nivel, nivelIndex) => {
                const nivelItem = document.createElement('li');
                nivelItem.classList.add('step');
                if (nivelIndex === 0) {
                    nivelItem.classList.add('step-primary'); // El primer nivel se marca como activo inicialmente
                }
                nivelItem.textContent = nivel.nivel_titulo;

                nivelItem.addEventListener('click', () => {
                    // Marcar todos los pasos anteriores y el actual como activos
                    const steps = courseSteps.querySelectorAll('.step');
                    steps.forEach((step, index) => {
                        if (index <= nivelIndex) {
                            step.classList.add('step-primary');
                        } else {
                            step.classList.remove('step-primary');
                        }
                    });

                    // Cargar el nivel y la primera lección automáticamente
                    loadLevelAndFirstLesson(nivel);
                });

                courseSteps.appendChild(nivelItem);
            });

            // Cargar el primer nivel y la primera lección automáticamente al cargar la página
            const firstNivel = Object.values(filteredData.niveles)[0];
        if (firstNivel) {
            loadLevelAndFirstLesson(firstNivel);
            lessonListContainer.style.display = 'block'; // Mostrar la lista de lecciones del primer curso
        }
        });
    }

    function loadLevelAndFirstLesson(nivel) {
        const lessonListContainer = document.getElementById('lesson-list-container');
        const lessonList = document.getElementById('lesson-list');

        lessonListContainer.style.display = 'block';
        lessonList.innerHTML = ''; // Limpiar las lecciones antes de añadir las nuevas

        const firstLeccion = Object.values(nivel.lecciones)[0];
        if (firstLeccion) {
            loadLesson(firstLeccion);
        }

        Object.values(nivel.lecciones).forEach((leccion) => {
            const leccionItem = document.createElement('div');
            leccionItem.classList.add('badge', 'badge-info', 'gap-2', 'p-4', 'hover:bg-blue-500', 'mb-2');
            leccionItem.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                </svg>
                ${leccion.leccion_titulo}
                ${leccion.leccion_completada > 0 ? '<span class="ml-2 badge badge-success">Lección Completada</span>' : ''}
            `;

            leccionItem.addEventListener('click', () => {
                loadLesson(leccion);
            });

            lessonList.appendChild(leccionItem);
        });
    }

    function loadLesson(leccion) {
        const image = document.getElementById('section-image');
        const video = document.getElementById('video-player');
        const audio = document.getElementById('audio-player');
        const resolverPreguntasBtn = document.getElementById('resolver-preguntas-btn');

        // Mostrar solo el video si hay un video, y el audio si no hay video pero sí imagen
        if (leccion.video_url) {
            video.src = leccion.video_url;
            video.style.display = "block";
            image.style.display = "none";
            audio.style.display = "none";
        } else if (leccion.imagen_url) {
            image.src = leccion.imagen_url;
            image.style.display = "block";
            video.style.display = "none";
            audio.src = leccion.audio_url;
            audio.style.display = "block";
            audio.load();
        } else {
            image.style.display = "none";
            video.style.display = "none";
            audio.src = leccion.audio_url;
            audio.style.display = "block";
            audio.load();
        }

        document.getElementById('lesson-description').textContent = leccion.descripcion;

        // Actualizar el enlace del botón "Responder Cuestionario" con el ID de la lección
        resolverPreguntasBtn.href = `preguntas_e?leccion_id=${leccion.leccion_id}`;
    }

    // Agregar un event listener al select del curso para recargar el contenido cuando cambie la selección
    const courseSelect = document.getElementById('curso');
    courseSelect.addEventListener('change', loadCourseContent);

    loadCourseContent(); 
</script>

<div class="fixed bottom-0 bg-base-300 right-0 m-4 w-80 max-w-sm overflow-hidden rounded-lg shadow-md sm:bottom-auto sm:top-0 sm:right-0">
    <div class="flex">
        <div class="flex items-center justify-center w-12 bg-blue-500">
            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
            </svg>
        </div>

        <div class="px-4 py-2 -mx-3">
            <div class="mx-3">
                <span class="font-semibold">Nunca pares de aprender!
                </span>
                <p class="text-lg">
                    <strong><?php echo $_SESSION['nombre_usuario']; ?></strong>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../Estudiantes/footer.php';
?>