<?php
require_once __DIR__ . '/core/AuthMiddleware.php';
require_once __DIR__ . '/api/MahasiswaController.php';

// Inisialisasi middleware auth
$auth = new AuthMiddleware();

// Set header umum JSON
header('Content-Type: application/json');

// Dapatkan URI dan method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalisasi path agar kompatibel dengan Vercel
$uri = str_replace('/index.php', '', $uri);

$method = $_SERVER['REQUEST_METHOD'];

// Baca input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Routing utama
switch (true) {

    // Root route
    case $uri === '/':
        echo json_encode(["message" => "Koneksi success"]);
        break;

    // GET semua mahasiswa
    case $uri === '/mahasiswa' && $method === 'GET':
        $auth->verify();
        (new MahasiswaController())->index();
        break;

    // GET mahasiswa berdasarkan ID
    case preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches) && $method === 'GET':
        $auth->verify();
        (new MahasiswaController())->show($matches[1]);
        break;

    // POST tambah mahasiswa
    case $uri === '/mahasiswa' && $method === 'POST':
        $auth->verify();
        (new MahasiswaController())->store();
        break;

    // PUT update mahasiswa
    case preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches) && $method === 'PUT':
        $auth->verify();
        (new MahasiswaController())->update($matches[1]);
        break;

    // DELETE mahasiswa
    case preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches) && $method === 'DELETE':
        $auth->verify();
        (new MahasiswaController())->destroy($matches[1]);
        break;

    // Default route
    default:
        http_response_code(404);
        echo json_encode([
            "error" => "Route tidak ditemukan",
            "uri" => $uri
        ]);
        break;
}