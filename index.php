<?php
require 'config/db_config.php';
require 'src/GestorReservas.php';

$mensaje = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gestor = new GestorReservas($pdo);
    try {
        $id = $gestor->procesar($_POST['fecha'], $_POST['hora'], $_POST['personas'], $_POST['ubicacion']);
        $mensaje = "¡Reserva exitosa! ID de referencia: #$id";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas - MSI Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row mb-4">
        <div class="col text-center">
            <h1 class="display-5 fw-bold">Gestión de Reservas</h1>
            <p class="text-muted">Evaluación Técnica - Octavio</p>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $mensaje ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= $error ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">Nueva Reserva</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" name="hora" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Personas</label>
                            <input type="number" name="personas" class="form-control" min="1" max="12" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ubicación</label>
                            <select name="ubicacion" class="form-select">
                                <option value="A">Ubicación A</option>
                                <option value="B">Ubicación B</option>
                                <option value="C">Ubicación C</option>
                                <option value="D">Ubicación D</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Confirmar Reserva</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Listado de Reservas (SQL Optimizado)</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ubicación</th>
                                <th>Sección</th>
                                <th>Fecha/Hora</th>
                                <th>Personas</th>
                                <th>Mesas Unidas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT m.ubicacion, m.seccion, r.fecha, r.hora, r.cantidad_personas,
                                    GROUP_CONCAT(m.numero_mesa SEPARATOR ', ') as nro_mesas
                                    FROM reservas r
                                    JOIN reserva_mesa rm ON r.id = rm.reserva_id
                                    JOIN mesas m ON rm.mesa_id = m.id
                                    GROUP BY r.id, m.ubicacion, m.seccion
                                    ORDER BY r.fecha DESC, r.hora DESC";
                            
                            $stmt = $pdo->query($sql);
                            while ($row = $stmt->fetch()): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= $row['ubicacion'] ?></span></td>
                                    <td><?= $row['seccion'] ?></td>
                                    <td><?= $row['fecha'] ?> <?= $row['hora'] ?></td>
                                    <td><?= $row['cantidad_personas'] ?></td>
                                    <td><span class="text-primary fw-bold"><?= $row['nro_mesas'] ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelector('input[name="hora"]').addEventListener('input', function() {
        if (this.value.length === 5) {
            this.blur(); 
        }
    });
</script>
</body>
</html>