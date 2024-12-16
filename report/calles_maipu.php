<?php

include 'libreria.php';

function listaCallesMaipu()
{
    $sql = "select id, nombre, id_via, tipo, ruta_nac, ruta_prov, id_distrit, ST_AsGeoJSON(ST_Transform(geom,4326)) as geom from public.calles_maipu";
    $calles = consultaGeojson2($sql); // Envío como consulta SQL y devuelvo como consulta JSON
    return $calles;
}

echo listaCallesMaipu();

?>
