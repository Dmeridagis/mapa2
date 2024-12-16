<?php
session_start();
include 'libreria.php';

// Obtener los datos del formulario de registro
$usuario = $_POST['reg_usuario'];
$clave = $_POST['reg_clave'];
$email = isset($_POST['reg_email']) ? trim($_POST['reg_email']) : null;
$dni = $_POST['reg_dni'];
$fechanac = $_POST['reg_fechanac'];
$direccion = $_POST['reg_direccion'];

// Validar que el correo electrónico no sea vacío y tenga un formato válido
if (empty($email)) {
    echo "<script>
        alert('El campo de correo electrónico es obligatorio.');
        document.location.href='../formulario.html';
    </script>";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('Por favor ingrese un correo electrónico válido.');
        document.location.href='../formulario.html';
    </script>";
    exit();
}

// Verificar si el usuario, correo o DNI ya están registrados
$q = "SELECT * FROM public.usuarios WHERE usuario = '$usuario' OR email = '$email' OR dni = '$dni'";
$w = consultar($q);

if (count($w) > 0) {
    echo "<script>
        alert('El usuario, correo o DNI ya están registrados. Por favor, intente con otros datos.');
        document.location.href='../formulario.html';
    </script>";
    exit();
} else {
    // Intentar insertar el nuevo usuario en la base de datos
    try {
        $q = "INSERT INTO public.usuarios (usuario, clave, email, estado, direccion, fechanac, dni) 
              VALUES ('$usuario', '$clave', '$email', 1, '$direccion', '$fechanac', '$dni')";
        $conexion = pg_connect("host=localhost dbname=mapagis user=postgres password=postgres");
        if (!$conexion) {
            throw new Exception("Error al conectar a la base de datos.");
        }
    
        $resultado = pg_query($conexion, $q);
    
        if (!$resultado) {
            throw new Exception(pg_last_error($conexion)); // Captura el error específico
        }
    
        echo "<script>
            alert('Registro exitoso. ¡Ahora puede iniciar sesión!');
            document.location.href='../formulario.html';
        </script>";
        exit();
    } catch (Exception $e) {
        echo "<script>
            alert('Error al registrar: " . addslashes($e->getMessage()) . "');
            document.location.href='../formulario.html';
        </script>";
        exit();
    }
}
?>
