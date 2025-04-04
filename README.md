## Bitrix Chrome Console

Дебаг в консоли Google Chrome для 1С-Битрикс

Используется библиотека [barbushin/php-console](https://github.com/barbushin/php-console)
и расширение для Google Chrome [barbushin/php-console-extension](https://github.com/barbushin/php-console-extension)

### Использование
Установить библиотеку из composer:
```sh
composer require dvtoid/bitrix-chrome-console
```

Скачать расширение для Google Chrome из репы [barbushin/php-console-extension](https://github.com/barbushin/php-console-extension)

Установить в Chrome в разделе расширения (chrome://extensions/), кнопка "Загрузить распакованное расширение"

### .settings.php

В .settings.php или .settings_extra.php добавить секцию настроек:

```php
'bitrix_chrome_console' =>
  array(
    'value' =>
      array(
        'enabled' => true,
        'password' => 'PASSWORD',
        'debug' => false,
        'storage' => array(
          'type' => 'file',
          'path' => $_SERVER['DOCUMENT_ROOT'] . '/tmp/bc.data'
        ),
        // optional
        /*
        'terminal' => true, // eval terminal
        'base_path' => '/home/bitrix/www', // strip sources base path
        'encoding' => 'CP1251', // if needed
        'ssl' => true, // all PHPConsole clients will be redirected to HTTPS
        'allowed_ips' => array(
          '127.0.0.1'
        )
        */
      ),
    'readonly' => true,
  )
```

### php.ini

В начале php.ini подключить инициализацию консоли

```php
require __DIR__ . '/../path/to/vendor/autoload.php';

\BitrixChromeConsole\Console::init();
```

### Вывод в консоль

```php
use \BitrixChromeConsole\Console;
```

```php
Console::log(['some','data']);
```
![console1](https://devtoid.ru/images/bitrix-chrome-console/console1.png)

```php
Console::log(['log','data'], 'tag');
```
![console2](https://devtoid.ru/images/bitrix-chrome-console/console2.png)

```php
Console::log(['log','data'], 'tag', true);
```
![console31](https://devtoid.ru/images/bitrix-chrome-console/console3.png)

Или через хелпрер console()

```php
console(['log','data'], 'tag', true);
```
