<?php
$conexion = new mysqli("localhost", "root", "", "bdapps");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Eliminar imagen si existe
    $res = $conexion->query("SELECT avatar FROM usuarios WHERE id_usuarios = $id");
    if ($res && $res->num_rows > 0) {
        $fila = $res->fetch_assoc();
        if (!empty($fila['avatar']) && file_exists("imagenes/" . $fila['avatar'])) {
            unlink("imagenes/" . $fila['avatar']);
        }
    }

    // Eliminar el usuario
    $conexion->query("DELETE FROM usuarios WHERE id_usuarios = $id");
}

$conexion->close();

// Redirigir de vuelta a la lista
header("Location: verUsuarios.php");
exit();
?>
