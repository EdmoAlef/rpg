<?php
   
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'models/Character.php';
     
class Batalha extends REST_Controller {
    
	/**
     * Get All Data from this method.
     *
     * @return Response
    */

    private $characters;

    private $attacker;

    private $defender;

    public $phases = array();

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
    /**
     * Método da api para carregar personagens do jogo
     */
    public function loadChar_post()
    {

        $data = array();
        $this->loadDataPersonagens();

        $personagens = $this->loadDataPersonagens();

        foreach ($personagens['personagens'] as $personagem => $config) {
            
            $Character = new Character($personagem,$config);

            $oRetorno            = new stdClass();
            $oRetorno->nome      = $Character->getNome();
            $oRetorno->vida      = $Character->getVida();
            $oRetorno->forca     = $Character->getForca();
            $oRetorno->agilidade = $Character->getAgilidade();
            $oRetorno->arma      = $Character->getArmas();
            $oRetorno->imagem    = $Character->getImagem();

            $data[] = $oRetorno;

        }

        $this->response($data, REST_Controller::HTTP_OK);

    }

    /**
     * método da api para inicar rodada
     */
    public function rodada_post()
    {
        
        $this->loadDataPersonagens(); 

        $charOne = new Character('Humano',$this->characters['personagens']['Humano']);
        $charTwo = new Character('Orc',$this->characters['personagens']['Orc']);

        $isFirstTurn = true;

        $this->doIntializeRound($charOne,$charTwo,$isFirstTurn);

        $this->doBatte();

        $this->response($this->phases, REST_Controller::HTTP_OK);

    }

    /**
     * verifica o personagem que iniciará o ataque
     * @param class
     * @param class
     * @param boolean
     */ 

    protected function doIntializeRound($charOne,$charTwo,$isFirstTurn)
    {
        if($isFirstTurn){

            $this->doReadPhase("Rodada Iniciou o combate entre o ".$charOne->getNome(). " e ".$charTwo->getNome() );
            
            while ($isFirstTurn){

                $this->doReadPhase("Personagem [".$charOne->getNome()."] jogou dado de 20 faces ");
                $charOneRandom  = $this->doRoll(20);
                $charOneStepOne = ((int)$charOne->getAgilidade() + $charOneRandom);
                $this->doReadPhase("Personagem [".$charOne->getNome()."] O dado de 20 retornou {$charOneRandom}, somado a agilidade de ".$charOne->getAgilidade()." tota de ".$charOneStepOne);
                
                $this->doReadPhase("Personagem [".$charTwo->getNome()."] jogou dado de 20 faces ");
                $charTwoRandom  = $this->doRoll(20);
                $charTwoStepOne = ((int)$charTwo->getAgilidade() + $charOneRandom);
                $this->doReadPhase("Personagem [".$charTwo->getNome()."] O dado de 20 retornou {$charTwoRandom}, somado a agilidade de ".$charTwo->getAgilidade()." tota de ".$charTwoStepOne);
                
                if( $charOneStepOne == $charTwoStepOne ){

                    $this->doReadPhase("Empate, dados serão jogados novamente.");

                }elseif( $charOneStepOne > $charTwoStepOne){
                    
                    $this->doReadPhase("Personagem [".$charOne->getNome()."] : Vencedor, iniciará o ataque.");
                    $this->setAttacker($charOne);
                    $this->setDefender($charTwo);

                    $isFirstTurn = false;

                }elseif ($charTwoStepOne > $charOneStepOne ) {
                    
                    $this->doReadPhase("Personagem [".$charTwo->getNome()."] : Vencedor, iniciará o ataque.");
                    $this->setAttacker($charTwo);
                    $this->setDefender($charOne);

                    $isFirstTurn = false;

                }

            }

        }

    }
    
    /**
     * método para jogar os dados
     * @param integer
     * @return integer
     */ 
    public function doRoll($d = 20)
    {
        return rand(1, $d);
    }

    /**
     * Informa o atacante
     * @param class
     */
    public function setAttacker(Character $char ){
        $this->attacker = $char;
    } 

    /**
     * Informa o defensor
     * @param class
     */
    public function setDefender(Character $char){
        $this->defender = $char;
    }

