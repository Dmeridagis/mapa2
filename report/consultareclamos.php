<?php

include 'libreria.php';

// Verificar que el parámetro 'codigo' se reciba y sea un número entero
if (!isset($_GET['codigo']) || !is_numeric($_GET['codigo'])) {
    echo json_encode(['error' => 'Parámetro inválido']);
    exit;
}

// Convertir el parámetro a un entero para mayor seguridad
$codigo = intval($_GET["codigo"]);

// Consulta para obtener un reclamo específico de la tabla 'reclamos_vecinos'
$sql = "
    SELECT 
        id_acciones AS id,
        tipo,
        mensaje,
        fecha,
        imagen,
        id_vecino,
        id_distrito,
        ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geom
    FROM public.reclamos_vecinos
    WHERE id_acciones = $codigo";

// Ejecutar la consulta y obtener los datos en formato GeoJSON
$reclamo = consultaGeojson2($sql);

// Validar si se obtuvo un resultado
if ($reclamo) {
    echo $reclamo;
} else {
    echo json_encode(['error' => 'No se encontró el reclamo']);
}

?>

