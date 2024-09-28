<?php
session_start();
include_once __DIR__ . '/../Docentes/header.php';

if (isset($_POST['submit'])) {
    $videoId = $_POST['video_id'];
    $newDescription = $_POST['new_description'];

    // Actualizar la descripción del video en la base de datos
    $updateQuery = "UPDATE video_alf SET descripcion = '$newDescription' WHERE video_alf.video_id = $videoId";
    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        // Redirect back to video_alf.php with a success parameter
        header("Location: videos_alf_docente?status=success");
        exit();
    } else {
        // Redirect back to video_alf.php with an error parameter
        header("Location: videos_alf_docente?status=error");
        exit();
    }
} else {
    // If no form submission, redirect back to video_alf.php
    header("Location: videos_alf_docente");
    exit();
}
?>