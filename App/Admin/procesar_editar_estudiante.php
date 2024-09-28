<?php
include_once __DIR__ . '/../header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento_identidad = $_POST["documento_identidad"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $genero = $_POST["genero"];
    $eemail = $_POST['email'];
    $telefono = $_POST["telefono"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $contrasena = $_POST["contrasena"];

    // Hash de la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Actualizar los datos del estudiante
    $sql = "UPDATE estudiantes SET nombre = ?, apellido = ?, genero = ?, email = ?, telefono = ?, fecha_nacimiento = ?, contrasena = ? WHERE documento_identidad = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssi", $nombre, $apellido, $genero, $eemail, $telefono, $fecha_nacimiento, $contrasena_hash, $documento_identidad);
    $stmt->execute();
    $stmt->close();

    // Redirigir a la página de detalles del estudiante
    header("Location: estudiantes?documento_identidad=$documento_identidad");
    exit;
}
?>