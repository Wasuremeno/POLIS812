<?php
// tests/CacheTest.php

require_once __DIR__ . '/../vendor/autoload.php';

use Cache\CacheManager;
use Cache\Cache;

class CacheTest
{
    private $cache;
    
    public function __construct()
    {
        // Используем memory драйвер для тестов
        $config = [
            'driver' => 'memory',
            'memory' => [
                'prefix' => 'test_'
            ]
        ];
        
        $this->cache = CacheManager::create($config);
    }
    
    public function runAllTests()
    {
        echo "Запуск тестов кэша...\n";
        echo "========================\n\n";
        
        $this->testSetGet();
        $this->testHas();
        $this->testDelete();
        $this->testClear();
        $this->testTtl();
        
        echo "\nВсе тесты пройдены!\n";
    }
    
    private function testSetGet()
    {
        echo "Тест 1: set() и get()... ";
        
        $this->cache->set('test_key', 'test_value');
        $value = $this->cache->get('test_key');
        
        if ($value === 'test_value') {
            echo "OK\n";
        } else {
            echo "FAIL\n";
            throw new \Exception("Ожидалось 'test_value', получено '{$value}'");
        }
    }
    
    private function testHas()
    {
        echo "Тест 2: has()... ";
        
        $this->cache->set('exists', true);
        
        if ($this->cache->has('exists') && !$this->cache->has('not_exists')) {
            echo "OK\n";
        } else {
            echo "FAIL\n";
            throw new \Exception("Некорректная работа метода has()");
        }
    }
    
    private function testDelete()
    {
        echo "Тест 3: delete()... ";
        
        $this->cache->set('to_delete', 'value');
        $this->cache->delete('to_delete');
        
        if (!$this->cache->has('to_delete')) {
            echo "OK\n";
        } else {
            echo "FAIL\n";
            throw new \Exception("Ключ не был удален");
        }
    }
    
    private function testClear()
    {
        echo "Тест 4: clear()... ";
        
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->cache->clear();
        
        if (!$this->cache->has('key1') && !$this->cache->has('key2')) {
            echo "OK\n";
        } else {
            echo "FAIL\n";
            throw new \Exception("Кэш не был очищен полностью");
        }
    }
    
    private function testTtl()
    {
        echo "Тест 5: TTL (быстрый тест)... ";
        
        $this->cache->set('expiring', 'value', 1); // 1 секунда
        usleep(1100000); // 1.1 секунды
        
        if (!$this->cache->has('expiring')) {
            echo "OK\n";
        } else {
            echo "FAIL\n";
            throw new \Exception("TTL не работает");
        }
    }
}

// Запуск тестов
try {
    $test = new CacheTest();
    $test->runAllTests();
} catch (\Exception $e) {
    echo "\nОшибка: " . $e->getMessage() . "\n";
    exit(1);
}