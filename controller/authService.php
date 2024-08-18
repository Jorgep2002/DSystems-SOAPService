<?php

require_once 'repository/userRepository.php';

class AuthService {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    // Método para registrar una persona
    public function registerPerson($userId, $email, $password, $name, $status, $rol) {
        $success = $this->userRepository->addPerson($userId, $email, $password, $name, $status, $rol);
        if ($success) {
            return "Persona registrada correctamente.";
        } else {
            return "Error al registrar la persona.";
        }
    }

    // Método para obtener todas las personas registradas
    public function getAllPersons() {
        $personasArray = $this->userRepository->getAllPersons();
        return json_encode($personasArray); // Devuelve los datos en formato JSON
    }

    // Método para login (simple)
    public function login($email, $password) {
        $authenticated = $this->userRepository->authenticate($email, $password);
        if ($authenticated) {
            return "Logueado correctamente.";
        } else {
            return "Email o contraseña incorrectos.";
        }
    }
}

?>
