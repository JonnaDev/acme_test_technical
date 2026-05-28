<?php
require_once('../config/database.php');

class VehicleController {

    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
    }

    public function index() {
        $sql = "SELECT 
                    v.placa, 
                    v.marca, 
                    v.color,
                    v.tipo_vehiculo,
                    CONCAT(c.primer_nombre, ' ', IFNULL(c.segundo_nombre, ''), ' ', c.apellidos) AS nombre_conductor,
                    CONCAT(p.primer_nombre, ' ', IFNULL(p.segundo_nombre, ''), ' ', p.apellidos) AS nombre_propietario
                FROM vehiculos v
                INNER JOIN personas c ON v.conductor_id = c.id
                INNER JOIN personas p ON v.propietario_id = p.id";

        $result = $this->db->conn->query($sql);

        // 3. Transformar el resultado en un array asociativo limpio
        $vehicles = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $vehicles[] = $row;
            }
        }

        return $vehicles;
    }

    public function store(array $data) {
        if (empty($data['placa']) || empty($data['conductor_id']) || empty($data['propietario_id'])) {
            return "Campos obligatorios vacíos.";
        }

        $stmt = $this->db->prepare("INSERT INTO vehiculos (placa, color, marca, tipo_vehiculo, conductor_id, propietario_id) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "ssssii", 
            $data['placa'], 
            $data['color'], 
            $data['marca'], 
            $data['tipo_vehiculo'], 
            $data['conductor_id'], 
            $data['propietario_id']
        );

        if ($stmt->execute()) {
            
        } else {
            return "Error al registrar el vehículo: " . $this->db->error;
        }
    }
}