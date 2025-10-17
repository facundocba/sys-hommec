<?php

class File
{
    private $db;
    private $table = 'archivos_paciente';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todos los archivos de un paciente
     */
    public function getByPaciente($idPaciente)
    {
        $query = "SELECT
                    ap.*,
                    u.nombre as usuario_nombre
                  FROM {$this->table} ap
                  INNER JOIN usuarios u ON ap.id_usuario_subio = u.id
                  WHERE ap.id_paciente = ?
                  ORDER BY ap.fecha_subida DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$idPaciente]);

        return $stmt->fetchAll();
    }

    /**
     * Obtener archivo por ID
     */
    public function getById($id)
    {
        $query = "SELECT
                    ap.*,
                    u.nombre as usuario_nombre,
                    p.nombre_completo as paciente_nombre
                  FROM {$this->table} ap
                  INNER JOIN usuarios u ON ap.id_usuario_subio = u.id
                  INNER JOIN pacientes p ON ap.id_paciente = p.id
                  WHERE ap.id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Crear nuevo registro de archivo
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (id_paciente, nombre_archivo, nombre_original, ruta, tipo, tamano,
                   id_usuario_subio, descripcion)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            $data['id_paciente'],
            $data['nombre_archivo'],
            $data['nombre_original'],
            $data['ruta'],
            $data['tipo'],
            $data['tamano'],
            $data['id_usuario_subio'],
            $data['descripcion'] ?? null
        ])) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Actualizar información del archivo
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET descripcion = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['descripcion'],
            $id
        ]);
    }

    /**
     * Eliminar archivo (solo registro en BD, el archivo físico se elimina en el controlador)
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Marcar email como enviado
     */
    public function markEmailSent($id)
    {
        $query = "UPDATE {$this->table}
                  SET email_enviado = 1, fecha_email_enviado = NOW()
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([$id]);
    }

    /**
     * Obtener estadísticas de archivos
     */
    public function getStats()
    {
        $query = "SELECT
                    COUNT(*) as total_archivos,
                    SUM(tamano) as tamano_total,
                    COUNT(DISTINCT id_paciente) as pacientes_con_archivos
                  FROM {$this->table}";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Contar archivos de un paciente
     */
    public function countByPaciente($idPaciente)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE id_paciente = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idPaciente]);
        $row = $stmt->fetch();

        return (int)$row['total'];
    }
}
