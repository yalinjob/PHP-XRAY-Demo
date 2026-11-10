<?php
// Small app that touches each pinned dependency. The code is safe; the findings come from old package versions.
require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Laminas\Diactoros\Response\JsonResponse;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($path === '/health') {
    $response = new JsonResponse(['status' => 'ok']);
    http_response_code($response->getStatusCode());
    header('Content-Type: application/json');
    echo $response->getBody();
    exit;
}

if ($path === '/versions') {
    header('Content-Type: application/json');
    echo json_encode([
        'php'    => PHP_VERSION,
        'guzzle' => Client::MAJOR_VERSION ?? 'n/a',
        'twig'   => Environment::VERSION,
    ]);
    exit;
}

if ($path === '/token') {
    $jwt = JWT::encode(['sub' => 'demo', 'iat' => time()], 'demo-secret-not-for-production');
    header('Content-Type: application/json');
    echo json_encode(['token' => $jwt]);
    exit;
}

$twig = new Environment(new ArrayLoader([
    'index' => '<h1>{{ title }}</h1><p>Demo app for JFrog Xray. Version {{ version }}.</p>',
]));
echo $twig->render('index', [
    'title'   => 'PHP Xray Demo',
    'version' => getenv('APP_VERSION') ?: 'dev',
]);
