<?php
include 'libreria.php';

// Obtener el código del reclamo desde la URL
$codigo = $_GET['codigo'];

// Construir la consulta SQL para eliminar el reclamo
$q = "DELETE FROM public.reclamos_vecinos WHERE id_acciones = $codigo";

// Ejecutar la consulta
$ex = ejecutar($q);

// Verificar si la consulta fue exitosa y redirigir o mostrar un mensaje de error
if ($ex) {
    header('Location: ../mapa.php');
} else {
    echo "Error en ejecución. <br>$q";
}
?>
