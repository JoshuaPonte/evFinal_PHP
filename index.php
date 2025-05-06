<?php include "includes/conexion.php";
// Conexión
$conexion = new mysqli("localhost", "root", "", "bs_usuarios");
if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);

// Variables iniciales
$editar = false;
$usuarioEditar = ['id' => '', 'nombre' => '', 'correo' => ''];

// Si se va a editar
if (isset($_GET['editar'])) {
    $editar = true;
    $idEditar = (int)$_GET['editar'];
    $resultado = $conexion->query("SELECT * FROM usuarios WHERE id = $idEditar");
    if ($resultado->num_rows > 0) {
        $usuarioEditar = $resultado->fetch_assoc();
    }
}

// Guardar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['actualizar'])) {
        $id = (int)$_POST['id'];
        $nombre = $conexion->real_escape_string($_POST['nombre']);
        $correo = $conexion->real_escape_string($_POST['correo']);
        $conexion->query("UPDATE usuarios SET nombre = '$nombre', correo = '$correo' WHERE id = $id");
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['agregar'])) {
        $nombre = $conexion->real_escape_string($_POST['nombre']);
        $correo = $conexion->real_escape_string($_POST['correo']);
        $conexion->query("INSERT INTO usuarios (nombre, correo) VALUES ('$nombre', '$correo')");
        header("Location: index.php");
        exit();
    }
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conexion->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .table-container { margin-top: 20px; }
        .table-actions a { margin: 0 5px; }
        h1 { margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center">Gestión de Usuarios</h1>

    <!-- Formulario agregar/editar -->
    <div class="form-container">
        <form class="row g-3 align-items-center" method="POST" action="">
            <input type="hidden" name="id" value="<?= $usuarioEditar['id'] ?>">
            <div class="col-auto">
                <input type="text" class="form-control" name="nombre" placeholder="Nombre" required
                       value="<?= htmlspecialchars($usuarioEditar['nombre']) ?>">
            </div>
            <div class="col-auto">
                <input type="email" class="form-control" name="correo" placeholder="Correo Electrónico" required
                       value="<?= htmlspecialchars($usuarioEditar['correo']) ?>">
            </div>
            <div class="col-auto">
                <?php if ($editar): ?>
                    <button type="submit" class="btn btn-primary" name="actualizar">Actualizar Usuario</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                <?php else: ?>
                    <button type="submit" class="btn btn-success" name="agregar">Agregar Usuario</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <hr>
    <h2 class="mb-3">Lista de Usuarios</h2>

    <!-- Tabla de usuarios -->
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $resultado = $conexion->query("SELECT * FROM usuarios ORDER BY id");
                while ($fila = $resultado->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['correo']) ?></td>
                        <td class="table-actions">
                            <a href="index.php?editar=<?= $fila['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="index.php?eliminar=<?= $fila['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conexion->close(); ?>
