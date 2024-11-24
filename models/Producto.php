<?php
// models/Usuario.php

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del usuario
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo usuario (registro)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, email, password) 
                  VALUES (:nombre, :email, :password)";

        $stmt = $this->conn->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", password_hash($this->password, PASSWORD_DEFAULT)); // Usamos hash para la seguridad

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Autenticación de usuario (login)
    public function login() {
        $query = "SELECT id, nombre, email, password FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verificar la contraseña
            if (password_verify($this->password, $row['password'])) {
                // Asignar datos del usuario
                $this->id = $row['id'];
                $this->nombre = $row['nombre'];
                $this->email = $row['email'];
                return true;
            }
        }
        return false;
    }

    // Verificar si el email ya está registrado
    public function emailExiste() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
?>
