<?php
// index.php
require_once('../config/database.php');
require_once('../controllers/VehicleController.php');


$db_instance = new Database('127.0.0.1', 'root', '', 'acme');
$vehicleController = new VehicleController($db_instance->conn);
$vehicles = $vehicleController->index();


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Vehículos - ACME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Informe de Vehículos Asegurados</h2>
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Conductor</th>
                <th>Propietario</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td><?= htmlspecialchars($vehicle['placa']) ?></td>
                    <td><?= htmlspecialchars($vehicle['marca']) ?></td>
                    <td><?= htmlspecialchars($vehicle['nombre_conductor']) ?></td>
                    <td><?= htmlspecialchars($vehicle['nombre_propietario']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>