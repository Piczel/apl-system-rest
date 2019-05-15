<?php
declare(strict_types = 1);

namespace Application\Util\Database;

use PDO;
use PDOStatement;
use PDOException;
use Application\Config;

class Connection
{
    private static $instance;

    public static function get_instance() : Connection
    {
        if(self::$instance == null) 
            self::$instance = new Connection();
        
        return self::$instance;
    }
    
    private $connection = null;
    private function __construct()
    {
        $dns = 
            Config::get('mysql', 'driver') .
            ':host=' . Config::get('mysql', 'host') .
            ';port=' . Config::get('mysql', 'port') .
            ';dbname=' . Config::get('mysql', 'dbname') . 
            ';charset=utf8';
        
        if($this->connection == null) {
            $this->connection = new PDO(
                $dns,
                Config::get('mysql', 'username'),
                Config::get('mysql', 'password')
            );
        }
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // PDO::ERRMODE_SILENT
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
        
    }
    /** @param string       SQL query
     * 
     *  @return PDOStatement
     */
    public function prepare(
        string $sql
    ) : PDOStatement
    {
        try {
            return $this->connection->prepare($sql);
        } catch(PDOException $exc) {
            
        }
        return null;
    }
    /** @param string       SQL query
     *  @param array        Parameters
     *  @param int          Fetch mode
     * 
     *  @return array       Result set
     */
    public function query(
        string $sql,
        array $values = [],
        int $fetch_mode = PDO::FETCH_ASSOC
    ) : array
    {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($values);
            return $statement->fetchAll($fetch_mode);
        } catch(PDOException $exc) {
            
        }
        return [];     
    }

    /** @param string       SQL query
     *  @param array        Parameters
     * 
     *  @return bool        Success / failure
     */
    public function execute(
        string $sql,
        array $values = []
    ) : bool
    {
        try {
            $statement = $this->connection->prepare($sql);
            return $statement->execute($values);
            
        } catch(PDOException $exc) {
            
        }
        return false;     
    }

    /** @param string       SQL query
     *  @param array        Parameters
     * 
     *  @return int         Amount of rows query resulted in
     */
    public function count(
        string $sql,
        array $values = []
    ) : int
    {
        return count($this->query($sql, $values));
    }

    /** @return int         Last insert ID */
    public function insert_id() : int
    {
        return intval($this->connection->lastInsertId());
    }

    /** @return string      Last produced error */
    public function error() : string
    {
        $info = $this->connection->errorInfo();
        return 'ERROR: '.$info[0].' '.$info[2];
    }
}
?>