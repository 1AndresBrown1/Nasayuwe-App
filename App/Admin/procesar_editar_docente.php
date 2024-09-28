<?php
include_once __DIR__ . '/../header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento_identidad = $_POST['documento_identidad'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $tipo_documento = $_POST['tipo_documento'];
    $telefono = $_POST['telefono'];
    $titulo = $_POST['titulo'];
    $email = $_POST['email'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $contrasena = $_POST['contrasena'];
    $estado = $_POST['estado']; // Nuevo campo

    // Hash de la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Actualizar los datos del docente
    $sql = "UPDATE docentes SET nombre = ?, apellido = ?, tipo_documento = ?, telefono = ?, titulo = ?, email = ?, fecha_nacimiento = ?, contrasena = ?, estado = ? WHERE documento_identidad = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssssss", $nombre, $apellido, $tipo_documento, $telefono, $titulo, $email, $fecha_nacimiento, $contrasena_hash, $estado, $documento_identidad);
    $stmt->execute();
    $stmt->close();

    // Redirigir a la página de detalles del docente
    header("Location: docentes?documento_identidad=$documento_identidad");
    exit;
}
?>
