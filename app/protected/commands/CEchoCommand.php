<?php

/**
 * Created by IntelliJ IDEA.
 * User: wolkodlack
 * Date: 2/26/17
 * Time: 12:46 PM
 */

Yii::import('system.cli.commands.*');



class CEchoCommand extends CConsoleCommand {
    public $message;

    /**
     * @return string
     */
    public function getHelp() {
        return ""
        . "USAGE\n"
        . "  yiic echo <message>\n"
        . "DESCRIPTION\n"
        . "  Echoes message\n"
        . "ARGUMENTS\n"
        . " * message: string\n";
    }

    /**
     * Executes the action.
     *
     * @param array $args <p>command line parameters specific for this command</p>
     * @return void
     */
    public function run($args) {
        if (!empty($args[0]))
            echo "{$args[0]}\n";
        return;
    }
}