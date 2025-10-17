<?php

class PrestacionPaciente
{
    private $db;
    private $table = 'prestaciones_pacientes';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las prestaciones de un paciente
     */
    public function getByPaciente($idPaciente, $filters = [])
    {
        $query = "SELECT
                    pp.*,
                    pac.nombre_completo as paciente_nombre,
                    tp.nombre as prestacion_nombre,
                    tp.descripcion as prestacion_descripcion,
                    prof.nombre as profesional_nombre,
                    prof.especialidad as profesional_especialidad,
                    emp.nombre as empresa_nombre,
                    f.nombre as frecuencia_nombre,
                    f.sesiones_por_mes as frecuencia_sesiones
                  FROM {$this->table} pp
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  LEFT JOIN empresas emp ON pp.id_empresa = emp.id
                  LEFT JOIN frecuencias f ON pp.id_frecuencia = f.id
                  WHERE pp.id_paciente = ?";

        $params = [$idPaciente];

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND pp.estado = ?";
            $params[] = $filters['estado'];
        }

        $query .= " ORDER BY pp.fecha_inicio DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener todas las prestaciones con filtros
     */
    public function getAll($filters = [])
    {
        $query = "SELECT
                    pp.*,
                    pac.nombre_completo as paciente_nombre,
                    tp.nombre as prestacion_nombre,
                    prof.nombre as profesional_nombre,
                    emp.nombre as empresa_nombre,
                    DATEDIFF(pp.fecha_fin, CURDATE()) as dias_restantes
                  FROM {$this->table} pp
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  LEFT JOIN empresas emp ON pp.id_empresa = emp.id
                  WHERE 1=1";

        $params = [];

        // Filtro por búsqueda
        if (!empty($filters['search'])) {
            $query .= " AND (pac.nombre_completo LIKE ? OR tp.nombre LIKE ? OR prof.nombre LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filtro por profesional
        if (!empty($filters['profesional'])) {
            $query .= " AND pp.id_profesional = ?";
            $params[] = $filters['profesional'];
        }

        // Filtro por empresa
        if (!empty($filters['empresa'])) {
            $query .= " AND pp.id_empresa = ?";
            $params[] = $filters['empresa'];
        }

        // Filtro por prestación
        if (!empty($filters['prestacion'])) {
            $query .= " AND pp.id_tipo_prestacion = ?";
            $params[] = $filters['prestacion'];
        }

        // Filtro por estado
        if (!empty($filters['estado'])) {
            $query .= " AND pp.estado = ?";
            $params[] = $filters['estado'];
        }

        // Filtro por recurrente
        if (isset($filters['recurrente']) && $filters['recurrente'] !== '') {
            $query .= " AND pp.es_recurrente = ?";
            $params[] = (int)$filters['recurrente'];
        }

        // Ordenamiento: próximos a vencer primero
        $query .= " ORDER BY
                    CASE
                        WHEN pp.es_recurrente = 1 THEN 0
                        ELSE 1
                    END,
                    pp.fecha_fin ASC,
                    pp.fecha_inicio DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener prestación por ID
     */
    public function getById($id)
    {
        $query = "SELECT
                    pp.*,
                    pac.nombre_completo as paciente_nombre,
                    tp.nombre as prestacion_nombre,
                    prof.nombre as profesional_nombre,
                    emp.nombre as empresa_nombre
                  FROM {$this->table} pp
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  LEFT JOIN empresas emp ON pp.id_empresa = emp.id
                  WHERE pp.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nueva prestación para paciente
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (id_paciente, id_tipo_prestacion, id_profesional, id_empresa, fecha_inicio, fecha_fin,
                   es_recurrente, id_frecuencia, sesiones_personalizadas, frecuencia_servicio,
                   valor_profesional, valor_empresa, observaciones, estado)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['id_paciente'],
            $data['id_tipo_prestacion'],
            $data['id_profesional'],
            $data['id_empresa'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['es_recurrente'],
            $data['id_frecuencia'],
            $data['sesiones_personalizadas'],
            $data['frecuencia_servicio'],
            $data['valor_profesional'],
            $data['valor_empresa'],
            $data['observaciones'],
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
                  SET id_tipo_prestacion = ?, id_profesional = ?, id_empresa = ?,
                      fecha_inicio = ?, fecha_fin = ?, es_recurrente = ?,
                      id_frecuencia = ?, sesiones_personalizadas = ?, frecuencia_servicio = ?,
                      valor_profesional = ?, valor_empresa = ?,
                      observaciones = ?, estado = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['id_tipo_prestacion'],
            $data['id_profesional'],
            $data['id_empresa'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['es_recurrente'],
            $data['id_frecuencia'],
            $data['sesiones_personalizadas'],
            $data['frecuencia_servicio'],
            $data['valor_profesional'],
            $data['valor_empresa'],
            $data['observaciones'],
            $data['estado'],
            $id
        ]);
    }

    /**
     * Eliminar (finalizar) prestación
     */
    public function delete($id)
    {
        $query = "UPDATE {$this->table} SET estado = 'finalizado', fecha_fin = CURDATE() WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Obtener prestaciones próximas a vencer
     */
    public function getExpiring($days = 7)
    {
        $query = "SELECT
                    pp.*,
                    pac.nombre_completo as paciente_nombre,
                    tp.nombre as prestacion_nombre,
                    prof.nombre as profesional_nombre,
                    emp.nombre as empresa_nombre,
                    DATEDIFF(pp.fecha_fin, CURDATE()) as dias_restantes
                  FROM {$this->table} pp
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  LEFT JOIN empresas emp ON pp.id_empresa = emp.id
                  WHERE pp.es_recurrente = 0
                    AND pp.estado = 'activo'
                    AND pp.fecha_fin IS NOT NULL
                    AND DATEDIFF(pp.fecha_fin, CURDATE()) <= ?
                    AND DATEDIFF(pp.fecha_fin, CURDATE()) >= 0
                  ORDER BY pp.fecha_fin ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$days]);

        return $stmt->fetchAll();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getStats()
    {
        $query = "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN es_recurrente = 1 THEN 1 ELSE 0 END) as recurrentes,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END) as finalizados,
                    COALESCE(SUM(valor_profesional), 0) as total_profesionales,
                    COALESCE(SUM(valor_empresa), 0) as total_empresas
                  FROM {$this->table}";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Contar prestaciones activas de un paciente
     */
    public function countActivasByPaciente($idPaciente)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE id_paciente = ? AND estado = 'activo'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idPaciente]);
        $row = $stmt->fetch();

        return (int)$row['total'];
    }
}
