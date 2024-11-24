<?php

class CRUD {
    private $conn;
    private $table_name = "productos";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo producto
    public function create($nombre_producto, $categoria, $precio, $cantidad, $proveedor) {
        $query = "INSERT INTO " . $this->table_name . " (nombre_producto, categoria, precio, cantidad, proveedor) 
                  VALUES (:nombre_producto, :categoria, :precio, :cantidad, :proveedor)";

        $stmt = $this->conn->prepare($query);

        // Validar los parámetros antes de ejecutar
        if (!$this->validate($nombre_producto, $categoria, $precio, $cantidad, $proveedor)) {
            return "Error: Datos inválidos.";
        }

        $stmt->bindParam(":nombre_producto", $nombre_producto);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":proveedor", $proveedor);

        try {
            if ($stmt->execute()) {
                return "success"; // Cambié el mensaje de éxito por 'success' para poder usarlo con SweetAlert
            }
            return "Error: No se pudo agregar el producto.";
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }

    // Leer todos los productos
    public function read() {
        $query = "SELECT id, nombre_producto, categoria, precio, cantidad, proveedor, fecha_agregado FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }

    // Leer un producto específico
    public function readOne($id) {
        $query = "SELECT id, nombre_producto, categoria, precio, cantidad, proveedor FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }

    // Actualizar un producto
    public function update($id, $nombre_producto, $categoria, $precio, $cantidad, $proveedor) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre_producto = :nombre_producto, categoria = :categoria, precio = :precio, 
                      cantidad = :cantidad, proveedor = :proveedor 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Validar los parámetros antes de ejecutar
        if (!$this->validate($nombre_producto, $categoria, $precio, $cantidad, $proveedor)) {
            return "Error: Datos inválidos.";
        }

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nombre_producto", $nombre_producto);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":proveedor", $proveedor);

        try {
            if ($stmt->execute()) {
                return "success"; // Cambié el mensaje de éxito por 'success' para poder usarlo con SweetAlert
            }
            return "Error: No se pudo actualizar el producto.";
        } catch (PDOException $e) {
            return "Error de base de datos: " . $e->getMessage();
        }
    }

    // Eliminar un producto
    public function delete($id) {
        // Verificar que el ID sea un número entero
        if (!is_numeric($id) || $id <= 0) {
            return false; // ID no válido
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT); // Asegúrate de que el parámetro sea un entero

        try {
            if ($stmt->execute()) {
                return true; // El producto fue eliminado correctamente
            }
            return false; // Si no se pudo eliminar
        } catch (PDOException $e) {
            error_log($e->getMessage()); // Registrar el error para depuración
            return false; // Si ocurre un error en la ejecución
        }
    }

    // Método para validar datos
    private function validate($nombre_producto, $categoria, $precio, $cantidad, $proveedor) {
        if (empty($nombre_producto) || empty($categoria) || empty($precio) || empty($cantidad) || empty($proveedor)) {
            return false;
        }
        if (!is_numeric($precio) || !is_numeric($cantidad)) {
            return false;
        }
        return true;
    }
}
?>
