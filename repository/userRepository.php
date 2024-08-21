<?php

require_once 'models/persona.php';

class UserRepository {
    private $personas;

    public function __construct() {
        // Inicializar la lista de personas con algunos objetos Persona predefinidos
        $this->personas = [
            new Persona(1, "juan.perez@example.com", "password123", "Juan Pérez", "activo", "admin"),
            new Persona(2, "ana.garcia@example.com", "password123", "Ana García", "activo", "user"),
            new Persona(3, "luis.martinez@example.com", "password123", "Luis Martínez", "activo", "user")
        ];
    }

    // Método para registrar una persona
    public function addPerson($userId, $email, $password, $name, $status, $rol) {
        try {
            $nuevaPersona = new Persona($userId, $email, $password, $name, $status, $rol);
            $this->personas[] = $nuevaPersona;
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Método para obtener todas las personas registradas
    public function getAllPersons() {
        return array_map(function($persona) {
            return $persona->toArray();
        }, $this->personas);
    }

    // Método para autenticar un usuario
    public function authenticate($email, $password) {
        foreach ($this->personas as $persona) {
            if ($persona->getEmail() === $email && $persona->getPassword() === $password) {
                return true;
            }
        }
        return false;
    }

    // Método para obtener un usuario por su email
    public function getUserByEmail($email) {
        foreach ($this->personas as $persona) {
            if ($persona->getEmail() === $email) {
                return $persona;
            }
        }
        return null;
    }
}

?>
