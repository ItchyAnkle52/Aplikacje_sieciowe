<?php

// W skrypcie definicji kontrolera nie trzeba dołączać żadnego skryptu inicjalizacji.
// Konfiguracja, Messages i Smarty są dostępne za pomocą odpowiednich funkcji.
// Kontroler ładuje tylko to z czego sam korzysta.

require_once 'CalcForm.class.php';
require_once 'CalcResult.class.php';


class CalcCtrl {

	private $msgs;   //wiadomości dla widoku
	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku

	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->kwota = getFromRequest('kwota');
		$this->form->lata = getFromRequest('lata');
		$this->form->oprocentowanie = getFromRequest('oprocentowanie');
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zostały przekazane
		if (! (isset ( $this->form->kwota ) && isset ( $this->form->lata ) && isset ( $this->form->oprocentowanie ))) {
			// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
			return false; //zakończ walidację z błędem
		} 
		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ($this->form->kwota== "") {
			getMessages()->addError('Nie podano kwoty');
		}
		if ($this->form->lata == "") {
			getMessages()->addError('Nie podano lat');
		}
		if ($this->form->oprocentowanie == "") {
			getMessages()->addError('Nie podano oprocentowania');
		}

		// nie ma sensu walidować dalej gdy brak parametrów
		if (! getMessages()->isError()) {
			if (! is_numeric ( $this->form->kwota ) || $this->form->kwota <= 0) {
				getMessages()->addError('Kwota musi być dodatnią liczbą całkowitą');
			}
			
			if (! is_numeric ( $this->form->lata) || $this->form->lata <= 0) {
				getMessages()->addError('Lata muszą być dodatnią liczbą całkowitą');
			}
			if (! is_numeric ( $this->form->oprocentowanie) || $this->form->oprocentowanie <= 0) {
				getMessages()->addError('Oprocentowanie musi być dodatnią liczbą całkowitą');
			}
		}
		return ! getMessages()->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	public function process(){

		$this->getParams();
		
		if ($this->validate()) {
				
			//konwersja parametrów na int
			$this->form->kwota = intval($this->form->kwota);
			$this->form->lata = intval($this->form->lata);
			$this->form->oprocentowanie = floatval($this->form->oprocentowanie);
			getMessages()->addInfo('Parametry poprawne.');
				
			//wykonanie operacji
			$rata = $this->form->kwota/($this->form->lata*12);
			$this->result->res = round($rata*($this->form->oprocentowanie/100)+$rata,2);
		
			getMessages()->addInfo('Wykonano obliczenia.');
		}
		
		$this->generateView();
	}
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		$variables = array(
			'page_title' => 'Kalkulator Kredytowy',
			'page_header' => 'Prosty Kalkulator Kredytowy',
			'form' => $this->form,
			'result' => $this->result,
		);

		echo getTwig()->render('CalcView.html',$variables);
	}
}
