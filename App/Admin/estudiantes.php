<?php
include_once __DIR__ . '/../header.php';

// Check if a status parameter is present in the URL
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'El docente se ha actualizado con éxito',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    } elseif ($_GET['status'] === 'success1') {
        echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'El docente se ha registrado con éxito',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    } elseif ($_GET['status'] === 'error2') {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al preparar la sentencia: ' $conexion->error,
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1500
                });
              </script>";
    }
}

?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var contrasenaInput = document.getElementById("contrasena");
        var verificarContrasenaInput = document.getElementById("verificarContrasena");

        function validarContrasenas() {
            var contrasena = contrasenaInput.value;
            var verificarContrasena = verificarContrasenaInput.value;

            if (contrasena === verificarContrasena) {
                // Contraseñas coinciden, aplicar estilo verde
                verificarContrasenaInput.style.borderColor = "green";
            } else {
                // Contraseñas no coinciden, aplicar estilo rojo
                verificarContrasenaInput.style.borderColor = "red";
            }
        }

        contrasenaInput.addEventListener("input", validarContrasenas);
        verificarContrasenaInput.addEventListener("input", validarContrasenas);

        // Evento para reiniciar el estilo cuando se enfoca en el campo
        verificarContrasenaInput.addEventListener("focus", function() {
            verificarContrasenaInput.style.borderColor = "";
        });
    });
</script>


