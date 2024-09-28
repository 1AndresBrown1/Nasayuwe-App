<?php
	date_default_timezone_set('America/Mexico_City');
	include_once __DIR__ . '/../header.php';
	
	if(isset($_POST['save'])){
		$video_url = $_POST['video_url'];
		$video_name = $_POST['video_name']; 
		$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : ''; // Asegurar que se defina la descripción
        $category = isset($_POST['category']) ? $_POST['category'] : ''; // Asegurar que se defina la categoría
		
		// Verificar si el enlace es de YouTube
		if (strpos($video_url, 'youtube.com') !== false) {
			// Extraer el ID del video de YouTube del URL
			$video_id = explode('=', $video_url)[1];
			$name = $video_name;
			$location = 'https://www.youtube.com/watch?v=' . $video_id; // La ubicación es el propio enlace de YouTube
			
			// Insertar el video en la base de datos
			// Asegurarse de que la tabla tenga exactamente cuatro columnas: id, video_name, location, descripcion, category (si la columna category existe en la tabla)
			mysqli_query($conn, "INSERT INTO `video_alf` (video_name, location, descripcion, category) VALUES ('$name', '$location', '$descripcion', '$category')") or die(mysqli_error($conn));
			header("Location: videos_alf_admin?status1=success1");
		} else {
			// Si no es un enlace de YouTube, mostrar un mensaje de error
			header("Location: videos_alf_admin?status1=error1");
		}
	}
?>
