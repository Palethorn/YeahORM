<?php
namespace Yeah\ORM\Mysql;

class SchemaGenerator {

    private $schema_path;
    private $conn;

    public function __construct($conn, $schema_path, $namespace = '') {
        $this->conn = $conn;
        $this->schema_path = $schema_path;
        $this->namespace = $namespace;
    }

    public function generate() {
        $stmt = $this->conn->prepare('SHOW TABLES');
        $stmt->execute();
        $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        foreach($tables as $table) {
            $stmt = $this->conn->prepare('DESCRIBE ' . $table);
            $stmt->execute();
            $fields = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            $stmt = $this->conn->prepare('SHOW INDEX FROM ' . $table);
            $stmt->execute();
            $indexes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            $field_mapping = array();
            $index_mapping = array();
        
            foreach($fields as $field) {
                $field_mapping[$field['Field']] = array(
                    'type' => $field['Type'],
                    'null' => $field['Null'] == 'NO' ? false : true,
                    'default' => $field['Default']
                );
            }
        
            $mapping = array(
                'name' => $table,
                'class' => \camelize($table),
                'namespace' => $this->namespace,
                'fields' => $field_mapping,
                'indexes' => $index_mapping
            );

            file_put_contents($this->schema_path . DIRECTORY_SEPARATOR . $table . '.php', "<?php\n return " . var_export($mapping, true) . ";\n");
        }
    }
}