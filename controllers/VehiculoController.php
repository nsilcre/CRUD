<?php
// controllers/VehiculoController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Vehiculo.php';

class VehiculoController
{
    private $db;
    private Vehiculo $vehiculo;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->vehiculo = new Vehiculo($this->db);
    }

    private function validarDatos(array $data, string &$error): bool
    {
        $error = '';

        $marca  = trim($data['marca'] ?? '');
        $modelo = trim($data['modelo'] ?? '');
        $fecha  = $data['fecha_matriculacion'] ?? '';
        $puertasRaw = $data['puertas'] ?? '';
        $motor  = $data['motor'] ?? '';
        $disp   = $data['disponibilidad'] ?? '';

        if ($marca === '' || $modelo === '') {
            $error = 'Marca y modelo son obligatorios.';
            return false;
        }

        // Validar fecha (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $error = 'La fecha de matriculación no es válida.';
            return false;
        }
        [$y, $m, $d] = array_map('intval', explode('-', $fecha));
        if (!checkdate($m, $d, $y)) {
            $error = 'La fecha de matriculación no existe.';
            return false;
        }
        // No permitir fechas futuras
        $fechaMat = new DateTime($fecha);
        $hoy      = new DateTime('today');
        if ($fechaMat > $hoy) {
            $error = 'La fecha de matriculación no puede ser posterior a hoy.';
            return false;
        }

        // Validar puertas (entre 1 y 8)
        $puertas = filter_var($puertasRaw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 8]]);
        if ($puertas === false) {
            $error = 'El número de puertas debe estar entre 1 y 8.';
            return false;
        }

        // Validar motor
        $motoresValidos = ['Diesel', 'Gasolina', 'Hibrido', 'Electrico'];
        if (!in_array($motor, $motoresValidos, true)) {
            $error = 'El tipo de motor no es válido.';
            return false;
        }

        // Validar disponibilidad
        $dispValidas = ['Si', 'No'];
        if (!in_array($disp, $dispValidas, true)) {
            $error = 'El valor de disponibilidad no es válido.';
            return false;
        }

        return true;
    }

    public function index(): void
    {
        $stmt = $this->vehiculo->read();
        $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/vehiculos/listar.php';
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = '';
            if (!$this->validarDatos($_POST, $error)) {
                include __DIR__ . '/../views/vehiculos/crear.php';
                return;
            }

            $this->vehiculo->marca               = $_POST['marca'];
            $this->vehiculo->modelo              = $_POST['modelo'];
            $this->vehiculo->fecha_matriculacion = $_POST['fecha_matriculacion'];
            $this->vehiculo->puertas             = (int)$_POST['puertas'];
            $this->vehiculo->motor               = $_POST['motor'];
            $this->vehiculo->disponibilidad      = $_POST['disponibilidad'];

            if ($this->vehiculo->create()) {
                header('Location: index.php?controller=vehiculo&action=index&message=created');
                exit;
            } else {
                $error = 'Error al crear el vehículo.';
                include __DIR__ . '/../views/vehiculos/crear.php';
            }
        } else {
            $error = '';
            include __DIR__ . '/../views/vehiculos/crear.php';
        }
    }

    public function edit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = '';
            if (!$this->validarDatos($_POST, $error)) {
                // Volvemos a cargar el objeto con los datos enviados para no perderlos
                $vehiculo = $this->vehiculo;
                $vehiculo->id                  = (int)($_POST['id'] ?? 0);
                $vehiculo->marca               = $_POST['marca'] ?? '';
                $vehiculo->modelo              = $_POST['modelo'] ?? '';
                $vehiculo->fecha_matriculacion = $_POST['fecha_matriculacion'] ?? '';
                $vehiculo->puertas             = (int)($_POST['puertas'] ?? 0);
                $vehiculo->motor               = $_POST['motor'] ?? '';
                $vehiculo->disponibilidad      = $_POST['disponibilidad'] ?? '';
                include __DIR__ . '/../views/vehiculos/editar.php';
                return;
            }

            $this->vehiculo->id                  = (int)$_POST['id'];
            $this->vehiculo->marca               = $_POST['marca'];
            $this->vehiculo->modelo              = $_POST['modelo'];
            $this->vehiculo->fecha_matriculacion = $_POST['fecha_matriculacion'];
            $this->vehiculo->puertas             = (int)$_POST['puertas'];
            $this->vehiculo->motor               = $_POST['motor'];
            $this->vehiculo->disponibilidad      = $_POST['disponibilidad'];

            if ($this->vehiculo->update()) {
                header('Location: index.php?controller=vehiculo&action=index&message=updated');
                exit;
            } else {
                $error = 'Error al actualizar el vehículo.';
            }
        }

        if (isset($_GET['id'])) {
            $this->vehiculo->id = (int)$_GET['id'];
            $this->vehiculo->readOne();

            if ($this->vehiculo->marca ?? false) {
                $vehiculo = $this->vehiculo;
                $error = $error ?? '';
                include __DIR__ . '/../views/vehiculos/editar.php';
            } else {
                echo 'Vehículo no encontrado.';
            }
        }
    }

    public function delete(): void
    {
        if (isset($_GET['id'])) {
            $this->vehiculo->id = (int)$_GET['id'];

            if ($this->vehiculo->delete()) {
                header('Location: index.php?controller=vehiculo&action=index&message=deleted');
            } else {
                header('Location: index.php?controller=vehiculo&action=index&message=error_delete');
            }
            exit;
        }
    }
}
