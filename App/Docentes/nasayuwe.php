<?php
include_once __DIR__ . '/../Docentes/header.php';
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Consulta para obtener todos los niveles, lecciones y preguntas de los cursos
$sql = "
    SELECT c.id AS curso_id, c.titulo AS curso_titulo, n.id AS nivel_id, n.titulo AS nivel_titulo, 
           l.id AS leccion_id, l.titulo AS leccion_titulo, l.descripcion, l.imagen_url, l.audio_url, l.video_url, l.duracion, 
           p.pregunta, p.opcion1, p.opcion2, p.opcion3, p.opcion4, p.correcta
    FROM cursos c
    JOIN niveles n ON n.curso_id = c.id
    JOIN lecciones l ON l.nivel_id = n.id
    LEFT JOIN preguntas_leccion p ON p.leccion_id = l.id
    ORDER BY c.id, n.id, l.id";

$result = $conexion->query($sql);

// Crear un array de cursos con sus niveles, lecciones y preguntas
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
                'leccion_titulo' => htmlspecialchars($row['leccion_titulo'], ENT_QUOTES, 'UTF-8'),
                'descripcion' => htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8'),
                'imagen_url' => $imagen_url,
                'video_url' => $video_url,
                'audio_url' => $audio_url,
                'duracion' => htmlspecialchars($row['duracion'], ENT_QUOTES, 'UTF-8'),
                'pregunta' => [
                    'texto' => $row['pregunta'],
                    'opciones' => [
                        'opcion1' => htmlspecialchars($row['opcion1'], ENT_QUOTES, 'UTF-8'),
                        'opcion2' => htmlspecialchars($row['opcion2'], ENT_QUOTES, 'UTF-8'),
                        'opcion3' => htmlspecialchars($row['opcion3'], ENT_QUOTES, 'UTF-8'),
                        'opcion4' => htmlspecialchars($row['opcion4'], ENT_QUOTES, 'UTF-8'),
                    ],
                    'correcta' => $row['correcta']
                ]
            ];
        }
    }
}
?>

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

    <!-- Sección de reproducción y preguntas -->
    <div class="video-section">
        <div class="mockup-window bg-base-300 border">
            <div  id="media-container">
                <img id="section-image" src="" alt="Imagen de la lección" class="w-full object-contain bg-gray-200" style="display: none;">
                <video id="video-player" controls class="video-player" style="display: none;">
                    <source id="video-source" type="video/mp4">
                    Tu navegador no soporta la reproducción de video.
                </video>
            </div>
        </div>
        <div class="mt-4">
            <audio id="audio-player" controls class="w-full" style="display: none;">
                <source id="audio-source" src="" type="audio/mp3">
                Tu navegador no soporta la reproducción de audio.
            </audio>

            <!-- Tabs para preguntas -->
            <div role="tablist" class="tabs tabs-lifted mt-8">
                <input type="radio" name="question_tabs" role="tab" class="tab" aria-label="Lección" checked />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    <h4 class="font-bold">Contenido de la Lección</h4>
                    <p class="hidden" id="lesson-content"></p>
                    <p id="lesson-description" class="description rounded-lg p-4 my-5 bg-base-200"></p> 

                </div>

                <input type="radio" name="question_tabs" role="tab" class="tab" aria-label="Pregunta" />
                <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                    <h4 class="font-bold">Pregunta:</h4>
                    <div id="lesson-question" class="pregunta"></div> <!-- Aquí se mostrarán las preguntas -->
                    <button id="submit-answer" class="btn my-4">Enviar Respuesta</button>
                    <p id="result-message" class="resultado"></p> <!-- Mensaje de resultado -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const data = <?php echo json_encode($cursos, JSON_UNESCAPED_UNICODE); ?>;
    let currentLesson = null;
    let selectedAnswer = null;

    function loadCourseContent() {
        const courseSteps = document.getElementById('course-steps');
        const lessonListContainer = document.getElementById('lesson-list-container');
        const lessonList = document.getElementById('lesson-list');

        courseSteps.innerHTML = ''; // Limpiar el contenido antes de recargarlo

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
            const firstNivel = Object.values(curso.niveles)[0];
            if (firstNivel) {
                loadLevelAndFirstLesson(firstNivel);
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
            `;

            leccionItem.addEventListener('click', () => {
                loadLesson(leccion);
            });

            lessonList.appendChild(leccionItem);
        });
    }

    function loadLesson(leccion) {
        currentLesson = leccion;

        const image = document.getElementById('section-image');
        const video = document.getElementById('video-player');
        const audio = document.getElementById('audio-player');
        const resultMessage = document.getElementById('result-message');

        resultMessage.textContent = '';

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

        // Mostrar el contenido de la lección
        document.getElementById('lesson-content').textContent = leccion.descripcion;

        const questionDiv = document.getElementById('lesson-question');
        questionDiv.innerHTML = '';
        if (leccion.pregunta && leccion.pregunta.texto) {
            const questionText = document.createElement('p');
            questionText.textContent = leccion.pregunta.texto;
            questionDiv.appendChild(questionText);

            const opcionesDiv = document.createElement('div');
            opcionesDiv.classList.add('opciones');
            ['opcion1', 'opcion2', 'opcion3', 'opcion4'].forEach((opcionKey) => {
                const opcionLabel = document.createElement('label');
                const opcionRadio = document.createElement('input');
                opcionRadio.type = 'radio';
                opcionRadio.name = 'opcion';
                opcionRadio.value = opcionKey;
                opcionRadio.addEventListener('change', () => {
                    selectedAnswer = opcionKey;
                });

                opcionLabel.appendChild(opcionRadio);
                opcionLabel.appendChild(document.createTextNode(leccion.pregunta.opciones[opcionKey]));
                opcionesDiv.appendChild(opcionLabel);
                opcionesDiv.appendChild(document.createElement('br'));
            });
            questionDiv.appendChild(opcionesDiv);
        }
    }

    document.getElementById('submit-answer').addEventListener('click', () => {
        const resultMessage = document.getElementById('result-message');

        if (selectedAnswer) {
            const correctAnswer = 'opcion' + currentLesson.pregunta.correcta;

            if (selectedAnswer === correctAnswer) {
                resultMessage.textContent = "¡Respuesta correcta!";
                resultMessage.style.color = "green";
            } else {
                resultMessage.textContent = `Respuesta incorrecta. La correcta era: ${currentLesson.pregunta['opcion' + currentLesson.pregunta.correcta]}`;
                resultMessage.style.color = "red";
            }

            document.getElementById('submit-answer').disabled = true;
            disableRadioButtons();
        } else {
            resultMessage.textContent = "Por favor selecciona una respuesta.";
        }
    });

    function disableRadioButtons() {
        const radios = document.querySelectorAll('input[name="opcion"]');
        radios.forEach((radio) => {
            radio.disabled = true;
        });
    }

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
include_once __DIR__ . '/../Docentes/footer.php';
?>
