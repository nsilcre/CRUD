<?php

/**
 * Modelo de dominio para la tabla `vehiculos`.
 */
class Vehiculo
{
    private PDO $conn;
    private string $table_name = 'vehiculos';

    // Se asume que la tabla vehiculos tiene una columna id INT AUTO_INCREMENT PRIMARY KEY.
    public ?int $id = null;
    public string $marca;
    public string $modelo;
    public string $fecha_matriculacion; // formato 'Y-m-d'
    public int $puertas;
    public string $motor;          // 'Diesel', 'Gasolina', 'Hibrido', 'Electrico'
    public string $disponibilidad; // 'Si', 'No'

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Leer todos los vehículos
    /**
     * Devuelve un statement con todos los vehículos ordenados por id.
     */
    public function read()
    {
        $query = "SELECT id, marca, modelo, fecha_matriculacion, puertas, motor, disponibilidad
                  FROM {$this->table_name}
                  ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Inserta un nuevo vehículo en la base de datos.
     */
    public function create(): bool
    {
        $query = "INSERT INTO {$this->table_name}
                  (marca, modelo, fecha_matriculacion, puertas, motor, disponibilidad)
                  VALUES (:marca, :modelo, :fecha_matriculacion, :puertas, :motor, :disponibilidad)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':marca', $this->marca);
        $stmt->bindParam(':modelo', $this->modelo);
        $stmt->bindParam(':fecha_matriculacion', $this->fecha_matriculacion);
        $stmt->bindParam(':puertas', $this->puertas, PDO::PARAM_INT);
        $stmt->bindParam(':motor', $this->motor);
        $stmt->bindParam(':disponibilidad', $this->disponibilidad);

        return $stmt->execute();
    }

    /**
     * Carga en el propio objeto los datos de un vehículo concreto (por id).
     */
    public function readOne(): void
    {
        $query = "SELECT id, marca, modelo, fecha_matriculacion, puertas, motor, disponibilidad
                  FROM {$this->table_name}
                  WHERE id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->id                   = (int)$row['id'];
            $this->marca                = $row['marca'];
            $this->modelo               = $row['modelo'];
            $this->fecha_matriculacion  = $row['fecha_matriculacion'];
            $this->puertas              = (int)$row['puertas'];
            $this->motor                = $row['motor'];
            $this->disponibilidad       = $row['disponibilidad'];
        }
    }

    /**
     * Actualiza un vehículo existente.
     */
    public function update(): bool
    {
        $query = "UPDATE {$this->table_name}
                  SET marca = :marca,
                      modelo = :modelo,
                      fecha_matriculacion = :fecha_matriculacion,
                      puertas = :puertas,
                      motor = :motor,
                      disponibilidad = :disponibilidad
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':marca', $this->marca);
        $stmt->bindParam(':modelo', $this->modelo);
        $stmt->bindParam(':fecha_matriculacion', $this->fecha_matriculacion);
        $stmt->bindParam(':puertas', $this->puertas, PDO::PARAM_INT);
        $stmt->bindParam(':motor', $this->motor);
        $stmt->bindParam(':disponibilidad', $this->disponibilidad);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Elimina un vehículo por id.
     */
    public function delete(): bool
    {
        $query = "DELETE FROM {$this->table_name}
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
