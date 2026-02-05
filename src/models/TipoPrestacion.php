<?php

class TipoPrestacion
{
    private $db;
    private $table = 'tipos_prestacion';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los tipos con filtros
     */
    public function getAll($filters = [])
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Filtro por búsqueda
        if (!empty($filters['search'])) {
            $query .= " AND nombre LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        $query .= " ORDER BY nombre ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener tipo por ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nuevo tipo
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nombre, descripcion, modo_frecuencia, estado)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['modo_frecuencia'] ?? 'sesiones',
            $data['estado']
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar tipo
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nombre = ?, descripcion = ?, modo_frecuencia = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['modo_frecuencia'] ?? 'sesiones',
            $data['estado'],
            $id
        ]);
    }

    /**
     * Eliminar tipo (cambiar estado)
     */
    public function delete($id)
    {
        $query = "UPDATE {$this->table} SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Verificar si el nombre ya existe
     */
    public function nameExists($nombre, $excludeId = null)
    {
        $query = "SELECT id FROM {$this->table} WHERE nombre = ?";
        $params = [$nombre];

        if ($excludeId !== null) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }

    /**
     * Obtener estadísticas de uso
     */
    public function getStats($id)
    {
        $query = "SELECT
                    COUNT(p.id) as total_prestaciones
                  FROM prestaciones p
                  WHERE p.id_tipo_prestacion = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }
}
