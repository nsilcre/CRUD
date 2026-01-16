<?php /** Vista de login: usa $_SESSION['csrf_token'] para protección CSRF */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="styles/login-applogin.css">
</head>
<body>
    <!-- Fondo animado decorativo -->
    <div class="bg-animation"></div>
    <div class="particle-system" id="particleSystem"></div>
    <div class="geometric-shapes">
        <div class="geo-shape triangle" style="top: 10%; left: 5%;"></div>
        <div class="geo-shape square" style="top: 60%; right: 10%;"></div>
        <div class="geo-shape circle" style="bottom: 20%; left: 15%;"></div>
    </div>

    <div class="page-container">
        <!-- Cabecera con logo de la aplicación -->
        <header class="page-header">
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo-icon"><i class="fas fa-user-shield"></i></div>
                    <div class="logo-text">Mi Aplicación</div>
                </div>
            </div>
        </header>

        <main class="page-content">
            <div class="main-card">
                <!-- Tarjeta principal de login -->
                <div class="main-card-header">
                    <div class="main-card-icon"><i class="fas fa-user-shield"></i></div>
                    <h1 class="main-card-title">Bienvenido</h1>
                    <p class="main-card-subtitle">Ingresa tus credenciales para acceder al sistema</p>
                </div>

                <!-- Mensaje de error de login (si existe en sesión) -->
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Formulario de login -->
                <form id="loginForm" method="POST" action="index.php?controller=auth&action=login">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="form-group">
                        <label for="userInput" class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" id="userInput" placeholder="Tu nombre de usuario" value="<?php echo isset($_COOKIE['usuario_guardado']) ? htmlspecialchars($_COOKIE['usuario_guardado']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="passInput" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="passInput" placeholder="Tu contraseña" required>
                            <button class="btn btn-secondary" type="button" id="passwordToggle">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="recordarme">
                            <label class="form-check-label" for="exampleCheck1">Recordarme</label>
                        </div>
                        <a href="#" class="text-decoration-none" style="color: var(--primary-color);">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </form>
                <div class="mt-4 text-center">
                    <small class="text-secondary">¿No tienes una cuenta? <a href="#" class="text-decoration-none fw-semibold" style="color: var(--primary-color);">Regístrate aquí</a></small>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica para mostrar/ocultar la contraseña
        document.getElementById('passwordToggle').addEventListener('click', function() {
            const passInput = document.getElementById('passInput');
            const icon = this.querySelector('i');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        // Genera partículas de fondo decorativas
        function createParticles() {
            const particleSystem = document.getElementById('particleSystem');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                particleSystem.appendChild(particle);
            }
        }
        document.addEventListener('DOMContentLoaded', function() { createParticles(); });
    </script>
</body>
</html>
