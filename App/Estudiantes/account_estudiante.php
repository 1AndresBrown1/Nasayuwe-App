<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="./image/estudiante.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <!--alert message-->
    <?php if (@$_GET['w']) {
        echo '<script>alert("' . @$_GET['w'] . '");</script>';
    }
    ?>
    <!--alert message end-->

</head>
<?php

include_once __DIR__ . '/../Estudiantes/header.php';

?>

<body class="">
    <div class="container mx-auto">
        <div>
            <?php
            if (!(isset($_SESSION['nombre_usuario']))) {
                // header("location: ../login.php");
            } else {
                $name = $_SESSION['nombre_usuario'];
                $email = $_SESSION['email'];
            }
            ?>
        </div>
    </div>

    <!--navigation menu-->
    <nav class="bg-white border-b border-gray-200">
        <div class="container mx-auto flex items-center justify-between p-4">
            <div class="flex items-center space-x-4">
                <a href="evaluaciones_estudiantes?q=0" class="text-xl font-semibold <?php echo @$_GET['q'] == 1 ? 'text-blue-500' : 'text-gray-700'; ?>">Inicio</a>
                <a href="evaluaciones_estudiantes?q=2" class="text-xl font-semibold <?php echo @$_GET['q'] == 3 ? 'text-blue-500' : 'text-gray-700'; ?>">Calificaciones</a>
            </div>
            <button type="button" class="text-gray-500 focus:outline-none md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </nav>

    <div class="container mx-auto mt-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <!--home start-->
            <?php if (@$_GET['q'] == 1) {

                $result = mysqli_query($conexion, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                echo '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temática</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Preguntas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marcas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiempo Límite</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">';
                $c = 1;
                while ($row = mysqli_fetch_array($result)) {
                    $title = $row['title'];
                    $total = $row['total'];
                    $sahi = $row['sahi'];
                    $time = $row['time'];
                    $eid = $row['eid'];
                    $q12 = mysqli_query($conexion, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error98');
                    $rowcount = mysqli_num_rows($q12);
                    if ($rowcount == 0) {
                        echo '<tr>
                        <td class="px-6 py-4 whitespace-nowrap">' . $c++ . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $title . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $total . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $sahi * $total . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $time . '&nbsp;min</td>
                        <td class="px-6 py-4 whitespace-nowrap"><a href="account_estudiante?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">Examen</a></td>
                        </tr>';
                    } else {
                        echo '<tr style="color:#99cc32">
                        <td class="px-6 py-4 whitespace-nowrap">' . $c++ . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $title . '&nbsp;<span title="This quiz is already solve by you"></span></td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $total . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $sahi * $total . '</td>
                        <td class="px-6 py-4 whitespace-nowrap">' . $time . '&nbsp;min</td>
                        <td class="px-6 py-4 whitespace-nowrap"><a href="update?q=quizre&step=25&eid=' . $eid . '&n=1&t=' . $total . '" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Otra vez</a></td>
                        </tr>';
                    }
                }
                echo '</tbody></table></div>';
            } ?>
            <!--home closed-->

            <!--quiz start-->
            <?php
            if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
                $eid = @$_GET['eid'];
                $sn = @$_GET['n'];
                $total = @$_GET['t'];
                $q = mysqli_query($conexion, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' ");
                echo '<div class="p-4 bg-white border rounded-lg shadow-sm">';
                while ($row = mysqli_fetch_array($q)) {
                    $qns = $row['qns'];
                    $qid = $row['qid'];
                    echo '<b>Pregunta &nbsp;' . $sn . '&nbsp;::<br />' . $qns . '</b><br /><br />';
                }
                $q = mysqli_query($conexion, "SELECT * FROM options WHERE qid='$qid' ");
                echo '<form action="update_estudiante?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST"  class="form-horizontal mt-4">';
                while ($row = mysqli_fetch_array($q)) {
                    $option = $row['option'];
                    $optionid = $row['optionid'];
                    echo '<div><input type="radio" name="ans" value="' . $optionid . '" class="mr-2">' . $option . '</div><br />';
                }
                echo '<br /><button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Enviar</button></form></div>';
            }

            //result display
            if (@$_GET['q'] == 'result' && @$_GET['eid']) {
                $eid = @$_GET['eid'];
                $q = mysqli_query($conexion, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
                echo '<div class="p-4 bg-white border rounded-lg shadow-sm">';
                echo '<center><h1 class="text-2xl font-bold text-indigo-700">Resultados</h1><center><br /><table class="min-w-full divide-y divide-gray-200">';

                while ($row = mysqli_fetch_array($q)) {
                    $s = $row['score'];
                    $w = $row['wrong'];
                    $r = $row['sahi'];
                    $qa = $row['level'];
                    echo '<tr class="bg-blue-50"><td class="px-6 py-4">Total de Preguntas</td><td class="px-6 py-4">' . $qa . '</td></tr>
                          <tr class="bg-green-50"><td class="px-6 py-4">Respuestas Correctas</td><td class="px-6 py-4">' . $r . '</td></tr>
                          <tr class="bg-red-50"><td class="px-6 py-4">Respuestas Equivocadas</td><td class="px-6 py-4">' . $w . '</td></tr>
                          <tr class="bg-blue-50"><td class="px-6 py-4">Calificación</td><td class="px-6 py-4">' . $s . '</td></tr>';
                }
                $q = mysqli_query($conexion, "SELECT * FROM rank WHERE  email='$email' ") or die('Error157');
                while ($row = mysqli_fetch_array($q)) {
                    $s = $row['score'];
                    echo '<tr class="bg-purple-50"><td class="px-6 py-4">Calificación General</td><td class="px-6 py-4">' . $s . '</td></tr>';
                }
                echo '</table></div>';
            }
            ?>
            <!--quiz end-->

            <?php
            //history start
            if (@$_GET['q'] == 2) {
                $q = mysqli_query($conexion, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC ") or die('Error197');
                echo '<div class="overflow-x-auto mt-6"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr style="color:blue">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Examen</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preguntas Resueltas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buenas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equivocadas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntaje</th>
                    </tr>
                </thead>';
                $c = 0;
                while ($row = mysqli_fetch_array($q)) {
                    $eid = $row['eid'];
                    $s = $row['score'];
                    $w = $row['wrong'];
                    $r = $row['sahi'];
                    $qa = $row['level'];
                    $q23 = mysqli_query($conexion, "SELECT title FROM quiz WHERE  eid='$eid' ") or die('Error208');
                    while ($row = mysqli_fetch_array($q23)) {
                        $title = $row['title'];
                    }
                    $c++;
                    echo '<tr class="bg-white border-b"><td class="px-6 py-4 whitespace-nowrap">' . $c . '</td><td class="px-6 py-4 whitespace-nowrap">' . $title . '</td><td class="px-6 py-4 whitespace-nowrap">' . $qa . '</td><td class="px-6 py-4 whitespace-nowrap">' . $r . '</td><td class="px-6 py-4 whitespace-nowrap">' . $w . '</td><td class="px-6 py-4 whitespace-nowrap">' . $s . '</td></tr>';
                }
                echo '</table></div>';
            }

            //ranking start
            if (@$_GET['q'] == 3) {
                $q = mysqli_query($conexion, "SELECT * FROM rank  ORDER BY score DESC ") or die('Error223');
                echo '<div class="overflow-x-auto mt-6"><table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Género</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institución</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntaje</th>
                    </tr>
                </thead>';
                $c = 0;
                while ($row = mysqli_fetch_array($q)) {
                    $e = $row['email'];
                    $s = $row['score'];
                    $q12 = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE email='$e' ") or die('Error231');
                    while ($row = mysqli_fetch_array($q12)) {
                        $name = $row['nombre'];
                        $gender = $row['genero'];
                        $college = "INSTITUCIÓN EDUCATIVA TÉCNICA EDUARDO SANTOS";
                    }
                    $c++;
                    echo '<tr class="bg-white border-b"><td class="px-6 py-4 whitespace-nowrap">' . $c . '</td><td class="px-6 py-4 whitespace-nowrap">' . $name . '</td><td class="px-6 py-4 whitespace-nowrap">' . $gender . '</td><td class="px-6 py-4 whitespace-nowrap">' . $college . '</td><td class="px-6 py-4 whitespace-nowrap">' . $s . '</td><td>';
                }
                echo '</table></div>';
            }
            ?>
        </div>
    </div>
</body>

</html>
