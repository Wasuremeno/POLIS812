<?php

// Драйвер для хранения в файлах

namespace Cache\Drivers;

use Cache\Cache;
use Cache\Exceptions\CacheException;

class FileCache extends Cache{
    //путь к директории с кэшем
    private $path;
    //расширение файлов
    private $extension;
    //конструктор
    public function __construct(array $config = []){
        parent::__construct($config);

        $this->path = rtrim($config['path'] ?? '', '/') . '/';
        $this->extension = $config['extension'] ?? '.cache';
    
    // Создаем директорию, если ее нет
        if (!is_dir($this->path)) {
            if (!mkdir($this->path, 0775, true) && !is_dir($this->path)) {
                throw new CacheException("Не удалось создать директорию кэша: {$this->path}");
            }
        }
        
        
    // Проверяем права на запись
        if (!is_writable($this->path)) {
            throw new CacheException("Директория кэша недоступна для записи: {$this->path}");
        }
    }

    //сохраняет значение в кэше
    public function set(string $key, $value, ?int $ttl = null): bool
    {
        $key = $this->buildKey($key);
        $file = $this->getFilePath($key);
        
        $data = [
            'key' => $key,
            'value' => $value,
            'expire' => $this->calculateExpire($ttl),
            'created' => time()
        ];
        
        $content = serialize($data);
        $result = file_put_contents($file, $content);
        
        return $result !== false;
    }
    
    //кэширует значения
    public function getFilePath(string $key): string{
        $hashedKey = md5($key);
        return $this->path . $hashedKey . $this->extension;
    }
    //получает значения из кэша
    public function get(string $key, $default = null){
        $key = $this->buildKey($key);

        if (!$this->has($key)) {
            return $default;
        }

        $file = $this->getFilePath($key);
        $content = file_get_contents($file);
        $data = unserialize($content);

        return $data['value'] ?? $default;
    }

    //проверяет наличие ключа в кэше
    public function has(string $key): bool{
        $key = $this->buildKey($key);
        $file = $this->getFilePath($key);

        if (!file_exists($file)){
            return false;
        }

        $content = file_get_contents($file);
        $data = unserialize($content);

        if ($data === false || !isset($data['expire'])){
            //поврежденный файл
            unlink($file); //удаляет из файловой системы
            return false;
        }

        //удаляет просроченный файл
        if ($this->isExpired($data['expire'])){
            unlink($file);
            return false;
        }

        return true;
    }

    public function delete(string $key): bool{
        $key = $this->buildKey($key);
        $file = $this->getFilePath($key);

        if (file_exists($file)){
            return unlink($file);
        }

        return false;
    }

    //очищает весь кэш
    public function clear(): bool{
        $success = true;
        $pattern = $this->path . '*' . $this->extension;

        $files = glob($pattern);
        foreach ($files as $file){
            if (is_file($file)){
                if (!unlink($file)){
                    $success = false;
                }
            }
        }

        return $success;
    }

    //garbage collector будет соберать просроченные файлы
    public function garbageCollector(): int{
        $deleted = 0;
        $pattern = $this->path . '*' . $this->extension;

        $files = glob($pattern);
        foreach ($files as $file){
            if (is_file($file)){
                $content = file_get_contents($file);
                $data = unserialize($content);

                if ($data === false || !isset($data['expire'])){
                    //поврежденный файл
                    unlink($file);
                    $deleted++;
                }elseif($this->isExpired($data['expire'])){
                    unlink($file);
                    $deleted++;
                }
            }
        }
        return $deleted;
    }
    
}