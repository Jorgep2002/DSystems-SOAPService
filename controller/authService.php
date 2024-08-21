<?php

require_once 'repository/userRepository.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthService {
    private $userRepository;
    private $secretKey;

    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->secretKey = $_ENV['JWT_SECRET_KEY'];
    }

    public function getSecretKey() {
        return $this->secretKey;
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
    public function getAllPersons($token) {
        // Validar el token JWT
        if ($this->validateToken($token)) {
            // Lógica para obtener todas las personas
            return json_encode($this->fetchAllPersons());
        } else {
            return json_encode(['error' => 'Invalid token']);
        }
    }

    private function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    

    private function fetchAllPersons() {
        // Lógica para obtener todas las personas desde la base de datos
        return $this->userRepository->getAllPersons();
    }

    // Método para login con JWT.
    public function login($email, $password) {
        $authenticated = $this->userRepository->authenticate($email, $password);
        if ($authenticated) {
            $user = $this->userRepository->getUserByEmail($email);
            $payload = [
                'iat' => time(), // Tiempo en que fue emitido
                'exp' => time() + (60*60), // Tiempo de expiración (1 hora)
                'data' => [
                    'userId' => $user->getUserId(),
                    'email' => $user->getEmail(),
                    'rol' => $user->getRol()
            ]];

            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
            return json_encode(['message' => 'Logueado correctamente', 'token' => $jwt]);

        } else {
            return json_encode(['message' => 'Email o contraseña incorrectos']);
        }
    }
}

?>
