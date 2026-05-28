<?php
require_once __DIR__ . '/../../controllers/PersonController.php';

$personcontroller = new PersonController($db_instance->conn);

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $eliminar_persona  = $pc->delete((int)$_POST['id']);
    $msg = $eliminar_persona ? 'Persona eliminada.' : 'Error al eliminar.';
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$personas = $personcontroller->index();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personas – ACME</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-800 text-white px-6 py-3 flex items-center gap-6">
    <span class="font-bold text-lg">ACME Transportes</span>
    <a href="../vehiculos/index.php" class="text-sm hover:underline">Vehículos</a>
    <a href="index.php"              class="text-sm underline">Personas</a>
</nav>

<div class="max-w-6xl mx-auto p-6">

    <?php if ($msg): ?>
        <div class="mb-4 px-4 py-2 rounded text-sm bg-green-100 text-green-700">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">Personas registradas</h1>
        <a href="create.php" class="bg-blue-700 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">
            + Registrar persona
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="px-4 py-3">Cédula</th>
                    <th class="px-4 py-3">Nombre completo</th>
                    <th class="px-4 py-3">Teléfono</th>
                    <th class="px-4 py-3">Ciudad</th>
                    <th class="px-4 py-3">Dirección</th>
                    <th class="px-4 py-3">Rol</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <?php if (empty($personas)): ?>
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Sin registros.</td></tr>
            <?php else: ?>
                <?php foreach ($personas as $p): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><?= htmlspecialchars($p['numero_cedula'] ?? '—') ?></td>
                    <td class="px-4 py-3 font-medium">
                        <?= htmlspecialchars(trim($p['primer_nombre'] . ' ' . ($p['segundo_nombre'] ?? '') . ' ' . $p['apellidos'])) ?>
                    </td>
                    <td class="px-4 py-3"><?= htmlspecialchars($p['telefono'] ?? '—') ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($p['ciudad'] ?? '—') ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($p['direccion'] ?? '—') ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs
                            <?= $p['rol'] === 'conductor' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' ?>">
                            <?= ucfirst($p['rol']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center flex justify-center gap-2">
                        <a href="edit.php?id=<?= $p['id'] ?>"
                           class="text-blue-600 hover:underline text-xs">Editar</a>
                        <form method="POST"
                              onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars($p['primer_nombre'] . ' ' . $p['apellidos']) ?>?')">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
