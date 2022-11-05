<?php

require_once $conf->root_path.'/vendor/autoload.php';
require_once $conf->root_path.'/vendor/Messages.class.php';
require_once $conf->root_path.'/app/CalcForm.class.php';
require_once $conf->root_path.'/app/CalcResult.class.php';


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
		$this->form->kwota = isset($_REQUEST['kwota']) ? $_REQUEST['kwota'] : null;
		$this->form->lata = isset($_REQUEST['lata']) ? $_REQUEST['lata'] : null;
		$this->form->oprocentowanie = isset($_REQUEST['oprocentowanie']) ? $_REQUEST['oprocentowanie'] : null;
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
			$this->msgs->addError('Nie podano kwoty');
		}
		if ($this->form->lata == "") {
			$this->msgs->addError('Nie podano lat');
		}
		if ($this->form->oprocentowanie == "") {
			$this->msgs->addError('Nie podano oprocentowania');
		}

		// nie ma sensu walidować dalej gdy brak parametrów
		if (! $this->msgs->isError()) {
			if (! is_numeric ( $this->form->kwota ) || $this->form->kwota <= 0) {
				$this->msgs->addError('Kwota musi być dodatnią liczbą całkowitą');
			}
			
			if (! is_numeric ( $this->form->lata) || $this->form->lata <= 0) {
				$this->msgs->addError('Lata muszą być dodatnią liczbą całkowitą');
			}
			if (! is_numeric ( $this->form->oprocentowanie) || $this->form->oprocentowanie <= 0) {
				$this->msgs->addError('Oprocentowanie musi być dodatnią liczbą całkowitą');
			}
		}
		return ! $this->msgs->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
			//konwersja parametrów na int
			$this->form->kwota = intval($this->form->kwota);
			$this->form->lata = intval($this->form->lata);
			$this->form->oprocentowanie = floatval($this->form->oprocentowanie);
			$this->msgs->addInfo('Parametry poprawne.');
				
			//wykonanie operacji
			$rata = $this->form->kwota/($this->form->lata*12);
			$this->result->res = round($rata*($this->form->oprocentowanie/100)+$rata,2);
		
			$this->msgs->addInfo('Wykonano obliczenia.');
		}
		
		$this->generateView();
	}
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		global $conf;

		$variables = array(
			'page_title' => 'Kalkulator Kredytowy',
			'page_header' => 'Prosty Kalkulator Kredytowy',
			'form' => $this->form,
			'messages' => $this->msgs,
			'result' => $this->result,
			'conf' => $conf
		);
		$loader = new \Twig\Loader\FilesystemLoader($conf->root_path.'/templates');
		$loader->addPath($conf->root_path.'/app');
		$twig = new \Twig\Environment($loader, [
			//'cache' => _ROOT_PATH.'/twig_cache',
		
		]);
		echo $twig->render('CalcView.html', $variables);
	}
}
