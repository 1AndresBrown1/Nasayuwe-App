<?php
include_once __DIR__ . '/../header.php';

// Obtiene el ID del docente a editar desde la URL
$documento_identidad = $_GET['documento_identidad'];

// Consulta SQL para obtener los datos del docente
$sql = "SELECT * FROM estudiantes WHERE documento_identidad = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $documento_identidad);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container p-4 mt-20 sm:mt-6">
    <h1 class="text-3xl mb-10 font-bold">Editar estudiantes</h1>

    <form action="procesar_editar_estudiante" method="POST">
        <input type="hidden" id="documento_identidad" name="documento_identidad" value="<?php echo $row['documento_identidad']; ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

            <div>
                <label for="nombre" class="block text-sm">Nombre</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input value="<?php echo $row['nombre']; ?>" type="text" placeholder="Nombre" id="nombre" name="nombre" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo $row['apellido']; ?>" id="apellido" name="apellido" type="text" placeholder="Apellido" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

          
            <!-- <div>
                <label for="tipo_documento" class="block text-sm">Sexo</label>
                <div class="relative flex items-center mt-2">

                    <select id="genero" class="select select-bordered w-full max-w-xs py-2.5" name="genero" required>
                    <option value="Masculino" <?php echo ($genero == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($genero == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo ($genero == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
            </div> -->

           

            <div>
                <label for="telefono" class="block text-sm">Teléfono</label>
                <div class="relative flex items-center mt-2">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-3">
                            <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input value="<?php echo $row['telefono']; ?>" id="telefono" name="telefono" type="number" placeholder="Teléfono" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                    <input value="<?php echo $row['fecha_nacimiento']; ?>" id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="fecha_nacimiento" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                            <input value="<?php echo $row['email']; ?>"   id="email" name="email" type="email" placeholder="Correo@gmail.com" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                            <input  value="<?php echo $contrasena; ?>"  id="contrasena" name="contrasena" type="password" placeholder="" class="block w-full py-2.5 border rounded-lg pl-11 pr-5 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        </div>
                    </div>


                 
                </div>

            </div>
        </div>

        <button type="submit" class="btn mt-4">Actualizar Estudiante</button>
    </form>

</div>



<?php
include_once __DIR__ . '/../footer.php';
?>