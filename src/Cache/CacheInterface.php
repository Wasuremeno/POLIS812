<?php

//

namespace Cache;

interface CacheInterface{
//Сохраняет значение в кэше
    public function set(string $key, $value, ?int $ttl = null): bool;
//Получает значение из кэша
    public function get(string $key, $default = null);
//Проверяет наличие ключа в кэше
    public function has(string $key): bool;
//Удаляет значение из кэша
    public function delete(string $key): bool;
//Очищает весь кэш
    public function clear(): bool;

}