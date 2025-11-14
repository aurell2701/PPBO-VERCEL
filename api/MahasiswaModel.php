<?php
require_once __DIR__ . '/../core/Database.php';

class MahasiswaModel
{
    private $conn;
    private $table = "mahasiswa";

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // VALIDASI di model (extra safety)
        if (!$data || !isset($data['nama']) || !isset($data['jurusan'])) {
            return [
                "status" => false,
                "message" => "Field 'nama' dan 'jurusan' wajib diisi."
            ];
        }

        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nama, jurusan) VALUES (?, ?)");
        $stmt->execute([$data['nama'], $data['jurusan']]);

        return [
            "status" => true,
            "message" => "Data mahasiswa berhasil ditambahkan"
        ];
    }

    public function update($id, $data)
    {
        $check = $this->check($id);
        if ($check !== true) {
            return $check;
        }

        // VALIDASI
        if (!$data || !isset($data['nama']) || !isset($data['jurusan'])) {
            return [
                "status" => false,
                "message" => "Field 'nama' dan 'jurusan' wajib diisi untuk update."
            ];
        }

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET nama = ?, jurusan = ? WHERE id = ?");
        $stmt->execute([$data['nama'], $data['jurusan'], $id]);

        return [
            "status" => true,
            "message" => "Data mahasiswa berhasil diperbarui"
        ];
    }

    public function delete($id)
    {
        $check = $this->check($id);
        if ($check !== true) {
            return $check;
        }

        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);

        return [
            "status" => true,
            "message" => "Data mahasiswa dengan ID {$id} berhasil dihapus"
        ];
    }

    private function check($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            return [
                "status" => false,
                "message" => "Data mahasiswa dengan ID {$id} tidak ditemukan"
            ];
        }

        return true;
    }
}