<?php
require_once('./config/database.php');

class VehicleController {
    private mysqli $conn;
    private array $errors = [];

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getErrors(): array {
        return $this->errors;
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

    public function conductorDisponible(int $conductor_id, int $excluir_id = 0): bool {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total FROM vehiculos WHERE conductor_id = ? AND id != ?"
        );
        $stmt->bind_param('ii', $conductor_id, $excluir_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row['total'] === 0;
    }

    public function store(array $vehicle): bool {
        $this->errors = [];

        if (empty(trim($vehicle['placa']))) {
            $this->errors[] = "La placa es obligatoria";
        }   

        if (empty(trim($vehicle['color']))) {
            $this->errors[] = "El color es obligatorio";
        }

        if (empty(trim($vehicle['marca']))) {
            $this->errors[] = "La marca es obligatoria";
        }

        if (empty(trim($vehicle['tipo_vehiculo']))) {
            $this->errors[] = "El tipo de vehiculo es obligatorio";
        }

        if (empty($vehicle['conductor_id'])) {
            $this->errors[] = "El conductor es obligatorio";
        }

        if (empty($vehicle['propietario_id'])) {
            $this->errors[] = "El propietario es obligatorio";
        }

        if (!empty($this->errors)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO vehiculos (placa, color, marca, tipo_vehiculo, conductor_id, propietario_id)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssssii',
            $vehicle['placa'], $vehicle['color'], $vehicle['marca'],
            $vehicle['tipo_vehiculo'], $vehicle['conductor_id'], $vehicle['propietario_id']
        );
        return $stmt->execute();
    }

    public function update(int $id, array $vehicle): bool {
        $this->errors = [];

        if (empty(trim($vehicle['placa']))) {
            $this->errors[] = "La placa es obligatoria";
        }
        
        if (empty(trim($vehicle['color']))) {
            $this->errors[] = "El color es obligatorio";
        }

        if (empty(trim($vehicle['marca']))) {
            $this->errors[] = "La marca es obligatoria";
        }

        if(!in_array($vehicle['tipo_vehiculo'], ['particular', 'publico'])) {
            $this->errors[] = "El tipo de vehiculo debe ser particular o publico";
        }

        if (empty($vehicle['conductor_id'])) {
            $this->errors[] = "El conductor es obligatorio";
        }

        if (empty($vehicle['propietario_id'])) {
            $this->errors[] = "El propietario es obligatorio";
        }

        if (!empty($this->errors)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE vehiculos SET placa=?, color=?, marca=?, tipo_vehiculo=?,
             conductor_id=?, propietario_id=? WHERE id=?"
        );
        $stmt->bind_param('ssssiii',  
            $vehicle['placa'], $vehicle['color'], $vehicle['marca'],
            $vehicle['tipo_vehiculo'], $vehicle['conductor_id'], $vehicle['propietario_id'], $id
        );
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM vehiculos WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}