<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/Database.php';

/**
 * Controlador de autenticación (login/logout).
 */
class AuthController
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Muestra el formulario de login (GET) o procesa las credenciales (POST).
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
            return;
        }

        // Mostrar el formulario de login
        include __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Valida el formulario de login y establece la sesión de usuario.
     */
    private function processLogin(): void
    {
        // Comprobar CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Error de seguridad: Token CSRF inválido.';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_POST['usuario'])) {
            $_SESSION['error'] = 'Debes introducir usuario y contraseña.';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $usuario_form  = trim((string)$_POST['usuario']);
        $password_form = (string)$_POST['password'];

        try {
            $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE idusuario = :usuario');
            $stmt->execute([':usuario' => $usuario_form]);
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error conectando a la BD.';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = 'El usuario no existe';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // Comprobación de bloqueo (si existen las columnas)
        if (isset($row->bloqueado_hasta) && $row->bloqueado_hasta !== null) {
            $now = new DateTime('now');
            $bloqueadoHasta = new DateTime($row->bloqueado_hasta);
            if ($bloqueadoHasta > $now) {
                $_SESSION['error'] = 'Cuenta bloqueada temporalmente. Inténtalo más tarde.';
                header('Location: index.php?controller=auth&action=login');
                exit;
            }
        }

        // Verificar contraseña (hash)
        $passwordCorrecta = false;
        if (!empty($row->password)) {
            // Si está hasheada con password_hash
            if (password_verify($password_form, $row->password)) {
                $passwordCorrecta = true;
            }
            // Compatibilidad: por si hubiera contraseñas antiguas en texto plano
            if (!$passwordCorrecta && hash_equals($row->password, $password_form)) {
                $passwordCorrecta = true;
            }
        }

        if ($passwordCorrecta) {
            // Login correcto: almacenamos los datos mínimos de usuario en sesión.
            $_SESSION['nombre']    = $row->nombre;
            $_SESSION['apellidos'] = $row->apellidos;
            $_SESSION['idusuario'] = $row->idusuario;

            // Resetear intentos/bloqueo si existen
            if (isset($row->intentos) || isset($row->bloqueado_hasta)) {
                $stmt_reset = $this->db->prepare('UPDATE usuarios SET intentos = 0, bloqueado_hasta = NULL WHERE idusuario = :usuario');
                $stmt_reset->execute([':usuario' => $usuario_form]);
            }

            if (!empty($_POST['recordarme'])) {
                setcookie('usuario_guardado', $usuario_form, time() + (86400 * 30), '/');
            }

            header('Location: index.php?controller=vehiculo&action=index');
            exit;
        }

        // Contraseña incorrecta -> gestionar intentos si existen columnas
        if (isset($row->intentos)) {
            $intentos = (int)$row->intentos + 1;
            if ($intentos >= 5) {
                $bloqueo = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                $stmt_block = $this->db->prepare('UPDATE usuarios SET intentos = :intentos, bloqueado_hasta = :bloqueo WHERE idusuario = :usuario');
                $stmt_block->execute([
                    ':intentos' => $intentos,
                    ':bloqueo'  => $bloqueo,
                    ':usuario'  => $usuario_form,
                ]);
                $_SESSION['error'] = 'Has excedido el número de intentos. Cuenta bloqueada por 15 minutos.';
            } else {
                $stmt_inc = $this->db->prepare('UPDATE usuarios SET intentos = :intentos WHERE idusuario = :usuario');
                $stmt_inc->execute([
                    ':intentos' => $intentos,
                    ':usuario'  => $usuario_form,
                ]);
                $_SESSION['error'] = "Contraseña incorrecta. Intento $intentos de 5.";
            }
        } else {
            $_SESSION['error'] = 'Contraseña incorrecta.';
        }

        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    /**
     * Cierra la sesión del usuario actual.
     */
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}
