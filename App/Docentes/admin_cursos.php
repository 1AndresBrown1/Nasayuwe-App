<?php
include_once __DIR__ . '/../Docentes/header.php';
include_once __DIR__ . '/../db.php'; // Incluye tu archivo de conexión

// Si se ha enviado el formulario para agregar una lección con una pregunta y un video
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_leccion'])) {
    $nivel_id = $_POST['nivel_id'];
    $titulo = $_POST['titulo_leccion'];
    $descripcion = $_POST['descripcion_leccion'];
    $duracion = $_POST['duracion'];

    // Verificar si ambos, imagen y video, están presentes
    if (!empty($_FILES['imagen']['name']) && !empty($_FILES['video']['name'])) {
        echo "<p>Error: No puedes subir una imagen y un video al mismo tiempo. Selecciona solo uno.</p>";
    } else {
        // Definir la ruta base para las cargas
        $base_dir = realpath(__DIR__ . '/uploads');
        $imagen_dir = $base_dir . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        $audio_dir = $base_dir . DIRECTORY_SEPARATOR . 'audios' . DIRECTORY_SEPARATOR;
        $video_dir = $base_dir . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR;

        // Verificar y subir la imagen
        $imagen_url = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imagen_nombre = basename($_FILES['imagen']['name']);
            $imagen_tmp = $_FILES['imagen']['tmp_name'];
            $imagen_destino = $imagen_dir . $imagen_nombre;
            if (move_uploaded_file($imagen_tmp, $imagen_destino)) {
                $imagen_url = 'uploads/images/' . $imagen_nombre;
            } else {
                echo "<p>Error al subir la imagen. Verifica los permisos de la carpeta.</p>";
            }
        }

        // Verificar y subir el video
        $video_url = null;
        if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
            $video_nombre = basename($_FILES['video']['name']);
            $video_tmp = $_FILES['video']['tmp_name'];
            $video_destino = $video_dir . $video_nombre;
            if (move_uploaded_file($video_tmp, $video_destino)) {
                $video_url = 'uploads/videos/' . $video_nombre;
            } else {
                echo "<p>Error al subir el video. Verifica los permisos de la carpeta.</p>";
            }
        }

        // Verificar y subir el audio
        $audio_url = null;
        if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
            $audio_nombre = basename($_FILES['audio']['name']);
            $audio_tmp = $_FILES['audio']['tmp_name'];
            $audio_destino = $audio_dir . $audio_nombre;
            if (move_uploaded_file($audio_tmp, $audio_destino)) {
                $audio_url = 'uploads/audios/' . $audio_nombre;
            } else {
                echo "<p>Error al subir el audio. Verifica los permisos de la carpeta.</p>";
            }
        }

        // Obtener el curso_id a partir del nivel_id seleccionado
        $nivel_id = $_POST['nivel_id'];
        $sql_get_curso_id = "SELECT curso_id FROM niveles WHERE id = ?";
        $stmt_get_curso_id = $conexion->prepare($sql_get_curso_id);
        $stmt_get_curso_id->bind_param("i", $nivel_id);
        $stmt_get_curso_id->execute();
        $result_get_curso_id = $stmt_get_curso_id->get_result();
        $row_curso_id = $result_get_curso_id->fetch_assoc();
        $curso_id = $row_curso_id['curso_id'];

        // Verificar si ya existe la lección
        $sql_check_leccion = "SELECT * FROM lecciones WHERE nivel_id = ? AND titulo = ?";
        $stmt_check_leccion = $conexion->prepare($sql_check_leccion);
        $stmt_check_leccion->bind_param("is", $nivel_id, $titulo);
        $stmt_check_leccion->execute();
        $resultado_leccion = $stmt_check_leccion->get_result();

        if ($resultado_leccion->num_rows > 0) {
            echo "<p>Error: Ya existe una lección con el mismo título en este nivel.</p>";
        } else {
            // Insertar la lección en la base de datos
            $sql = "INSERT INTO lecciones (nivel_id, titulo, descripcion, imagen_url, audio_url, video_url, duracion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("issssss", $nivel_id, $titulo, $descripcion, $imagen_url, $audio_url, $video_url, $duracion);
            if ($stmt->execute()) {
                $leccion_id = $stmt->insert_id;  // Obtener el ID de la lección recién creada
                echo "<script>alert('Lección creada con éxito.');</script>"; // Alerta en el navegador

                // Insertar la pregunta en la base de datos
                $pregunta = $_POST['pregunta'];
                $opcion1 = $_POST['opcion1'];
                $opcion2 = $_POST['opcion2'];
                $opcion3 = $_POST['opcion3'];
                $opcion4 = $_POST['opcion4'];
                $correcta = $_POST['correcta'];

                // Verificar si ya existe la pregunta
                $sql_check_pregunta = "SELECT * FROM preguntas_leccion WHERE leccion_id = ? AND pregunta = ?";
                $stmt_check_pregunta = $conexion->prepare($sql_check_pregunta);
                $stmt_check_pregunta->bind_param("is", $leccion_id, $pregunta);
                $stmt_check_pregunta->execute();
                $resultado_pregunta = $stmt_check_pregunta->get_result();

                if ($resultado_pregunta->num_rows > 0) {
                    echo "<p>Error: Ya existe una pregunta con el mismo texto en esta lección.</p>";
                } else {
                    // Asignar un porcentaje fijo del 5% para cada pregunta (esto es solo un ejemplo, puedes cambiar la lógica)
                    $porcentaje = 5.00;

                    $sql_pregunta = "INSERT INTO preguntas_leccion (leccion_id, pregunta, opcion1, opcion2, opcion3, opcion4, correcta, porcentaje)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_pregunta = $conexion->prepare($sql_pregunta);
                    $stmt_pregunta->bind_param("issssssd", $leccion_id, $pregunta, $opcion1, $opcion2, $opcion3, $opcion4, $correcta, $porcentaje);
                    if ($stmt_pregunta->execute()) {
                        echo "<script>alert('Pregunta agregada con éxito.');</script>"; // Alerta para la pregunta
                    } else {
                        echo "<p>Error al agregar la pregunta: " . $conexion->error . "</p>"; // Alerta de error
                    }
                }
            } else {
                echo "<p>Error al crear la lección: " . $conexion->error . "</p>";
            }
        }
    }
}
?>

