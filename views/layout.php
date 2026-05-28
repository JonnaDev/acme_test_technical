<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'ACME Transportes' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-800 text-white px-6 py-3 flex items-center gap-6">
        <span class="font-bold text-lg">ACME Transportes</span>
        <a href="../vehiculos/index.php" class="hover:underline text-sm">Vehículos</a>
        <a href="../personas/index.php"  class="hover:underline text-sm">Personas</a>
    </nav>

    <!-- Contenido -->
    <div class="max-w-5xl mx-auto p-6">

        <?php if (!empty($msg)): ?>
            <div class="mb-4 px-4 py-2 rounded text-sm
                <?= str_starts_with($msg, 'Error') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <?php include $vista; ?>

    </div>

</body>
</html>
