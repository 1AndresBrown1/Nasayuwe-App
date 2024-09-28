<?php
include_once __DIR__ . '/../Docentes/header.php';
?>
<div class="w-full bg-center bg-cover h-screen p-4" style="background-image: url(./img/home.jpg)">
    <div class="flex items-center justify-center w-full h-full">


        <div class="text-center">
           
            <h1 class="text-3xl font-semibold text-white  lg:text-4xl mb-2">Bienvenido Docente: <?php echo $_SESSION['nombre_usuario'] ?></h1>
           
            <button class="w-full px-5 text-white py-2 mt-4 text-lg font-medium  capitalize transition-colors duration-300 transform bg-blue-600 rounded-md lg:w-auto hover:bg-blue-500 focus:outline-none focus:bg-blue-500">Ver Cursos</button>
        </div>


    </div>
</div>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>