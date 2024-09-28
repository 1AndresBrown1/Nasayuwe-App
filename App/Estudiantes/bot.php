<?php
include_once __DIR__ . '/../Estudiantes/header.php';
?>

<div class="container mx-auto p-4 mt-20 sm:mt-6">

    <div style="height: 75vh;" class="mockup-window bg-base-200 border overflow-auto">
        <div class="bg-base-200 px-4 py-16">
            <!--  -->
            <div class="card-body" id="form">
                <!-- Historial del chat se mostrará aquí -->
            </div>
            
            <!--  -->
        </div>
    </div>

    <div class="card-footer max-h-full mt-4">
                <form id="chat-form">
                    <!-- Campo oculto para almacenar el nombre del usuario -->
                    <input type="hidden" class="input input-bordered w-full max-w-xs" id="username" value="<?php echo $_SESSION['nombre_usuario']; ?>">
                    <!-- Campo oculto para almacenar la información de grado -->
                    <input type="hidden" id="grade" value="9no Grado">
                    <div class="flex gap-4">
                            <input type="text" class="input input-bordered w-full max-w-xs" id="data" placeholder="Escribe aquí..." required>
                            <button  type="button" id="send-btn" class="btn radius-10 px-5">Enviar</button>
                    </div>
                </form>

            </div>

</div>


<script>
    $(document).ready(function() {
        $("#chat-form").on("submit", function(event) {
            event.preventDefault(); // Evitar la recarga de la página
            enviarMensaje(); // Llama a la función para enviar el mensaje
        });

        $("#send-btn").on("click", function() {
            enviarMensaje(); // Llama a la función para enviar el mensaje
        });

        function enviarMensaje() {
            $valor = $("#data").val();
            $mensaje = '<div class="chat chat-end"><div class="chat-bubble">' + $valor + '</div></div>';
            $("#form").append($mensaje);
            $("#data").val('');

            // Acá inicia el código de ajax
            $.ajax({
                url: 'asisbot',
                type: 'POST',
                data: {
                    text: $valor,
                    username: $("#username").val(),
                    grade: $("#grade").val()
                },
                success: function(resultado) {
                    $respuesta = '<div class="chat chat-start"><div class="chat-bubble">' + resultado + '</div></div>';
                    $("#form").append($respuesta);
                    // Cuando el chat baja, la barra de desplazamiento llega automáticamente al final
                    $("#form").scrollTop($("#form")[0].scrollHeight);
                }
            });
        }
    });
</script>

<?php
include_once __DIR__ . '/../Estudiantes/footer.php';
?>