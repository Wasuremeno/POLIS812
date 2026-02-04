<?php
require_once __DIR__ . '/vendor/autoload.php';

use Cache\Cache;

echo "PHP Cache System Demo\n";
echo "=====================\n\n";

// Пример 1: Работа с файловым кэшем
echo "Пример 1: Сохранение и получение данных\n";
Cache::set('user', ['id' => 1, 'name' => 'John Doe'], 60); // TTL 60 секунд
$user = Cache::get('user');
echo "Пользователь: " . print_r($user, true) . "\n";

// Пример 2: Проверка наличия ключа
echo "\nПример 2: Проверка наличия ключа\n";
if (Cache::has('user')) {
    echo "Ключ 'user' существует\n";
} else {
    echo "Ключ 'user' не существует\n";
}

// Пример 3: Удаление
echo "\nПример 3: Удаление ключа\n";
Cache::delete('user');
if (!Cache::has('user')) {
    echo "Ключ 'user' удален\n";
}

// Пример 4: Очистка всего кэша
echo "\nПример 4: Очистка всего кэша\n";
Cache::set('key1', 'value1', 30);
Cache::set('key2', 'value2', 30);
Cache::clear();
echo "Кэш очищен\n";

// Пример 5: Работа с TTL
echo "\nПример 5: Демонстрация TTL\n";
Cache::set('temp_data', 'Эта запись исчезнет через 3 секунды', 3);
echo "Данные сохранены с TTL=3 секунды\n";
sleep(4);
if (!Cache::has('temp_data')) {
    echo "Данные автоматически удалены по истечении TTL\n";
}

echo "\nДемонстрация завершена!\n";