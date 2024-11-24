<?php
include_once 'config/Database.php';
include_once 'classes/CRUD.php';


$database = new Database();
$db = $database->getConnection();
$crud = new CRUD($db);




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $nombre_producto = $_POST['nombre_producto'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];

    $result = $crud->create($nombre_producto, $categoria, $precio, $cantidad, $proveedor);

    header("Location: index.php?created=true");
    exit;
}


?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="body">

<header class="bg-primary text-white text-center py-3">
    <h1>Gestión de Inventario</h1>
</header>

<main class="container mt-5">
    <h2>Añadir Nuevo Producto</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="nombre_producto" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <input type="text" class="form-control" id="categoria" name="categoria" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
        </div>
        <div class="mb-3">
            <label for="proveedor" class="form-label">Proveedor</label>
            <input type="text" class="form-control" id="proveedor" name="proveedor" required>
        </div>
        <button type="submit" class="btn btn-success">Crear Producto</button>

    </form>

</main>

<footer class="footer">
    <p>&copy; <?= date("Y") ?> Gestión de Inventario. Todos los derechos reservados.</p>
</footer>

</body>
</html>
