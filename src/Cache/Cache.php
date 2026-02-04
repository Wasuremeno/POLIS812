<?php 
// Абстрактный базовый класс
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
    //Ключ с учетом префикса
    public function buildKey(string $key): string{
        return $this->prefix . $key;
    }
    //Проверяет истекло ли время
    protected function isExpired(?int $expire): bool{
        if ($expire === null) {
            return false;
        }
        return time() > $expire;
    }
    //Расчитывает время на основе TTL
    protected function calculateExpire(?int $ttl): ?int{
        if ($ttl === null){
            return null;
        }
        return time() + $ttl;
    }

    //без декларации абстракных методов ничего не работает в FileCache

    //я поменял свое мнение, не стоит мне использовать тут абстрактные методы, я и так уже весь день сижу с xDebug
    // abstract public function set(string $key, $value, ?int $ttl = null): bool;
    // abstract public function get(string $key, $default = null);
    // abstract public function has(string $key): bool;
    // abstract public function delete(string $key): bool;
    // abstract public function clear(): bool;
}
