<?php
/**
 * Preloader Script 2.x
 *
 * This file is generated automatically by the Preloader package.
 *
 * The following script uses `opcache_compile_file($file)` syntax to preload each file in this list into Opcache.
 * To full enable preload, add this file to your `php.ini` in `opcache.preload` key to preload
 * this list of files PHP at startup. This file also includes some information about Opcache.
 *
 *
 * Add (or update) this line in `php.ini`:
 *
 *     opcache.preload=/var/www/html/laser-beams/docker-configs/php/preloader.php
 *
 *
 * --- Config ---
 * Generated at: 2022-01-07 00:57:09 UTC
 * Opcache
 *     - Used Memory: 9.6 MB
 *     - Free Memory: 118.4 MB
 *     - Wasted Memory: 0.0 MB
 *     - Cached files: 52
 *     - Hit rate: 12.50%
 *     - Misses: 91
 * Preloader config
 *     - Memory limit: 32 MB
 *     - Overwrite: true
 *     - Files excluded: 0
 *     - Files appended: 0
 *
 *
 * For more information:
 * @see https://github.com/darkghosthunter/preloader
 */



$files = [
    '/var/www/html/laser-beams/config/listeners.php',
    '/var/www/html/laser-beams/config/routes.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/RouteParser.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Listener.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/RouteCollector.php',
    '/var/www/html/laser-beams/app/Controllers/ActivityController.php',
    '/var/www/html/laser-beams/app/Controllers/TodoItemController.php',
    '/var/www/html/laser-beams/app/Helpers/ResponseHelper.php',
    '/var/www/html/laser-beams/app/Helpers/StatusCodeHelper.php',
    '/var/www/html/laser-beams/app/Listeners/Pool.php',
    '/var/www/html/laser-beams/app/Models/Activity.php',
    '/var/www/html/laser-beams/app/Models/Model.php',
    '/var/www/html/laser-beams/app/Models/TodoItem.php',
    '/var/www/html/laser-beams/app/Validators/ActivityValidator.php',
    '/var/www/html/laser-beams/app/Validators/TodoItemValidator.php',
    '/var/www/html/laser-beams/bin/simps.php',
    '/var/www/html/laser-beams/config/database.php',
    '/var/www/html/laser-beams/config/redis.php',
    '/var/www/html/laser-beams/config/servers.php',
    '/var/www/html/laser-beams/vendor/autoload.php',
    '/var/www/html/laser-beams/vendor/composer/ClassLoader.php',
    '/var/www/html/laser-beams/vendor/composer/autoload_real.php',
    '/var/www/html/laser-beams/vendor/composer/autoload_static.php',
    '/var/www/html/laser-beams/vendor/composer/platform_check.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/DataGenerator.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/DataGenerator/GroupCountBased.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/DataGenerator/RegexBasedAbstract.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/Dispatcher.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/Dispatcher/GroupCountBased.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/Dispatcher/RegexBasedAbstract.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/Route.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/RouteParser/Std.php',
    '/var/www/html/laser-beams/vendor/nikic/fast-route/src/functions.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/db/src/BaseModel.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/db/src/PDO.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/db/src/Redis.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Application.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Config.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Context.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Route.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Server/Http.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/Singleton.php',
    '/var/www/html/laser-beams/vendor/simple-swoole/simps/src/functions.php',
    '/var/www/html/laser-beams/vendor/symfony/deprecation-contracts/function.php',
    '/var/www/html/laser-beams/vendor/symfony/polyfill-php80/bootstrap.php'
];

foreach ($files as $file) {
    try {
        if (!(is_file($file) && is_readable($file))) {
            throw new \Exception("{$file} does not exist or is unreadable.");
        }
        opcache_compile_file($file);
    } catch (\Throwable $e) {
        echo 'Preloader Script has stopped with an error:' . \PHP_EOL;
        echo 'Message: ' . $e->getMessage() . \PHP_EOL;
        echo 'File: ' . $file . \PHP_EOL;

        throw $e;
    }
}

