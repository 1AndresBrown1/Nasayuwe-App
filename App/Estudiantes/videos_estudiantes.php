<?php

include_once __DIR__ . '/../Estudiantes/header.php';

// Obtener la categoría seleccionada, si está presente
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Obtener el video_id de la URL, si está presente
$currentVideoId = isset($_GET['video_id']) ? intval($_GET['video_id']) : 1;

// Modificar la consulta para incluir el filtro de categoría si se ha seleccionado una
if ($category_filter !== '') {
    $query = mysqli_query($conn, "SELECT * FROM `video` WHERE `video_id` = $currentVideoId AND `category` = '$category_filter'") or die(mysqli_error($conn));
} else {
    $query = mysqli_query($conn, "SELECT * FROM `video` WHERE `video_id` = $currentVideoId") or die(mysqli_error($conn));
}

// Si existe el video actual, obtener los IDs del anterior y siguiente
if ($fetch = mysqli_fetch_array($query)) {

    // Obtener el video anterior
    $previousQuery = mysqli_query($conn, "SELECT video_id FROM `video` WHERE video_id < $currentVideoId" . ($category_filter !== '' ? " AND `category` = '$category_filter'" : "") . " ORDER BY video_id DESC LIMIT 1");
    $previousVideo = mysqli_fetch_assoc($previousQuery);
    $previousVideoId = $previousVideo ? $previousVideo['video_id'] : null;

    // Obtener el siguiente video
    $nextQuery = mysqli_query($conn, "SELECT video_id FROM `video` WHERE video_id > $currentVideoId" . ($category_filter !== '' ? " AND `category` = '$category_filter'" : "") . " ORDER BY video_id ASC LIMIT 1");
    $nextVideo = mysqli_fetch_assoc($nextQuery);
    $nextVideoId = $nextVideo ? $nextVideo['video_id'] : null;
?>

    <div class="container mx-auto p-4 mt-20 sm:mt-6">
        <!-- Formulario para filtrar videos por categoría -->
        <form method="GET" action="videos_estudiantes" class="mb-5">
            <div class="form-control w-full max-w-xs">
                <label for="category_filter" class="label">
                    <span class="label-text">Filtrar por Categoría:</span>
                </label>
                <select name="category" id="category_filter" class="select select-bordered" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <option value="Educación" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Educación') ? 'selected' : ''; ?>>Educación</option>
                    <option value="Entretenimiento" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Entretenimiento') ? 'selected' : ''; ?>>Entretenimiento</option>
                    <option value="Tecnología" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Tecnología') ? 'selected' : ''; ?>>Tecnología</option>
                    <option value="Otros" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Otros') ? 'selected' : ''; ?>>Otros</option>
                </select>
            </div>
        </form>

        <div class="mockup-window bg-base-300 border">
            <h5 class="font-bold p-3 ms-4 mb-3"><?php echo $fetch['video_name'] ?></h5>

            <div class="bg-base-200 flex justify-center">
                <div class="w-full h-full">
                    <?php
                    function getYouTubeVideoId($url)
                    {
                        $query_string = parse_url($url, PHP_URL_QUERY);
                        parse_str($query_string, $params);
                        return isset($params['v']) ? $params['v'] : false;
                    }

                    if (strpos($fetch['location'], 'youtube.com') !== false) {
                        $video_id = getYouTubeVideoId($fetch['location']);
                        if ($video_id !== false) {
                            echo '<iframe style="height: 65vh;" class="w-full" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
                        } else {
                            echo '<p class="text-red-500">Error: No se pudo obtener el ID del video de la URL de YouTube.</p>';
                        }
                    } else {
                        echo '<p class="text-red-500">Error: El enlace proporcionado no es una URL de YouTube.</p>';
                    }
                    ?>
                    <div class="mt-1 p-4">
                        <p class="text-xl"><?php echo $fetch['descripcion']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div class="join">
                <a href="?video_id=<?php echo $previousVideoId; ?>&category=<?php echo urlencode($category_filter); ?>" class="btn join-item py-2 px-4 <?php echo !$previousVideoId ? ' cursor-not-allowed' : ''; ?>" <?php echo !$previousVideoId ? 'disabled' : ''; ?>>Anterior</a>
                <a href="?video_id=<?php echo $nextVideoId; ?>&category=<?php echo urlencode($category_filter); ?>" class="btn join-item py-2 px-4 <?php echo !$nextVideoId ? ' cursor-not-allowed' : ''; ?>" <?php echo !$nextVideoId ? 'disabled' : ''; ?>>Siguiente</a>
            </div>
            <a href="uppdf?video_id=<?php echo $currentVideoId; ?>&category=<?php echo urlencode($category_filter); ?>" class="btn hover:bg-red-900 join-item bg-red-500 text-white py-2 px-4 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M5.478 5.559A1.5 1.5 0 0 1 6.912 4.5H9A.75.75 0 0 0 9 3H6.912a3 3 0 0 0-2.868 2.118l-2.411 7.838a3 3 0 0 0-.133.882V18a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0 0 17.088 3H15a.75.75 0 0 0 0 1.5h2.088a1.5 1.5 0 0 1 1.434 1.059l2.213 7.191H17.89a3 3 0 0 0-2.684 1.658l-.256.513a1.5 1.5 0 0 1-1.342.829h-3.218a1.5 1.5 0 0 1-1.342-.83l-.256-.512a3 3 0 0 0-2.684-1.658H3.265l2.213-7.191Z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v6.44l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 0 1 1.06-1.06l1.72 1.72V3a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                </svg>
                <span>Subir Tarea</span>
            </a>
        </div>
    </div>

<?php
} else {
    echo '
    <div class="w-full text-white bg-red-500">
        <div class="container flex items-center justify-between px-6 py-4 mx-auto">
            <div class="flex items-center">
                <svg viewBox="0 0 40 40" class="w-6 h-6 fill-current">
                    <path d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z"></path>
                </svg>
                <p class="mx-3">El video no existe o ha sido borrado por el docente encargado!</p>
            </div>
            <form action="?video_id=1" method="get">
                <button class="p-1 transition-colors duration-300 transform rounded-md hover:bg-opacity-25 hover:bg-gray-600 focus:outline-none">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Volver
                </button>
            </form>
        </div>
    </div>';
}
?>

<?php
include_once __DIR__ . '/../Estudiantes/footer.php';
?>
