<?php
/**
 * Province Model
 * MedFlow - Sistema Administrativo Médico
 */

class Province
{
    private $db;
    private $table = 'provincias';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtener todas las provincias
     */
    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtener provincia por ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /**
     * Obtener provincia por código
     */
    public function getByCode($code)
    {
        $query = "SELECT * FROM {$this->table} WHERE codigo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$code]);

        return $stmt->fetch();
    }

    /**
     * Obtener provincia por nombre
     */
    public function getByName($name)
    {
        $query = "SELECT * FROM {$this->table} WHERE nombre = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name]);

        return $stmt->fetch();
    }

    /**
     * Buscar provincia por nombre parcial
     */
    public function searchByName($search)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE nombre LIKE ?
                  ORDER BY nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['%' . $search . '%']);

        return $stmt->fetchAll();
    }
}
