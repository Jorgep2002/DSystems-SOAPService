<?php

require_once 'models/persona.php';
require_once 'config/datasourceManager.php';

class UserRepository {
    private $conexion;

    public function __construct(DataSourceManager $dataSourceManager) {
        $this->conexion = $dataSourceManager->getConexion();
    }

    // Método para registrar una persona
    public function addPerson($email, $password, $name, $status, $rol) {
        $stmt = null; // Definir $stmt antes del bloque try
        try {
            // Generar un ID único para el usuario
            $userId = uniqid('user_', true);
            
            // Preparar la consulta SQL
            $query = "INSERT INTO users (user_id, email, password, name, status, rol) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($query);

            if ($stmt === false) {
                throw new Exception('Error en la preparación de la consulta: ' . $this->conexion->error);
            }

            // Encriptar la contraseña antes de guardarla en la base de datos
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Vincular los parámetros a la consulta
            $stmt->bind_param("ssssss", $userId, $email, $hashedPassword, $name, $status, $rol);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception('Error al ejecutar la consulta: ' . $stmt->error);
            }
        } catch (Exception $e) {
            // Manejar la excepción
            return false;
        } finally {
            // Cerrar la declaración si fue inicializada
            if ($stmt !== null) {
                $stmt->close();
            }
        }
    }

    // Método para obtener todas las personas registradas
    public function getAllPersons() {
        $query = "SELECT user_id, email, name, status, rol FROM users";
        $result = $this->conexion->query($query);

        if ($result === false) {
            return [];
        }

        $personas = [];
        while ($row = $result->fetch_assoc()) {
            $personas[] = $row;
        }

        return $personas;
    }

    // Método para autenticar un usuario
    public function authenticate($email, $password) {
        $query = "SELECT user_id, email, password, name, status, rol FROM users WHERE email = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false;
        }

        $user = $result->fetch_assoc();
        return password_verify($password, $user['password']);
    }

    // Método para obtener un usuario por su email
    public function getUserByEmail($email) {
        $query = "SELECT user_id, email, name, status, rol FROM users WHERE email = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $user = $result->fetch_assoc();

        return new Persona(
            $user['user_id'],
            $user['email'],
            '',
            $user['name'],
            $user['status'],
            $user['rol']
        );
    }
}
?>
