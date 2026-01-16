<!-- Vista: creación de un nuevo vehículo -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Vehículo</title>
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
                <!-- Tarjeta principal con título del formulario -->
                <div class="main-card-header">
                    <div class="main-card-icon"><i class="fas fa-car"></i></div>
                    <h1 class="main-card-title">Crear Nuevo Vehículo</h1>
                    <p class="main-card-subtitle">Completa el formulario para dar de alta un nuevo vehículo.</p>
                </div>

                <!-- Mensaje de error de validación (si hay problemas con los datos) -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de alta de vehículo -->
                <form method="POST" action="index.php?controller=vehiculo&action=create">
                    <div class="form-group"><label for="marca" class="form-label">Marca</label><input type="text" class="form-control" name="marca" id="marca" required></div>
                    <div class="form-group"><label for="modelo" class="form-label">Modelo</label><input type="text" class="form-control" name="modelo" id="modelo" required></div>
                    <div class="form-group"><label for="fecha_matriculacion" class="form-label">Fecha de matriculación</label><input type="date" class="form-control" name="fecha_matriculacion" id="fecha_matriculacion" max="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="form-group"><label for="puertas" class="form-label">Puertas</label><input type="number" min="1" max="8" class="form-control" name="puertas" id="puertas" required></div>
                    <div class="form-group"><label for="motor" class="form-label">Motor</label>
                        <select class="form-control" name="motor" id="motor" required>
                            <option value="">-- Selecciona --</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Hibrido">Híbrido</option>
                            <option value="Electrico">Eléctrico</option>
                        </select>
                    </div>
                    <div class="form-group"><label for="disponibilidad" class="form-label">Disponibilidad</label>
                        <select class="form-control" name="disponibilidad" id="disponibilidad" required>
                            <option value="">-- Selecciona --</option>
                            <option value="Si">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=vehiculo&action=index" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Volver al listado</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar Vehículo</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de partículas en la vista de creación
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
