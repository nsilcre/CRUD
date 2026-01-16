<!-- Vista: edición de un vehículo existente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Vehículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="styles/crud.css">
</head>
<body>
    <div class="bg-animation"></div>
    <div class="particle-system" id="particles"></div>
    <div class="geometric-shapes">
        <div class="geo-shape triangle" style="top: 10%; left: 5%;"></div>
        <div class="geo-shape square" style="top: 60%; right: 10%;"></div>
        <div class="geo-shape circle" style="bottom: 20%; left: 15%;"></div>
    </div>

    <div class="page-container">
        <!-- Cabecera con logo y usuario actual -->
        <header class="page-header">
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo-icon"><i class="fas fa-car"></i></div>
                    <div class="logo-text">Gestión de Vehículos</div>
                </div>
                <div class="user-section">
                    <div class="user-info">
                        <div class="user-welcome">Bienvenido</div>
                        <div class="user-name">
                            <?php if (isset($_SESSION['nombre'], $_SESSION['apellidos'])): ?>
                                <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellidos']); ?>
                            <?php else: ?>
                                Administrador
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="index.php?controller=auth&action=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <main class="page-content">
            <div class="main-card">
                <!-- Tarjeta principal con título del formulario de edición -->
                <div class="main-card-header">
                    <div class="main-card-icon"><i class="fas fa-car"></i></div>
                    <h1 class="main-card-title">Editar Vehículo</h1>
                    <p class="main-card-subtitle">Modifica los datos del vehículo seleccionado.</p>
                </div>

                <!-- Mensaje de error cuando la validación de edición falla -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de edición de vehículo -->
                <form method="POST" action="index.php?controller=vehiculo&action=edit">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($vehiculo->id); ?>">
                    <div class="form-group"><label for="marca" class="form-label">Marca</label><input type="text" class="form-control" name="marca" id="marca" value="<?php echo htmlspecialchars($vehiculo->marca); ?>" required></div>
                    <div class="form-group"><label for="modelo" class="form-label">Modelo</label><input type="text" class="form-control" name="modelo" id="modelo" value="<?php echo htmlspecialchars($vehiculo->modelo); ?>" required></div>
                    <div class="form-group"><label for="fecha_matriculacion" class="form-label">Fecha de matriculación</label><input type="date" class="form-control" name="fecha_matriculacion" id="fecha_matriculacion" max="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($vehiculo->fecha_matriculacion); ?>" required></div>
                    <div class="form-group"><label for="puertas" class="form-label">Puertas</label><input type="number" min="1" max="8" class="form-control" name="puertas" id="puertas" value="<?php echo htmlspecialchars($vehiculo->puertas); ?>" required></div>
                    <div class="form-group"><label for="motor" class="form-label">Motor</label>
                        <select class="form-control" name="motor" id="motor" required>
                            <?php $motores = ['Diesel','Gasolina','Hibrido','Electrico']; ?>
                            <?php foreach ($motores as $m): ?>
                                <option value="<?php echo $m; ?>" <?php echo ($vehiculo->motor === $m) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label for="disponibilidad" class="form-label">Disponibilidad</label>
                        <select class="form-control" name="disponibilidad" id="disponibilidad" required>
                            <option value="Si" <?php echo ($vehiculo->disponibilidad === 'Si') ? 'selected' : ''; ?>>Sí</option>
                            <option value="No" <?php echo ($vehiculo->disponibilidad === 'No') ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=vehiculo&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver al listado</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync me-1"></i> Actualizar Vehículo</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de partículas en la vista de edición
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        document.addEventListener('DOMContentLoaded', function() { createParticles(); });
    </script>
</body>
</html>