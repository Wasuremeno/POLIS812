<?php 

namespace Cache;

use Cache\Exceptions\CacheException;

abstract class Cache implements CacheInterface{
    //Конфигурация
    protected $config = [];
    //Префикс для ключа 
    protected $prefix = '';
    //Конструктор
    public function __construct(array $config){
        $this->config = $config;
        $this->prefix = $config['prefix'] ?? '';
    }
}