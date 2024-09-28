<?php
session_start();
include_once __DIR__ . '/../db.php';
// Verifica si ya hay una sesión activa
if (isset($_SESSION['nombre_usuario'])) {
  // Verifica si el usuario es un administrador
  if ($_SESSION['docente'] == 'docente') {
    // Si el usuario es un administrador, rediríjalo al index.php
    $message = 'Docente';
  } else {
    // Si el usuario no es un administrador, rediríjalo a la página correspondiente según el tipo de usuario
    if ($_SESSION['estudiante'] === "estudiante") {
      header('Location: dashboard_estudiantes');
      exit();
    } elseif ($_SESSION['admin'] === "admin") {
      header("Location: ../Admin/index.php");
      exit();
    }
  }
} else {
  // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
  header('Location: login');
  exit();
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>document</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


</head>

<body>


  <div class="drawer lg:drawer-open">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
      <!-- Page content here -->