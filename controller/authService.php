<?php

require_once 'models/persona.php';

class AuthService {
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
    public function registerPerson($userId, $email, $password, $name, $status, $rol) {
        try {
            $nuevaPersona = new Persona($userId, $email, $password, $name, $status, $rol);
            $this->personas[] = $nuevaPersona;
            return "Persona registrada: {$nuevaPersona->getName()}";
        } catch (Exception $e) {
            return "Error al registrar la persona: " . $e->getMessage();
        }
    }

    // Método para obtener todas las personas registradas
    public function getAllPersons() {
        $personasArray = array_map(function($persona) {
            return $persona->toArray();
        }, $this->personas);

        return json_encode($personasArray); // Devuelve los datos en formato JSON
    }

    // Método para login (simple)
    public function login($email, $password) {
        foreach ($this->personas as $persona) {
            if ($persona->getEmail() === $email && $persona->getPassword() === $password) {
                return "Logueado correctamente como {$persona->getName()} ({$persona->getRol()})";
            }
        }
        return "Email o contraseña incorrectos";
    }
}

?>
