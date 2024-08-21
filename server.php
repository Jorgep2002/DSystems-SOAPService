<?php

// Cargar autoload de Composer para poder usar las dependencias instaladas
require_once 'vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once "vendor/econea/nusoap/src/nusoap.php";
require_once "controller/authService.php";

$nameSpace = "serverSOAP";
$server = new soap_server();
$server->configureWSDL("server", $nameSpace);
$server->wsdl->schemaTargetNamespace = $nameSpace;

// Instancia de AuthService
$authService = new AuthService();

// Funciones SOAP que interactúan con AuthService

function registerPerson($userId, $email, $password, $name, $status, $rol) {
    global $authService;
    return $authService->registerPerson($userId, $email, $password, $name, $status, $rol);
}

function getAllPersons($token) {
    global $authService;
    return $authService->getAllPersons($token);
}

function login($email, $password) {
    global $authService;
    return $authService->login($email, $password);
}

// Registrar los métodos en el servidor SOAP
$server->register(
    "registerPerson",
    array('userId' => 'xsd:int', 'email' => 'xsd:string', 'password' => 'xsd:string', 'name' => 'xsd:string', 'status' => 'xsd:string', 'rol' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $nameSpace,
    false,
    'rpc',
    'encoded',
    'Registra una nueva persona'
);

$server->register(
    "getAllPersons",
    array('token' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $nameSpace,
    false,
    'rpc',
    'encoded',
    'Devuelve una lista de todas las personas registradas'
);

$server->register(
    "login",
    array('email' => 'xsd:string', 'password' => 'xsd:string'),
    array('return' => 'xsd:string'),
    $nameSpace,
    false,
    'rpc',
    'encoded',
    'Realiza login simple con un email y contraseña'
);

// Procesar la solicitud SOAP
$server->service(file_get_contents("php://input"));

?>