<div class="container p-4 mt-20 sm:mt-6">

    <h1 class="text-3xl mb-10 font-bold">Registro estudiantes</h1>


    <div role="tablist" class="tabs tabs-lifted">

        <input type="radio" name="my_tabs_2" role="tab" class="tab" aria-label="Crear" checked="checked" />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">

            <form action="procesar_registro_estudiante" method="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    <div>
                        <label for="nombre" class="block text-sm">Nombre</label>
                        <div class="relative flex items-center mt-2">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input type="text" placeholder="Nombre" id="nombre" name="nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>

                    <div>
                        <label for="apellido" class="block text-sm">Apellido</label>
                        <div class="relative flex items-center mt-2">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="apellido" name="apellido" type="text" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>

                    <div>
                        <label for="tipo_documento" class="block text-sm">Tipo</label>
                        <div class="relative flex items-center mt-2">

                            <select id="tipo_documento" class="select select-bordered w-full max-w-xs py-2.5" name="tipo_documento" required>
                                <option class="form-control" value="cedula">Cédula</option>
                                <option class="form-control" value="pasaporte">Pasaporte</option>
                                <option class="form-control" value="otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="tipo_documento" class="block text-sm">Sexo</label>
                        <div class="relative flex items-center mt-2">

                            <select id="genero" class="select select-bordered w-full max-w-xs py-2.5" name="genero" required>
                                <option value="">Selecciona un género</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="documento_identidad" class="block text-sm">Numero de documento</label>
                        <div class="relative flex items-center mt-2">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path fill-rule="evenodd" d="M7.491 5.992a.75.75 0 0 1 .75-.75h12a.75.75 0 1 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM7.49 11.995a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM7.491 17.994a.75.75 0 0 1 .75-.75h12a.75.75 0 1 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM2.24 3.745a.75.75 0 0 1 .75-.75h1.125a.75.75 0 0 1 .75.75v3h.375a.75.75 0 0 1 0 1.5H2.99a.75.75 0 0 1 0-1.5h.375v-2.25H2.99a.75.75 0 0 1-.75-.75ZM2.79 10.602a.75.75 0 0 1 0-1.06 1.875 1.875 0 1 1 2.652 2.651l-.55.55h.35a.75.75 0 0 1 0 1.5h-2.16a.75.75 0 0 1-.53-1.281l1.83-1.83a.375.375 0 0 0-.53-.53.75.75 0 0 1-1.062 0ZM2.24 15.745a.75.75 0 0 1 .75-.75h1.125a1.875 1.875 0 0 1 1.501 2.999 1.875 1.875 0 0 1-1.501 3H2.99a.75.75 0 0 1 0-1.501h1.125a.375.375 0 0 0 .036-.748H3.74a.75.75 0 0 1-.75-.75v-.002a.75.75 0 0 1 .75-.75h.411a.375.375 0 0 0-.036-.748H2.99a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                                </svg>

                            </span>
                            <input id="documento_identidad" name="documento_identidad" type="number" placeholder="Numero documento" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm">Teléfono</label>
                        <div class="relative flex items-center mt-2">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="telefono" name="telefono" type="number" placeholder="Teléfono" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>

                    <div>
                        <label for="titulo" class="block text-sm">Titulo</label>
                        <div class="relative flex items-center mt-2">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                    <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                </svg>

                            </span>
                            <input id="titulo" name="titulo" type="text" placeholder="titulo" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>


                    <div>
                        <label for="fecha_nacimiento" class="block text-sm">Fecha de nacimiento</label>
                        <div class="relative flex items-center mt-2">
                            <span class="absolute">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                    <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                                </svg>


                            </span>
                            <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="fecha_nacimiento" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>





                </div>


                <div class="flex w-full flex-col border-opacity-50 mt-5">
                    <div class="card bg-base-300 rounded-box grid place-items-center">

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 p-4">
                            <div>
                                <label for="email" class="block text-sm">Correo</label>
                                <div class="relative flex items-center mt-2">
                                    <span class="absolute">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                                        </svg>
                                    </span>
                                    <input id="eemail" name="eemail" type="eemail" placeholder="Correo@gmail.com" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                                </div>
                            </div>


                            <div>
                                <label for="contrasena" class="block text-sm">Contraseña:</label>
                                <div class="relative flex items-center mt-2">
                                    <span class="absolute">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                            <path fill-rule="evenodd" d="M12 3.75a6.715 6.715 0 0 0-3.722 1.118.75.75 0 1 1-.828-1.25 8.25 8.25 0 0 1 12.8 6.883c0 3.014-.574 5.897-1.62 8.543a.75.75 0 0 1-1.395-.551A21.69 21.69 0 0 0 18.75 10.5 6.75 6.75 0 0 0 12 3.75ZM6.157 5.739a.75.75 0 0 1 .21 1.04A6.715 6.715 0 0 0 5.25 10.5c0 1.613-.463 3.12-1.265 4.393a.75.75 0 0 1-1.27-.8A6.715 6.715 0 0 0 3.75 10.5c0-1.68.503-3.246 1.367-4.55a.75.75 0 0 1 1.04-.211ZM12 7.5a3 3 0 0 0-3 3c0 3.1-1.176 5.927-3.105 8.056a.75.75 0 1 1-1.112-1.008A10.459 10.459 0 0 0 7.5 10.5a4.5 4.5 0 1 1 9 0c0 .547-.022 1.09-.067 1.626a.75.75 0 0 1-1.495-.123c.041-.495.062-.996.062-1.503a3 3 0 0 0-3-3Zm0 2.25a.75.75 0 0 1 .75.75c0 3.908-1.424 7.485-3.781 10.238a.75.75 0 0 1-1.14-.975A14.19 14.19 0 0 0 11.25 10.5a.75.75 0 0 1 .75-.75Zm3.239 5.183a.75.75 0 0 1 .515.927 19.417 19.417 0 0 1-2.585 5.544.75.75 0 0 1-1.243-.84 17.915 17.915 0 0 0 2.386-5.116.75.75 0 0 1 .927-.515Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input id="contrasena" name="contrasena" type="password" placeholder="" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                                </div>
                            </div>


                            <div>
                                <label for="verificarContrasena" class="block text-sm">Verificar contraseña:</label>
                                <div class="relative flex items-center mt-2">
                                    <span class="absolute">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                                            <path fill-rule="evenodd" d="M12 3.75a6.715 6.715 0 0 0-3.722 1.118.75.75 0 1 1-.828-1.25 8.25 8.25 0 0 1 12.8 6.883c0 3.014-.574 5.897-1.62 8.543a.75.75 0 0 1-1.395-.551A21.69 21.69 0 0 0 18.75 10.5 6.75 6.75 0 0 0 12 3.75ZM6.157 5.739a.75.75 0 0 1 .21 1.04A6.715 6.715 0 0 0 5.25 10.5c0 1.613-.463 3.12-1.265 4.393a.75.75 0 0 1-1.27-.8A6.715 6.715 0 0 0 3.75 10.5c0-1.68.503-3.246 1.367-4.55a.75.75 0 0 1 1.04-.211ZM12 7.5a3 3 0 0 0-3 3c0 3.1-1.176 5.927-3.105 8.056a.75.75 0 1 1-1.112-1.008A10.459 10.459 0 0 0 7.5 10.5a4.5 4.5 0 1 1 9 0c0 .547-.022 1.09-.067 1.626a.75.75 0 0 1-1.495-.123c.041-.495.062-.996.062-1.503a3 3 0 0 0-3-3Zm0 2.25a.75.75 0 0 1 .75.75c0 3.908-1.424 7.485-3.781 10.238a.75.75 0 0 1-1.14-.975A14.19 14.19 0 0 0 11.25 10.5a.75.75 0 0 1 .75-.75Zm3.239 5.183a.75.75 0 0 1 .515.927 19.417 19.417 0 0 1-2.585 5.544.75.75 0 0 1-1.243-.84 17.915 17.915 0 0 0 2.386-5.116.75.75 0 0 1 .927-.515Z" clip-rule="evenodd" />
                                        </svg>

                                    </span>
                                    <input id="verificarContrasena" name="verificarContrasena" type="password" placeholder="" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <button type="submit" class="btn mt-4">Agregar Estudiante</button>
            </form>

        </div>

        <input
            type="radio"
            name="my_tabs_2"
            role="tab"
            class="tab"
            aria-label="Editar" />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Fecha de Nacimiento</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Género</th>
                            <th scope="col">Email</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                  
                        // Consulta para obtener los datos de los estudiantes
                        $sql = "SELECT * FROM estudiantes";

                        $result = $conexion->query($sql);

                        if ($result && $result->num_rows > 0) {
                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<th scope='row'>$i</th>";
                                echo "<td>" . $row['nombre'] . "</td>";
                                echo "<td>" . $row['apellido'] . "</td>";
                                echo "<td>" . $row['fecha_nacimiento'] . "</td>";
                                echo "<td>" . $row['documento_identidad'] . "</td>";
                                echo "<td>" . $row['genero'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                // echo "<td>" . $row['nombre_grupo'] . " (" . $row['nombre_a'] . ")</td>";
                                echo "<td>
                        <a class='btn' href='editar_estudiante?documento_identidad=" . $row['documento_identidad'] . "'>Editar</a>
                    </td>";
                                echo "</tr>";
                                $i++;
                            }
                            $result->free();
                        } else {
                            echo '<tr><td colspan="7">No hay datos de estudiantes disponibles.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<?php
include_once __DIR__ . '/../footer.php';
?>