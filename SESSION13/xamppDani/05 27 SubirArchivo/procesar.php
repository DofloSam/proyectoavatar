<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$baseDeDatos = "bdapps";

// Procesar la imagen si viene del formulario
if (isset($_FILES["inputImg"])) {
    $nombre = $_FILES["inputImg"]["name"];
    $extension = pathinfo($nombre, PATHINFO_EXTENSION);
    $nombre = "imagen_" . date("Y_m_d_His") . "." . $extension;

    $temporal = $_FILES["inputImg"]["tmp_name"];

    // Crear carpeta si no existe
    if (!file_exists("imagenes")) {
        mkdir("imagenes", 0777, true);
    }

    move_uploaded_file($temporal, "imagenes/$nombre");

    // Guardar nombre en la base de datos
    $conn = new mysqli($servidor, $usuario, $contraseña, $baseDeDatos);
    $sql = "INSERT INTO imagenes(imagen) VALUES ('$nombre')";
    $conn->query($sql);
    $conn->close();
}

// Mostrar imágenes
$conn = new mysqli($servidor, $usuario, $contraseña, $baseDeDatos);
$sql = "SELECT imagen FROM imagenes";
$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $rutaImagen = "imagenes/" . $fila["imagen"];
        echo "<div class='col-md-3 text-center mb-4'>";
        echo "<img src='$rutaImagen' class='img-fluid img-thumbnail' style='max-height: 200px;'><br>";
        echo "<small>{$fila["imagen"]}</small>";
        echo "</div>";
    }
} else {
    echo "<p class='text-muted'>No hay imágenes guardadas.</p>";
}

$conn->close();
?>
