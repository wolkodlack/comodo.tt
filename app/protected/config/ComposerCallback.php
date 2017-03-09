<?php

namespace config;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;



/**
 * ComposerCallback provides composer hooks
 *
 * This setup class triggers `./yiic migrate` at post-install and post-update.
 * For a package the class triggers `./yiic <vendor/<packageName>-<action>` at post-package-install and
 * post-package-update.
 * See composer manual (http://getcomposer.org/doc/articles/scripts.md)
 *
 * Usage example
 *
 * config.php
 * 'params' => [
 *      'composer.callbacks' => [
 *          'post-update' => ['yiic', 'migrate'],
 *          'post-install' => ['yiic', 'migrate'],
 *          'yiisoft/yii-install' => ['yiic', 'webapp', realpath(dirname(__FILE__)) ],
 *      ],
 * ]
 *
 * composer.json
 * "scripts": {
 *      "pre-install-cmd": "config\\ComposerCallback::preInstall",
 *      "post-install-cmd": "config\\ComposerCallback::postInstall",
 *      "pre-update-cmd": "config\\ComposerCallback::preUpdate",
 *      "post-update-cmd": "config\\ComposerCallback::postUpdate",
 *      "post-package-install": ["config\\ComposerCallback::postPackageInstall"],
 *      "post-package-update": ["config\\ComposerCallback::postPackageUpdate"]
 *  }
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @package phundament.app
 * @since 0.7.1
 */
defined('YII_PATH') or define('YII_PATH', dirname(__FILE__).'/../vendor/yiisoft/yii/framework');

defined('CONSOLE_CONFIG') or (function() {
    if(file_exists(dirname(__FILE__).'/console.php')) {
        define('CONSOLE_CONFIG', dirname(__FILE__).'/console.php');
    }

})();


// we don't check YII_PATH, since it will be downloaded with composer
if (!is_file(CONSOLE_CONFIG)) {
    throw new \Exception("File '".CONSOLE_CONFIG."' from CONSOLE_CONFIG not found!");
}

class ComposerCallback {
    /**
     * Displays welcome message
     * @static
     * @param \Composer\Script\Event $event
     */
    public static function preInstall(Event $event) {
        $composer = $event->getComposer();
        // do stuff
        echo "[INFO] Installer does: \n\n";
        echo " * download packages specified in composer.json\n"
            ." * trigger composer callbacks\n\n";
        self::runHook('pre-install');
    }


    /**
     * Executes ./yiic migrate
     * @static
     * @param \Composer\Script\Event $event
     */
    public static function postInstall(Event $event) {
        self::runHook('post-install-prepare');
        self::runHook('post-install');
        echo "\n", "[INFO] Installation completed\n\n";
    }


    /**
     * Displays welcome message
     *
     * @static
     * @param \Composer\Script\Event $event
     */
    public static function preUpdate(Event $event) {
        echo "[INFO] Updating your application\n";
        self::runHook('pre-update');
    }


    /**
     * Executes ./yiic migrate
     *
     * @static
     * @param \Composer\Script\Event $event
     */
    public static function postUpdate(Event $event) {
        self::runHook('post-update');
        echo "\n\nUpdate completed.\n\n";
    }

    /**
     * Executes ./yiic <vendor/<packageName>-<action>
     *
     * @static
     * @param \Composer\Script\Event $event
     */
    public static function postPackageInstall(PackageEvent $event) {
        $installedPackage = $event->getOperation()->getPackage();
        $hookName = $installedPackage->getPrettyName().'-install';
        self::runHook($hookName);
    }


    /**
     * Executes ./yiic <vendor/<packageName>-<action>
     *
     * @static
     * @param \Composer\Script\Event $event
     */
    public static function postPackageUpdate(PackageEvent $event) {
        $installedPackage = $event->getOperation()->getTargetPackage();
        $hookName = $installedPackage->getPrettyName().'-update';
        self::runHook($hookName);
    }


    /**
     * Asks user to confirm by typing y or n.
     *
     * Credits to Yii CConsoleCommand
     *
     * @param string $message to echo out before waiting for user input
     * @return bool if user confirmed
     */
    public static function confirm($message) {
        echo $message . ' [yes|no] ';
        return !strncasecmp(trim(fgets(STDIN)), 'y', 1);
    }


    /**
     * Runs Yii command, if available (defined in config/composer.php)
     */
    private static function runHook($name) {
        $app = self::getYiiApplication();
        if ($app === null) {
            echo "[WARN] Ignoring Hook. Can't initialize YiiApplication";
            return;
        }
        if (isset($app->params['composer.callbacks'][$name])) {
            $args = $app->params['composer.callbacks'][$name];
            $sArgs = join($args, ',');
            echo "[INFO] Executing: composer.callback: ".$name." (${sArgs})\n\n";

            $command = \Yii::getPathOfAlias('system.cli.commands');
            echo "[INFO] Command: $command \n";
            $app->commandRunner->addCommands($command);
            $app->commandRunner->run($args);
        }
    }

    /**
     * Creates console application, if Yii is available
     */
    private static function getYiiApplication() {
        if (!is_file(YII_PATH.'/yii.php')) {
            return null;
        }
        require_once(YII_PATH . '/yii.php');
        spl_autoload_register(array('YiiBase', 'autoload'));
        if (\Yii::app() === null) {
            if (is_file(CONSOLE_CONFIG)) {
                echo "[INFO] Creating CONSOLE APPLICATION\n";
                $app = \Yii::createConsoleApplication(CONSOLE_CONFIG);
            }
            else {
                throw new \Exception("File from CONSOLE_CONFIG not found");
            }
        } else {
            $app = \Yii::app();
        }
        return $app;
    }
}