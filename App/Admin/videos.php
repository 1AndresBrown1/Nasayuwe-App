<?php
include_once __DIR__ . '/../header.php';

// Mostrar notificaciones según los parámetros de la URL
function showNotification($status, $type, $message)
{
    if (isset($_GET[$status])) {
        if ($_GET[$status] === $type) {
            echo "<script>
                    Swal.fire({
                        title: '" . ucfirst($type) . "',
                        text: '$message',
                        icon: '$type',
                        showConfirmButton: false,
                        timer: 1500
                    });
                  </script>";
        }
    }
}

showNotification('status', 'success', 'Descripción del video actualizada exitosamente');
showNotification('status', 'error', 'Error al actualizar la descripción del video');
showNotification('status1', 'success1', 'Se guardó con éxito el video');
showNotification('status1', 'error1', 'Error al cargar archivo: es muy largo');
showNotification('delete_status', 'success', 'Video eliminado exitosamente');
showNotification('delete_status', 'error', 'Error al eliminar el video');

?>

<div class="container p-4 mt-20 sm:mt-6">
    <h1 class="font-bold text-3xl mb-5">Subir Videos</h1>

    <!-- Filtro de categorías -->
    <form method="GET" action="videos_admin" class="mb-5">
        <div class="flex gap-4 items-center">
            <label for="category" class="font-medium">Filtrar por Categoría:</label>
            <select name="category" class="input input-bordered" id="category" onchange="this.form.submit()">
                <option value="">Todas</option>
                <option value="Educación" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Educación') ? 'selected' : ''; ?>>Educación</option>
                <option value="Entretenimiento" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Entretenimiento') ? 'selected' : ''; ?>>Entretenimiento</option>
                <option value="Tecnología" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Tecnología') ? 'selected' : ''; ?>>Tecnología</option>
                <option value="Otros" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Otros') ? 'selected' : ''; ?>>Otros</option>
            </select>
        </div>
    </form>

    <!-- Botones para abrir modales -->
    <button class="btn btn-Success me-2" onclick="document.getElementById('my_modal_5').showModal()">Agregar Video</button>
    <a href="uppdf_admin">
        <button class="btn btn-error">Estudiantes</button>
    </a>
    <hr class="mt-10">

    <!-- Mostrar videos de la base de datos -->
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $filter = "";
            if (isset($_GET['category']) && $_GET['category'] !== '') {
                $category = mysqli_real_escape_string($conn, $_GET['category']);
                $filter = "WHERE category='$category'";
            }

            $result = mysqli_query($conn, "SELECT * FROM `video` $filter ORDER BY `video_id` ASC") or die(mysqli_error($conn));

            // Función para obtener el ID del video de YouTube
            function getYouTubeVideoId($url)
            {
                parse_str(parse_url($url, PHP_URL_QUERY), $params);
                return $params['v'] ?? false;
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $video_id = getYouTubeVideoId($row['location']);
                if ($video_id !== false) {
            ?>
                    <div class="rounded-lg shadow-md overflow-hidden">
                        <iframe class="w-full h-56" src="https://www.youtube.com/embed/<?php echo $video_id; ?>" frameborder="0" allowfullscreen></iframe>
                        <div class="p-4">
                            <h2 class="text-lg font-semibold"><?php echo $row['video_name']; ?></h2>
                            <p class=""><?php echo $row['descripcion']; ?></p>
                            <p class="font-medium"><strong>Categoría:</strong> <?php echo $row['category']; ?></p>
                            <div class="flex gap-2 mt-4">
                                <!-- Botón para abrir el modal de edición -->
                                <button class="btn btn-warning" onclick="document.getElementById('edit_modal_<?php echo $row['video_id']; ?>').showModal()">
                                    <!-- Icono de edición -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>
                                </button>
                                <!-- Botón para eliminar el video -->
                                <button type="button" class="btn btn-danger" onclick="deleteVideo(<?php echo $row['video_id']; ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de edición -->
                    <dialog id="edit_modal_<?php echo $row['video_id']; ?>" class="modal modal-bottom sm:modal-middle">
                        <div class="modal-box">
                            <form action="edit_video" method="POST">
                                <div>
                                    <div>
                                        <input type="hidden" name="video_id" value="<?php echo $row['video_id']; ?>">
                                        <div>
                                            <p class="font-semibold mb-2">URL del video de YouTube</p>
                                            <input type="text" name="video_url" class="input input-bordered w-full max-w-xs" value="<?php echo $row['location']; ?>">
                                        </div>
                                        <div class="mt-4 mb-4">
                                            <p class="font-semibold mb-2">Nueva descripción - <?php echo $row['video_id']; ?></p>
                                            <input type="text" name="new_description" class="input input-bordered w-full max-w-xs" value="<?php echo $row['video_name']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="category_<?php echo $row['video_id']; ?>">Categoría del Video</label>
                                            <input type="text" name="category" id="category_<?php echo $row['video_id']; ?>" class="input input-bordered w-full max-w-xs" value="<?php echo $row['category']; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="submit" class="btn btn-Warning">Guardar cambios</button>
                                    </div>
                                </div>
                            </form>

                            <div class="modal-action">
                                <form method="dialog">
                                    <!-- Botón para cerrar el modal -->
                                    <button class="btn">Cerrar</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
            <?php
                } else {
                    echo "<p>Error: No se pudo obtener el ID del video de la URL de YouTube.</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Modal para agregar video -->
<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <form action="save_video" method="POST">
            <div class="modal-content">
                <h3 class="font-bold mb-4">Insertar Video</h3>
                <input type="text" name="video_name" class="input input-bordered w-full max-w-xs" placeholder="Nombre del video">
                <textarea name="video_description" class="textarea textarea-bordered w-full max-w-xs mt-2" placeholder="Descripción del video"></textarea>
                <input type="text" name="video_url" class="input input-bordered w-full max-w-xs mt-2" placeholder="URL del video">
                <div class="form-group mt-4">
                    <label for="category" class="font-medium">Categoría del Video</label>
                    <select name="category" class="input input-bordered w-full max-w-xs" required>
                        <option value="Educación">Educación</option>
                        <option value="Entretenimiento">Entretenimiento</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="save" class="btn btn-primary mt-3">Guardar</button>
        </form>

        <div class="modal-action">
            <form method="dialog">
                <!-- Botón para cerrar el modal -->
                <button class="btn">Cerrar</button>
            </form>
        </div>
    </div>
</dialog>

<script>
    function deleteVideo(videoId) {
        if (confirm("¿Estás seguro de que deseas eliminar este video?")) {
            window.location.href = `delete_video?video_id=${videoId}`;
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
include_once __DIR__ . '/../footer.php';
?>