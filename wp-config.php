<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'my');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '|K^rkga_%]D^+qHVF/<gX_<|>]e11rEd>V5!nBzcCe-=bChJYP>W &x~vdohhP5(');
define('SECURE_AUTH_KEY',  'z%J|hRSDb:O93GXVJ)U8+15+|N$+}_[4BB4@bB/z|#%$2]Nmx||0![N+,=DIAI(q');
define('LOGGED_IN_KEY',    ',t*[7x -w6c3Y}l2$6]4ceZZ?cD+X`N?`oo.R7z5i!S@|c7{=6m6Ls|fZyN>?b|C');
define('NONCE_KEY',        'w4o^C[Y3+|w~+>zjh}bs(2/u]g][cacgAn1@;f-Yk>,em)Bfnm>5wDwBcl- RPT4');
define('AUTH_SALT',        'f;nk/4~v<3PWqQ|Fa}p8NM)bxCid4b:z`8@8+W0(p_yBdovI9X~G`DyfU~r&9(4(');
define('SECURE_AUTH_SALT', 'nJSilSaJ*nE@`Ls/?#Ml|p+wzZU+<+f[CW>t;CMoCFPt(A|n5Wz_|ne#%|*Zg@(I');
define('LOGGED_IN_SALT',   '@Vw&Hv8Ig+L3u-SpE3}P`!@}; `CZ|c|+3+?`oz8|)lVYf!!U|$W&}|Ls4fRWRyJ');
define('NONCE_SALT',       '>S9BBL+%0**w+zg/RXE.C+Y*9Ptkw!Pv7=+~}:GKqaf@Njw9>=(B6s~XMQrN@!}y');
/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
