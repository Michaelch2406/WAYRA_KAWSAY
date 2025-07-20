<?php
class Cultura {
    private $conn;
    private $table_contenido = "contenido";
    private $table_eventos = "eventos";
    private $table_usuarios = "usuarios";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $categoria;
    public $fecha;
    public $ubicacion;
    public $importancia;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        if (!$this->conn) {
            return false;
        }

        // Leer contenido cultural aprobado
        $query = "SELECT c.id, c.titulo as nombre, c.cuerpo as descripcion, c.url_multimedia as imagen, 
                         c.tipo as categoria, c.fecha_creacion as fecha, '' as ubicacion, 5 as importancia,
                         u.nombre as autor_nombre
                  FROM " . $this->table_contenido . " c 
                  INNER JOIN " . $this->table_usuarios . " u ON c.id_usuario_autor = u.id
                  WHERE c.estado = 'aprobado' AND c.tipo IN ('historia', 'leyenda', 'tradicion', 'baile')
                  ORDER BY c.fecha_creacion DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    function readEventos() {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT e.id, e.nombre, e.descripcion, e.imagen, 'evento' as categoria, 
                         e.fecha_inicio as fecha, e.ubicacion_texto as ubicacion, 4 as importancia,
                         u.nombre as autor_nombre
                  FROM " . $this->table_eventos . " e 
                  LEFT JOIN " . $this->table_usuarios . " u ON e.id_usuario_creador = u.id
                  WHERE e.fecha_inicio >= NOW() - INTERVAL 30 DAY
                  ORDER BY e.fecha_inicio ASC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    function readByCategory($categoria) {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT c.id, c.titulo as nombre, c.cuerpo as descripcion, c.url_multimedia as imagen, 
                         c.tipo as categoria, c.fecha_creacion as fecha, '' as ubicacion, 5 as importancia,
                         u.nombre as autor_nombre
                  FROM " . $this->table_contenido . " c 
                  INNER JOIN " . $this->table_usuarios . " u ON c.id_usuario_autor = u.id
                  WHERE c.estado = 'aprobado' AND c.tipo = :categoria
                  ORDER BY c.fecha_creacion DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    function readOne() {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT c.id, c.titulo as nombre, c.cuerpo as descripcion, c.url_multimedia as imagen, 
                         c.tipo as categoria, c.fecha_creacion as fecha, '' as ubicacion, 5 as importancia,
                         u.nombre as autor_nombre
                  FROM " . $this->table_contenido . " c 
                  INNER JOIN " . $this->table_usuarios . " u ON c.id_usuario_autor = u.id
                  WHERE c.id = ? AND c.estado = 'aprobado' LIMIT 0,1";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row) {
                $this->nombre = $row['nombre'];
                $this->descripcion = $row['descripcion'];
                $this->imagen = $row['imagen'];
                $this->categoria = $row['categoria'];
                $this->fecha = $row['fecha'];
                $this->ubicacion = $row['ubicacion'];
                $this->importancia = $row['importancia'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    function search($keywords) {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT c.id, c.titulo as nombre, c.cuerpo as descripcion, c.url_multimedia as imagen, 
                         c.tipo as categoria, c.fecha_creacion as fecha, '' as ubicacion, 5 as importancia,
                         u.nombre as autor_nombre
                  FROM " . $this->table_contenido . " c 
                  INNER JOIN " . $this->table_usuarios . " u ON c.id_usuario_autor = u.id
                  WHERE c.estado = 'aprobado' 
                  AND (c.titulo LIKE :keywords OR c.cuerpo LIKE :keywords)
                  AND c.tipo IN ('historia', 'leyenda', 'tradicion', 'baile')
                  ORDER BY c.fecha_creacion DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $keywords = "%{$keywords}%";
            $stmt->bindParam(':keywords', $keywords);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    function getStats() {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT 
                    COUNT(*) as total_contenido,
                    COUNT(CASE WHEN tipo = 'historia' THEN 1 END) as total_historias,
                    COUNT(CASE WHEN tipo = 'leyenda' THEN 1 END) as total_leyendas,
                    COUNT(CASE WHEN tipo = 'tradicion' THEN 1 END) as total_tradiciones,
                    COUNT(CASE WHEN tipo = 'baile' THEN 1 END) as total_bailes
                  FROM " . $this->table_contenido . " 
                  WHERE estado = 'aprobado' AND tipo IN ('historia', 'leyenda', 'tradicion', 'baile')";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
