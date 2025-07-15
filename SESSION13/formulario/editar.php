<?php
$conexion = new mysqli("localhost", "root", "", "bdapps");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el ID del usuario a editar
if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$id = intval($_GET['id']);

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = $conexion->real_escape_string($_POST['nickname']);
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $email = $conexion->real_escape_string($_POST['email']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $fecha_nac = $conexion->real_escape_string($_POST['fecha_nac']);
    $avatar = $_POST['avatar_actual']; // Avatar actual por defecto

    // Procesar nuevo avatar si se subió
    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === UPLOAD_ERR_OK) {
        $maxFileSize = 5 * 1024 * 1024;
        if ($_FILES["avatar"]["size"] > $maxFileSize) {
            die("El avatar excede el tamaño máximo permitido.");
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $nombreArchivo = $_FILES["avatar"]["name"];
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            die("Tipo de archivo no permitido.");
        }

        $nuevoAvatar = "avatar_" . date("Y_m_d_His") . "." . $extension;
        $temporal = $_FILES["avatar"]["tmp_name"];
        $rutaDestino = "imagenes/" . $nuevoAvatar;

        if (move_uploaded_file($temporal, $rutaDestino)) {
            // Eliminar avatar anterior si existe
            if (!empty($avatar) && file_exists("imagenes/" . $avatar)) {
                unlink("imagenes/" . $avatar);
            }
            $avatar = $nuevoAvatar;
        } else {
            die("Error al mover el nuevo archivo de avatar.");
        }
    }

    // Actualizar en la base de datos
    $sql = "UPDATE usuarios SET nickname=?, nombre=?, apellido=?, email=?, telefono=?, fecha_nac=?, avatar=? WHERE id_usuarios=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssi", $nickname, $nombre, $apellido, $email, $telefono, $fecha_nac, $avatar, $id);

    if ($stmt->execute()) {
        echo "<h2>Usuario actualizado correctamente.</h2>";
        echo "<a href='verUsuarios.php'>Volver a la lista</a>";
    } else {
        echo "<p>Error al actualizar: " . $conexion->error . "</p>";
    }

    $stmt->close();
    $conexion->close();
    exit();
}

// Obtener los datos actuales del usuario
$resultado = $conexion->query("SELECT * FROM usuarios WHERE id_usuarios = $id");
if ($resultado->num_rows !== 1) {
    die("Usuario no encontrado.");
}
$usuario = $resultado->fetch_assoc();
?>

<h2>Editar Usuario</h2>
<form action="editar.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">

    <label>Nickname: <input type="text" name="nickname" value="<?php echo htmlspecialchars($usuario['nickname']); ?>" required></label><br>
    <label>Nombre: <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required></label><br>
    <label>Apellido: <input type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required></label><br>
    <label>Teléfono: <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required></label><br>
    <label>Fecha de nacimiento: <input type="date" name="fecha_nac" value="<?php echo $usuario['fecha_nac']; ?>" required></label><br>
    <label>Avatar actual:</label><br>
    <?php if (!empty($usuario['avatar'])): ?>
        <img src="imagenes/<?php echo $usuario['avatar']; ?>" width="100"><br>
    <?php else: ?>
        Sin avatar<br>
    <?php endif; ?>
    <input type="hidden" name="avatar_actual" value="<?php echo $usuario['avatar']; ?>">
    <label>Cambiar Avatar: <input type="file" name="avatar"></label><br><br>
    <button type="submit">Guardar Cambios</button>
    <a href="verUsuarios.php">Cancelar</a>
</form>
