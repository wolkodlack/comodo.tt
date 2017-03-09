<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'aliases' => array(
        'vendor' => 'application.vendor',
        //  'webroot' => dirname(__FILE__)  . '/../../www',  # WD:??
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR .'..',
    'name' => 'Comodo.TT application',

    // preloading 'log' component
    'preload'=>array('log'),

    // application c omponents
    'components' => array(

        // database settings are configured in database.php
        'db'=>require(dirname(__FILE__).'/database.php'),

        'log' => array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
    ),
    'modules' => [],

    'commandMap' => array(
//        'pre-install'   => [
//            'class' => 'application.commands.ModsInstallCommand',
//        ],

        'database'      => array(
            'class' => 'vendor.schmunk42.database-command.EDatabaseCommand',
        ),
        // composer callback
        'migrate'       => array(
            // alias of the path where you extracted the zip file
            'class'                 => 'vendor.yiiext.migrate-command.EMigrateCommand',
            // this is the path where you want your core application migrations to be created
            'migrationPath'         => 'application.migrations',
            // the name of the table created in your database to save versioning information
            'migrationTable'        => 'migration',
            // the application migrations are in a pseudo-module called "core" by default
            'applicationModuleName' => 'core',
            // define all available modules (if you do not set this, modules will be set from yii app config)
            'modulePaths'           => array(
                'rights'                => 'application.migrations.rights',
            ),
            // you can customize the modules migrations subdirectory which is used when you are using yii module config
            'migrationSubPath'      => 'application.protected.migrations',
            // here you can configure which modules should be active, you can disable a module by adding its name to this array
            'disabledModules'       => array(),
            // the name of the application component that should be used to connect to the database
            'connectionID'          => 'db',
            // alias of the template file used to create new migrations
            #'templateFile' => 'system.cli.migration_template',
        ),
        // composer callback
        'echo'        => [
            'class' => 'application.commands.CEchoCommand',
        ],
    ),

    'params' => array(
        'composer.callbacks' => array(
            // args for Yii command runner
//            'yiisoft/yii-install' => array('yiic', 'webapp',
//                dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..','git'),
            'post-install-prepare'  => ['yicc', 'preinstall'],

            'post-update'           => ['yiic', 'migrate'],
            'post-install'          => ['yiic', 'migrate'],
        ),
    ),
);

