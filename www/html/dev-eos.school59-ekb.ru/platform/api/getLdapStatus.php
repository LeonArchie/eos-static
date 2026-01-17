<?php
    // Логируем начало работы скрипта
    logger("DEBUG", "Функция getLdapStatus подключена и готова к использованию");

    /**
     * Функция для получения статуса LDAP через API
     * 
     * Отправляет GET-запрос к API бэкенда для проверки статуса LDAP,
     * парсит JSON-ответ и возвращает булево значение активности LDAP
     * 
     * @return bool Возвращает true, если LDAP активен, иначе false
     */
    
    function getLdapStatus() {
        // Логируем начало выполнения функции
        logger("DEBUG", "Вызвана функция getLdapStatus()");
        
        // Формируем полный URL для запроса, объединяя базовый адрес бэкенда и путь к API
        $apiUrl = BACKEND_URL . '/api/config/v1/read/ldap/enabled';
        
        // Логируем сформированный URL для отладки
        logger("DEBUG", "Сформирован URL для запроса: " . $apiUrl);
        
        // Инициализируем cURL сессию
        $ch = curl_init();
        
        // Настраиваем параметры cURL запроса в виде массива для удобства чтения
        curl_setopt_array($ch, [
            // URL для отправки запроса
            CURLOPT_URL => $apiUrl,
            
            // Возвращать результат в виде строки, а не выводить напрямую
            CURLOPT_RETURNTRANSFER => true,
            
            // Максимальное время ожидания ответа в секундах
            CURLOPT_TIMEOUT => 3,
            
            // Устанавливаем заголовки HTTP-запроса
            CURLOPT_HTTPHEADER => [
                'Accept: application/json' // Указываем, что ожидаем JSON-ответ
            ]
        ]);
        
        // Логируем начало выполнения запроса
        logger("DEBUG", "Выполнение cURL запроса к API...");
        
        // Выполняем запрос и сохраняем ответ
        $response = curl_exec($ch);
        
        // Получаем HTTP-код ответа для анализа результата
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Закрываем cURL сессию для освобождения ресурсов
        curl_close($ch);
        
        // Логируем полученный HTTP-код
        logger("DEBUG", "Получен HTTP код ответа: " . $httpCode);
        
        // Проверяем, успешен ли запрос (HTTP код 200 = OK)
        if ($httpCode === 200) {
            // Логируем успешный ответ от сервера
            logger("DEBUG", "Успешный ответ от API. Парсим JSON...");
            
            // Парсим JSON-ответ в ассоциативный массив (true во втором параметре)
            $data = json_decode($response, true);
            
            // Логируем полученные данные для отладки
            logger("DEBUG", "Полученные данные от API: " . print_r($data, true));
            
            // Проверяем структуру ответа и наличие ожидаемых полей
            if (isset($data['value'])) {
                // Преобразуем строковое значение "true"/"false" в булево
                $isLdapActive = ($data['value'] === 'true');
                
                // Логируем результат проверки
                logger("DEBUG", "Значение параметра LDAP enabled: " . $data['value']);
                logger("DEBUG", "LDAP статус (булево значение): " . ($isLdapActive ? 'true' : 'false'));
                
                // Возвращаем булево значение активности LDAP
                return $isLdapActive;
            } else {
                // Логируем проблему с форматом ответа
                logger("ERROR", "Некорректный формат ответа от API. Отсутствует поле 'value'");
                logger("DEBUG", "Полный ответ: " . $response);
                
                // Возвращаем false при некорректном формате ответа
                return false;
            }
        }
        
        // Если мы дошли сюда, значит запрос не был успешным (HTTP код не 200)
        
        // Логируем ошибку с деталями
        logger("ERROR", "Не удалось получить статус LDAP. HTTP код: " . $httpCode);
        
        // Дополнительная информация для отладки в зависимости от кода ошибки
        switch ($httpCode) {
            case 404:
                logger("ERROR", "API endpoint не найден. Проверьте путь: " . $apiUrl);
                break;
            case 500:
                logger("ERROR", "Внутренняя ошибка сервера при обращении к API");
                break;
            case 0:
                logger("ERROR", "Сервер недоступен. Проверьте доступность " . BACKEND_URL);
                break;
            default:
                logger("ERROR", "Неизвестная ошибка при обращении к API");
        }
        
        // Возвращаем false в случае любой ошибки
        return false;
    }
?>