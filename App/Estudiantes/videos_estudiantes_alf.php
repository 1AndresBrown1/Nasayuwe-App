<?php
include_once __DIR__ . '/../Estudiantes/header.php';

// Obtener la categoría seleccionada, si está presente
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Obtener el video_id actual de la URL, si está presente
$currentVideoId = isset($_GET['video_id']) ? intval($_GET['video_id']) : null;

// Modificar la consulta para incluir el filtro de categoría si se ha seleccionado una
if ($category_filter !== '') {
    // Si hay un filtro de categoría, obtener el video actual dentro de esa categoría
    $query = mysqli_query($conn, "SELECT * FROM `video_alf` WHERE `category` = '$category_filter' ORDER BY `video_id` ASC") or die(mysqli_error($conn));
} else {
    // Si no hay un filtro de categoría, obtener todos los videos
    $query = mysqli_query($conn, "SELECT * FROM `video_alf` ORDER BY `video_id` ASC") or die(mysqli_error($conn));
}

$videos = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Si hay videos, determinar cuál mostrar
if ($videos) {
    // Si no se ha especificado un video_id en la URL, tomar el primer video de la lista
    if (is_null($currentVideoId)) {
        $currentVideoId = $videos[0]['video_id'];
    }

    // Filtrar el video actual
    $currentVideo = null;
    foreach ($videos as $video) {
        if ($video['video_id'] == $currentVideoId) {
            $currentVideo = $video;
            break;
        }
    }

    // Si no se encontró el video actual en la categoría, mostrar el primer video de la lista
    if (!$currentVideo) {
        $currentVideo = $videos[0];
        $currentVideoId = $currentVideo['video_id'];
    }

    // Determinar el video anterior y siguiente
    $previousVideoId = null;
    $nextVideoId = null;
    foreach ($videos as $index => $video) {
        if ($video['video_id'] == $currentVideoId) {
            if (isset($videos[$index - 1])) {
                $previousVideoId = $videos[$index - 1]['video_id'];
            }
            if (isset($videos[$index + 1])) {
                $nextVideoId = $videos[$index + 1]['video_id'];
            }
            break;
        }
    }

    ?>
    <div class="container mx-auto p-4 mt-20 sm:mt-6">
        <!-- Formulario para filtrar videos por categoría -->
        <form method="GET" action="videos_estudiantes_alf" class="mb-5">
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
            <input type="hidden" name="video_id" value="<?php echo $currentVideoId; ?>">
        </form>

        <div class="mockup-window bg-base-300 border">
            <h5 class="font-bold p-3 ms-4 mb-3"><?php echo $currentVideo['video_name'] ?></h5>

            <div class="bg-base-200 flex justify-center">
                <div class="w-full h-full">
                    <?php
                    // Verificar si la función getYouTubeVideoId() ya está definida
                    if (!function_exists('getYouTubeVideoId')) {
                        function getYouTubeVideoId($url)
                        {
                            $query_string = parse_url($url, PHP_URL_QUERY);
                            parse_str($query_string, $params);
                            if (isset($params['v']) && !empty($params['v'])) {
                                return $params['v'];
                            } else {
                                return false;
                            }
                        }
                    }

                    // Verificar si la ubicación del video es una URL de YouTube
                    if (strpos($currentVideo['location'], 'youtube.com') !== false) {
                        // Si es una URL de YouTube, extraer el ID del video
                        $video_id = getYouTubeVideoId($currentVideo['location']);
                        // Verificar si se pudo obtener el ID del video correctamente
                        if ($video_id !== false) {
                            // Si se obtuvo correctamente, incrustar el reproductor de YouTube
                            echo '<iframe style="height: 65vh;" class="w-full" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
                        } else {
                            // Si no se pudo obtener el ID del video, mostrar un mensaje de error
                            echo '<p class="text-red-500">Error: No se pudo obtener el ID del video de la URL de YouTube.</p>';
                        }
                    } else {
                        // Si no es una URL de YouTube, mostrar un mensaje de error
                        echo '<p class="text-red-500">Error: El enlace proporcionado no es una URL de YouTube.</p>';
                    }
                    ?>
                    <div class="mt-1 p-4">
                        <p class="text-xl"><?php echo $currentVideo['descripcion']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div class="join">
                <!-- Asegurarse de que el botón "Anterior" esté deshabilitado si no hay un video anterior -->
                <a href="?video_id=<?php echo $previousVideoId ? $previousVideoId : $currentVideoId; ?>&category=<?php echo urlencode($category_filter); ?>" class="btn join-item py-2 px-4 <?php echo !$previousVideoId ? ' cursor-not-allowed' : ''; ?>">Anterior</a>

                <!-- Asegurarse de que el botón "Siguiente" esté deshabilitado si no hay un video siguiente -->
                <a href="?video_id=<?php echo $nextVideoId ? $nextVideoId : $currentVideoId; ?>&category=<?php echo urlencode($category_filter); ?>" class="btn join-item py-2 px-4 <?php echo !$nextVideoId ? ' cursor-not-allowed' : ''; ?>">Siguiente</a>
            </div>
            <a href="uppdf_alf?video_id=<?php echo $currentVideoId; ?>&category=<?php echo urlencode($category_filter); ?>" class="btn hover:bg-red-900 join-item bg-red-500 text-white py-2 px-4 flex items-center space-x-2">
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
    // Si no se encuentra el video, muestra un mensaje
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
    </div>
    ';
}
?>

<?php
include_once __DIR__ . '/../Estudiantes/footer.php';
?>
