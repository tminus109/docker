<?php
require "../vendor/autoload.php";

use Firebase\JWT\JWT;

$method = $_SERVER["REQUEST_METHOD"];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$dispatcher = FastRoute\simpleDispatcher(function ($r) {
    $r->get('/', 'home');
    $r->post('/api/login-user', 'login');
    $r->get('/api/szallasok', 'accomodationsList');
    $r->get('/api/szallasok/{id}', 'accomodationById');
    $r->get('/api/get-new-access-token', 'getNewAccessToken');
    $r->post('/api/logout-user', 'logout');
});

function home()
{
    require './build/index.html';
}

function login($vars, $body)
{
    header('Content-type: application/json');
    if (!isUserValid($body)) {
        http_response_code(401);
        echo json_encode(['error' => 'invalid credentials']);
        return;
    }

    // 1. Megvalósítandó: Access token kigenerálása és elküldése
    $accessToken = JWT::encode(
        [
            "iat" => time(),
            "exp" => time() + 15,
            "sub" => "felhasznaloEgyediAzonositoja",
            "felhasznaloNeve" => "Gipsz Jakab",
        ],
        $_SERVER['JWT_TOKEN_SECRET']
    );

    // 2. Megvalósítandó: Refresh token kigenerálása és beállítása sütinek
    $refreshToken = JWT::encode(
        [
            "iat" => time(),
            "exp" => time() + 60 * 60 * 24 * 30,
            "sub" => "felhasznaloEgyediAzonositoja",
            "felhasznaloNeve" => "Gipsz Jakab",
        ],
        $_SERVER['JWT_TOKEN_SECRET']
    );

    setcookie('kodbazisRefreshToken', $refreshToken, [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => "/",
        'httponly' => true,
        'secure' => true,
        'samesite' => 'None', // csak fejlesztési célokkal 'None', amúgy 'Lax'
    ]);

    echo json_encode(["accessToken" => $accessToken]);
};

function isUserValid($body)
{
    $content = file_get_contents(__DIR__ . "/store/users.json");
    $users = json_decode($content, true);
    if (!isset($users[$body['email'] ?? ""])) {
        return false;
    }

    $pw = $users[$body['email']];
    return password_verify($body["password"] ?? "", $pw);
}

function accomodationsList()
{
    header('Content-type: application/json');
    // Megvalósítandó: Access token kikérése a kérés fejlécéből és dekódolás
    $accessToken = getTokenFromHeaderOrSendErrorReponse();
    $decoded = decodeJwtOrSendErrorResponse($accessToken);
    // $userId = $decoded["sub"]; (user egyedi azonosítója, ha szükség lenne rá)

    // Ha sikeres volt a dekódolás, akkor válasz megküldése, máskülönben 401
    echo file_get_contents("./store/szallasok.json");
};

function accomodationById($vars, $body)
{
    header('Content-type: application/json');

    $accessToken = getTokenFromHeaderOrSendErrorReponse();
    $decoded = decodeJwtOrSendErrorResponse($accessToken);

    $content =  file_get_contents('./store/szallasok.json');
    $items  = json_decode($content, true);
    $filtered = array_values(
        array_filter($items, fn ($item) => $item['id'] == $vars['id'])
    );

    if (!count($filtered)) {
        http_response_code(404);
        echo json_encode(['error' => 'not found']);
    }

    echo json_encode($filtered[0]);
}

function getNewAccessToken()
{
    // Megvalósítandó: Access token megújítása a refresh tokennel
    // Ha nincs, vagy nem érvényes a refresh token, akkor 401-es hiba
    $decoded = decodeJwtOrSendErrorResponse($_COOKIE['kodbazisRefreshToken']);
    $accessToken =  JWT::encode([
        "sub" => $decoded['sub'],
        "felhasznaloNeve" => $decoded['felhasznaloNeve'],
        "iat" => time(),
        "exp" => time() + 15,
    ], $_SERVER['JWT_TOKEN_SECRET']);

    header('Content-type: application/json');
    echo json_encode(["accessToken" => $accessToken]);
};

function logout()
{
    // Megvalósítandó: Refresh token törlése
    setcookie('kodbazisRefreshToken', false, [
        'expires' => 1,
        'path' => "/",
        'httponly' => true,
        'secure' => true,
        'samesite' => 'None', // csak fejlesztési célokkal 'None', amúgy 'Lax'
    ]);
};

function decodeJwtOrSendErrorResponse($token)
{
    try {
        $decoded = JWT::decode($token ?? '', $_SERVER['JWT_TOKEN_SECRET'], ['HS256']);
        return (array)$decoded;
    } catch (\Firebase\JWT\ExpiredException $err) {
        http_response_code(401);
        header('Content-type: application/json');
        echo json_encode(['error' => 'token expired']);
        exit;
    } catch (Exception $exception) {
        http_response_code(401);
        echo json_encode(['error' => 'validation failed']);
        exit;
    }
}

function getTokenFromHeaderOrSendErrorReponse()
{
    $headers = getallheaders();
    $isFound = preg_match(
        '/Bearer\s(\S+)/',
        $headers['Authorization'] ?? '',
        $matches
    );
    if (!$isFound) {
        http_response_code(401);
        echo json_encode(['error' => 'unauthorized']);
        exit;
    }
    return $matches[1];
}

// https://kodbazis.hu/php-tanfolyam-haladoknak/json-api-epitese
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $path);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        home();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        home();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $body = json_decode(file_get_contents('php://input'), true);
        $handler($vars, $body);
        break;
}
