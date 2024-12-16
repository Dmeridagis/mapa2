<?php
include 'config.php';
$cnx="";

function conectar(){
    global $cnx;
	$cadena="host=".HOST." port=".PORT." dbname=".BASE." user=".USER." password=".PASS." options='-c client_encoding=utf8'";
    $cnx=pg_connect($cadena);
}
function desconectar(){
    global $cnx;
    pg_close($cnx);
}
function consultar($sql=''){
    global $cnx;
    conectar();
    $bolsa=pg_query($cnx,$sql); // Ejecuta la consulta SQL y almacena el resultado en $bolsa
    if(pg_num_rows($bolsa)>0){
            while($f=pg_fetch_assoc($bolsa)){
                $datos[]=$f; // Si hay filas, las almacena en un array asociativo $datos
            }
        }else{
            $datos=array(); // Si no hay filas, $datos es un array vacío
        }
	pg_free_result($bolsa); // Libera la memoria asociada con el resultado
        unset($bolsa); // Destruye la variable $bolsa
        unset($f); // Destruye la variable $f
	desconectar(); // Cierra la conexión a la base de datos
    //return $salida;
	return $datos; // Devuelve el array asociativo con los datos obtenidos
}


//consultaAsociado($sql=''): Similar a consultar(), pero esta función devuelve el resultado como un objeto en lugar de un array asociativo.

//json_encode(): Convierte el array asociativo a JSON.

//json_decode(): Convierte el JSON a un objeto PHP.
function consultaAsociado($sql=''){
    global $cnx;
    conectar();
    $bolsa=pg_query($cnx,$sql);
    if(pg_num_rows($bolsa)>0){
            while($f=pg_fetch_assoc($bolsa)){
                $datos[]=$f;
            }
        }else{
            $datos=array();
        }
	pg_free_result($bolsa);
        unset($bolsa);
        unset($f);
	desconectar();
    //return $salida;
	return json_decode(json_encode($datos),false); // objeto json_decode(): Convierte el JSON a un objeto PHP.
}

function consultaGeojson($sql=''){
    global $cnx;
    conectar();
	$datos=[];
    $bolsa=pg_query($cnx,$sql);
    if(pg_num_rows($bolsa)>0){
            while($f=pg_fetch_assoc($bolsa)){
				    $feature = array(
					'type'=>'Feature');
					$feature['geometry'] = json_decode($f['geom']);
					unset($f['geom']);
					$feature['properties'] = $f;
					array_push($datos, $feature);
            }
			$featureCollection = ['type'=>'FeatureCollection', 'features'=>$datos];
        }else{
            $featureCollection=array();
        }
	pg_free_result($bolsa);
        unset($bolsa);
        unset($f);
	desconectar();
	return json_decode(json_encode($featureCollection),true);
}

function consultaGeojson2($sql=''){
    global $cnx;
    conectar();
	$datos=[];
    $bolsa=pg_query($cnx,$sql);
    if(pg_num_rows($bolsa)>0){
            while($f=pg_fetch_assoc($bolsa)){
				    $feature = array(
					'type'=>'Feature');
					$feature['geometry'] = json_decode($f['geom']);
					unset($f['geom']);
					$feature['properties'] = $f;
					array_push($datos, $feature);
            }
			$featureCollection = ['type'=>'FeatureCollection', 'features'=>$datos];
        }else{
            $featureCollection=array();
        }
	pg_free_result($bolsa);
        unset($bolsa);
        unset($f);
	desconectar();
	return json_encode($featureCollection);
}

function ejecutar($sql){
    global $cnx;
    conectar();
    $exito=pg_query($cnx,$sql);
	
    if($exito==true or $exito==1){
        return 1;
    }else{
        return 0;
    }
	pg_free_result($exito);
	desconectar();
}

?>