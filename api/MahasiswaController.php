<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/MahasiswaModel.php';

class MahasiswaController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new MahasiswaModel();
    }

    public function index() {
        $data = $this->model->getAll();
        return $this->response($data);
    }

    public function show($id) {
        $data = $this->model->getById($id);
        if ($data) {
            return $this->response($data);
        }
        return $this->response(["error" => "Data tidak ditemukan"], 404);
    }

    public function store() {
        $input = json_decode(file_get_contents("php://input"), true);

        // VALIDASI BODY KOSONG / SALAH FORMAT
        if (!$input || !isset($input["nama"]) || empty($input["nama"])) {
            return $this->response([
                "error" => "Field 'nama' wajib diisi dan tidak boleh kosong."
            ], 400);
        }

        $result = $this->model->create($input);
        return $this->response($result, 201);
    }

    public function update($id) {
        $input = json_decode(file_get_contents("php://input"), true);

        // VALIDASI UPDATE
        if (!$input || !isset($input["nama"]) || empty($input["nama"])) {
            return $this->response([
                "error" => "Field 'nama' wajib diisi untuk update."
            ], 400);
        }

        $result = $this->model->update($id, $input);
        return $this->response($result);
    }

    public function destroy($id) {
        $result = $this->model->delete($id);
        return $this->response($result);
    }
}