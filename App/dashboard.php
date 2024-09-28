<?php
include_once __DIR__ . '/../App/header.php';


// error_reporting(0);


// Verifica si ya hay una sesión activa
if (isset($_SESSION['nombre_usuario'])) {
    // Verifica si el usuario es un administrador
    if ($_SESSION['admin'] == 'admin') {
        // Si el usuario es un administrador, rediríjalo al index.php
        $message = 'Administrador';
    } else {
        // Si el usuario no es un administrador, rediríjalo a la página correspondiente según el tipo de usuario
        if ($_SESSION['docente'] === 'docente') {
            header("Location: index_docentes.php");
            exit();
        } elseif ($_SESSION['estudiante'] === 'estudiante') {
            header("Location: index_estudiantes.php");
            exit();
        }
    }
} else {
    // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
    header('Location: login');
    exit();
}
?>


<div class="w-full h-screen bg-center bg-cover h-[38rem]" style="background-image: url(./img/home.jpg)">
    <div class="flex items-center justify-center w-full h-full bg-gray-900/40">
        <div class="text-center">
            <h1 class="text-3xl font-semibold text-white lg:text-4xl">Bienvenido: <?php echo $_SESSION['nombre_usuario'] ?></h1>
            <button class="px-5 py-2 mt-4 text-sm font-medium text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-md lg:w-auto hover:bg-blue-500 focus:outline-none focus:bg-blue-500">Crear curso</button>
        </div>
    </div>
</div>


</section>

<?php include_once __DIR__ . '/../App/footer.php'; ?>