<?php

// This is the database connection configuration.
return array(
	//'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database
	
	'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=pplive_aplus_doc',
	//'connectionString' => 'mysql:host=masterdb.doc.synacast.com;port=3306;dbname=pplive_aplus_doc',
	//'connectionString' => 'mysql:host=slavedb.doc.synacast.com;port=3306;dbname=pplive_aplus_doc',
	'emulatePrepare' => true,
	'username' => 'root',
	//'username' => 'pp_aplus_doc',
	'password' => '',
	//'password' => 'i2SP91JqJaDDZNPV',
	'charset' => 'utf8',
	'tablePrefix' => 'doc_',
);
