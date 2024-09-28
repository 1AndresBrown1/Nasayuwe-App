<?php
session_start();
require 'db.php';

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si ya hay una sesión activa
if (!empty($_SESSION['nombre_usuario'])) {
    header('Location: index_docente'); // Redirigir a la página principal del docente si ya está logueado
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificacion = $_POST['identificacion'];
    $contrasena = $_POST['contrasena'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Validar que los campos no estén vacíos
    if (empty($identificacion) || empty($contrasena) || empty($tipo_usuario)) {
        $error_message = 'Por favor, complete todos los campos requeridos.';
    } else {
        // Determinar la tabla y los campos a consultar según el tipo de usuario
        if ($tipo_usuario === 'docente') {
            $query = "SELECT documento_identidad, nombre, email, contrasena, estado FROM docentes WHERE documento_identidad = ?";
        } elseif ($tipo_usuario === 'estudiante') {
            $query = "SELECT documento_identidad, nombre, email, contrasena FROM estudiantes WHERE documento_identidad = ?";
        } else {
            $error_message = 'Tipo de usuario no válido';
            exit();
        }

        // Preparar la consulta para evitar inyección SQL
        if ($stmt = $conexion->prepare($query)) {
            $stmt->bind_param('s', $identificacion);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verificar el estado si es un docente
                if ($tipo_usuario === 'docente' && $row['estado'] !== 'activo') {
                    $error_message = 'Su cuenta no está activa. Por favor, contacte al administrador.';
                } else {
                    // Verificar la contraseña ingresada
                    if (password_verify($contrasena, $row['contrasena'])) {
                        // Iniciar sesión para el usuario
                        $_SESSION['identificacion_usuario'] = $row['documento_identidad'];
                        $_SESSION['email'] = $row['email'];

                        if ($tipo_usuario === 'docente') {
                            $_SESSION['nombre_usuario'] = $row['nombre'];
                            $_SESSION['docente'] = 'docente';
                            $_SESSION['id_docente'] = $_SESSION['identificacion_usuario'];
                            $_SESSION['identificacion'] = $_SESSION['identificacion_usuario'];

                            header('Location: index_docente'); // Redirigir al panel del docente
                        } elseif ($tipo_usuario === 'estudiante') {
                            $_SESSION['nombre_usuario'] = $row['nombre'];
                            $_SESSION['estudiante'] = 'estudiante';
                            $_SESSION['id_estudiante'] = $_SESSION['identificacion_usuario'];
                            $_SESSION['identificacion'] = $_SESSION['identificacion_usuario'];

                            header('Location: dashboard_estudiantes'); // Redirigir al panel del estudiante
                        }
                        exit();
                    } else {
                        $error_message = 'Contraseña incorrecta';
                    }
                }
            } else {
                $error_message = 'Usuario no encontrado';
            }
            $stmt->close();
        } else {
            $error_message = 'Error en la preparación de la consulta';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="bg-white dark:bg-gray-900">
        <div class="flex justify-center h-screen">
            <div class="hidden bg-cover lg:block lg:w-2/3" style="background-image: url(./img/home.jpg)">
                <div class="flex items-center h-full px-20 bg-gray-900 bg-opacity-40">
                    <div></div>
                </div>
            </div>

            <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
                <div class="flex-1">
                    <div class="text-center">
                        <div class="flex justify-center mx-auto">
                            <img class="w-auto h-20 sm:h-20" src="./img/logos/Admin.png" alt="">
                        </div>
                        <p class="mt-3 text-gray-500 dark:text-gray-300">Inicia sesión para acceder a tu cuenta</p>
                    </div>

                    <div class="mt-8">
                        <form action="login" method="post">
                            <?php if (isset($error_message)): ?>
                                <div class="fixed top-4 right-4 z-50 flex w-72 max-w-sm overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800">
                                    <div class="flex items-center justify-center w-12 bg-yellow-500">
                                        <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
                                        </svg>
                                    </div>
                                    <div class="px-4 py-2 -mx-3">
                                        <div class="mx-3">
                                            <span class="font-semibold text-yellow-400 dark:text-yellow-300">Info</span>
                                            <p class="text-sm text-gray-600 dark:text-gray-200">
                                                <?php echo $error_message; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div>
                                <label for="identificacion" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Numero de usuario</label>
                                <input type="number" name="identificacion" id="identificacion" placeholder="12345" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>

                            <div class="mt-6">
                                <div class="flex justify-between mb-2">
                                    <label for="contrasena" class="text-sm text-gray-600 dark:text-gray-200">Contraseña</label>
                                    <a href="#" class="text-sm text-gray-400 focus:text-blue-500 hover:text-blue-500 hover:underline">¿Olvidó su contraseña?</a>
                                </div>

                                <input type="password" name="contrasena" id="contrasena" placeholder="Your Password" class="block w-full px-4 py-2 mt-2 mb-6 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />

                                <label for="contrasena" class="text-sm text-gray-600 dark:text-gray-200">Selecciona el tipo de usuario:</label>
                                <select id="tipo_usuario" name="tipo_usuario" autocomplete="tipo_usuario" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40">
                                    <option value="docente">Docente</option>
                                    <option value="estudiante">Estudiante</option>
                                </select>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                    Inicia Sección
                                </button>
                            </div>
                        </form>

                        <p class="mt-6 text-sm text-center text-gray-400">Ir como <a href="loginA" class="text-blue-500 focus:outline-none focus:underline hover:underline">Administrador</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
