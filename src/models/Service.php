<?php

class Service
{
    private $db;
    private $table = 'prestaciones';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las prestaciones con filtros
     */
    public function getAll($filters = [], $limit = null, $offset = null)
    {
        $query = "SELECT p.*, tp.nombre as tipo_prestacion_nombre
                  FROM {$this->table} p
                  LEFT JOIN tipos_prestacion tp ON p.id_tipo_prestacion = tp.id
                  WHERE 1=1";
        $params = [];

        // Filtro por búsqueda (nombre, código, descripción)
        if (!empty($filters['search'])) {
            $query .= " AND (p.nombre LIKE ? OR p.codigo LIKE ? OR p.descripcion LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtro por tipo
        if (!empty($filters['tipo_prestacion'])) {
            $query .= " AND p.id_tipo_prestacion = ?";
            $params[] = $filters['tipo_prestacion'];
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND p.estado = ?";
            $params[] = $filters['estado'];
        }

        // Ordenamiento
        $query .= " ORDER BY p.nombre ASC";

        // Paginación
        if ($limit !== null) {
            $query .= " LIMIT ?";
            $params[] = (int)$limit;

            if ($offset !== null) {
                $query .= " OFFSET ?";
                $params[] = (int)$offset;
            }
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Contar prestaciones con filtros
     */
    public function count($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND (nombre LIKE ? OR codigo LIKE ? OR descripcion LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['estado'])) {
            $query .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return (int)$row['total'];
    }

    /**
     * Obtener prestación por ID
     */
    public function getById($id)
    {
        $query = "SELECT p.*, tp.nombre as tipo_prestacion_nombre
                  FROM {$this->table} p
                  LEFT JOIN tipos_prestacion tp ON p.id_tipo_prestacion = tp.id
                  WHERE p.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nueva prestación
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nombre, descripcion, codigo, id_tipo_prestacion, estado)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['codigo'],
            $data['id_tipo_prestacion'],
            $data['estado']
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar prestación
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nombre = ?, descripcion = ?, codigo = ?, id_tipo_prestacion = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['codigo'],
            $data['id_tipo_prestacion'],
            $data['estado'],
            $id
        ]);
    }

    /**
     * Eliminar (desactivar) prestación
     */
    public function delete($id)
    {
        $query = "UPDATE {$this->table} SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Verificar si el código ya existe
     */
    public function codigoExists($codigo, $excludeId = null)
    {
        if (empty($codigo)) {
            return false;
        }

        $query = "SELECT id FROM {$this->table} WHERE codigo = ?";
        $params = [$codigo];

        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }

    /**
     * Obtener todas las prestaciones con estadísticas de uso
     */
    public function getAllWithStats($filters = [])
    {
        $query = "SELECT
                    p.*,
                    COUNT(DISTINCT pac.id) as total_pacientes,
                    COUNT(DISTINCT CASE WHEN pac.paciente_recurrente = 1 THEN pac.id END) as pacientes_activos,
                    COUNT(DISTINCT pac.id_profesional) as profesionales_asignados
                  FROM {$this->table} p
                  LEFT JOIN pacientes pac ON p.id = pac.id_prestacion
                  WHERE 1=1";
        $params = [];

        // Filtro por búsqueda
        if (!empty($filters['search'])) {
            $query .= " AND (p.nombre LIKE ? OR p.codigo LIKE ? OR p.descripcion LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND p.estado = ?";
            $params[] = $filters['estado'];
        }

        $query .= " GROUP BY p.id ORDER BY p.nombre ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener estadísticas detalladas de la prestación
     */
    public function getStats($id)
    {
        $query = "SELECT
                    COUNT(*) as total_pacientes,
                    SUM(CASE WHEN paciente_recurrente = 1 THEN 1 ELSE 0 END) as pacientes_activos,
                    SUM(CASE WHEN paciente_recurrente = 0 THEN 1 ELSE 0 END) as pacientes_finalizados
                  FROM pacientes
                  WHERE id_prestacion = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Obtener profesionales que trabajan con esta prestación
     */
    public function getProfessionals($id)
    {
        $query = "SELECT DISTINCT
                    prof.id,
                    prof.nombre,
                    prof.especialidad,
                    COUNT(DISTINCT pac.id) as total_pacientes
                  FROM profesionales prof
                  INNER JOIN pacientes pac ON prof.id = pac.id_profesional
                  WHERE pac.id_prestacion = ?
                  GROUP BY prof.id
                  ORDER BY prof.nombre ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetchAll();
    }
}