    /**
     * Executa a batalha entre os personagens
     * @param boolean
     */
    public function doBatte($isInBattle = true){

        $rodada = 1;

        while ($isInBattle) {
            
             $this->doReadPhase("Inicio rodada {$rodada}.");
             $this->doReadPhase(" Personagem ".$this->attacker->getNome()." possui ".$this->attacker->getVida()." pontos de vida.");
             $this->doReadPhase(" Personagem ".$this->defender->getNome()." possui ".$this->defender->getVida()." pontos de vida.");

            /** 
             * Calcula ataque personagem
             */
            $battleAttackerRandom  = $this->doRoll(20);
            $battleAttackerAgility = $this->attacker->getAgilidade();
            $battleAttackerWeapon  = (int)$this->attacker->getArmas()['ataque'];
            $battleAttackerAux     = $battleAttackerRandom + $battleAttackerAgility + $battleAttackerWeapon;
            
            $this->doReadPhase("Personagem [".$this->attacker->getNome()."] iniciou o ataque.");
            $this->doReadPhase("Personagem [".$this->attacker->getNome()."] jogou o dado de 20 faces e retornou : {$battleAttackerRandom}.");
            $this->doReadPhase("Personagem [".$this->attacker->getNome()."] atacou.");
            
            /** 
             * Calcula defesa personagem
             */
            $battleDefenderRandom  = $this->doRoll(20);
            $battleDefenderAgility = $this->defender->getAgilidade();
            $battleDefenderWeapon  = (int)$this->defender->getArmas()['defesa'];
            $battleDefenderAux     = $battleDefenderRandom + $battleDefenderAgility + $battleDefenderWeapon;

            $this->doReadPhase("Personagem [".$this->defender->getNome()."] irá tentar a defesa.");
            $this->doReadPhase("Personagem [".$this->defender->getNome()."] jogou o dado de 20 faces e retornou : {$battleDefenderRandom}.");

            /**
             * Valida calculo do ataque / defesa
             */
            if($battleAttackerAux > $battleDefenderAux){
                $this->doReadPhase("Personagem [".$this->defender->getNome()."] não conseguiu se esquivar e receberá o dano.");
                $this->doDamage();
            }else{
                $this->doReadPhase("Personagem [".$this->defender->getNome()."] se esquivou do ataque.");
            }

            $isInBattle = $this->getCriticalLife();

            /**
             * Valida se ainda há batalha
             *  para trocar turno
             */
            if($isInBattle){
                $attacker = $this->defender;
                $defender = $this->attacker;
                $this->setAttacker($attacker);
                $this->setDefender($defender);
                $rodada++;
            }
        }

    }

    /**
     * Valida se o personagem perdeu a vida
     * @return boolean
     */
    public function getCriticalLife(){
        
        if($this->defender->getVida() <= 0){
            $this->doReadPhase("Personagem ".$this->defender->getNome()." foi derrotado. Vencedor : ".$this->attacker->getNome());
            return false;
        }

        if($this->attacker->getVida() <= 0){
            $this->doReadPhase("Personagem ".$this->attacker->getNome()." foi derrotado. Vencedor : ".$this->defender->getNome());
            return false;
        }

        return true;

    }  

    /**
     * Escreve as fases do jogo
     * @param text
     */
    public function doReadPhase($description = "")
    {
        $this->phases[] = $description;
    } 

    /**
     * Método para calcular o dano do atacante 
     *
     */
    protected function doDamage()
    {
            
        /**
         * Calcula o dano da arma
         */
        $weapon       = $this->attacker->getArmas();
        $weaponAttack = (int)$weapon['ataque'];
        $weaponRandom = $this->doRoll((int)$weapon['dado']);
        $weaponAux    = $weaponAttack + $weaponRandom;

        $this->doReadPhase("Personagem [".$this->attacker->getNome()."]  jogou o dado de ".(int)$weapon['dado']." da arma ".$weapon['nome']." e retornou ".$weaponRandom);
        $this->doReadPhase("Personagem [".$this->attacker->getNome()."]  ataque calculado foi de {$weaponAux} pontos");

        /**
         * Calcula a vida do defensor
         */
        $this->doReadPhase("Personagem [".$this->defender->getNome()."]  sofreu dano de {$weaponAux} pontos");

        $lifeDefender = $this->defender->getVida();
        $this->defender->setVida( $lifeDefender - $weaponAux );

    } 

}