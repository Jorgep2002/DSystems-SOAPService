<?php

require_once 'repository/userRepository.php';
require_once 'models/persona.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AuthService {
    private $userRepository;
    private $secretKey;

    public function __construct() {
        $dataSourceManager = new DataSourceManager();

        // Pasar la instancia de DataSourceManager a UserRepository
        $this->userRepository = new UserRepository($dataSourceManager);
        $this->secretKey = $_ENV['JWT_SECRET_KEY'];
    }

    public function getSecretKey() {
        return $this->secretKey;
    }

    // Método para registrar una persona
    public function registerPerson($email, $password, $name, $status, $rol) {
        $success = $this->userRepository->addPerson($email, $password, $name, $status, $rol);
        if ($success) {
            return json_encode(['message' => 'Persona registrada correctamente.']);
        } else {
            return json_encode(['message' => 'Error al registrar la persona.']);
        }
    }

    // Método para obtener todas las personas registradas
    public function getAllPersons($token) {
        // Validar el token JWT
        if ($this->validateToken($token)) {
            // Obtener todas las personas
            $persons = $this->userRepository->getAllPersons();
            return json_encode($persons);
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

    // Método para login con JWT
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
                ]
            ];

            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
            return json_encode(['message' => 'Logueado correctamente', 'token' => $jwt]);

        } else {
            return json_encode(['message' => 'Email o contraseña incorrectos']);
        }
    }
}

?>
