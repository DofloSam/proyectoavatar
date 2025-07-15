<?php
$conexion = new mysqli("localhost", "root", "", "bdapps");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$resultado = $conexion->query("SELECT * FROM usuarios");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #8e9eab);
            font-family: 'Poppins', sans-serif;
            padding: 40px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        img.avatar {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            cursor: pointer;
        }

        .btn-custom {
            background-color: #82a2d0;
            color: white;
            border: none;
        }

        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Usuarios Registrados</h2>

    <div class="search-bar">
        <input type="text" id="busqueda" class="form-control w-25" placeholder="Buscar...">
    </div>

    <?php if ($resultado->num_rows > 0): ?>
        <div class='table-responsive'>
            <table class='table table-bordered table-hover' id='tablaUsuarios'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Nickname</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha Nac.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id_usuarios'] ?></td>
                        <td>
                            <img src="imagenes/<?= !empty($fila['avatar']) ? $fila['avatar'] : 'soloavatar.jpg' ?>" class="avatar" alt="Avatar">
                        </td>
                        <td><?= $fila['nickname'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td><?= $fila['apellido'] ?></td>
                        <td><?= $fila['email'] ?></td>
                        <td><?= $fila['telefono'] ?></td>
                        <td><?= $fila['fecha_nac'] ?></td>
                        <td>
                            <a href='borrar.php?id=<?= $fila['id_usuarios'] ?>' class='btn btn-danger btn-sm' onclick="return confirm('¿Borrar este usuario?');">Borrar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class='text-center'>No hay usuarios registrados.</p>
    <?php endif; ?>
    
    <div class="text-center mt-4">
        <a href="index.html" class="btn btn-custom btn-lg">Volver al Formulario</a>
    </div>
</div>

<!-- MODAL de Perfil de Usuario -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Perfil de Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formPerfil">
          <input type="hidden" id="modal-id">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Nickname:</label>
              <input type="text" class="form-control" id="modal-nickname" readonly>
            </div>
            <div class="col-md-6">
              <label>Nombre:</label>
              <input type="text" class="form-control" id="modal-nombre" readonly>
            </div>
            <div class="col-md-6">
              <label>Apellido:</label>
              <input type="text" class="form-control" id="modal-apellido" readonly>
            </div>
            <div class="col-md-6">
              <label>Email:</label>
              <input type="email" class="form-control" id="modal-email" readonly>
            </div>
            <div class="col-md-6">
              <label>Teléfono:</label>
              <input type="text" class="form-control" id="modal-telefono" readonly>
            </div>
            <div class="col-md-6">
              <label>Fecha de Nacimiento:</label>
              <input type="date" class="form-control" id="modal-fecha" readonly>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="btnEditar" class="btn btn-warning btn-sm">Editar</button>
        <button id="btnGuardar" class="btn btn-success btn-sm d-none">Guardar</button>
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    // Búsqueda
    $('#busqueda').on('keyup', function () {
        let valor = $(this).val().toLowerCase();
        $('#tablaUsuarios tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Click en Avatar para abrir modal
    $('img.avatar').on('click', function () {
        const $fila = $(this).closest('tr');
        $('#modal-id').val($fila.find('td:eq(0)').text().trim());
        $('#modal-nickname').val($fila.find('td:eq(2)').text().trim());
        $('#modal-nombre').val($fila.find('td:eq(3)').text().trim());
        $('#modal-apellido').val($fila.find('td:eq(4)').text().trim());
        $('#modal-email').val($fila.find('td:eq(5)').text().trim());
        $('#modal-telefono').val($fila.find('td:eq(6)').text().trim());
        $('#modal-fecha').val($fila.find('td:eq(7)').text().trim());

        $('#formPerfil input').prop('readonly', true);
        $('#btnEditar').removeClass('d-none');
        $('#btnGuardar').addClass('d-none');

        new bootstrap.Modal(document.getElementById('perfilModal')).show();
    });

    // Botón Editar en Modal
    $('#btnEditar').click(function () {
        $('#formPerfil input').prop('readonly', false);
        $(this).addClass('d-none');
        $('#btnGuardar').removeClass('d-none');
    });

    // Botón Guardar en Modal
    $('#btnGuardar').click(function () {
        const datos = {
            id_usuarios: $('#modal-id').val(),
            nickname: $('#modal-nickname').val(),
            nombre: $('#modal-nombre').val(),
            apellido: $('#modal-apellido').val(),
            email: $('#modal-email').val(),
            telefono: $('#modal-telefono').val(),
            fecha_nac: $('#modal-fecha').val()
        };

        $.ajax({
            url: 'editarinline.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datos),
            success: function () {
                alert('Datos actualizados correctamente.');
                location.reload();
            },
            error: function () {
                alert('Error al guardar los datos.');
            }
        });
    });
});
</script>
</body>
</html>
