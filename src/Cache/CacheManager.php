<?php
namespace Cache;

use Cache\Drivers\FileCache;
use Cache\Drivers\MemoryCache;
use Cache\Exceptions\CacheException;

class CacheManager{
    //экземпляр кэша
    private static $instance = null;
    //получает экземпляр кэша
    public static function getInstance(?array $config = null): CacheInterface{
        if (self::$instance === null){
            self::$instance = self::create($config);
        }

        return self::$instance;
    }


    //создает экземпляр кэша
    public static function create(?array $config = null): CacheInterface{
        if ($config === null){
            $configPath = __DIR__ . '/../../config/cache.php';

            if(!file_exists($configPath)) {
                throw new CacheException('Файл конфигурации не найден: {$configPath}');
            }
            $config = require $configPath;
        }
        $driver = $config['driver'] ?? 'file';

        switch ($driver){
            case 'file':
                $driverConfig = $config['file'] ?? [];
                return new MemoryCache($driverConfig);

                default:
                throw new CacheException('Неизвестный драйвер кэша: {$driver}');
        }
    }

        //устанавливает экземпляр кэша (!!!! для тестирования)
        public  static function setInstance(CacheInterface $instance): void{
            self::$instance = $instance;
        }

        //сбрасывает экземляр кэша
        public static function reset(): void{
            self::$instance = null;
        }
    }
