<?php
include 'libreria.php';

// Obtener el ID del reclamo desde la URL
$id = $_GET['id'];

// Consulta para obtener la imagen del reclamo
$sql = "SELECT imagen FROM public.reclamos_vecinos WHERE id_acciones = $id";
$resultado = consultar($sql);

// Verificar si existe la imagen
if (!empty($resultado[0]['imagen'])) {
    // Enviar los encabezados para una imagen binaria
    header("Content-Type: image/jpeg");
    echo pg_unescape_bytea($resultado[0]['imagen']);
} else {
    // Si no hay imagen, devolver un marcador de error o imagen por defecto
    header("Content-Type: image/png");
    readfile("default-image.png"); // Imagen por defecto
}
?>
