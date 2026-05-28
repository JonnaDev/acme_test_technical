<?php
require_once __DIR__ . '/../../controllers/VehicleController.php';

$vehicleController = new VehicleController($db_instance->conn);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $resultado  = $vehicleController->delete((int)$_POST['id']);
    $msg = $ok ? 'Vehículo eliminado.' : 'Error al eliminar.';
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$vehiculos = $vehicleController->index();
$vista  = __FILE__;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos – ACME</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-800 text-white px-6 py-3 flex items-center gap-6">
    <span class="font-bold text-lg">ACME Transportes</span>
    <a href="index.php"               class="text-sm underline">Vehículos</a>
    <a href="../personas/index.php"   class="text-sm hover:underline">Personas</a>
</nav>

<div class="max-w-6xl mx-auto p-6">

    <?php if ($msg): ?>
        <div class="mb-4 px-4 py-2 rounded text-sm <?= str_starts_with($msg,'Error') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">Vehículos registrados</h1>
        <a href="create.php" class="bg-blue-700 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">
            + Registrar vehículo
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="px-4 py-3">Placa</th>
                    <th class="px-4 py-3">Marca</th>
                    <th class="px-4 py-3">Color</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Conductor</th>
                    <th class="px-4 py-3">Propietario</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <?php if (empty($vehiculos)): ?>
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Sin registros.</td></tr>
            <?php else: ?>
                <?php foreach ($vehiculos as $v): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($v['placa']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($v['marca']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($v['color']) ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs
                            <?= $v['tipo_vehiculo'] === 'particular' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' ?>">
                            <?= ucfirst($v['tipo_vehiculo']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3"><?= htmlspecialchars($v['nombre_conductor']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($v['nombre_propietario']) ?></td>
                    <td class="px-4 py-3 text-center flex justify-center gap-2">
                        <a href="edit.php?id=<?= $v['id'] ?>"
                           class="text-blue-600 hover:underline text-xs">Editar</a>
                        <form method="POST"
                              onsubmit="return confirm('¿Eliminar <?= htmlspecialchars($v['placa']) ?>?')">
                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                            <button class="text-red-500 hover:underline text-xs">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
