<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

//załaduj Twig
require_once _ROOT_PATH.'/vendor/autoload.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów

function getParams(&$kwota,&$lata,&$oprocentowanie){
	$kwota = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
	$lata = isset($_REQUEST['lata']) ? $_REQUEST['lata'] : null;
	$oprocentowanie = isset($_REQUEST['oprocentowanie']) ? $_REQUEST['oprocentowanie'] : null;	
}
// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zostały przekazane - jeśli nie to wyświetl widok bez obliczeń
function validate(&$kwota,&$lata,&$oprocentowanie,&$messages){
	// sprawdzenie, czy parametry zostały przekazane
	if ( ! (isset($kwota) && isset($lata) && isset($oprocentowanie))) {
		// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
		// teraz zakładamy, ze nie jest to błąd. Po prostu nie wykonamy obliczeń
		return false;
	}

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $kwota == "") {
		$messages [] = 'Nie podano kwoty';
	}
	if ( $lata == "") {
		$messages [] = 'Nie podano lat';
	}
	if ( $oprocentowanie == "") {
		$messages [] = 'Nie podano oprocentowania';
	}

	//nie ma sensu walidować dalej gdy brak parametrów
	if (!empty($messages)) return false;
	
	// sprawdzenie, czy $x i $y są liczbami całkowitymi
	if (! is_numeric( $kwota ) || $kwota <= 0) {
		$messages [] = 'Kwota musi być dodatnią liczbą całkowitą';
	}
	
	if (! is_numeric( $lata ) || $lata <= 0) {
		$messages [] = 'Lata muszą być dodatnią liczbą całkowitą';
	}
	if (! is_numeric( $oprocentowanie ) || $oprocentowanie <= 0) {
		$messages [] = 'Oprocentowanie musi być dodatnią liczbą całkowitą';
	}	

	if (!empty($messages)) return false;
	else return true;
}

$variables = array(
	'app_url' => _APP_URL,
	'root_path' => _ROOT_PATH,
	'page_title' => 'Kalkulator Kredytowy',
	'page_header' => 'Prosty Kalkulator Kredytowy',
);

function process(&$kwota,&$lata,&$oprocentowanie,&$messages,&$result){
	//konwersja parametrów
	$kwota = intval($kwota);
	$lata = intval($lata);
	$oprocentowanie = floatval($oprocentowanie);
	//wykonanie operacji
	$rata = $kwota/($lata*12);
	$result = round($rata*($oprocentowanie/100)+$rata,2);
}

getParams($kwota, $lata, $oprocentowanie);
if ( validate($kwota, $lata, $oprocentowanie,$messages) ) { // gdy brak błędów
	process($kwota, $lata, $oprocentowanie,$messages,$result);
}

if(isset($kwota))$variables['kwota'] = $kwota;
	if(isset($lata))$variables['lata'] = $lata;
	if(isset($oprocentowanie))$variables['oprocentowanie'] = $oprocentowanie;
	if(isset($result))$variables['result'] = $result;
	if(isset($messages))$variables['messages'] = $messages;
$loader = new \Twig\Loader\FilesystemLoader(_ROOT_PATH.'/templates');
$loader->addPath(_ROOT_PATH.'/app'); //szablon strony kalkulatora
$twig = new \Twig\Environment($loader, [
    //'cache' => _ROOT_PATH.'/twig_cache',

]);

// 5. Wywołanie szablonu (wygenerowanie widoku)
echo $twig->render('calc.html', $variables);