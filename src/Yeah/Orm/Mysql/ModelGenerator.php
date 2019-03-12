<?php
namespace Yeah\Orm\Mysql;

class ModelGenerator {
    private $schema_path;

    public function __construct($schema_path, $model_path) {
        $this->schema_path = $schema_path;
        $this->model_path = $model_path;
    }

    public function generate() {
        scan_dir($this->schema_path, function($dir, $file) {

            $full_path = $dir . DIRECTORY_SEPARATOR . $file;

            if(!is_file($full_path) || !preg_match('/(.*)\.php$/', $file)) {
                return;
            }

            $mapping = require_once $full_path;

            if(!is_array($mapping)) {
                return;
            }

            $class_def = array(
                'namespace' => $mapping['namespace'], 
                'name' => $mapping['class'],
                'props' => array()
            );

            foreach($mapping['fields'] as $field => $def) {
                $camelized = camelize($field);
                $class_def['props'][$field] = array(
                    'getter' => 'get' . $camelized,
                    'setter' => 'set' . $camelized
                );
            }

            ob_start();
            require __DIR__ . '/../../../templates/model.tmpl';
            $class = ob_get_contents();
            ob_end_clean();

            file_put_contents($this->model_path . DIRECTORY_SEPARATOR . $class_def['name'] . '.php', '<?php ' . PHP_EOL . $class);
        });
    }
}