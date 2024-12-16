<?php
session_start(); // Asegurarse de que la sesión esté iniciada
include 'libreria.php'; // Archivo para manejar consultas a la base de datos

// Capturar datos del formulario
$usuario = $_POST['login_usuario'];
$clave = $_POST['login_clave'];

// Consulta para verificar las credenciales del usuario
$q = "SELECT * FROM public.usuarios WHERE usuario = '$usuario' AND clave = '$clave'";

$w = consultar($q);

// Verificar el número de registros encontrados
if (count($w) === 0) {
    // Si no hay coincidencias, destruir sesión y mostrar alerta
    session_destroy();
    echo "<script>
        alert('Usuario o contraseña incorrectos.');
        document.location.href='../formulario.html';
    </script>";
    exit();
} elseif (count($w) === 1) {
    // Usuario encontrado
    if ($w[0]["estado"] == 1) {
        // Estado activo, iniciar sesión
        $_SESSION['key'] = 'PHP1.$#@'; // Marcador de sesión
        $_SESSION['user_role'] = $w[0]['usuario']; // Rol del usuario
        $_SESSION['id_vecino'] = $w[0]['id']; // ID del usuario

        // Redirigir al inicio general
        header('Location: ../pageuser.php');
        exit();
    } else {
        // Usuario con estado desactivado
        session_destroy();
        echo "<script>
            alert('Usuario desactivado. Contacte al administrador.');
            document.location.href='../formulario.html';
        </script>";
        exit();
    }
} else {
    // Casos inesperados (ejemplo: múltiples registros coinciden, lo cual no debería suceder)
    session_destroy();
    echo "<script>
        alert('Error en el sistema. Contacte al soporte técnico.');
        document.location.href='../formulario.html';
    </script>";
    exit();
}
?>

