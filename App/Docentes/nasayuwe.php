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


<div class="flex">
    <!-- Sección de reproducción -->
    <div class="video-section p-4">
        <div class="mockup-window bg-base-300 border">
            <div id="media-container">
                <img id="section-image" src="" alt="Imagen de la lección" class="w-full  object-contain bg-gray-200" style="display: none;">
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
            <p id="lesson-description" class="description rounded-lg p-4 my-5 bg-base-200"></p> <!-- Aquí irá la descripción -->
            <div id="lesson-question" class="pregunta"></div> <!-- Aquí se mostrarán las preguntas -->
            <button id="submit-answer" class="btn my-4">Enviar Respuesta</button>
            <p id="result-message" class="resultado"></p> <!-- Mensaje de resultado -->
        </div>
    </div>

    <!-- Sidebar de lecciones -->
    <div class="course-sidebar ms-2  bg-base-300 shadow-md p-2">
        <h2 class="text-lg font-bold my-5 mb-4">Contenido del curso</h2>
        <div id="course-content" class="space-y-4 w-48">
            <!-- Aquí se generarán dinámicamente las secciones -->
        </div>
    </div>
</div>

<script>
    const data = <?php echo json_encode($cursos, JSON_UNESCAPED_UNICODE); ?>;
    let currentLesson = null;
    let selectedAnswer = null;

    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    function getCookie(name) {
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(';');
        name = name + "=";
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function isLessonAnswered(lessonId) {
        const answeredLessons = getCookie('answeredLessons') || "";
        return answeredLessons.includes(lessonId);
    }

    function saveLessonAsAnswered(lessonId) {
        let answeredLessons = getCookie('answeredLessons') || "";
        if (!answeredLessons.includes(lessonId)) {
            answeredLessons += lessonId + ",";
            setCookie('answeredLessons', answeredLessons, 365);
        }
    }

    function loadCourseContent() {
        const courseContent = document.getElementById('course-content');
        courseContent.innerHTML = ''; // Limpiar el contenido antes de recargarlo

        let firstLessonLoaded = false;

        Object.values(data).forEach((curso) => {
            Object.values(curso.niveles).forEach((nivel) => {
                const nivelDiv = document.createElement('div');
                nivelDiv.classList.add('mb-4');

                const nivelTitle = document.createElement('h3');
                nivelTitle.textContent = nivel.nivel_titulo;
                nivelTitle.classList.add('text-lg', 'font-bold', 'mb-2');
                nivelDiv.appendChild(nivelTitle);

                Object.values(nivel.lecciones).forEach((leccion, index) => {
                    const leccionDiv = document.createElement('div');
                    leccionDiv.classList.add('mb-2', 'border-b', 'pb-2', 'flex', 'items-center');

                         // Agregar el nuevo ícono SVG antes del título de la lección
                         const svgIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    svgIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
                    svgIcon.setAttribute("viewBox", "0 0 24 24");
                    svgIcon.setAttribute("fill", "currentColor");
                    svgIcon.classList.add("w-4", "h-4", "mr-2");

                    const svgPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    svgPath.setAttribute("fill-rule", "evenodd");
                    svgPath.setAttribute("d", "M19.902 4.098a3.75 3.75 0 0 0-5.304 0l-4.5 4.5a3.75 3.75 0 0 0 1.035 6.037.75.75 0 0 1-.646 1.353 5.25 5.25 0 0 1-1.449-8.45l4.5-4.5a5.25 5.25 0 1 1 7.424 7.424l-1.757 1.757a.75.75 0 1 1-1.06-1.06l1.757-1.757a3.75 3.75 0 0 0 0-5.304Zm-7.389 4.267a.75.75 0 0 1 1-.353 5.25 5.25 0 0 1 1.449 8.45l-4.5 4.5a5.25 5.25 0 1 1-7.424-7.424l1.757-1.757a.75.75 0 1 1 1.06 1.06l-1.757 1.757a3.75 3.75 0 1 0 5.304 5.304l4.5-4.5a3.75 3.75 0 0 0-1.035-6.037.75.75 0 0 1-.354-1Z");
                    svgPath.setAttribute("clip-rule", "evenodd");
                    svgIcon.appendChild(svgPath);

                    leccionDiv.appendChild(svgIcon);
                    
                    const leccionTitle = document.createElement('p');
                    leccionTitle.classList.add('section-title');
                    leccionTitle.textContent = `${leccion.leccion_titulo} (${leccion.duracion})`;

                    leccionDiv.appendChild(leccionTitle);

                    leccionTitle.addEventListener('click', () => {
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

                        if (isLessonAnswered(leccion.leccion_id)) {
                            resultMessage.textContent = "Ya has respondido esta lección.";
                            resultMessage.style.color = "blue";
                            document.getElementById('submit-answer').disabled = true;
                            disableRadioButtons();
                        } else {
                            document.getElementById('submit-answer').disabled = false;
                            enableRadioButtons();
                        }

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
                    });

                    if (!firstLessonLoaded && index === 0) {
                        leccionTitle.click();
                        firstLessonLoaded = true;
                    }

                    nivelDiv.appendChild(leccionDiv);
                });

                courseContent.appendChild(nivelDiv);
            });
        });
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
            saveLessonAsAnswered(currentLesson.leccion_id);
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

    function enableRadioButtons() {
        const radios = document.querySelectorAll('input[name="opcion"]');
        radios.forEach((radio) => {
            radio.disabled = false;
        });
    }

    loadCourseContent();
</script>
<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>