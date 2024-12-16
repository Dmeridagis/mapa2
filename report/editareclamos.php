<?php
include 'consultareclamos.php'; // Asegúrate de que esto incluya correctamente tu lógica de $reclamos
$reclamos = $reclamos ?? []; // Asigna el valor de $reclamos a $sa. Si $reclamos no está definido, se asigna un array vacío.
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <title>Editar Reclamos</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/leaflet.css">
    <link href="../css/leaflet.draw.css" rel="stylesheet" type="text/css"/>    
    <link rel="stylesheet" href="../osm/Control.OSMGeocoder.css"/>
  </head>
  <body>
    <div id="container">
      <div class="row">
        <div class="col-md-3" style="padding-left: 30px;">
          <form method="post" id="edit_form_reclamo"><?php
include 'libreria.php';

$codigo = intval($_GET['codigo'] ?? 0);

if ($codigo > 0) {
    // Consulta directa a la base de datos para obtener el reclamo específico
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

    $reclamo = consultaGeojson2($sql);
} else {
    $reclamo = '{"error": "Código inválido"}';
}
?>

            <br />
            <label> Código</label>
            <input type="text" name="txtcodigo" id="txtcodigo" class="form-control" readonly />
            <br />
            <label> Tipo de Reclamo</label>
            <input type="text" name="txttipo" id="txttipo" class="form-control" />
            <br />
            <label> Mensaje</label>
            <textarea name="txtmensaje" id="txtmensaje" class="form-control" rows="3"></textarea>
            <br />
            <label> Fecha</label>
            <input type="date" name="txtfecha" id="txtfecha" class="form-control" />
            <br />
            <label> ID Vecino</label>
            <input type="number" name="txtidvecino" id="txtidvecino" class="form-control" />
            <br />
            <label> ID Distrito</label>
            <input type="number" name="txtiddistrito" id="txtiddistrito" class="form-control" />
            <br />
            <label> Imagen</label>
            <input type="text" name="txtimagen" id="txtimagen" class="form-control" placeholder="URL de la imagen" />
            <br />
            <label> Coordenadas</label>
            <textarea name="txtgeom" id="txtgeom" class="form-control" rows="3" readonly></textarea>
            <br />
            <input type="submit" name="editreclamo" id="editreclamo" value="Actualizar" class="btn btn-success" />
          </form>
        </div>
        <div class="col-md-9">
          <div id="map" style="height: 600px"></div>
        </div>
      </div>
    </div>
    <script src="../js/jquery-2.1.4.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/leaflet.js"></script>
    <script src="../js/leaflet.draw.js"></script>
    <script src="../osm/Control.OSMGeocoder.js"></script>
    <script>
      var map = L.map("map", {
        zoom: 14,
        center: [-33.00759, -68.654432],
        zoomControl: false,
        attributionControl: false
      });

      var osm = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 21
      }).addTo(map);

      var osmGeocoder = new L.Control.OSMGeocoder({
        collapsed: true,
        text: 'Buscar'
      });
      map.addControl(osmGeocoder);

      var jsonPHP = JSON.parse('<?php echo $reclamos; ?>');

      var geojson = new L.GeoJSON(jsonPHP, {
        onEachFeature: function(feature, marker) {
          marker.bindPopup('<h4>' + feature.properties.tipo + '</h4><p>' + feature.properties.mensaje + '</p>');
        }
      }).addTo(map);
      map.fitBounds(geojson.getBounds());

      map.on('draw:edited', function(e) {
        var layers = e.layers;
        layers.eachLayer(function(layer) {
          $('#txtgeom').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
        });
      });

      if (jsonPHP) {
        $('#txtcodigo').val(jsonPHP['features'][0]['properties']['id']);
        $('#txttipo').val(jsonPHP['features'][0]['properties']['tipo']);
        $('#txtmensaje').val(jsonPHP['features'][0]['properties']['mensaje']);
        $('#txtfecha').val(jsonPHP['features'][0]['properties']['fecha']);
        $('#txtidvecino').val(jsonPHP['features'][0]['properties']['id_vecino']);
        $('#txtiddistrito').val(jsonPHP['features'][0]['properties']['id_distrito']);
        $('#txtimagen').val(jsonPHP['features'][0]['properties']['imagen']);
        $('#txtgeom').val(JSON.stringify(jsonPHP['features'][0]['geometry']));
      }

      $(document).ready(function() {
        $('#edit_form_reclamo').on("submit", function(event) {
          event.preventDefault();
          if ($('#txttipo').val() == "") {
            alert("El tipo de reclamo es requerido");
          } else if ($('#txtmensaje').val() == "") {
            alert("El mensaje es requerido");
          } else {
            $.ajax({
              url: "editareclamos.php",
              method: "POST",
              data: $('#edit_form_reclamo').serialize(),
              beforeSend: function() {
                $('#editreclamo').val("Actualizando...");
              },
              success: function(data) {
                alert("Reclamo actualizado correctamente.");
                location.reload();
              }
            });
          }
        });
      });
    </script>
  </body>
</html>
