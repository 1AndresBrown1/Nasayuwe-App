<?php
include_once __DIR__ . '/../Estudiantes/header.php';

// Verifica si hay una sesión activa
if (!isset($_SESSION['nombre_usuario'])) {
    // Redirigir a la página de inicio de sesión si no hay sesión
    header('Location: login.php');
    exit();
}

// Asegúrate de que el documento de identidad esté en la sesión
if (!isset($_SESSION['identificacion'])) {
    die("No se pudo obtener el documento de identidad. Por favor, inicie sesión nuevamente.");
}

// Inicializa la variable $pdfExists
$pdfExists = false;
$res = array();

// Obtener el video_id de la URL, si está presente
$currentVideoId = isset($_GET['video_id']) ? intval($_GET['video_id']) : 1;

// Verificar si ya hay un archivo PDF asociado con un id igual o mayor al video_id actual
$sel = $conn->query("SELECT * FROM files WHERE vid >= $currentVideoId AND documento_identidad = " . $_SESSION['identificacion']);
while ($row = $sel->fetch_assoc()) {
    $res[] = $row;
    if ($row['vid'] >= $currentVideoId) {
        $pdfExists = true;
    }
}
?>


<div class="container mx-auto mt-20 sm:mt-6">
    <div class="mx-auto w-9/12 flex flex-col justify-center">
        <h2 class="text-2xl font-bold">Subir Archivo PDF</h2>
        <?php if ($pdfExists && !empty($res[0]['url'])): ?>
            <p class="my-4">Ya has subido un archivo PDF para este video.</p>
            <a class="link mt-3 btn" href="<?php echo $res[0]['url']; ?>" target="_blank">Ver PDF Subido</a><br><br>
        <?php else: ?>
            <form action="upload" method="post" enctype="multipart/form-data">
                <input type="hidden" name="vid" value="<?php echo $currentVideoId; ?>">

                <div class="mt-4">
                    <label for="title" class="block text-lg font-medium">Título:</label>
                    <input type="text" name="title" id="title" class="input input-bordered w-full max-w-xs my-3" required>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-lg font-medium">Descripción:</label>
                    <textarea name="description" id="description" rows="4" class="input input-bordered w-full max-w-xs my-3"></textarea>
                </div>

                <div class="my-4">

                    <label for="dropzone-file" class="flex flex-col items-center w-full max-w-lg p-5 mt-2 text-center  border-2 border-gray-300 border-dashed cursor-pointer  dark:border-gray-700 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-500 dark:text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>

                        <h2 class="mt-1 font-medium tracking-wide">Seleccionar Archivo PDF</h2>

                        <p class="mt-2 text-xs tracking-wide">Sube o arrastra aquí tu archivo PDF.</p>

                        <input id="dropzone-file" type="file" name="file" accept="application/pdf" class="hidden" required />
                    </label>
                </div>


                <div class="mt-6">
                    <input type="submit" value="Subir Archivo" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 cursor-pointer">
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php
include_once __DIR__ . '/../Estudiantes/footer.php';
?>