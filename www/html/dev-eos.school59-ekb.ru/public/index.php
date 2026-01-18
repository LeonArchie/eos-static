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
        header("Location: /err/50x.html");
        exit();
    }

    $file_path = PRIVATE_ROOT_PATH . '/platform/functions/check_auth.php';
    if (!@require_once $file_path) {
        header("Location: /err/50x.html");
        exit();
    }

    logger("INFO", "index успешно инициализирован");

    // //Инициализация проверки или запуска сессии
    // startSessionIfNotStarted();

    // // Основная логика
    // if (checkAuth()) {
    //     logger("INFO", "Авторизация успешна, перенаправление на dashboard");
    //     header("Location:" . PUBLIC_ROOT_PATH . "platform/dashboard.php");
    // } else {
    //     logger("WARNING", "Авторизация не пройдена, перенаправление на logout");
    //     header("Location: " . PUBLIC_ROOT_PATH . LOGOUT_PATH);
    // }
    // exit();


    echo 'ОНО РАБОТАКЕТ'
?>