<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Administrador de Cursos</h2>

    <!-- Formulario para crear una lección con una pregunta -->
    <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
        <h3 class="text-xl font-bold">Crear una Lección</h3>
        <div class="mb-4">
            <label for="curso_id" class="block">Seleccionar Curso:</label>
            <select id="curso_id" name="curso_id" required class="border rounded w-full py-2 px-3" onchange="cargarNiveles(this.value)"> 
                <option value="">Selecciona un curso</option>
                <?php
                // Mostrar los cursos existentes en un select
                $result_cursos = $conexion->query("SELECT id, titulo FROM cursos");
                while ($row_curso = $result_cursos->fetch_assoc()) {
                    echo "<option value='" . $row_curso['id'] . "'>" . $row_curso['titulo'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="nivel_id" class="block">Seleccionar Nivel:</label>
            <select id="nivel_id" name="nivel_id" required class="border rounded w-full py-2 px-3">
                <?php
                // Mostrar los niveles existentes en un select
                $result = $conexion->query("SELECT id, titulo FROM niveles");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['titulo'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="titulo_leccion" class="block">Título de la Lección:</label>
            <input type="text" id="titulo_leccion" name="titulo_leccion" required class="border rounded w-full py-2 px-3">
        </div>
        <div class="mb-4">
            <label for="descripcion_leccion" class="block">Descripción de la Lección:</label>
            <textarea id="descripcion_leccion" name="descripcion_leccion" required class="border rounded w-full py-2 px-3"></textarea>
        </div>
        <div class="mb-4">
            <label for="imagen" class="block">Subir Imagen:</label>
            <input type="file" id="imagen" name="imagen" class="border rounded w-full py-2 px-3" accept="image/*">
        </div>
        <div class="mb-4">
            <label for="audio" class="block">Subir Audio:</label>
            <input type="file" id="audio" name="audio" class="border rounded w-full py-2 px-3" accept="audio/*">
        </div>
        <div class="mb-4">
            <label for="video" class="block">Subir Video:</label>
            <input type="file" id="video" name="video" class="border rounded w-full py-2 px-3" accept="video/*">
        </div>
        <div class="mb-4">
            <label for="duracion" class="block">Duración:</label>
            <input type="text" id="duracion" name="duracion" class="border rounded w-full py-2 px-3">
        </div>

        <!-- Pregunta para la lección -->
        <h3 class="text-lg font-bold mb-4">Agregar Pregunta</h3>

        <div class="mb-4">
            <label for="pregunta" class="block">Pregunta:</label>
            <textarea id="pregunta" name="pregunta" required class="border rounded w-full py-2 px-3"></textarea>
        </div>
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label>Opción 1:</label>
                <input type="text" name="opcion1" required class="border rounded w-full py-2 px-3">
            </div>
            <div>
                <label>Opción 2:</label>
                <input type="text" name="opcion2" required class="border rounded w-full py-2 px-3">
            </div>
            <div>
                <label>Opción 3:</label>
                <input type="text" name="opcion3" required class="border rounded w-full py-2 px-3">
            </div>
            <div>
                <label>Opción 4:</label>
                <input type="text" name="opcion4" required class="border rounded w-full py-2 px-3">
            </div>
        </div>
        <div class="mb-4">
            <label for="correcta">Opción Correcta (1-4):</label>
            <select id="correcta" name="correcta" required class="border rounded w-full py-2 px-3">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>

        <button type="submit" name="crear_leccion" class="bg-green-500 text-white py-2 px-4 rounded">Crear Lección</button>
    </form>

</div>

<script>
    document.getElementById('imagen').addEventListener('change', function () {
        if (this.files.length > 0) {
            document.getElementById('video').disabled = true;
        } else {
            document.getElementById('video').disabled = false;
        }
    });

    document.getElementById('video').addEventListener('change', function () {
        if (this.files.length > 0) {
            document.getElementById('imagen').disabled = true;
        } else {
            document.getElementById('imagen').disabled = false;
        }
    });
</script>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
