<?php
// index.php

// Capturar la URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '/';

// Definir rutas
$routes = [
    // HOME
    '/' => 'App/Login/login.php',
    'dashboard' => 'App/dashboard.php',

    // Login
    'login' => 'App/Login/login.php',
    'logout' => 'App/Login/logout.php',
    'loginA' => 'App/Login/loginA.php',

    // Admin
    'perfiladmin' => 'App/Admin/perfil.php',
    'cursosadmin' => 'App/Admin/cursos.php',
    'usuariosadmin' => 'App/Admin/usuarios.php',
    'videos_alf_admin' => 'App/Admin/videos_alf.php',
    'videos_admin' => 'App/Admin/videos.php',
    'save_video_alf' => 'App/Admin/save_video_alf.php',
    'edit_video_alf' => 'App/Admin/edit_video_alf.php',
    'delete_video_alf' => 'App/Admin/delete_video_alf.php',
    'edit_video' => 'App/Admin/edit_video.php',
    'save_video' => 'App/Admin/save_video.php',
    'delete_video' => 'App/Admin/delete_video.php',
    'procesar_registro_docente' => 'App/Admin/procesar_registro_docente.php',
    'editar_docente' => 'App/Admin/editar_docente.php',
    'procesar_editar_docente' => 'App/Admin/procesar_editar_docente.php',
    'procesar_registro_estudiante' => 'App/Admin/procesar_registro_estudiante.php',
    'editar_estudiante' => 'App/Admin/editar_estudiante.php',
    'procesar_editar_estudiante' => 'App/Admin/procesar_editar_estudiante.php',
    'uppdf_admin' => 'App/Admin/uppdf.php',
    'docentes' => 'App/Admin/docentes.php',
    'estudiantes' => 'App/Admin/estudiantes.php',

    // Docentes
    'nasayuwe' => 'App/Docentes/nasayuwe.php',
    'foro' => 'App/Docentes/foro.php',
    'procesar_foro' => 'App/Docentes/procesar_foro.php',
    'procesar_comentario' => 'App/Docentes/procesar_comentario.php',
    'load_comments' => 'App/Docentes/load_comments.php',
    'admin_cursos' => 'App/Docentes/admin_cursos.php',
    'preguntas' => 'App/Docentes/preguntas.php',
    'procesar_examen.php' => 'App/Docentes/procesar_examen.php',


    'index_docente' => 'App/Docentes/index_docente.php',
    'perfildocente' => 'App/Docentes/perfildocente.php',
    'cursosdocentes' => 'App/Docentes/cursosdocentes.php',
    'videos_alf_docente' => 'App/Docentes/videos_alf_docente.php',
    'videos_docentes' => 'App/Docentes/videos_docentes.php',
    'evaluaciones' => 'App/Docentes/evaluaciones.php',
    'update' => 'App/Docentes/update.php',
    'account' => 'App/Docentes/account.php',
    'uppdf_docente' => 'App/Docentes/uppdf.php',
    'save_video_alf_docentes' => 'App/Docentes/save_video_alf_docentes.php',
    'edit_video_alf_docentes' => 'App/Docentes/edit_video_alf_docentes.php',
    'delete_video_alf_docente' => 'App/Docentes/delete_video_alf_docente.php',
    'save_video_docente' => 'App/Docentes/save_video_docente.php',
    'edit_video_docente' => 'App/Docentes/edit_video_docente.php',
    'delete_video_docente' => 'App/Docentes/delete_video_docente.php',

    //
    'dashboard_estudiantes' => 'App/Estudiantes/dashboard_estudiantes.php',
    'cursos_estudiantes' => 'App/Estudiantes/cursos_estudiantes.php',
    'videos_estudiantes_alf' => 'App/Estudiantes/videos_estudiantes_alf.php',
    'videos_estudiantes' => 'App/Estudiantes/videos_estudiantes.php',
    'evaluaciones_estudiantes' => 'App/Estudiantes/evaluaciones_estudiantes.php',
    'account_estudiante' => 'App/Estudiantes/account_estudiante.php',
    'update_estudiante' => 'App/Estudiantes/update_estudiante.php',

    // Cursos


    // Bot
    'bot' => 'App/Estudiantes/bot.php',
    'asisbot' => 'App/Estudiantes/asisbot.php',
    'uppdf_alf' => 'App/Estudiantes/uppdf_alf.php',
    'upload_alf' => 'App/Estudiantes/upload_alf.php',
    'uppdf' => 'App/Estudiantes/uppdf.php',
    'upload' => 'App/Estudiantes/upload.php',

];

// Manejar la solicitud
if (array_key_exists($url, $routes)) {
    include $routes[$url];
} else {
    include '404.php'; // Archivo de error 404
}
