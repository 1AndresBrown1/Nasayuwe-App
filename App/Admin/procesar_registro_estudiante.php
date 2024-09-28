<?php
include_once __DIR__ . '/../header.php';
// Función para cifrar la contraseña
function hashPassword($password)
{
    // Hash de la contraseña
    $options = [
        'cost' => 12,
    ];
    $password_hash = password_hash($password, PASSWORD_BCRYPT, $options);
    return $password_hash;
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $documento_identidad = $_POST['documento_identidad'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $genero = $_POST['genero'];
    $eemail = $_POST['eemail'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $contrasena = $_POST['contrasena'];
    $verificarContrasena = $_POST['verificarContrasena'];

    // Validar si las contraseñas coinciden
    if ($contrasena !== $verificarContrasena) {
        echo "Las contraseñas no coinciden. Por favor, verifica.";
    } else {
        // Hash de la contraseña
        $password_hash = hashPassword($contrasena);

        // Preparar y ejecutar la consulta de inserción con consulta preparada
        $sql = "INSERT INTO estudiantes (documento_identidad, nombre, apellido, genero, email, telefono,  fecha_nacimiento, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            echo '<div class="alert alert-danger" role="alert">Error al preparar la sentencia: ' . $conexion->error . '</div>';
        } else {
            $stmt->bind_param("isssssss", $documento_identidad, $nombre, $apellido, $genero, $eemail, $telefono,  $fecha_nacimiento, $password_hash);

            if ($stmt->execute()) {
                // Registro exitoso, redirigir a estudiantes.php
                echo '<script>window.location.href = "estudiantes?status=success1";</script>';
                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert">Error en la consulta estudiante: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }
    }
}
