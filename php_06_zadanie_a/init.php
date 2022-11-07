<?php
require_once dirname(__FILE__).'/core/Config.class.php';
$conf = new Config();
include dirname(__FILE__).'/config.php'; //ustaw konfigurację

function &getConf(){ global $conf; return $conf; }

//załaduj definicję klasy Messages i stwórz obiekt
require_once getConf()->root_path.'/core/Messages.class.php';
$msgs = new Messages();

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

require_once getConf()->root_path.'/core/functions.php';

$action = getFromRequest('action');
