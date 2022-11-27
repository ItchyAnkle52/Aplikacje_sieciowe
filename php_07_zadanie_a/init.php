<?php
require_once 'core/Config.class.php';
$conf = new core\Config();
require_once 'config.php'; //ustaw konfigurację

function &getConf(){ global $conf; return $conf; }

//załaduj definicję klasy Messages i stwórz obiekt
require_once 'core/Messages.class.php';
$msgs = new core\Messages();

function &getMessages(){ global $msgs; return $msgs; }

//przygotuj twig, twórz tylko raz - wtedy kiedy potrzeba

$twig = null;	
function &getTwig(){
	global $twig;
	if (!isset($twig)){
		require_once getConf()->root_path.'/vendor/autoload.php';
		$loader = new \Twig\Loader\FilesystemLoader;
		$loader->addPath(getConf()->root_path.'/app/views');
		$loader->addPath(getConf()->root_path.'/app/views/templates');
		$twig = new \Twig\Environment($loader);
		$twig->addGlobal('messages', getMessages());
		$twig->addGlobal('conf',getConf());
	}
	return $twig;
}

require_once 'core/ClassLoader.class.php'; //załaduj i stwórz loader klas
$cloader = new core\ClassLoader();
function &getLoader() {
    global $cloader;
    return $cloader;
}

require_once 'core/functions.php';

session_start(); //uruchom lub kontynuuj sesję
$conf->roles = isset($_SESSION['_roles']) ? unserialize($_SESSION['_roles']) : array(); //wczytaj role

$action = getFromRequest('action');
