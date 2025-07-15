<?php
$conexion = new mysqli("localhost", "root", "", "bdapps");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_usuarios'])) {
    $stmt = $conexion->prepare("UPDATE usuarios SET nickname=?, nombre=?, apellido=?, email=?, telefono=?, fecha_nac=? WHERE id_usuarios=?");
    $stmt->bind_param("ssssssi", $data['nickname'], $data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['fecha_nac'], $data['id_usuarios']);

    if ($stmt->execute()) {
        echo "Usuario actualizado con éxito.";
    } else {
        http_response_code(500);
        echo "Error al actualizar el usuario.";
    }

    $stmt->close();
}
$conexion->close();
?>
