<?php
include_once __DIR__ . '/../Docentes/header.php';

if (!(isset($_SESSION['nombre_usuario']))) {
    // Si no está logueado, redirige o muestra un mensaje
} else {
    $name = $_SESSION['nombre_usuario'];
    $email = $_SESSION['email'];
}
?>

<div class="container mx-auto p-4 mt-20 sm:mt-6">
    <div class="navbar bg-base-100">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl">Panel</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1 z-30">
                <li class="<?php echo @$_GET['q'] == 0 ? 'text-blue-500' : 'text-gray-700'; ?>">
                    <a href="evaluaciones?q=0">Inicio</a>
                </li>
                <li class="<?php echo @$_GET['q'] == 1 ? 'text-blue-500' : 'text-gray-700'; ?>">
                    <a href="evaluaciones?q=1">Usuarios</a>
                </li>
                <li class="<?php echo @$_GET['q'] == 2 ? 'text-blue-500' : 'text-gray-700'; ?>">
                    <a href="evaluaciones?q=2">Calificaciones</a>
                </li>
                <li class="relative <?php echo @$_GET['q'] == 4 || @$_GET['q'] == 5 ? 'text-blue-500' : 'text-gray-700'; ?>">
                    <details>
                        <summary>Quiz</summary>
                        <ul class="bg-base-100 rounded-t-none p-2">
                            <li><a href="evaluaciones?q=4">Agregar Quiz</a></li>
                            <li><a href="evaluaciones?q=5">Eliminar Quiz</a></li>
                        </ul>
                    </details>
                </li>
            </ul>
        </div>
    </div>

    <?php if (@$_GET['q'] == 0) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $result = mysqli_query($conexion, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th></th>
                <th>Temática</th>
                <th>Total de Preguntas</th>
                <th>Intentos</th>
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
                      <td><a href="account?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" class="bg-green-500  text-white py-1 px-3 rounded-lg">Ver Examen</a></td>
                    </tr>';
                } else {
                    echo '<tr class="bg-green-100 text-green-700">
                      <th>' . $c++ . '</th>
                      <td>' . $title . ' <span class="ml-2" title="This quiz is already solved by you">&#10003;</span></td>
                      <td>' . $total . '</td>
                      <td>' . $sahi * $total . '</td>
                      <td><a href="update?q=quizre&step=25&eid=' . $eid . '&n=1&t=' . $total . '" class="bg-red-500 text-white py-1 px-3 rounded-lg">Restart</a></td>
                    </tr>';
                }
            }
            echo '</tbody><tfoot>
              <tr>
                <th></th>
                <th>Temática</th>
                <th>Total de Preguntas</th>
                <th>Intentos</th>
                <th></th>
              </tr>
            </tfoot></table></div>';
            ?>
        </div>
    <?php } ?>

    <?php if (@$_GET['q'] == 1) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $result = mysqli_query($conexion, "SELECT * FROM estudiantes") or die('Error');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th></th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Institución Académica</th>
                <th>Correo Electrónico</th>
                <th>Móvil</th>
                <th></th>
              </tr>
            </thead>
            <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
                $name = $row['nombre'];
                $mob = $row['telefono'];
                $gender = $row['genero'];
                $email = $row['email'];
                $college = "INSTITUCIÓN EDUCATIVA TÉCNICA EDUARDO SANTOS";
                echo '<tr>
                    <th>' . $c++ . '</th>
                    <td>' . $name . '</td>
                    <td>' . $gender . '</td>
                    <td>' . $college . '</td>
                    <td>' . $email . '</td>
                    <td>' . $mob . '</td>
                    <td><a href="update?demail=' . $email . '" class="text-red-600 hover:underline">Eliminar</a></td>
                  </tr>';
            }
            echo '</tbody><tfoot>
              <tr>
                <th></th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Institución Académica</th>
                <th>Correo Electrónico</th>
                <th>Móvil</th>
                <th></th>
              </tr>
            </tfoot></table></div>';
            ?>
        </div>
    <?php } ?>

    <?php if (@$_GET['q'] == 2) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $q = mysqli_query($conexion, "SELECT * FROM rank ORDER BY score DESC") or die('Error223');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th>Posición</th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Institución Académica</th>
                <th>Puntuación</th>
              </tr>
            </thead>
            <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($q)) {
                $email = $row['email'];
                $score = $row['score'];
                $q12 = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE email='$email'") or die('Error231');
                while ($row2 = mysqli_fetch_array($q12)) {
                    $name = $row2['nombre'];
                    $gender = $row2['genero'];
                    $college = "INSTITUCIÓN EDUCATIVA TÉCNICA EDUARDO SANTOS";
                    echo '<tr>
                        <th>' . $c++ . '</th>
                        <td>' . $name . '</td>
                        <td>' . $gender . '</td>
                        <td>' . $college . '</td>
                        <td>' . $score . '</td>
                    </tr>';
                }
            }
            echo '</tbody><tfoot>
              <tr>
                <th>Posición</th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Institución Académica</th>
                <th>Puntuación</th>
              </tr>
            </tfoot></table></div>';
            ?>
        </div>
    <?php } ?>

    <?php if (@$_GET['q'] == 3) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $result = mysqli_query($conexion, "SELECT * FROM feedback ORDER BY date DESC") or die('Error');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th></th>
                <th>Asunto</th>
                <th>Correo Electrónico</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Enviado por</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>';
            $c = 1;
            while ($row = mysqli_fetch_array($result)) {
                $date = $row['date'];
                $date = date("d-m-Y", strtotime($date));
                $time = $row['time'];
                $subject = $row['subject'];
                $name = $row['name'];
                $email = $row['email'];
                $id = $row['id'];
                echo '<tr>
                    <th>' . $c++ . '</th>
                    <td><a href="eval.php?q=3&fid=' . $id . '" class="text-blue-500 hover:underline">' . $subject . '</a></td>
                    <td>' . $email . '</td>
                    <td>' . $date . '</td>
                    <td>' . $time . '</td>
                    <td>' . $name . '</td>
                    <td><a href="eval.php?q=3&fid=' . $id . '" class="text-green-600 hover:underline">Abrir</a></td>
                    <td><a href="update?fdid=' . $id . '" class="text-red-600 hover:underline">Eliminar</a></td>
                  </tr>';
            }
            echo '</tbody><tfoot>
              <tr>
                <th></th>
                <th>Asunto</th>
                <th>Correo Electrónico</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Enviado por</th>
                <th></th>
                <th></th>
              </tr>
            </tfoot></table></div>';
            ?>
        </div>
    <?php } ?>

    <?php if (@$_GET['fid']) { ?>
        <div class="rounded-lg p-4 border">
            <?php
            $id = @$_GET['fid'];
            $result = mysqli_query($conexion, "SELECT * FROM feedback WHERE id='$id' ") or die('Error');
            while ($row = mysqli_fetch_array($result)) {
                $name = $row['name'];
                $subject = $row['subject'];
                $date = $row['date'];
                $date = date("d-m-Y", strtotime($date));
                $time = $row['time'];
                $feedback = $row['feedback'];
                echo '<div class="bg-gray-50 p-4 rounded-lg">
                <a href="update?q1=2" class="text-blue-500 hover:underline">Volver a Archivos</a>
                <h2 class="text-center text-2xl font-bold mt-4">' . $subject . '</h2>
                <div class="bg-gray-200 p-4 mt-4 rounded-lg">
                  <p><b>Fecha:</b> ' . $date . '</p>
                  <p><b>Hora:</b> ' . $time . '</p>
                  <p><b>Enviado por:</b> ' . $name . '</p>
                  <p>' . $feedback . '</p>
                </div>
              </div>';
            }
            ?>
        </div>
    <?php } ?>

    <?php if (@$_GET['q'] == 4 && !(@$_GET['step'])) { ?>
        <div class="rounded-lg p-4 border">
            <h2 class="text-center text-2xl font-bold">Detalles del examen</h2>
            <form class="mt-4" name="form" action="update?q=addquiz" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Título del Quiz</label>
                    <input type="text" id="name" name="name" placeholder="Ingrese el título del Quiz" class="mt-1 p-2 w-full border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="total" class="block text-sm font-medium text-gray-700">Número total de preguntas</label>
                    <input type="number" id="total" name="total" placeholder="Ingrese el número total de preguntas" class="mt-1 p-2 w-full border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="right" class="block text-sm font-medium text-gray-700">Marcas por respuesta correcta</label>
                    <input type="number" id="right" name="right" placeholder="Ingrese el número de marcas en la respuesta correcta" class="mt-1 p-2 w-full border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="wrong" class="block text-sm font-medium text-gray-700">Marcas por respuesta incorrecta</label>
                    <input type="number" id="wrong" name="wrong" placeholder="Ingrese el número de marcas en la respuesta incorrecta sin signo" class="mt-1 p-2 w-full border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="tag" class="block text-sm font-medium text-gray-700">Etiqueta del Quiz</label>
                    <input type="text" id="tag" name="tag" placeholder="Ingrese una etiqueta para que puedan buscar el examen" class="mt-1 p-2 w-full border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="desc" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="desc" name="desc" placeholder="Escribe una descripción acá..." class="mt-1 p-2 w-full border rounded-lg" rows="4" required></textarea>
                </div>
                <div class="text-center">
                    <input type="submit" value="Enviar" class="bg-blue-500 text-white py-2 px-4 rounded-lg">
                </div>
            </form>
        </div>
    <?php } ?>

    <?php if (@$_GET['q'] == 4 && (@$_GET['step']) == 2) { ?>
        <div class="rounded-lg p-4 border">
            <h2 class="text-center text-2xl font-bold">Ingrese los detalles de las preguntas</h2>
            <form class="mt-4" name="form" action="update?q=addqns&n=<?php echo @$_GET['n']; ?>&eid=<?php echo @$_GET['eid']; ?>&ch=4" method="POST">
                <?php for ($i = 1; $i <= @$_GET['n']; $i++) { ?>
                    <div class="mb-4">
                        <label for="qns<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Pregunta número <?php echo $i; ?></label>
                        <textarea id="qns<?php echo $i; ?>" name="qns<?php echo $i; ?>" placeholder="Escribe la pregunta número <?php echo $i; ?> acá..." class="mt-1 p-2 w-full border rounded-lg" rows="2" required></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="<?php echo $i; ?>1" class="block text-sm font-medium text-gray-700">Opción a</label>
                            <input type="text" id="<?php echo $i; ?>1" name="<?php echo $i; ?>1" placeholder="Ingresa la opción a" class="mt-1 p-2 w-full border rounded-lg" required>
                        </div>
                        <div>
                            <label for="<?php echo $i; ?>2" class="block text-sm font-medium text-gray-700">Opción b</label>
                            <input type="text" id="<?php echo $i; ?>2" name="<?php echo $i; ?>2" placeholder="Ingresa la opción b" class="mt-1 p-2 w-full border rounded-lg" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="<?php echo $i; ?>3" class="block text-sm font-medium text-gray-700">Opción c</label>
                            <input type="text" id="<?php echo $i; ?>3" name="<?php echo $i; ?>3" placeholder="Ingresa la opción c" class="mt-1 p-2 w-full border rounded-lg" required>
                        </div>
                        <div>
                            <label for="<?php echo $i; ?>4" class="block text-sm font-medium text-gray-700">Opción d</label>
                            <input type="text" id="<?php echo $i; ?>4" name="<?php echo $i; ?>4" placeholder="Ingresa la opción d" class="mt-1 p-2 w-full border rounded-lg" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="ans<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Seleccione la respuesta correcta</label>
                        <select id="ans<?php echo $i; ?>" name="ans<?php echo $i; ?>" class="mt-1 p-2 w-full border rounded-lg" required>
                            <option value="a">Seleccione la respuesta a la pregunta <?php echo $i; ?></option>
                            <option value="a">Opción a</option>
                            <option value="b">Opción b</option>
                            <option value="c">Opción c</option>
                            <option value="d">Opción d</option>
                        </select>
                    </div>
                <?php } ?>
                <div class="text-center">
                    <input type="submit" value="Enviar" class="bg-blue-500 text-white py-2 px-4 rounded-lg">
                </div>
            </form>
        </div>
    <?php } ?>

    <?php if (@$_GET['q'] == 5) { ?>
        <div class="rounded-lg p-4 border">
            <h2 class="text-center text-2xl font-bold">Eliminar Quiz</h2>
            <?php
            $result = mysqli_query($conexion, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
            echo '<div class="overflow-x-auto"><table class="table table-xs">
            <thead>
              <tr>
                <th></th>
                <th>Temática</th>
                <th>Total de Preguntas</th>
                <th>Intentos</th>
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
                echo '<tr>
                    <th>' . $c++ . '</th>
                    <td>' . $title . '</td>
                    <td>' . $total . '</td>
                    <td>' . $sahi * $total . '</td>
                    <td><a href="update?q=rmquiz&eid=' . $eid . '" class="bg-red-500 text-white py-1 px-3 rounded-lg">Eliminar</a></td>
                  </tr>';
            }
            echo '</tbody><tfoot>
              <tr>
                <th></th>
                <th>Temática</th>
                <th>Total de Preguntas</th>
                <th>Intentos</th>
                <th></th>
              </tr>
            </tfoot></table></div>';
            ?>
        </div>
    <?php } ?>
</div>
<?php
include_once __DIR__ . '/../Docentes/footer.php';
?>
