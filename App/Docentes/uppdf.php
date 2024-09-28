<?php
include_once __DIR__ . '/../Docentes/header.php';

$res = array();

// Obtener el video_id de la URL, si está presente
$currentVideoId = isset($_GET['video_id']) ? $_GET['video_id'] : 1;

// Consulta para obtener los datos combinados de files y estudiantes
$query = "SELECT f.id AS vid, f.vid, f.title, f.description, f.url, e.documento_identidad, e.nombre, e.apellido 
          FROM files f
          LEFT JOIN estudiantes e ON f.documento_identidad = e.documento_identidad
          WHERE f.id >= $currentVideoId";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error en la consulta: ' . mysqli_error($conn));
}

// Almacenar resultados en $res
while ($val = mysqli_fetch_assoc($result)) {
    $res[] = $val;
}
?>

<div class="container p-4 mt-20 sm:mt-6">
    <h1 class="font-bold text-3xl">Tarea de los Estudiantes</h1>

    <div class="row justify-content-md-center">
        <div class="overflow-x-auto">
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Documento de Identidad</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($res as $val) { ?>
                        <tr>
                            <td><?php echo $val['vid']; ?></td>
                            <td><?php echo $val['title']; ?></td>
                            <td><?php echo $val['documento_identidad']; ?></td>
                            <td><?php echo $val['nombre']; ?></td>
                            <td><?php echo $val['apellido']; ?></td>
                            <td><?php echo $val['description']; ?></td>
                            <td>
                                <a class="btn btn-success btn-icon" target="_blank" href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/edu-alex/' . $val['url']; ?>">
                                    <box-icon name='globe' type='solid'></box-icon>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
