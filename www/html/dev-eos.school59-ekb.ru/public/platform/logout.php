<?php
    //SPDX-License-Identifier: AGPL-3.0-only WITH LICENSE-ADDITIONAL
    //Copyright (C) 2025 Петунин Лев Михайлович

    if (!defined('PUBLIC_ROOT_PATH')) {
        define('PUBLIC_ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
    }

    if (!defined('PRIVATE_ROOT_PATH')) {
        define('PRIVATE_ROOT_PATH', (dirname($_SERVER['DOCUMENT_ROOT'])) . '/private');
    }

    $file_path = PRIVATE_ROOT_PATH . '/init.php';
    if (!@require_once $file_path) {
        header("Location: " . PRIVATE_ROOT_PATH . "/err/50x.html");
        exit();
    }

    // Запуск сессии, если она еще не была запущена
    startSessionIfNotStarted();

    // Логирование информации о пользователе перед выходом
    if (isset($_SESSION['username'])) {
        // Логируем информацию о том, что пользователь начал процесс выхода
        logger("INFO", "Пользователь " . $_SESSION['username'] . " начал процесс выхода из системы.");
    }

    // Удаление access_token из сессии
    if (isset($_SESSION['access_token'])) {
        unset($_SESSION['access_token']);
    }

    // Удаление refresh_token из сессии
    if (isset($_SESSION['refresh_token'])) {
        unset($_SESSION['refresh_token']);
    }

    // Удаление userid из сессии
    if (isset($_SESSION['userid'])) {
        unset($_SESSION['userid']);
    }

    // Удаление refresh_token из сессии
    if (isset($_SESSION['username'])) {
        unset($_SESSION['username']);
    }

    // Очистка всех данных сессии
    session_unset();

    // Уничтожение сессии
    if (!session_destroy()) {
        // Логируем ошибку, если не удалось уничтожить сессию
        logger("ERROR", "Не удалось уничтожить сессию.");
    }

    // Получение IP-адреса пользователя для логирования
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'неизвестный IP';
    // Логируем информацию о перенаправлении на страницу авторизации
    logger("INFO", "Перенаправление на страницу авторизации. IP пользователя: " . $ipAddress);

    // Перенаправление пользователя на страницу авторизации
    header("Location:/authorization/login.php");
    exit(); // Завершение выполнения скрипта
?>  
