<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// 1. pobranie parametrów

$kwota = $_REQUEST ['kwota'];
$lata = $_REQUEST ['lata'];
$oprocentowanie = $_REQUEST ['oprocentowanie'];

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zostały przekazane
if ( ! (isset($kwota) && isset($lata) && isset($oprocentowanie))) {
	//sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
	$messages [] = 'Błędne wywołanie aplikacji. Brak jednego z parametrów.';
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
if (empty( $messages )) {
	
	// sprawdzenie, czy parametry są liczbami całkowitymi
	if (! is_numeric( $kwota ) || $kwota <= 0) {
		$messages [] = 'Kwota musi być dodatnią liczbą całkowitą';
	}
	
	if (! is_numeric( $lata ) || $lata <= 0) {
		$messages [] = 'Lata muszą być dodatnią liczbą całkowitą';
	}
	if (! is_numeric( $oprocentowanie ) || $oprocentowanie <= 0) {
		$messages [] = 'Oprocentowanie musi być dodatnią liczbą całkowitą';
	}	

}

// 3. wykonaj zadanie jeśli wszystko w porządku

if (empty ( $messages )) { // gdy brak błędów
	
	//konwersja parametrów na int
	$kwota = intval($kwota);
	$lata = intval($lata);
	$oprocentowanie = floatval($oprocentowanie);
	
	//wykonanie operacji
	$rata = $kwota/($lata*12);
	$result = round($rata*($oprocentowanie/100)+$rata,2);
}

// 4. Wywołanie widoku z przekazaniem zmiennych
include 'calc_view.php';