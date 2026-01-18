<?php
    //SPDX-License-Identifier: AGPL-3.0-only WITH LICENSE-ADDITIONAL
    //Copyright (C) 2025 Петунин Лев Михайлович
    
    if (!defined('PUBLIC_ROOT_PATH')) {
        define('PUBLIC_ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
    }

    if (!defined('PRIVATE_ROOT_PATH')) {
        define('PRIVATE_ROOT_PATH', (dirname($_SERVER['DOCUMENT_ROOT'])) . '/private');
    }

    $file_path = PRIVATE_ROOT_PATH . '/initialization/configurations.php';
    if (!@require_once $file_path) {
        header("Location: " . PRIVATE_ROOT_PATH . "/err/50x.html");
        exit();
    }

    // Функция для логирования
    function logger($level = 'INFO', $message = '') {
        // Определяем порядок уровней логирования
        $levels = [
            'ERROR' => 4,
            'WARN'  => 3,
            'INFO'  => 2,
            'DEBUG' => 1
        ];
        
        // Получаем уровень логирования из константы (по умолчанию 'DEBUG')
        $logLevel = defined('LOG_LVL') ? LOG_LVL : 'INFO';
        
        // Если уровень переданного сообщения ниже минимального уровня логирования,
        // то не записываем его в лог
        if (!isset($levels[$level]) || !isset($levels[$logLevel])) {
            // Если уровень не распознан, считаем его INFO
            $levelWeight = $levels['INFO'];
            $minWeight = $levels['DEBUG'];
        } else {
            $levelWeight = $levels[$level];
            $minWeight = $levels[$logLevel];
        }
        
        // Проверяем, нужно ли логировать это сообщение
        if ($levelWeight < $minWeight) {
            return false;
        }
        
        $logFile = LOGGER_PATH;
        
        // Проверяем, существует ли файл логов, и создаем его, если нет
        if (!file_exists($logFile)) {
            touch($logFile); // Создаем файл, если он не существует
        }
        
        // Определяем инициатора (пользователя или "неизвестный")
        $initiator = isset($_SESSION['username']) ? $_SESSION['username'] : 'неизвестный';
        
        // Получаем ID сессии
        $sessionId = session_id();
        
        // Получаем текущий URL
        $url = $_SERVER['REQUEST_URI'];
        
        // Формируем строку для записи в лог
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] [Инициатор: $initiator] [ID сессии: $sessionId] [URL: $url] $message" . PHP_EOL;
        
        // Записываем сообщение в лог-файл
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        return true; // Возвращаем успешное выполнение
    }

    $file_path = PRIVATE_ROOT_PATH . '/initialization/function.php';
    if (!@require_once $file_path) {
        header("Location: " . PRIVATE_ROOT_PATH . "/err/50x.html");
        exit();
    }
?>