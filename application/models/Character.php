<?php

class Character extends CI_Model
{
	/**
     * Constructor
     *
     * @return Response
    */

	protected $nome      = "";
	
	protected $vida      = 0;
	
	protected $forca     = 0;
	
	protected $agilidade = 0;

	protected $armas 	 = array();

	protected $imagem 	 = "";

	/**
	 * Carrega as caracteristicas do personagem
	 * @param @text
	 * @param @array
	 * @return this
	 */ 
    public function loadChar($nome = "", $config = array() ) 
    {
    	
    	$this->setNome($nome);
    	$this->setVida($config['hp']);
    	$this->setForca($config['forca']);
    	$this->setAgilidade($config['agilidade']);
    	$this->setArmas($config['arma']);
    	$this->setImagem($config['imagem']);

    	return $this;
    }

    protected function setNome($nome)
    {
    	$this->nome = $nome;
    } 

    protected function setVida($vida)
    {
    	$this->vida = $vida;
    }

    protected function setForca($forca)
    {
    	$this->forca = $forca;
    }

    protected function setAgilidade($agilidade)
    {
    	$this->agilidade = $agilidade;
    }

    protected function setArmas($armas)
    {
    	$this->armas = $armas;
    } 

    public function setImagem($imagem)
    {	
    	$this->imagem = base_url() ."imagens/{$imagem}";
    } 

    public function getNome()
    {
    	return $this->nome;
    }

    public function getVida()
    {
    	return $this->vida;
    } 
    public function getForca()
    {
    	return $this->forca;
    }

    public function getAgilidade()
    {
    	return $this->agilidade;
    }

    public function getArmas()
    {
    	return $this->armas;
    } 

    public function getImagem()
    {
    	return $this->imagem;
    } 
	


}

?>