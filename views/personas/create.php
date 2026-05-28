<?php
require_once __DIR__ . '/../../controllers/PersonController.php';

$pc    = new PersonController($db_instance->conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $pc->store($_POST);
    if ($ok) {
        header('Location: index.php?msg=Persona registrada correctamente.');
        exit;
    }
    $error = 'Error al registrar la persona.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Persona – ACME</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-blue-800 text-white px-6 py-3 flex items-center gap-6">
    <span class="font-bold text-lg">ACME Transportes</span>
    <a href="../vehiculos/index.php" class="text-sm hover:underline">Vehículos</a>
    <a href="index.php"              class="text-sm underline">Personas</a>
</nav>

<div class="max-w-2xl mx-auto p-6">

    <h1 class="text-xl font-bold text-gray-800 mb-4">Registrar Persona</h1>

    <?php if ($error): ?>
        <div class="mb-4 px-4 py-2 bg-red-100 text-red-700 rounded text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded shadow p-6">
        <form method="POST" class="grid grid-cols-2 gap-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número de Cédula</label>
                <input type="number" name="numero_cedula"
                       value="<?= htmlspecialchars($_POST['numero_cedula'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
                <select name="rol" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">-- Seleccione --</option>
                    <option value="conductor"   <?= ($_POST['rol'] ?? '') === 'conductor'   ? 'selected' : '' ?>>Conductor</option>
                    <option value="propietario" <?= ($_POST['rol'] ?? '') === 'propietario' ? 'selected' : '' ?>>Propietario</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre *</label>
                <input type="text" name="primer_nombre" required maxlength="255"
                       value="<?= htmlspecialchars($_POST['primer_nombre'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                <input type="text" name="segundo_nombre" maxlength="255"
                       value="<?= htmlspecialchars($_POST['segundo_nombre'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
                <input type="text" name="apellidos" required maxlength="255"
                       value="<?= htmlspecialchars($_POST['apellidos'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="number" name="telefono"
                       value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                <input type="text" name="ciudad" maxlength="255"
                       value="<?= htmlspecialchars($_POST['ciudad'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="direccion" maxlength="255"
                       value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="col-span-2 flex justify-end gap-3 pt-2">
                <a href="index.php" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 text-sm bg-blue-700 text-white rounded hover:bg-blue-800">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>
