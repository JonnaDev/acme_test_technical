<?php
require_once __DIR__ . '/../../controllers/PersonController.php';

$personController = new PersonController($db_instance->conn);

$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)$_POST['id'];
    $result = $personController->update($id, $_POST);
    if ($result == true) {
        header('Location: index.php?msg=' . urlencode('Persona actualizada correctamente'));
        exit;
    }
    $errors = $personController->getErrors();
}

$persona = $personController->show($id);
if (!$persona) {
    header('Location: index.php?msg404=' . urlencode('Persona no encontrada.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Persona – ACME</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',sans-serif; }
        .navbar-brand { font-weight:700; color:#e8a020 !important; }
        .nav-link.active { color:#e8a020 !important; font-weight:600; }
        .card { border:none; box-shadow:0 2px 8px rgba(0,0,0,.08); border-radius:10px; }
        .card-header { background:#1a3c5e; color:#fff; border-radius:10px 10px 0 0 !important; }
        .btn-primary-acme { background:#1a3c5e; color:#fff; border:none; }
        .btn-primary-acme:hover { background:#122c46; color:#fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background:#1a3c5e;">
    <div class="container">
        <a class="navbar-brand" href="../vehiculos/index.php"><i class="bi bi-truck-front-fill me-2"></i>ACME Transportes S.A.</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../vehiculos/index.php"><i class="bi bi-car-front me-1"></i>Vehículos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="index.php"><i class="bi bi-people me-1"></i>Personas</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card mx-auto" style="max-width:720px;">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>Editar Persona
                <span class="text-warning ms-2">
                    <?= htmlspecialchars('#' .$persona['id'] . ' - ' . $persona['primer_nombre'] . ' ' .  ' ' . $persona['apellidos']) ?>
                </span>
            </h5>
        </div>
        <div class="card-body">

            <?php if (!empty($errors)): ?>
                <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded text-sm shadow-sm">
                    <strong class="font-bold block mb-1">Por favor corrige los siguientes errores:</strong>
                    <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
                </div>
            <?php endif; ?>

            <?php $p = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $persona; ?>

            <form method="POST" novalidate>
                <input type="hidden" name="id" value="<?= $persona['id'] ?>">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Número de Cédula</label>
                        <input type="number" class="form-control" name="numero_cedula"
                               value="<?= htmlspecialchars($p['numero_cedula'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Primer Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="primer_nombre"
                               maxlength="255"
                               value="<?= htmlspecialchars($p['primer_nombre'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Segundo Nombre</label>
                        <input type="text" class="form-control" name="segundo_nombre"
                               maxlength="255"
                               value="<?= htmlspecialchars($p['segundo_nombre'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="apellidos"
                               maxlength="255"
                               value="<?= htmlspecialchars($p['apellidos'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="number" class="form-control" name="telefono"
                               value="<?= htmlspecialchars($p['telefono'] ?? '') ?>">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" class="form-control" name="direccion"
                               maxlength="255"
                               value="<?= htmlspecialchars($p['direccion'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad"
                               maxlength="255"
                               value="<?= htmlspecialchars($p['ciudad'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" name="rol">
                            <option value="conductor"   <?= ($p['rol'] ?? '') === 'conductor'   ? 'selected' : '' ?>>Conductor</option>
                            <option value="propietario" <?= ($p['rol'] ?? '') === 'propietario' ? 'selected' : '' ?>>Propietario</option>
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary-acme">
                        <i class="bi bi-floppy me-1"></i>Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
