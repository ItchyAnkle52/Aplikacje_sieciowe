<?php

namespace app\controllers;
use PDOException;

require getConf()->root_path.'/vendor/catfan/Medoo/src/Medoo.class.php';

class ResultCtrl {

	private $records; //rekordy pobrane z bazy danych


    public function __construct(){
		//stworzenie potrzebnych obiektów
	}

	
	public function action_resultList(){
		// 2. Przygotowanie mapy z parametrami wyszukiwania (nazwa_kolumny => wartość)
		$search_params = []; //przygotowanie pustej struktury (aby była dostępna nawet gdy nie będzie zawierała wierszy)
		// 3. Pobranie listy rekordów z bazy danych
		// W tym wypadku zawsze wyświetlamy listę osób bez względu na to, czy dane wprowadzone w formularzu wyszukiwania są poprawne.
		// Dlatego pobranie nie jest uwarunkowane poprawnością walidacji (jak miało to miejsce w kalkulatorze)
		
		//przygotowanie frazy where na wypadek większej liczby parametrów
		$num_params = sizeof($search_params);
		if ($num_params > 1) {
			$where = [ "AND" => &$search_params ];
		} else {
			$where = &$search_params;
		}
		//dodanie frazy sortującej po nazwisku
		$where ["ORDER"] = "wynik";
		//wykonanie zapytania
		
		try{
			$this->records = getDB()->select("result", [
					"idresult",
					"lata",
					"kwota",
					"oprocentowanie",
					"wynik",
				], $where );
		} catch (PDOException $e){
			getMessages()->addError('Wystąpił błąd podczas pobierania rekordów');
			if (getConf()->debug) getMessages()->addError($e->getMessage());			
		}	

		// 4. wygeneruj widok
		$variables = array(
			'page_title' => 'Wyniki',
			'page_header' => 'Prosty Kalkulator Kredytowy',
			'record' => $this->records,
		);
		echo getTwig()->render('ResultList.tpl',$variables);

	}
}