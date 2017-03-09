<?php

/**
 * Created by IntelliJ IDEA.
 * User: wolkodlack
 * Date: 3/9/17
 * Time: 4:25 PM
 */
class PreInstallCommand extends CConsoleCommand {

    /**
     * @return string
     */
    public function getHelp() {
        return ""
            . "USAGE\n"
            . "  yiic preinstall \n"
            . "DESCRIPTION\n"
            . "  Setups required modules, and framework folders\n"
            . "ARGUMENTS\n"
            . " - no -\n";
    }

    /**
     * Executes the action.
     *
     * @param array $args <p>command line parameters specific for this command</p>
     * @return void
     */
    public function run($args) {
        if (!empty($args[0]))
            $_module = $args[0];

        echo "[INFO] Installing modules\n";
        exec('sudo apt-get install php7.0-sqlite3');

        $protectedDir = realpath( dirname(__FILE__).'/..' );
        $migrationsDir = $protectedDir . '/migrations/rights';

        if(!file_exists($migrationsDir)) {
            echo "[INFO] Creating migrations dir: ", $migrationsDir, "\n\n";
            mkdir($migrationsDir);
        }

        $runtimeDir = $protectedDir . '/runtime';
        if(file_exists($runtimeDir)) {
            chmod($runtimeDir, 0777);
        }
        else {
            echo "[INFO] Creating runtime dir: ", $runtimeDir, "\n";
            mkdir($runtimeDir, 0777);
        }

        $assetsDir = $protectedDir . '/../assets';
        if(file_exists($assetsDir)) {
            echo "[INFO] Fixing assets dir permissions\n";
            chmod($assetsDir , 0777);
        }
        else {
            echo "[INFO] Creating runtime dir: ", $assetsDir, "\n";
            mkdir($assetsDir, 0777);
            chmod($assetsDir , 0777);
        }

        $dbFile = $protectedDir.'/data/comodo.ct.db';
        exec('touch '. $dbFile);
        echo "[INFO] changing DB file permissions\n";
        chmod($dbFile, 0777);
        chmod(dirname($dbFile), 0777);

        $imageDir = $protectedDir . '/../images';
        if(!file_exists($imageDir)) {
            echo "[INFO] Creating image folder";
            mkdir($imageDir, 0777);
        }
        chmod($imageDir, 0777);


        return;
    }

}
