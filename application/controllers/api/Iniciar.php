<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Batalha extends REST_Controller {
    
	/**
     * Get All Data from this method.
     *
     * @return Response
    */

    private $arquivojson;

    public function __construct() {
       parent::__construct();
       $this->load->library('Format');
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = 0)
	{   
        // $data = $this->loadPersonagens();
        // $this->response($data, REST_Controller::HTTP_OK);
	}
      
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
        $input = $this->input->post();
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    } 
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $input = $this->put();
     
        $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
       
        $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
    }
    
    /**
     * Carrega a configurações dos personagens
     * @return json
     */
    protected function loadDataPersonagens()
    {

        $arquivo     = APPPATH .'json/dados.json';
        $this->arquivojson = file_get_contents($arquivo);
        
        return $this->arquivojson;
        
    }

    public function loadChar_post(){

        $data = array();
        $this->loadDataPersonagens();

        $personagens = $this->format::factory($this->arquivojson,'json')->to_array();

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

}