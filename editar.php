<?php
include_once 'config/Database.php';
include_once 'classes/CRUD.php';

$database = new Database();
$db = $database->getConnection();
$crud = new CRUD($db);

// Validar el ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_producto = $_POST['nombre_producto'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];

    // Intentar actualizar el producto
    if ($crud->update($id, $nombre_producto, $categoria, $precio, $cantidad, $proveedor)) {
        // Redirigir a la página de inicio con parámetro 'updated=true'
        header("Location: index.php?updated=true");
        exit; // Asegurarse de que no haya más ejecución del código
    } else {
        // Redirigir a la página de inicio con parámetro 'updated=false' en caso de error
        header("Location: index.php?updated=false");
        exit;
    }
} else {
    // Si no es una solicitud POST, mostramos el producto para editar
    $stmt = $crud->read();
    $producto = null;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['id'] == $id) {
            $producto = $row;
            break;
        }
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="body">

<div class="container mt-5">
    <h2>Editar Producto</h2>
    <?php if ($producto): ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre_producto" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" value="<?= $producto['nombre_producto'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="<?= $producto['categoria'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio" value="<?= $producto['precio'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?= $producto['cantidad'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor" value="<?= $producto['proveedor'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

    <?php else: ?>
        <p>Producto no encontrado.</p>
    <?php endif; ?>
</div>
<footer class="footer">
    <p>&copy; <?= date("Y") ?> Gestión de Inventario. Todos los derechos reservados.</p>
</footer>
</body>
</html>
