<?php
include_once __DIR__ . '/../header.php';

if (isset($_GET['video_id'])) {
    // Sanitizar el ID del video para evitar inyecciones SQL
    $videoId = intval($_GET['video_id']); 

    // Realizar la eliminación del video en la base de datos
    $deleteQuery = "DELETE FROM video WHERE video_id = $videoId";
    $result = mysqli_query($conn, $deleteQuery);

    if ($result) {
        // Redirigir de vuelta a videos_alf_admin.php con un parámetro de éxito
        header("Location: videos_admin?delete_status=success");
        exit();
    } else {
        // Registrar el error en el log y redirigir con un parámetro de error
        error_log("Error al eliminar el video con ID: $videoId. Error: " . mysqli_error($conn));
        header("Location: videos_admin?delete_status=error");
        exit();
    }
} else {
    // Si no se proporciona el parámetro video_id, redirigir de vuelta
    header("Location: videos_admin");
    exit();
}
?>
