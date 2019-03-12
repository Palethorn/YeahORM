<?php
namespace Yeah;

class ServiceManager {
    private $service_registry;
    private $running;

    public function __construct() {
        $this->service_registry = array();
        $this->running = array();
        $this->running['yeah.service_manager'] = $this;
    }

    public function register($id, $config) {
        $this->service_registry[$id] = $config;
    }

    public function run($id) {
        if(isset($this->running[$id])) {
            return $this->running[$id];
        }

        $config = $this->service_registry[$id];
        $class = '\\' . $config['class'];
        $inject = array();

        foreach($config['inject'] as $param) {
            if(preg_match('/service{([a-zA-Z0-9_\.]+)}/', $param, $matches)) {
                $service = $this->run($matches[1]);
                $inject[] = $service;
                continue;
            }

            $inject[] = $param;
        }

        $reflClass = new \ReflectionClass($class);
        $instance = $reflClass->newInstanceArgs($inject);
        return $this->running[$id] = $instance;
    }
}
