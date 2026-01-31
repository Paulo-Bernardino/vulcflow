<?php
class Database
{
    private static $PATTERNS = '/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/';
    protected   $host       = ''; 
    protected   $db_service = ''; 
    protected   $db_port    = 1521; 
    protected   $password   = ''; 
    protected   $user       = ''; 
    protected   $charset    = ''; 
    protected   $conn_data  = ''; 
    protected   $pdo        = null; 
    protected   $error      = null; 
    protected   $debug      = false; 
    public      $last_id    = null; 

    public function __construct($host = null, $db_service = null, $db_port = null, $password = null, $user = null,
                                $charset  = null, $debug    = null, $connect_data = null )
    {
        $this->host       = ($host       == null ? HOSTNAME     : $host         );
        $this->db_service = ($db_service == null ? DB_SERVICE   : $db_service   );
        $this->db_port    = ($db_port    == null ? DB_PORT      : $db_port      );
        $this->password   = ($password   == null ? DB_PASSWORD  : $password     );
        $this->user       = ($user       == null ? DB_USER      : $user         );
        $this->charset    = ($charset    == null ? DB_CHARSET   : $charset      );
        $this->debug      = ($debug      == null ? DEBUG        : $debug        );
        $this->conn_data = ( $connect_data == null ? 'SERVICE_NAME' : $connect_data);
        $this->connect();
    }

    final protected function connect() {
        try {
            $this->pdo = new Oci8("oci:dbname={$this->host}:{$this->db_port}/{$this->db_service};charset={$this->charset}", $this->user, $this->password);
            if ( $this->debug === true ) $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
            $oracleSession = new OracleSessionInit();
            $oracleSession->postConnect($this->pdo);    
            unset( $this->host, $this->db_service, $this->db_port, $this->password, $this->user, $this->charset );
        } catch (Oci8Exception $e) {
            echo $e->getMessage();
            return;
        }
    }

    public function query( $stmt, $data_array = null, $id = null) {
        $query = $this->pdo->prepare( $stmt );
        if ( $id != null ) {
            $query->bindParam($id, $this->last_id, PDO::PARAM_INT, 11);
        }
        $check_exec = $query->execute( $data_array );
        if ( $check_exec ) {
            return $query;
        } else {
            $error = $query->errorInfo();
            $this->error = $error[2];
            return false;
        }
    }

    public function quote($string) {
        return $this->pdo->quote($string);
    }

    public function insert( $table, $values, $primaryKey = null ) {
        $SQL = ['INSERT INTO'];
        $SQL[] = $table;
        if (!empty($values)) {
            $columns = array_keys($values);
            $SQL[] = '(' . implode(', ', $columns) . ')';
            $SQL[] = 'VALUES';
            $columnValues = array();
            foreach ($values as $value) { $columnValues[] = "'{$value}'"; }
            $SQL[] = '(' . implode(', ', $columnValues) . ')';
        }
        if (!is_null($primaryKey)) $SQL[] = "RETURNING {$primaryKey} INTO :{$primaryKey}";
        $stmt = implode(' ', $SQL);
        if (!is_null($primaryKey)) {
            $this->query($stmt, null, $primaryKey);
            return $this->last_id;
        } else {
            return $this->query($stmt)->rowCount();
        }
    }

    public function update( $table, $where_field, $where_field_value, $values ) {
        if (empty($table) || empty($where_field) || empty($where_field_value)) return;
        $SQL  = "UPDATE {$table} SET " ;
        if (!empty($values)) {
            $columnValues = [];
            foreach ($values as $key => $value) { $columnValues[] = "{$key} = '{$value}'"; }
            $SQL .= implode(', ', $columnValues);
        }
        $SQL .= " WHERE {$where_field} = '{$where_field_value}'";
        return $this->query($SQL)->rowCount();
    }

    public function delete( $table, $where_field, $where_field_value ) {
        if ( empty($table) || empty($where_field) || empty($where_field_value)  ) return;
        $stmt = "DELETE FROM {$table} WHERE $where_field = '{$where_field_value}'";
        return $this->query($stmt)->rowCount();
    }

    public function beginTransaction() { $this->pdo->beginTransaction(); }
    public function commit() { $this->pdo->commit(); }
    public function rollback() { $this->pdo->rollback(); }
}