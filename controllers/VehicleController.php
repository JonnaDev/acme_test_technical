<?php
require_once __DIR__ . '/../config/database.php';

class VehicleController {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function index(): array {
        $result = $this->conn->query(
            "SELECT v.*,
                CONCAT(c.primer_nombre, ' ', c.apellidos) AS nombre_conductor,
                CONCAT(p.primer_nombre, ' ', p.apellidos) AS nombre_propietario
             FROM vehiculos v
             INNER JOIN personas c ON v.conductor_id   = c.id
             INNER JOIN personas p ON v.propietario_id = p.id
             ORDER BY v.id DESC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function show(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM vehiculos WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    // Regla: un conductor solo puede tener UN vehículo asignado
    public function conductorDisponible(int $conductor_id, int $excluir_id = 0): bool {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total FROM vehiculos WHERE conductor_id = ? AND id != ?"
        );
        $stmt->bind_param('ii', $conductor_id, $excluir_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row['total'] === 0;
    }

    public function store(array $d): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO vehiculos (placa, color, marca, tipo_vehiculo, conductor_id, propietario_id)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssii',
            $d['placa'], $d['color'], $d['marca'],
            $d['tipo_vehiculo'], $d['conductor_id'], $d['propietario_id']
        );
        return $stmt->execute();
    }

    public function update(int $id, array $d): bool {
        $stmt = $this->conn->prepare(
            "UPDATE vehiculos SET placa=?, color=?, marca=?, tipo_vehiculo=?,
             conductor_id=?, propietario_id=? WHERE id=?"
        );
        $stmt->bind_param('ssssiii',
            $d['placa'], $d['color'], $d['marca'],
            $d['tipo_vehiculo'], $d['conductor_id'], $d['propietario_id'], $id
        );
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM vehiculos WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}