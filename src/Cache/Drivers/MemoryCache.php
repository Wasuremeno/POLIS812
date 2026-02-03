<?php 
// Драйвер для хранения в памяти 

namespace Cache\Drivers;

use Cache\Cache;

class MemoryCache extends Cache{
    //хранилище
    private $storage = [];
    //значение в кэше
    public function set(string $key, $value, ?int $ttl = null): bool{
        $key = $this->buildKey($key);
        
        $this->storage[$key] = [
            'value' => $value,
            'expire' => $this->calculateExpire($ttl)
        ];
        
        return true;
    }


    public function get(string $key, $default = null){
        $key = $this->buildKey($key);
        if (!$this->has($key)){
            return $default;
        }

        return $this->storage[$key]['value'];
    }



    //проверяет что ключ в наличии 

    public function has(string $key): bool{
        $key = $this->buildKey($key);

        if (!isset($this->storage[$key])){
            return false;
        }

        $item = $this->storage[$key];

        //удаляем просроченный элемент
        if ($this->isExpired($item['expire'])){
            unset($this->storage[$key]);
            return false;
        }
        return true;
    }
    public function delete(string $key): bool{
        $key = $this->buildKey($key);

        if (isset($this->storage[$key])) {
            unset($this->storage[$key]);
            return true;
        }
        return false;
    }

    //очищает кэш полностью
    public function clear(): bool {
        $this->storage = [];
        return true;
    }
    //получает все элементы хранилища
    public function getAll(): array {
        return $this->storage;
    }
}