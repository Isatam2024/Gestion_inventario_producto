<?php
include_once 'config/Database.php';
include_once 'classes/CRUD.php';

$database = new Database();
$db = $database->getConnection();
$crud = new CRUD($db);

$stmt = $crud->read();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="body">

<header class="bg-primary text-white text-center py-3">
    <h1>Gestión de Inventario</h1>
</header>

<div class="content">
    <!-- Sidebar -->
    <nav class="sidebar custom-sidebar">
        <a href="#">Inicio</a>
        <a href="crear.php">Agregar Producto</a>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2>Lista de Productos</h2>
        <table class="table table-bordered">
            <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nombre Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Proveedor</th>
                <th>Fecha Agregado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre_producto'] ?></td>
                    <td><?= $row['categoria'] ?></td>
                    <td><?= $row['precio'] ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td><?= $row['proveedor'] ?></td>
                    <td><?= $row['fecha_agregado'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning btn-edit" data-id="<?= $row['id'] ?>">Editar</a>
                        <button class="btn btn-outline-danger btn-delete" data-id="<?= $row['id'] ?>">Eliminar</button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="footer">
    <p>&copy; <?= date("Y") ?> Gestión de Inventario. Todos los derechos reservados.</p>
</footer>

<script>
    // SweetAlert para eliminar
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a eliminar.php con el ID del producto
                    window.location.href = `eliminar.php?id=${productId}`;
                }
            });
        });
    });


    // Mostrar SweetAlert para actualizar producto
    const urlParams = new URLSearchParams(window.location.search);

    // Verificar si la URL contiene 'updated' y mostrar la alerta correspondiente
    if (urlParams.has('updated')) {
        const updated = urlParams.get('updated');

        if (updated === 'true') {
            Swal.fire({
                title: 'Producto editado',
                text: 'El producto ha sido editado exitosamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (updated === 'false') {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al editar el producto.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    }

    // Mostrar SweetAlert para agregar producto
    if (urlParams.has('created') && urlParams.get('created') === 'true') {
        Swal.fire({
            title: 'Producto agregado',
            text: 'El producto ha sido agregado correctamente',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    }


    if (urlParams.has('deleted')) {
        const deleted = urlParams.get('deleted');

        if (deleted === 'true') {
            Swal.fire({
                title: '¡Eliminado!',
                text: 'El producto ha sido eliminado exitosamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else if (deleted === 'false') {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo eliminar el producto. Intente nuevamente.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }
</script>

</body>
</html>
