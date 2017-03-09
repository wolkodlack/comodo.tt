<?php

// This is the database connection configuration.
return array(
    'tablePrefix'      => '',
	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/comodo.ct.db',
	// uncomment the following lines to use a MySQL database
	/*
	'connectionString' => 'mysql:host=localhost;dbname=testdrive',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	*/
);