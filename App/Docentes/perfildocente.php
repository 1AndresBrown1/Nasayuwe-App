<?php
include_once __DIR__ . '/../Docentes/header.php';


// Función para obtener los datos del usuario desde la sesión
function obtenerDatosDocente($documento_identidad) {
    global $conexion; // Asumiendo que $conexion está disponible en este contexto

    $sql = "SELECT nombre, apellido, telefono, email FROM docentes WHERE documento_identidad=?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $documento_identidad);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre, $apellido, $telefono, $email);

    // Si no se encontraron resultados, devuelve un array vacío
    if (!mysqli_stmt_fetch($stmt)) {
        return array();
    }

    // Devuelve los datos del docente en un array asociativo
    return array(
        'nombre' => $nombre,
        'apellido' => $apellido,
        'telefono' => $telefono,
        'email' => $email
    );
}

// Obtener los datos del docente desde la sesión si existen
$documento_identidad = $_SESSION['identificacion'];
$datosDocente = obtenerDatosDocente($documento_identidad);

// Asignar los datos obtenidos a las variables correspondientes
$nombre = isset($datosDocente['nombre']) ? $datosDocente['nombre'] : '';
$apellido = isset($datosDocente['apellido']) ? $datosDocente['apellido'] : '';
$telefono = isset($datosDocente['telefono']) ? $datosDocente['telefono'] : '';
$email = isset($datosDocente['email']) ? $datosDocente['email'] : '';

// Procesar el formulario de actualización cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados del formulario
    $nuevos_nombre = $_POST['nombre'];
    $nuevos_apellidos = $_POST['apellidos'];
    $nuevo_telefono = $_POST['telefono'];
    $nuevo_email = $_POST['email'];

    // Realizar la actualización en la base de datos
    $sql = "UPDATE docentes SET nombre=?, apellido=?, telefono=?, email=? WHERE documento_identidad=?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $nuevos_nombre, $nuevos_apellidos, $nuevo_telefono, $nuevo_email, $documento_identidad);

    if (mysqli_stmt_execute($stmt)) {
        // Actualización exitosa
        $_SESSION['nombre'] = $nuevos_nombre; // Actualizar datos en la sesión
        $_SESSION['apellido'] = $nuevos_apellidos;
        $_SESSION['telefono'] = $nuevo_telefono;
        $_SESSION['email'] = $nuevo_email;

        // Mostrar la alerta de actualización exitosa
        echo '<script>alert("Los datos han sido actualizados correctamente.");</script>';
        
        // Redirigir a la misma página después de un breve tiempo para evitar envíos duplicados
        echo '<script>
                setTimeout(function() {
                    window.location.href = "perfildocente";
                }, 1000);  // Esperar 1 segundo antes de redirigir
              </script>';
    } else {
        // Error al ejecutar la consulta de actualización
        echo '<script>alert("Error al actualizar el perfil. Por favor, intenta nuevamente.");</script>';
    }
    // Cerrar la consulta
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <!-- Encabezado y estilos -->
    <script type="module" src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<style>
    .text-uppercase {
        text-transform: uppercase;
    }
</style>

<body>

<section class="container mx-auto w-9/12 h-screen flex flex-col justify-center mt-0">
    <h1 class="text-3xl mb-10 font-bold">Editar Perfil de <?php echo $_SESSION['nombre_usuario']; ?></h1>

    <form method="post" action="perfildocente">

        <div class="flex flex-col gap-2 md:flex-row">
            <div>
                <label for="nombre" class="block text-sm">Nombre</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input value="<?php echo $nombre; ?>" type="text" placeholder="Nombre" id="nombre" name="nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

            <div>
                <label for="apellidos" class="block text-sm">Apellidos</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="apellidos" name="apellidos" value="<?php echo $apellido; ?>" type="text" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

            <div>
                <label for="telefono" class="block text-sm">Teléfono</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="telefono" name="telefono" value="<?php echo $telefono; ?>" type="number" placeholder="Teléfono" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm">Correo</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>
                    </span>
                    <input id="email" name="email" value="<?php echo $email; ?>" type="email" placeholder="Correo@gmail.com" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>
        </div>

        <button type="submit" class="w-40 mt-5 flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
            <svg class="w-5 h-5 mx-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
            </svg>

            <span class="mx-1">Actualizar</span>
        </button>
    </form>
</section>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
