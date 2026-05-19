<?php

require_once __DIR__ . '/../../config/database.php';

class Frequency
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las frecuencias activas
     */
    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT *
            FROM frecuencias
            WHERE estado = 'activo'
            ORDER BY orden ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Obtener frecuencia por ID
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM frecuencias
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Obtener sesiones por mes de una frecuencia
     */
    public function getSessionsPerMonth($frecuenciaId, $sessionesPersonalizadas = null)
    {
        if (empty($frecuenciaId)) {
            return 4; // Default
        }

        $frecuencia = $this->getById($frecuenciaId);

        if (!$frecuencia) {
            return 4; // Default
        }

        // Si es frecuencia personalizada (id = 9), usar sesiones_personalizadas
        if ($frecuencia['id'] == 9 && $sessionesPersonalizadas !== null) {
            return intval($sessionesPersonalizadas);
        }

        return intval($frecuencia['sesiones_por_mes']);
    }

    /**
     * Obtener horas esperadas para un período (YYYY-MM).
     *
     * - Si la prestación tiene horas_mes_override = 1, usa horas_mes tal cual.
     * - Si no, calcula horas_por_dia × días_reales_del_mes_del_periodo.
     * - Si no hay horas_por_dia ni horas_mes, devuelve 0.
     *
     * @param array $prestacion Fila de prestaciones_pacientes (con campos horas_por_dia, horas_mes, horas_mes_override).
     * @param string|null $periodo Formato 'YYYY-MM'. Si null, usa el mes actual.
     * @return float Horas esperadas en el período.
     */
    public function getHoursPerMonth($prestacion, $periodo = null)
    {
        if (!empty($prestacion['horas_mes_override']) && isset($prestacion['horas_mes'])) {
            return floatval($prestacion['horas_mes']);
        }

        $horasPorDia = floatval($prestacion['horas_por_dia'] ?? 0);
        if ($horasPorDia <= 0) {
            return 0;
        }

        if ($periodo === null) {
            $periodo = date('Y-m');
        }

        $year = intval(substr($periodo, 0, 4));
        $month = intval(substr($periodo, 5, 2));
        $diasMes = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return round($horasPorDia * $diasMes, 2);
    }

    /**
     * Formatear frecuencia para mostrar según el modo.
     *
     * @param array $prestacion Datos de la prestación (campos: modo_frecuencia, horas_por_dia, horas_mes, etc.)
     * @param string|null $periodo Período YYYY-MM para mostrar el total mensual (opcional).
     * @return string Texto formateado de la frecuencia.
     */
    public function formatFrecuencia($prestacion, $periodo = null)
    {
        $modo = $prestacion['modo_frecuencia'] ?? 'sesiones';

        if ($modo === 'horas') {
            $hsDia = floatval($prestacion['horas_por_dia'] ?? 0);
            if ($hsDia <= 0) {
                return 'Sin frecuencia configurada';
            }

            $hsMes = $this->getHoursPerMonth($prestacion, $periodo);
            $hsDiaStr = rtrim(rtrim(number_format($hsDia, 1, '.', ''), '0'), '.');
            $hsMesStr = rtrim(rtrim(number_format($hsMes, 2, '.', ''), '0'), '.');
            $texto = $hsDiaStr . ' hs/día (' . $hsMesStr . ' hs/mes';

            if (!empty($prestacion['horas_mes_override'])) {
                $texto .= ' - manual';
            } elseif ($periodo) {
                $texto .= ' - ' . $this->formatPeriodoCorto($periodo);
            }
            $texto .= ')';

            return $texto;
        }

        // Modo sesiones (sin cambios respecto a la versión anterior)
        if (!empty($prestacion['frecuencia_nombre'])) {
            $texto = $prestacion['frecuencia_nombre'];
            if ($prestacion['id_frecuencia'] == 9 && !empty($prestacion['sesiones_personalizadas'])) {
                $texto .= ' (' . $prestacion['sesiones_personalizadas'] . ' sesiones/mes)';
            } elseif (!empty($prestacion['frecuencia_sesiones'])) {
                $texto .= ' (' . $prestacion['frecuencia_sesiones'] . ' sesiones/mes)';
            }
            return $texto;
        }
        return $prestacion['frecuencia_servicio'] ?? 'No especificada';
    }

    /**
     * Formatear YYYY-MM como "May 2026"
     */
    private function formatPeriodoCorto($periodo)
    {
        $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $year = substr($periodo, 0, 4);
        $monthIdx = intval(substr($periodo, 5, 2)) - 1;
        return ($meses[$monthIdx] ?? '') . ' ' . $year;
    }

    /**
     * Crear nueva frecuencia
     */
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO frecuencias (nombre, sesiones_por_mes, descripcion, orden, estado)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['nombre'],
            $data['sesiones_por_mes'],
            $data['descripcion'] ?? null,
            $data['orden'] ?? 0,
            $data['estado'] ?? 'activo'
        ]);
    }

    /**
     * Actualizar frecuencia
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE frecuencias
            SET nombre = ?,
                sesiones_por_mes = ?,
                descripcion = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['nombre'],
            $data['sesiones_por_mes'],
            $data['descripcion'] ?? null,
            $data['orden'] ?? 0,
            $data['estado'] ?? 'activo',
            $id
        ]);
    }

    /**
     * Eliminar (desactivar) frecuencia
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("
            UPDATE frecuencias
            SET estado = 'inactivo'
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
