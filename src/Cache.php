<?php
namespace Cache;


class Cache
{
    
      //Магические методы для удобного доступа
   
    public static function __callStatic($method, $arguments)
    {
        $instance = CacheManager::getInstance();
        
        if (!method_exists($instance, $method)) {
            throw new \BadMethodCallException("Метод {$method} не существует");
        }
        
        return $instance->$method(...$arguments);
    }
}