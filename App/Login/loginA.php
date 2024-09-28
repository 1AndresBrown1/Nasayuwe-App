<?php
session_start();
require 'db.php';

if (!empty($_SESSION['usuario'])) {
    header('Location: dashboard');
    exit();
}

if (!empty($_POST['name']) && !empty($_POST['password'])) {
    $input = $_POST['name']; // This can be name, email, or id_usuario
    $password = $_POST['password'];

    // Evitar la inyección de SQL utilizando sentencias preparadas
    $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE name = ? OR email = ? OR id_usuario = ?');
    $stmt->bind_param('sss', $input, $input, $input); // 'sss' indicates that all parameters are of type string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['identificacion'] = $row['id_usuario'];

        if (password_verify($password, $row['contrasena'])) {
            $_SESSION['nombre_usuario'] = $row['name'];

            $_SESSION['admin'] = 'admin';
            header("Location: dashboard");
            exit();
        } else {
            $error_message = 'Usuario no registrado o contraseña incorrecta';
        }
    } else {
        $error_message = 'Usuario no registrado o contraseña incorrecta';
    }

    $stmt->close();
} else {
    $error_message = 'Por favor, ingrese nombre de usuario y contraseña.';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <section class="bg-white dark:bg-gray-900">
        <div class="container flex flex-col items-center justify-center min-h-screen px-6 mx-auto">
            <div class="flex justify-center mx-auto">
                <img class="w-auto h-20 sm:h-20" src="./img/logos/Admin.png" alt="">
            </div>

            <h1 class="mt-4 text-2xl font-semibold tracking-wide text-center text-gray-800 capitalize md:text-3xl dark:text-white">
                Bienvenido </h1>


            <div class="w-full max-w-md mx-auto mt-6">
                <form action="loginA" method="POST">

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


                    <div class="mb-3">
                        <label for="name" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Numero de usuario</label>
                        <input type="text" name="name" id="name" placeholder="12345" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                    </div>

                    <button type="submit" class="w-full px-6 py-3 mt-4 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                        Iniciar Sesión </button>

                </form>

                <div class="mt-6 text-center">
                    <a href="login" class="text-sm text-blue-500 hover:underline dark:text-blue-400">o Volver a Docente/Estudiante</a>
                </div>
            </div>
        </div>
    </section>

</body>

</html>