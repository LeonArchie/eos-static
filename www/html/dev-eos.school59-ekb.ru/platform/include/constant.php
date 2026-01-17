<?php
    //SPDX-License-Identifier: AGPL-3.0-only WITH LICENSE-ADDITIONAL
    //Copyright (C) 2025 Петунин Лев Михайлович

    if (!defined('VERSION_WEB')) {
        define('VERSION_WEB', '0.9.1');
    }

    // Определение переменных
    if (!defined('LOGGER_PATH')) {
        define('LOGGER_PATH', '/var/log/nginx/web.log');
    }

    if (!defined('LOGOUT_PATH')) {
        define('LOGOUT_PATH', '/platform/logout.php');
    }

    if (!defined('BACKEND_URL')) {
        define('BACKEND_URL', 'http://api-eos-dev.local/');
    }

?>