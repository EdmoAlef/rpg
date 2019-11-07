<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Turn extends REST_Controller {
    
	/**
     * Get All Data from this method.
     *
     * @return Response
    */

    private $characters;

    public function __construct() {
       parent::__construct();
       $this->load->library('Format');
    }

    /**
     * Carrega a configurações dos personagens
     * @return json
     */
    protected function loadDataPersonagens()
    {

        $arquivo           = APPPATH .'json/dados.json';
        $this->characters = file_get_contents($arquivo);
        $this->characters = $this->format::factory($this->characters,'json')->to_array();

        return $this->characters;
        
    }

    public function loadChar_post(){

        $data = array();
        $this->loadDataPersonagens();

        $personagens = $this->loadDataPersonagens();

        foreach ($personagens['personagens'] as $personagem => $config) {
            
            $this->load->model('Character');
            $this->Character->loadChar($personagem,$config);

            $oRetorno            = new stdClass();
            $oRetorno->nome      = $this->Character->getNome();
            $oRetorno->vida      = $this->Character->getVida();
            $oRetorno->forca     = $this->Character->getForca();
            $oRetorno->agilidade = $this->Character->getAgilidade();
            $oRetorno->arma      = $this->Character->getArmas();
            $oRetorno->imagem    = $this->Character->getImagem();

            $data[] = $oRetorno;

        }

        $this->response($data, REST_Controller::HTTP_OK);

    }

    public function rodada_post(){

        $this->loadDataPersonagens(); 

        $charOne = $this->characters['personagens']['Humano'];
        $charTwo = $this->characters['personagens']['Orc'];

        $isFirstTurn = is_array($charOne) && is_array($charTwo);
        
    }
}