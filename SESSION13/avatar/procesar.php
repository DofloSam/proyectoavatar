<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$baseDeDatos = "bdapps";



if (isset($_FILES["inputImg"])){
    $nombre = $_FILES["inputImg"]["name"];
    $extension = pathinfo($nombre, PATHINFO_EXTENSION);
    
    $nombre = "imagen_".date("Y_m_d_His").".".$extension;

    $temporal = $_FILES["inputImg"]["tmp_name"];
    move_uploaded_file($temporal, "imagenes/$nombre");

    $conn = new mysqli($servidor, $usuario, $contraseña, $baseDeDatos); 

    // Guardamos la imagen en la BD
    $sql = "INSERT INTO imagenes(imagen) VALUES ('$nombre')";
 
    
    $conn->query($sql); 
  
}

$conn = new mysqli($servidor, $usuario, $contraseña, $baseDeDatos);
$sql = "SELECT imagen FROM imagenes ORDER BY id_imagenes DESC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Galería de Imágenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .upload-form {
            margin-bottom: 30px;
            text-align: center;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        
        .upload-form input[type="file"] {
            margin-right: 10px;
        }
        
        .upload-form button {
            padding: 8px 16px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .upload-form button:hover {
            background: #45a049;
        }
        
        .galeria {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        
        .imagen-container {
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .imagen-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .imagen-galeria {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        
        .mensaje {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="upload-form">
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="inputImg" accept="image/*" required>
            <button type="submit">Subir Imagen</button>
        </form>
    </div>

    <div class="galeria">
        <?php
        if ($resultado->num_rows > 0) {
            while($fila = $resultado->fetch_assoc()) {
                echo '<div class="imagen-container">';
                echo '<img src="imagenes/'.$fila["imagen"].'" class="imagen-galeria" alt="Imagen subida">';
                echo '</div>';
            }
        } else {
            echo '<p class="mensaje">No hay imágenes en la galería. Sube la primera!</p>';
        }
        $conn->close();
        ?>
    </div>
</body>
</html>