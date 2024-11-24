<?php
include_once 'config/Database.php';
include_once 'classes/CRUD.php';

$database = new Database();
$db = $database->getConnection();
$crud = new CRUD($db);

// Obtener el ID desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Verificar si el ID es válido (número entero)
if ($id && is_numeric($id)) {
    $id = (int)$id;  // Convertir a entero para mayor seguridad

    // Eliminar el producto llamando a la función delete() del CRUD
    if ($crud->delete($id)) {
        // Si la eliminación fue exitosa, redirigir a index.php con un mensaje
        header("Location: index.php?deleted=true");
        exit;
    } else {
        // Si no se pudo eliminar el producto, redirigir con error
        header("Location: index.php?deleted=false");
        exit;
    }
} else {
    // Si el ID no es válido o no se ha pasado en la URL
    header("Location: index.php?deleted=false");
    exit;
}
?>
