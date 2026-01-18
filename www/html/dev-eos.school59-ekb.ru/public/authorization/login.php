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

    // Запуск сессии, если она еще не запущена
    startSessionIfNotStarted();

    include PRIVATE_ROOT_PATH . "platform/functions/getLdapStatus.php";

    // Получаем статус LDAP
    try {
        $ldap_active = getLdapStatus();
    } catch (Exception $e) {
        logger("ERROR", "Ошибка при запросе статуса LDAP: " . $e->getMessage());
        $ldap_active = false;
    }

    $auth_type_disabled = !$ldap_active;
    $default_auth_type = $ldap_active ? 'ldap' : 'internal';

    include PUBLIC_ROOT_PATH . "platform/snackbars/inital_error.php";

    // Логируем успешную инициализацию скрипта
    logger("DEBUG", "login.php успешно инициализирован.");
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <!--Заголовок-->
        <title>ЕОС Авторизиция</title>	
        <!--Кодировка-->
        <meta charset="utf-8">							
        <!--Ключевые слова-->
        <meta
            name="description"
            content="Единое окно сотрудникв"
        />
        <!--Минус роботы-->
        <meta 
            name="robots"
            content="noindex, nofollow" 
        />
        <!-- Фавикон -->
        <link
            rel="icon"
            sizes="16x16 32x32 48x48"
            type="image/png"
            href="/platform/images/eos_icon.png"
        />
        <link rel="stylesheet" href="css/login.css"/>
        <link rel="stylesheet" href="/platform/css/snackbars.css"/>
    </head>
    <body>
        <?php include PUBLIC_ROOT_PATH . 'authorization/include/eos_header.html'; ?>
        <!-- Основной контент -->
        <main class="authorization">
            <h2>Авторизация</h2>
            <form id="authForm">
                <!-- Поле для логина -->
                <div class="input-group">
                    <label for="login">Логин:</label>
                    <input type="text" id="login" name="login" placeholder="Введите логин" required>
                </div>
                <!-- Поле для пароля -->
                <div class="input-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" placeholder="Введите пароль" required>
                </div>
                <!-- Поле для выбора типа авторизации -->
                <div class= "input-group select-group">
                    <label for="auth_type">Сервер:</label>
                    <select id="auth_type" name="auth_type" <?php echo $auth_type_disabled ? 'disabled' : ''; ?> required>
                        <option value="internal" <?php echo $default_auth_type === 'internal' ? 'selected' : ''; ?>>Внутренняя</option>
                        <option value="ldap" <?php echo $default_auth_type === 'ldap' ? 'selected' : ''; ?>>LDAP</option>
                    </select>
                </div>
                <!-- Кнопка отправки -->
                <input type="submit" value="Войти">
            </form>
            <?php include PUBLIC_ROOT_PATH . 'platform/include/loading.html'; ?>
        </main>
         <?php include PUBLIC_ROOT_PATH . 'platform/include/error.php'; ?>
        <?php include PUBLIC_ROOT_PATH . 'platform/include/footer.php'; ?>
        <!-- Подключаем скрипты -->
        <script src="../platform/js/snackbars.js"></script>
        <script src="js/login.js"></script>
    </body>
</html>