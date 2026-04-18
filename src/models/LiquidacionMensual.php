<?php

class LiquidacionMensual
{
    private $db;
    private $table = 'liquidaciones_mensuales';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener liquidaciones por período con datos relacionados
     */
    public function getByPeriodo($periodo, $filters = [])
    {
        $query = "SELECT
                    lm.*,
                    pp.id_paciente,
                    pp.id_profesional,
                    pp.id_empresa,
                    pp.id_frecuencia,
                    pp.sesiones_personalizadas,
                    pp.horas_semana,
                    pp.estado as prestacion_estado,
                    pac.nombre_completo as paciente_nombre,
                    prof.nombre as profesional_nombre,
                    prof.especialidad,
                    tp.nombre as prestacion_nombre,
                    tp.modo_frecuencia,
                    e.nombre as empresa_nombre
                  FROM {$this->table} lm
                  INNER JOIN prestaciones_pacientes pp ON lm.id_prestacion_paciente = pp.id
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  LEFT JOIN empresas e ON pp.id_empresa = e.id
                  WHERE lm.periodo = ?";

        $params = [$periodo];

        if (!empty($filters['profesional'])) {
            $query .= " AND pp.id_profesional = ?";
            $params[] = $filters['profesional'];
        }

        if (!empty($filters['empresa'])) {
            $query .= " AND pp.id_empresa = ?";
            $params[] = $filters['empresa'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (pac.nombre_completo LIKE ? OR prof.nombre LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $query .= " ORDER BY prof.nombre, pac.nombre_completo";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener resumen agrupado por profesional para un período
     */
    public function getResumenByPeriodo($periodo, $filters = [])
    {
        $query = "SELECT
                    prof.id as profesional_id,
                    prof.nombre as profesional_nombre,
                    prof.especialidad,
                    COUNT(lm.id) as total_prestaciones,
                    SUM(lm.sesiones_esperadas) as total_sesiones_esperadas,
                    SUM(lm.sesiones_realizadas) as total_sesiones_realizadas,
                    SUM(lm.total_profesional) as total_profesional,
                    SUM(lm.total_empresa) as total_empresa
                  FROM {$this->table} lm
                  INNER JOIN prestaciones_pacientes pp ON lm.id_prestacion_paciente = pp.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  WHERE lm.periodo = ?";

        $params = [$periodo];

        if (!empty($filters['profesional'])) {
            $query .= " AND pp.id_profesional = ?";
            $params[] = $filters['profesional'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND prof.nombre LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $query .= " GROUP BY prof.id, prof.nombre, prof.especialidad
                     ORDER BY prof.nombre";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtener detalle de liquidaciones de un profesional en un período
     */
    public function getDetalleByProfesionalPeriodo($profesionalId, $periodo)
    {
        $query = "SELECT
                    lm.*,
                    pac.nombre_completo as paciente_nombre,
                    tp.nombre as prestacion_nombre,
                    tp.modo_frecuencia,
                    e.nombre as empresa_nombre
                  FROM {$this->table} lm
                  INNER JOIN prestaciones_pacientes pp ON lm.id_prestacion_paciente = pp.id
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  LEFT JOIN empresas e ON pp.id_empresa = e.id
                  WHERE pp.id_profesional = ? AND lm.periodo = ?
                  ORDER BY pac.nombre_completo";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$profesionalId, $periodo]);

        return $stmt->fetchAll();
    }

    /**
     * Obtener períodos disponibles (para filtro)
     */
    public function getPeriodosDisponibles()
    {
        $query = "SELECT DISTINCT periodo FROM {$this->table} ORDER BY periodo DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Guardar o actualizar liquidación (upsert)
     */
    public function save($data)
    {
        $query = "INSERT INTO {$this->table}
                  (id_prestacion_paciente, periodo, sesiones_esperadas, sesiones_realizadas,
                   valor_profesional, valor_empresa, total_profesional, total_empresa, observaciones)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE
                    sesiones_esperadas = VALUES(sesiones_esperadas),
                    sesiones_realizadas = VALUES(sesiones_realizadas),
                    valor_profesional = VALUES(valor_profesional),
                    valor_empresa = VALUES(valor_empresa),
                    total_profesional = VALUES(total_profesional),
                    total_empresa = VALUES(total_empresa),
                    observaciones = VALUES(observaciones)";

        $totalProf = floatval($data['sesiones_realizadas']) * floatval($data['valor_profesional']);
        $totalEmp = floatval($data['sesiones_realizadas']) * floatval($data['valor_empresa']);

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['id_prestacion_paciente'],
            $data['periodo'],
            $data['sesiones_esperadas'],
            $data['sesiones_realizadas'],
            $data['valor_profesional'],
            $data['valor_empresa'],
            $totalProf,
            $totalEmp,
            $data['observaciones'] ?? null
        ]);
    }

    /**
     * Guardar múltiples liquidaciones en una transacción
     */
    public function saveBatch($items)
    {
        $this->db->beginTransaction();

        try {
            foreach ($items as $data) {
                $this->save($data);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Obtener totales de un período
     */
    public function getTotalesByPeriodo($periodo)
    {
        $query = "SELECT
                    COUNT(DISTINCT pp.id_profesional) as total_profesionales,
                    COUNT(lm.id) as total_prestaciones,
                    SUM(lm.sesiones_esperadas) as total_sesiones_esperadas,
                    SUM(lm.sesiones_realizadas) as total_sesiones_realizadas,
                    SUM(lm.total_profesional) as total_profesional,
                    SUM(lm.total_empresa) as total_empresa
                  FROM {$this->table} lm
                  INNER JOIN prestaciones_pacientes pp ON lm.id_prestacion_paciente = pp.id
                  WHERE lm.periodo = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$periodo]);

        return $stmt->fetch();
    }

    /**
     * Verificar si ya existe liquidación para un período
     */
    public function existePeriodo($periodo)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE periodo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$periodo]);
        $row = $stmt->fetch();

        return (int)$row['total'] > 0;
    }

    /**
     * Eliminar liquidación por ID
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Obtener liquidación por prestación y período
     */
    public function getByPrestacionPeriodo($idPrestacionPaciente, $periodo)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE id_prestacion_paciente = ? AND periodo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idPrestacionPaciente, $periodo]);

        return $stmt->fetch();
    }
}
