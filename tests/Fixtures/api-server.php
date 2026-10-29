<?php

// Router for the PHP built-in web server used by HttpIntegrationTest.
// Every response is built from the request path, so a test can ask for the status,
// body or delay it needs.

$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$json = function (int $status, array $data): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
};

switch (true) {
    case $path === '/v2/oauth/token':
        if (($_POST['client_secret'] ?? null) !== 'test-client-secret') {
            $json(401, ['error' => 'invalid_client', 'message' => 'Client authentication failed']);
            break;
        }
        $json(200, ['access_token' => 'server-token', 'token_type' => 'Bearer', 'expires_in' => 3600]);
        break;

    case $path === '/v2/echo':
        $json(200, [
            'method' => $_SERVER['REQUEST_METHOD'],
            'query' => $_GET,
            'authorization' => $_SERVER['HTTP_AUTHORIZATION'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? null,
            'body' => file_get_contents('php://input'),
            'post' => $_POST,
            'files' => array_map(fn (array $file) => [
                'name' => $file['name'],
                'contents' => file_get_contents($file['tmp_name']),
            ], $_FILES),
        ]);
        break;

    case preg_match('#^/v2/status/(\d{3})/errors$#', $path, $m) === 1:
        $json((int) $m[1], ['errors' => [['message' => "Status {$m[1]}"]]]);
        break;

    case preg_match('#^/v2/status/(\d{3})/json$#', $path, $m) === 1:
        $json((int) $m[1], ['message' => "Status {$m[1]}"]);
        break;

    case preg_match('#^/v2/status/(\d{3})/html$#', $path, $m) === 1:
        http_response_code((int) $m[1]);
        header('Content-Type: text/html');
        echo "<html>Status {$m[1]}</html>";
        break;

    case $path === '/v2/slow':
        usleep(1500000);
        $json(200, ['slow' => true]);
        break;

    default:
        $json(404, ['errors' => [['message' => 'Unknown fixture path']]]);
}
