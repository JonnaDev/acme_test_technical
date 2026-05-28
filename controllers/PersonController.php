<?php
require_once('./config/database.php');

class PersonController {
    private mysqli $conn;
    private array $errors = [];

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function index(): array {
        /*
        la instancia desde la vista 'views/personas/index.php' ($nueva variable = new PersonController(); 
        se encarga de obtener todos los metodos en este caso de index y 
        almacenar el fetch::assoc dentro de la instancia para luego recorrer los datos 
        con un <?php foreach ($personas as $p): ?>)
        */
        $result = $this->conn->query("SELECT * FROM personas ORDER BY id ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function show(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM personas WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }


    public function getErrors(): array {
        return $this->errors;
    }

    public function store(array $person): bool {
        $this->errors = []; 
        

        if (empty($person['numero_cedula'])) {
            $this->errors[] = "El campo cedula es obligatorio";
        } elseif (!is_numeric($person['numero_cedula'])) {
            $this->errors[] = "La cedula debe ser un numero entero";
        }

        if (empty($person['primer_nombre']) || empty($person['segundo_nombre'])) {
            $this->errors[] = "Te falta tu primer nombre o tu segundo nombre, llenar campos";
        }

        if (!in_array($person['rol'], ['conductor', 'propietario'])) {
            $this->errors[] = "El rol debe de ser conductor o propietario";
        }

        if (empty($person['apellidos'])) {
            $this->errors[] = "Debe de diligenciar sus apellidos, por favor";
        }

        if (empty($person['telefono'])) {
            $this->errors[] = "Debe de diligenciar su numero de contacto";
        } elseif (!is_numeric($person['telefono'])) {
            $this->errors[] = "El campo telefono debe de ser un dato numerico";
        }

        if (empty($person['ciudad'])) {
            $this->errors[] = "Debe de diligenciar el campo ciudad";
        }

        if (empty($person['direccion'])) {
            $this->errors[] = "Debe diligenciar una direccion";
        }

        if (!empty($this->errors)) {
            return false; 
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO personas (numero_cedula, primer_nombre, segundo_nombre, apellidos, direccion, telefono, ciudad, rol)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param('issssiss',
            $person['numero_cedula'], $person['primer_nombre'], $person['segundo_nombre'],
            $person['apellidos'], $person['direccion'], $person['telefono'], $person['ciudad'], $person['rol']
        );

        return $stmt->execute();
    }


    public function update(int $id, array $person): bool {
        $this->errors = [];

        if (empty($person['numero_cedula'])) {
            $this->errors[] = "La cedula es obligatoria";
        } elseif (!is_numeric($person['numero_cedula'])) {
            $this->errors[] = "La cedula debe ser un numero entero";
        }

        if (empty($person['primer_nombre']) || empty($person['segundo_nombre'])) {
            $this->errors[] = "Te falta tu primer nombre o tu segundo nombre, llenar campos";
        }

        if (!in_array($person['rol'], ['conductor', 'propietario'])) {
            $this->errors[] = "El rol debe de ser conductor o propietario";
        }

        if (empty($person['apellidos'])) {
            $this->errors[] = "Debe de diligenciar sus apellidos, por favor";
        }

        if (empty($person['telefono'])) {
            $this->errors[] = "Debe de diligenciar su numero de contacto";
        } elseif (!is_numeric($person['telefono'])) {
            $this->errors[] = "El campo telefono debe de ser un dato numerico";
        }

        if (empty($person['ciudad'])) {
            $this->errors[] = "Debe de diligenciar el campo ciudad";
        }

        if (empty($person['direccion'])) {
            $this->errors[] = "Debe diligenciar una direccion";
        }

        if (!empty($this->errors)) {
            return false; 
        }

        $stmt = $this->conn->prepare(
            "UPDATE personas SET numero_cedula=?, primer_nombre=?, segundo_nombre=?,
             apellidos=?, direccion=?, telefono=?, ciudad=?, rol=? WHERE id=?"
        );
        $stmt->bind_param('issssissi',
            $person['numero_cedula'], $person['primer_nombre'], $person['segundo_nombre'],
            $person['apellidos'], $person['direccion'], $person['telefono'], $person['ciudad'], $person['rol'], $id
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