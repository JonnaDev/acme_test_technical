<?php
require_once __DIR__ . '/../config/database.php';

class PersonController {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function index(): array {
        $result = $this->conn->query("SELECT * FROM personas ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function show(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM personas WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function store(array $d): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO personas (numero_cedula, primer_nombre, segundo_nombre, apellidos, direccion, telefono, ciudad, rol)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('issssiss',
            $d['numero_cedula'], $d['primer_nombre'], $d['segundo_nombre'],
            $d['apellidos'], $d['direccion'], $d['telefono'], $d['ciudad'], $d['rol']
        );
        return $stmt->execute();
    }

    public function update(int $id, array $d): bool {
        $stmt = $this->conn->prepare(
            "UPDATE personas SET numero_cedula=?, primer_nombre=?, segundo_nombre=?,
             apellidos=?, direccion=?, telefono=?, ciudad=?, rol=? WHERE id=?"
        );
        $stmt->bind_param('issssissi',
            $d['numero_cedula'], $d['primer_nombre'], $d['segundo_nombre'],
            $d['apellidos'], $d['direccion'], $d['telefono'], $d['ciudad'], $d['rol'], $id
        );
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM personas WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    public function porRol(string $rol): array {
        $stmt = $this->conn->prepare("SELECT id, primer_nombre, apellidos FROM personas WHERE rol = ?");
        $stmt->bind_param('s', $rol);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}