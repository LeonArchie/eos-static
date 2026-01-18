<?php
    //SPDX-License-Identifier: AGPL-3.0-only WITH LICENSE-ADDITIONAL
    //Copyright (C) 2025 Петунин Лев Михайлович

/**
 * Загрузка конфигурации из .env файла в константы PHP
 */

class EnvLoader
{
    /**
     * @var string Путь к .env файлу
     */
    private static string $envPath = PRIVATE_ROOT_PATH . '/.env';
    
    /**
     * @var array Кеш загруженных переменных
     */
    private static array $loadedVars = [];
    
    /**
     * Загружает переменные из .env файла
     *
     * @param string|null $customPath Путь к кастомному .env файлу
     * @return void
     * @throws Exception Если файл не найден или произошла ошибка чтения
     */
    public static function load(?string $customPath = null): void
    {
        $envPath = $customPath ?? self::$envPath;
        
        if (!file_exists($envPath)) {
            throw new Exception(".env файл не найден по пути: {$envPath}");
        }
        
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($lines === false) {
            throw new Exception("Не удалось прочитать .env файл: {$envPath}");
        }
        
        foreach ($lines as $line) {
            // Пропускаем комментарии
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            
            // Разделяем имя и значение
            if (str_contains($line, '=')) {
                [$name, $value] = explode('=', $line, 2);
                
                $name = trim($name);
                $value = trim($value);
                
                // Обработка значений в кавычках
                if (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                    $value = $matches[1];
                }
                
                // Убираем комментарии в конце строки
                if (str_contains($value, ' #')) {
                    $value = explode(' #', $value)[0];
                    $value = trim($value);
                }
                
                // Сохраняем в кеш
                self::$loadedVars[$name] = $value;
                
                // Создаем константу, если она еще не определена
                if (!defined($name)) {
                    define($name, $value);
                }
            }
        }
    }
    
    /**
     * Получить значение переменной из кеша
     *
     * @param string $key Имя переменной
     * @param mixed $default Значение по умолчанию
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$loadedVars[$key] ?? $default;
    }
    
    /**
     * Получить все загруженные переменные
     *
     * @return array
     */
    public static function getAll(): array
    {
        return self::$loadedVars;
    }
    
    /**
     * Проверить, загружены ли переменные
     *
     * @return bool
     */
    public static function isLoaded(): bool
    {
        return !empty(self::$loadedVars);
    }
    
    /**
     * Установить путь к .env файлу
     *
     * @param string $path
     * @return void
     */
    public static function setEnvPath(string $path): void
    {
        self::$envPath = $path;
    }
}

// Автоматическая загрузка при подключении файла (опционально)
// Раскомментируйте, если хотите автоматическую загрузку:
try {
    EnvLoader::load();
} catch (Exception $e) {
    error_log("Ошибка загрузки .env файла: " . $e->getMessage());
}