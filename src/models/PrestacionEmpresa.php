<?php

require_once __DIR__ . '/../../config/database.php';

class PrestacionEmpresa
{
    private $db;
    private $table = 'prestaciones_empresas';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las prestaciones de empresas con filtros
     */
    public function getAll($filters = [])
    {
        $query = "SELECT
                    pe.*,
                    e.nombre as empresa_nombre,
                    tp.nombre as prestacion_nombre
                  FROM {$this->table} pe
                  INNER JOIN empresas e ON pe.id_empresa = e.id
                  INNER JOIN tipos_prestacion tp ON pe.id_tipo_prestacion = tp.id
                  WHERE 1=1";

        $params = [];

        // Filtro por empresa
        if (!empty($filters['id_empresa'])) {
            $query .= " AND pe.id_empresa = ?";
            $params[] = $filters['id_empresa'];
        }

        // Filtro por prestación
        if (!empty($filters['id_tipo_prestacion'])) {
            $query .= " AND pe.id_tipo_prestacion = ?";
            $params[] = $filters['id_tipo_prestacion'];
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND pe.estado = ?";
            $params[] = $filters['estado'];
        }

        // Búsqueda por nombre
        if (!empty($filters['search'])) {
            $query .= " AND (e.nombre LIKE ? OR tp.nombre LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $query .= " ORDER BY e.nombre, tp.nombre";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener una prestación-empresa por ID
     */
    public function getById($id)
    {
        $query = "SELECT
                    pe.*,
                    e.nombre as empresa_nombre,
                    tp.nombre as prestacion_nombre
                  FROM {$this->table} pe
                  INNER JOIN empresas e ON pe.id_empresa = e.id
                  INNER JOIN tipos_prestacion tp ON pe.id_tipo_prestacion = tp.id
                  WHERE pe.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Obtener valor_empresa específico por empresa y tipo de prestación
     * Método clave para autocompletar valores al crear prestaciones de pacientes
     */
    public function getValorByEmpresaPrestacion($idEmpresa, $idTipoPrestacion)
    {
        $query = "SELECT valor_empresa
                  FROM {$this->table}
                  WHERE id_empresa = ?
                  AND id_tipo_prestacion = ?
                  AND estado = 'activo'
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$idEmpresa, $idTipoPrestacion]);

        $result = $stmt->fetch();
        return $result ? $result['valor_empresa'] : null;
    }

    /**
     * Obtener todas las prestaciones configuradas para una empresa
     * Útil para filtrar qué prestaciones puede tener un paciente de esa empresa
     */
    public function getPrestacionesByEmpresa($idEmpresa)
    {
        $query = "SELECT
                    pe.*,
                    tp.nombre as prestacion_nombre,
                    tp.descripcion as prestacion_descripcion
                  FROM {$this->table} pe
                  INNER JOIN tipos_prestacion tp ON pe.id_tipo_prestacion = tp.id
                  WHERE pe.id_empresa = ?
                  AND pe.estado = 'activo'
                  ORDER BY tp.nombre";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$idEmpresa]);

        return $stmt->fetchAll();
    }

    /**
     * Crear nueva configuración prestación-empresa
     */
    public function create($data)
    {
        // Verificar si ya existe esta combinación
        $existing = $this->findByEmpresaPrestacion($data['id_empresa'], $data['id_tipo_prestacion']);
        if ($existing) {
            throw new Exception('Ya existe una configuración para esta empresa y prestación.');
        }

        $query = "INSERT INTO {$this->table}
                  (id_empresa, id_tipo_prestacion, valor_empresa, estado)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            $data['id_empresa'],
            $data['id_tipo_prestacion'],
            $data['valor_empresa'],
            $data['estado'] ?? 'activo'
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Actualizar configuración prestación-empresa
     */
    public function update($id, $data)
    {
        // Verificar si existe otra configuración con la misma empresa/prestación
        $existing = $this->findByEmpresaPrestacion($data['id_empresa'], $data['id_tipo_prestacion']);
        if ($existing && $existing['id'] != $id) {
            throw new Exception('Ya existe otra configuración para esta empresa y prestación.');
        }

        $query = "UPDATE {$this->table}
                  SET id_empresa = ?,
                      id_tipo_prestacion = ?,
                      valor_empresa = ?,
                      estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['id_empresa'],
            $data['id_tipo_prestacion'],
            $data['valor_empresa'],
            $data['estado'] ?? 'activo',
            $id
        ]);
    }

    /**
     * Cambiar estado (activar/desactivar)
     */
    public function changeStatus($id, $status)
    {
        $query = "UPDATE {$this->table} SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, $id]);
    }

    /**
     * Eliminar (físicamente)
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Buscar por empresa y prestación
     */
    public function findByEmpresaPrestacion($idEmpresa, $idTipoPrestacion)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE id_empresa = ? AND id_tipo_prestacion = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$idEmpresa, $idTipoPrestacion]);

        return $stmt->fetch();
    }

    /**
     * Obtener estadísticas
     */
    public function getStats()
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as activas,
                    SUM(CASE WHEN estado = 'inactivo' THEN 1 ELSE 0 END) as inactivas,
                    COUNT(DISTINCT id_empresa) as total_empresas,
                    COUNT(DISTINCT id_tipo_prestacion) as total_prestaciones
                  FROM {$this->table}";

        $stmt = $this->db->query($query);
        return $stmt->fetch();
    }
}
