<?php
require_once __DIR__ . '/../../controllers/VehicleController.php';
require_once __DIR__ . '/../../controllers/PersonController.php';

$vc = new VehicleController($db_instance->conn);
$pc = new PersonController($db_instance->conn);

$id    = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conductor_id = (int)$_POST['conductor_id'];

    if (!$vc->conductorDisponible($conductor_id, $id)) {
        $error = 'Error: ese conductor ya tiene otro vehículo asignado.';
    } else {
        $ok = $vc->update($id, $_POST);
        if ($ok) {
            header('Location: index.php?msg=Vehículo actualizado correctamente.');
            exit;
        }
        $error = 'Error al actualizar el vehículo.';
    }
}

$v = $vc->show($id);
if (!$v) {
    header('Location: index.php?msg=Error: vehículo no encontrado.');
    exit;
}

// En error de validación muestra $_POST, si no los datos del BD
$d = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $v;

$conductores  = $pc->porRol('conductor');
$propietarios = $pc->porRol('propietario');
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
        Editar Vehículo — <span class="text-blue-700"><?= htmlspecialchars($v['placa']) ?></span>
    </h1>

    <?php if ($error): ?>
        <div class="mb-4 px-4 py-2 bg-red-100 text-red-700 rounded text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded shadow p-6">
        <form method="POST" class="grid grid-cols-2 gap-4">
            <input type="hidden" name="id" value="<?= $v['id'] ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Placa *</label>
                <input type="text" name="placa" required maxlength="10"
                       value="<?= htmlspecialchars($d['placa']) ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm uppercase">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color *</label>
                <input type="text" name="color" required maxlength="30"
                       value="<?= htmlspecialchars($d['color']) ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marca *</label>
                <input type="text" name="marca" required maxlength="50"
                       value="<?= htmlspecialchars($d['marca']) ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                <select name="tipo_vehiculo" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="particular" <?= $d['tipo_vehiculo'] === 'particular' ? 'selected' : '' ?>>Particular</option>
                    <option value="publico"    <?= $d['tipo_vehiculo'] === 'publico'    ? 'selected' : '' ?>>Público</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conductor *</label>
                <select name="conductor_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <?php foreach ($conductores as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= (int)$d['conductor_id'] === $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['primer_nombre'] . ' ' . $c['apellidos']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Propietario *</label>
                <select name="propietario_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <?php foreach ($propietarios as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= (int)$d['propietario_id'] === $p['id'] ? 'selected' : '' ?>>
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
