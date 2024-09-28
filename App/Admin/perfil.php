<?php
include_once __DIR__ . '/../header.php';

// Función para obtener los datos del usuario desde la sesión
function obtenerDatosUsuario($identificacion)
{
    global $conexion; // Asumiendo que $conexion está disponible en este contexto

    $sql = "SELECT name, last_name, telefono, email FROM usuarios WHERE id_usuario=?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $identificacion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $name, $last_name, $telefono, $email);

    // Si no se encontraron resultados, devuelve un array vacío
    if (!mysqli_stmt_fetch($stmt)) {
        return array();
    }

    // Devuelve los datos del usuario en un array asociativo
    return array(
        'name' => $name,
        'last_name' => $last_name,
        'telefono' => $telefono,
        'email' => $email
    );
}

// Obtener los datos del usuario desde la sesión si existen
$id_usuario = $_SESSION['identificacion'];
$datosUsuario = obtenerDatosUsuario($id_usuario);

// Asignar los datos obtenidos a las variables correspondientes
$name = isset($datosUsuario['name']) ? $datosUsuario['name'] : '';
$last_name = isset($datosUsuario['last_name']) ? $datosUsuario['last_name'] : '';
$telefono = isset($datosUsuario['telefono']) ? $datosUsuario['telefono'] : '';
$email = isset($datosUsuario['email']) ? $datosUsuario['email'] : '';

// Procesar el formulario de actualización cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados del formulario
    $nuevos_name = $_POST['name'];
    $nuevos_apellidos = $_POST['apellidos'];
    $nuevo_telefono = $_POST['telefono'];
    $nuevo_email = $_POST['email'];

    // Realizar la actualización en la base de datos
    $sql = "UPDATE usuarios SET name=?, last_name=?, telefono=?, email=? WHERE id_usuario=?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $nuevos_name, $nuevos_apellidos, $nuevo_telefono, $nuevo_email, $id_usuario);

    if (mysqli_stmt_execute($stmt)) {
        // Actualización exitosa
        $_SESSION['name'] = $nuevos_name; // Actualizar datos en la sesión
        $_SESSION['last_name'] = $nuevos_apellidos;
        $_SESSION['telefono'] = $nuevo_telefono;
        $_SESSION['email'] = $nuevo_email;

        // Mostrar la alerta de actualización exitosa
        echo '<script>alert("Los datos han sido actualizados correctamente.");</script>';
        
        // Redirigir a la misma página después de un breve tiempo para evitar envíos duplicados
        echo '<script>
                setTimeout(function() {
                    window.location.href = "index.php";
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



<section class="container mx-auto w-9/12 h-screen flex flex-col justify-center  mt-0">
    <h1 class="text-3xl mb-10 font-bold">Editar Perfil</h1>

    <form method="post" action="perfiladmin">

        <div class="flex flex-col gap-2 md:flex-row">
            <div>
                <label for="nombre" class="block text-sm">Nombre</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input value="<?php echo $name; ?>" type="text" placeholder="Nombre" id="name" name="name" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

            <div>
                <label for="apellido" class="block text-sm">Apellido</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="apellidos" name="apellidos" value="<?php echo $last_name; ?>" type="text" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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

            <div>
                <label for="telefono" class="block text-sm">Teléfono</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input id="telefono" name="telefono"  value="<?php echo $telefono; ?>" type="number" placeholder="Teléfono" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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


<div class="fixed bottom-0 bg-base-300 right-0 m-4 w-80 max-w-sm overflow-hidden rounded-lg shadow-md sm:bottom-auto sm:top-0 sm:right-0">
    <div class="flex">
        <div class="flex items-center justify-center w-12 bg-blue-500">
            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
            </svg>
        </div>

        <div class="px-4 py-2 -mx-3">
            <div class="mx-3">
                <span class="font-semibold">Editar</span>
                <p class="text-lg">
                    <strong><?php echo $_SESSION['nombre_usuario']; ?></strong>
                </p>
            </div>
        </div>
    </div>
</div>


<?php
include_once __DIR__ . '/../footer.php';
?>