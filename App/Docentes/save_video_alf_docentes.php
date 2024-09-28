<?php
date_default_timezone_set('America/Mexico_City');
include_once __DIR__ . '/../Docentes/header.php';

if (isset($_POST['save'])) {
    $video_url = $_POST['video_url'];
    $video_name = $_POST['video_name'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : ''; // Asegurar que se defina la descripción
    $category = isset($_POST['category']) ? $_POST['category'] : ''; // Asegurar que se defina la categoría

    // Función para extraer el ID del video de YouTube
    function getYouTubeVideoId($url) {
        $query_string = parse_url($url, PHP_URL_QUERY);
        parse_str($query_string, $params);
        return isset($params['v']) ? $params['v'] : false;
    }

    // Verificar si el enlace es de YouTube
    if (strpos($video_url, 'youtube.com') !== false) {
        // Extraer el ID del video de YouTube del URL
        $video_id = getYouTubeVideoId($video_url);

        if ($video_id) {
            $location = 'https://www.youtube.com/watch?v=' . $video_id;

            // Usar una sentencia preparada para insertar el video en la base de datos
            $stmt = $conn->prepare("INSERT INTO `video_alf` (video_name, location, descripcion, category) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $video_name, $location, $descripcion, $category);

            if ($stmt->execute()) {
                header("Location: videos_alf_docente?status1=success1");
            } else {
                header("Location: videos_alf_docente?status1=error1");
            }

            $stmt->close();
        } else {
            header("Location: videos_alf_docente?status1=error1");
        }
    } else {
        // Si no es un enlace de YouTube, mostrar un mensaje de error
        header("Location: videos_alf_docente?status1=error1");
    }
}
?>
