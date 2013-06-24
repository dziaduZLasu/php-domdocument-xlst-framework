<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of index
 *
 * @author artur
 */
define('BASE_URL', 'http://localhost/');
define('MAIN_CONTROLLER', 'home');
define('MAIN_METHOD', 'index');
define('SYSTEM_ENCODING', 'utf-8');
define('PREFABRICATE_PATH', 'prefabricate/');
define('XSL_PATH', 'xsl/');
require_once 'system/Frenzid.php';
require_once 'libs/active_record/ActiveRecord.php';

session_start();

/*
 * Run active record
 */
ActiveRecord\Config::initialize(function($cfg) {
            $cfg->set_model_directory('models');
            $cfg->set_connections(array(
                'development' => 'mysql://root:5428slony@localhost/frenzid'));
        });

/*
 * catch all non xml output
 */
//ob_start();
$System = new Frenzid($_SERVER['REQUEST_URI']);
//ob_end_clean();

/*
 * parsed DOM
 */
echo $System->dom->saveXML();


