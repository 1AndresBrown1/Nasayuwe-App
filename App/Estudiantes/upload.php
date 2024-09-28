<?php
session_start();
include_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vid = intval($_POST['vid']);
    $documento_identidad = $_SESSION['identificacion']; // Del estudiante que inició sesión
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $type = 'pdf';

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($file_ext == 'pdf') {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_file_name = uniqid('pdf_', true) . '.' . $file_ext;
            $file_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $stmt = $conn->prepare("INSERT INTO files (vid, documento_identidad, title, description, url, type) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('iissss', $vid, $documento_identidad, $title, $description, $file_path, $type);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('El archivo PDF se ha subido y guardado exitosamente.');
                            window.location.href = 'videos_estudiantes';
                          </script>";
                } else {
                    echo "<script>
                            alert('Error al guardar en la base de datos: " . $stmt->error . "');
                            window.location.href = 'videos_estudiantes';
                          </script>";
                }
                $stmt->close();
            } else {
                echo "<script>
                        alert('Error al mover el archivo.');
                        window.location.href = 'videos_estudiantes';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Por favor, sube un archivo PDF válido.');
                    window.location.href = 'videos_estudiantes';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error en la subida del archivo.');
                window.location.href = 'videos_estudiantes';
              </script>";
    }
}
?>
