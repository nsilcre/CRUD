<!-- Vista: listado de vehículos -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="styles/crud.css">
</head>
<body>
    <!-- Fondo animado del dashboard -->
    <div class="bg-animation"></div>
    <div class="particle-system" id="particles"></div>
    <div class="geometric-shapes">
        <div class="geo-shape triangle" style="top: 10%; left: 5%;"></div>
        <div class="geo-shape square" style="top: 60%; right: 10%;"></div>
        <div class="geo-shape circle" style="bottom: 20%; left: 15%;"></div>
    </div>

    <div class="page-container">
        <!-- Cabecera con logo y datos de usuario logueado -->
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
                <div class="main-card-header">
                    <div class="main-card-icon"><i class="fas fa-list"></i></div>
                    <h1 class="main-card-title">Listado de Vehículos</h1>
                    <p class="main-card-subtitle">Gestiona todos los vehículos registrados en el sistema.</p>
                </div>

                <!-- Mensajes de éxito/error tras operaciones CRUD -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert <?php echo ($_GET['message'] === 'error_delete') ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                        <?php
                        if ($_GET['message'] === 'created') echo '<i class="fas fa-check-circle me-2"></i>Vehículo creado correctamente.';
                        if ($_GET['message'] === 'updated') echo '<i class="fas fa-check-circle me-2"></i>Vehículo actualizado correctamente.';
                        if ($_GET['message'] === 'deleted') echo '<i class="fas fa-check-circle me-2"></i>Vehículo eliminado correctamente.';
                        if ($_GET['message'] === 'error_delete') echo '<i class="fas fa-exclamation-triangle me-2"></i>Error al eliminar el vehículo.';
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Barra de acciones: título + botón de alta de vehículo -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Vehículos Registrados</h3>
                    <a href="index.php?controller=vehiculo&action=create" class="btn btn-primary"><i class="fas fa-car"></i> Añadir Nuevo Vehículo</a>
                </div>

                <!-- Tabla principal de vehículos -->
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr><th>ID</th><th>Marca</th><th>Modelo</th><th>Fecha Matriculación</th><th>Puertas</th><th>Motor</th><th>Disponibilidad</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($vehiculos)): ?>
                                <?php foreach ($vehiculos as $vehiculo): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($vehiculo['id']); ?></td>
                                        <td><?php echo htmlspecialchars($vehiculo['marca']); ?></td>
                                        <td><?php echo htmlspecialchars($vehiculo['modelo']); ?></td>
                                        <td><?php echo htmlspecialchars($vehiculo['fecha_matriculacion']); ?></td>
                                        <td><?php echo htmlspecialchars($vehiculo['puertas']); ?></td>
                                        <td><?php echo htmlspecialchars($vehiculo['motor']); ?></td>
                                        <td><?php echo htmlspecialchars($vehiculo['disponibilidad']); ?></td>
                                        <td>
                                            <a href="index.php?controller=vehiculo&action=edit&id=<?php echo urlencode($vehiculo['id']); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-delete-url="index.php?controller=vehiculo&action=delete&id=<?php echo urlencode($vehiculo['id']); ?>" data-user-name="<?php echo htmlspecialchars($vehiculo['marca'] . ' ' . $vehiculo['modelo']); ?>"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">No hay vehículos registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación para eliminar un vehículo -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    <p>¿Estás seguro de que quieres eliminar el vehículo <strong id="userNameToDelete"></strong>?</p>
                    <p class="mb-0 text-secondary">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a id="confirmDeleteButton" href="#" class="btn btn-danger"><i class="fas fa-trash me-1"></i>Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuración del modal de borrado: se rellena el nombre del vehículo y la URL de borrado
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const deleteUrl = button.getAttribute('data-delete-url');
                const userName = button.getAttribute('data-user-name');
                const modalUserName = deleteModal.querySelector('#userNameToDelete');
                const confirmButton = deleteModal.querySelector('#confirmDeleteButton');
                if (modalUserName) modalUserName.textContent = userName;
                if (confirmButton) confirmButton.setAttribute('href', deleteUrl);
            });
        }
        // Efecto de partículas en el fondo del listado
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