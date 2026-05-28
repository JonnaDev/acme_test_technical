<?php
require_once __DIR__ . '/../../controllers/VehicleController.php';
require_once __DIR__ . '/../../controllers/PersonController.php';

$vehicleController = new VehicleController($db_instance->conn);
$personController  = new PersonController($db_instance->conn);

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)$_POST['id'];
    $result = $vehicleController->update($id, $_POST);
    if ($result) {
        header('Location: index.php?msg=' . urlencode('Vehículo actualizado correctamente.'));
        exit;
    }
    $errors = $vehicleController->getErrors();
}

$vehiculo = $vehicleController->show($id);
if (!$vehiculo) {
    header('Location: index.php?msg=' . urlencode('Vehículo no encontrado.'));
    exit;
}

$v = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $vehiculo;

$conductores  = $personController->porRol('conductor');
$propietarios = $personController->porRol('propietario');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vehículo – ACME</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-800 text-white px-6 py-3 flex items-center gap-6">
    <span class="font-bold text-lg">ACME Transportes</span>
    <a href="index.php"             class="text-sm underline">Vehículos</a>
    <a href="../personas/index.php" class="text-sm hover:underline">Personas</a>
</nav>

<div class="max-w-2xl mx-auto p-6">

    <h1 class="text-xl font-bold text-gray-800 mb-4">
        Editar Vehículo — <span class="text-blue-700"><?= htmlspecialchars($vehiculo['placa']) ?></span>
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded text-sm shadow-sm">
            <strong class="font-bold block mb-1">Por favor corrige los siguientes errores:</strong>
            <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded shadow p-6">
        <form method="POST" class="grid grid-cols-2 gap-4">
            <input type="hidden" name="id" value="<?= $vehiculo['id'] ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Placa *</label>
                <input type="text" name="placa" maxlength="10"
                       value="<?= htmlspecialchars($v['placa'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm uppercase">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color *</label>
                <input type="text" name="color" maxlength="30"
                       value="<?= htmlspecialchars($v['color'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
                <input type="text" name="marca" maxlength="50"
                       value="<?= htmlspecialchars($v['marca'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                <select name="tipo_vehiculo"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="particular" <?= ($v['tipo_vehiculo'] ?? '') === 'particular' ? 'selected' : '' ?>>Particular</option>
                    <option value="publico"    <?= ($v['tipo_vehiculo'] ?? '') === 'publico'    ? 'selected' : '' ?>>Público</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Conductor * <span class="text-gray-400 font-normal">(máx. 1 vehículo)</span>
                </label>
                <select name="conductor_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($conductores as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= (int)($v['conductor_id'] ?? 0) === $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['primer_nombre'] . ' ' . $c['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Propietario *</label>
                <select name="propietario_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($propietarios as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= (int)($v['propietario_id'] ?? 0) === $p['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['primer_nombre'] . ' ' . $p['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-span-2 flex justify-end gap-3 pt-2">
                <a href="index.php" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-sm bg-blue-700 text-white rounded hover:bg-blue-800">
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
