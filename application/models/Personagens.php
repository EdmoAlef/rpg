<?php

class Personagens extends CI_Model
{
	/**
     * Constructor
     *
     * @return Response
    */

	protected $arquivojson;


    public function __construct() 
    {
    	$this->load->library('Format');
    }

    /**  
     * Carrega a configurações dos personagens
     * @return json
     */
    protected function loadDataPersonagens()
    {

		$arquivo 	 = APPPATH .'json/dados.json';
		$this->arquivojson = file_get_contents($arquivo);
		
		return $this->arquivojson;
		
    }

    public function loadPersonagens(){

    	$this->loadDataPersonagens();
    	$personagens = $this->format::factory($this->arquivojson,'json')->to_array();

        foreach ($personagens['personagens'] as $personagem => $value) {
            $this->load->model('Character');
            $this->Character->loadChar($personagem,$value);

        }


    }


       
}

?>