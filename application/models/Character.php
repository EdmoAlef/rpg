<?php

class Character
{
	/**
     * Constructor
     *
     * @return Response
    */

	public $nome      = "";
	
	public $vida      = 0;
	
	public $forca     = 0;
	
	public $agilidade = 0;

	public $armas 	 = array();

	public $imagem 	 = "";

    public function __construct($nome = "", $config = array())
    {
        $this->setNome($nome);
        $this->setVida($config['hp']);
        $this->setForca($config['forca']);
        $this->setAgilidade($config['agilidade']);
        $this->setArmas($config['arma']);
        $this->setImagem($config['imagem']);

        return $this;
    }

    public function setNome($nome)
    {
    	$this->nome = $nome;
    } 

    public function setVida($vida)
    {
    	$this->vida = $vida;
    }

    public function setForca($forca)
    {
    	$this->forca = $forca;
    }

    public function setAgilidade($agilidade)
    {
    	$this->agilidade = $agilidade;
    }

    public function setArmas($armas)
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