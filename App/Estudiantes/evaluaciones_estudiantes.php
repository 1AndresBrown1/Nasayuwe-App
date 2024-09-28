<?php
include_once __DIR__ . '/../Estudiantes/header.php';

if (!(isset($_SESSION['nombre_usuario']))) {
    // Si no está logueado, redirige o muestra un mensaje
} else {
    $name = $_SESSION['nombre_usuario'];
    $email = $_SESSION['email'];
}

// Establecer valor predeterminado para `q` si no está definido
$q = isset($_GET['q']) ? $_GET['q'] : 1;
?>

<div class="container mx-auto p-4 mt-20 sm:mt-6">
    <div class="navbar bg-base-100">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl">Panel</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 z-30">
                <li class="<?php echo $q == 1 ? 'text-blue-500' : 'text-gray-700'; ?>">
                    <a href="evaluaciones_estudiantes?q=1">Inicio</a>
                </li>
                <li class="<?php echo $q == 2 ? 'text-blue-500' : 'text-gray-700'; ?>">
                    <a href="evaluaciones_estudiantes?q=2">Historia</a>
                </li>
            </ul>
        </div>
    </div>

    <?php if ($q == 1) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $result = mysqli_query($conexion, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th>#</th>
                <th>Temática</th>
                <th>Total de Preguntas</th>
                <th>Marcas</th>
                <th></th>
              </tr>
            </thead>
            <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
                $title = $row['title'];
                $total = $row['total'];
                $sahi = $row['sahi'];
                $eid = $row['eid'];
                $q12 = mysqli_query($conexion, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error98');
                $rowcount = mysqli_num_rows($q12);
                if ($rowcount == 0) {
                    echo '<tr>
                      <th>' . $c++ . '</th>
                      <td>' . $title . '</td>
                      <td>' . $total . '</td>
                      <td>' . $sahi * $total . '</td>
                      <td><a href="evaluaciones_estudiantes?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" class="bg-green-500 text-white py-1 px-3 rounded-lg">Examen</a></td>
                    </tr>';
                } else {
                    echo '<tr class="bg-green-100 text-green-700">
                      <th>' . $c++ . '</th>
                      <td>' . $title . ' <span class="ml-2" title="Este examen ya lo resolviste">&#10003;</span></td>
                      <td>' . $total . '</td>
                      <td>' . $sahi * $total . '</td>
                      <td><a href="update_estudiante?q=quizre&step=25&eid=' . $eid . '&n=1&t=' . $total . '" class="bg-red-500 text-white py-1 px-3 rounded-lg">Otra vez</a></td>
                    </tr>';
                }
            }
            echo '</tbody><tfoot>
              <tr>
                <th>#</th>
                <th>Temática</th>
                <th>Total de Preguntas</th>
                <th>Marcas</th>
                <th></th>
              </tr>
            </tfoot></table></div>';
            ?>
        </div>
    <?php } ?>

    <?php if ($q == 'quiz' && @$_GET['step'] == 2) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $eid = @$_GET['eid'];
            $sn = @$_GET['n'];
            $total = @$_GET['t'];
            $q = mysqli_query($conexion, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' ") or die('Error');
            echo '<div class="panel"><b>Pregunta &nbsp;' . $sn . '&nbsp;::<br />';
            while ($row = mysqli_fetch_array($q)) {
                $qns = $row['qns'];
                $qid = $row['qid'];
                echo $qns . '</b><br /><br />';
            }
            $q = mysqli_query($conexion, "SELECT * FROM options WHERE qid='$qid' ") or die('Error');
            echo '<form action="update_estudiante?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST">';
            while ($row = mysqli_fetch_array($q)) {
                $option = $row['option'];
                $optionid = $row['optionid'];
                echo '<input type="radio" name="ans" value="' . $optionid . '">' . $option . '<br /><br />';
            }
            echo '<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Enviar</button></form></div>';
            ?>
        </div>
    <?php } ?>

    <?php if ($q == 'result' && @$_GET['eid']) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $eid = @$_GET['eid'];
            $q = mysqli_query($conexion, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
            echo '<div class="panel"><center><h1 class="title" style="color:#660033">Resultados</h1><center><br /><table class="table table-xs">';
            while ($row = mysqli_fetch_array($q)) {
                $w = $row['wrong'];
                $r = $row['sahi'];
                $qa = $row['level'];
                echo '<tr style="color:#66CCFF"><td>Total de Preguntas</td><td>' . $qa . '</td></tr>
                      <tr style="color:#99cc32"><td>Respuestas Correctas&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>' . $r . '</td></tr> 
                      <tr style="color:red"><td>Respuestas Equivocadas&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>' . $w . '</td></tr>';
            }
            echo '</table></div>';
            ?>
        </div>
    <?php } ?>

    <?php if ($q == 2) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $q = mysqli_query($conexion, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC ") or die('Error197');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th>#</th>
                <th>Examen</th>
                <th>Preguntas Resueltas</th>
                <th>Buenas</th>
                <th>Equivocadas</th>
              </tr>
            </thead>
            <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($q)) {
                $eid = $row['eid'];
                $w = $row['wrong'];
                $r = $row['sahi'];
                $qa = $row['level'];
                $q23 = mysqli_query($conexion, "SELECT title FROM quiz WHERE eid='$eid' ") or die('Error208');
                $title = mysqli_fetch_array($q23)['title'];
                echo '<tr><td>' . $c++ . '</td><td>' . $title . '</td><td>' . $qa . '</td><td>' . $r . '</td><td>' . $w . '</td></tr>';
            }
            echo '</tbody></table></div>';
            ?>
        </div>
    <?php } ?>
</div>

<?php
include_once __DIR__ . '/../Estudiantes/footer.php';
?>
