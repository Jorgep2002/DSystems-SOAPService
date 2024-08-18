<?php

class Persona {
    private $userId;
    private $email;
    private $password;
    private $name;
    private $status;
    private $rol;

    public function __construct($userId, $email, $password, $name, $status, $rol) {
        // Validaciones básicas
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email no válido");
        }
        if (!is_numeric($userId)) {
            throw new Exception("El ID de usuario debe ser numérico");
        }

        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password; // Encriptar la contraseña
        // $this->password = $this->encryptPassword($password); // Encriptar la contraseña
        $this->name = $name;
        $this->status = $status;
        $this->rol = $rol;
    }

    // Métodos getter
    public function getUserId() {
        return $this->userId;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getRol() {
        return $this->rol;
    }

    // Método para convertir el objeto a un array, serializa en JSON
    public function toArray() {
        return [
            'userId' => $this->userId,
            'email' => $this->email,
            'name' => $this->name,
            'status' => $this->status,
            'rol' => $this->rol,
        ];
    }

    // Método para encriptar la contraseña
    // private function encryptPassword($password) {
    //     return password_hash($password, PASSWORD_BCRYPT);
    // }
}

?>
