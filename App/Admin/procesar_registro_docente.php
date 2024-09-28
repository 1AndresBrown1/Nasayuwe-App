<?php
include_once __DIR__ . '/../header.php';


// Función para cifrar la contraseña
function hashPassword($password) {
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
    $tipo_documento = $_POST['tipo_documento'];
    $telefono = $_POST['telefono'];
    $titulo = $_POST['titulo'];
    $email = $_POST['email'];
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
        $sql = "INSERT INTO docentes (documento_identidad, nombre, apellido, tipo_documento, telefono, titulo, email, fecha_nacimiento, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);

        if (!$stmt) {
            echo '<div class="alert alert-danger" role="alert">Error al preparar la sentencia: ' . $conexion->error . '</div>';
        } else {
            $stmt->bind_param("issssssss", $documento_identidad, $nombre, $apellido, $tipo_documento, $telefono, $titulo, $email, $fecha_nacimiento, $password_hash);

            if ($stmt->execute()) {
                // Registro exitoso, redirigir a docentes.php
                echo '<script>window.location.href = "./docentes?status=success1";</script>';
                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert">Error en la consulta docente: ' . $stmt->error . '</div>';
            }

            $stmt->close();
        }
    }
}
?>
