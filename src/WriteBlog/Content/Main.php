<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 29/09/14
 * Time: 11:38
 */

namespace App\WriteBlog\Content;


use App\Entity\Ancre;
use App\Entity\Clef;
use App\Entity\Neutre;

use App\Entity\Count;

use App\Metier\Command\FetchRandomEntity as FetchEntity;
use App\Metier\Command\UpdateEntity;
use App\Metier\Command\IncrementCount;


class Main {

    protected $log;

    protected $idLanguageTitle;
    protected $neutralSentenceNumber;
    protected $anchorPosition;
    protected $idClient;
    protected $isBlank;

    protected $content;
    protected $title;

    protected $idPraseClef;
    protected $cache;
    protected $phraseClefCall = 0;

    protected $commandHandler;

    protected $countUnicity;

    const TITLE_ONLY = 0;
    const TITLE_KEY_SENTENCE = 1;
    const TITLE_KEY_SENTENCE_AND_TITLE = 2;


    public function __construct(
        $em,
        $commandHandler,
        $idLanguageTitle,
        $neutralSentenceNumber,
        $anchorPosition,
        $idClient,
        $isBlank,
        $titleOption = 0,
        $countUnicity
        #0 pour titre
        #1 pour phrase clef
        #2 pour phrase clef et titre
    )
    {

        #logger
        //$this->log->pushHandler(new StreamHandler('/var/log/RunProgrammation/log', Logger::INFO));
        //$this->log->info('Write blog constructor');
        $this->em = $em;
        $this->commandHandler = $commandHandler;
        $this->cache = new Cache();
        $this->idLanguageTitle = $idLanguageTitle;
        $this->idClient=$idClient;
        $this->neutralSentenceNumber=$neutralSentenceNumber;
        $this->anchorPosition=$anchorPosition;
        $this->isBlank=$isBlank;

        $this->countUnicity = $countUnicity;

        $this->setTitle($titleOption);
        $this->setContent();
    }

    protected function getPhraseEntity( $model, $idLanguage, $idClient ) {

        $isClef = ( 'clef' == $model->getType());

        $cond = ['idLanguage'=>$idLanguage];
        if(isset($idClient))
        {
            $cond['idClient'] = $idClient;
        }

        if( ! $isClef ) {
            $cond['used'] = 0;
        }

        try {

            $fetchCommand = new FetchEntity( $model, $cond , $this->countUnicity);
            $this->commandHandler->handle( $fetchCommand );
            $row = $fetchCommand->getResponse();

        } catch( \Exception $e ) {


            if( ! $isClef ) {

                $updateCond = $cond;
                unset($updateCond['used']);
                $updateCommand = new UpdateEntity( $model,['used' => 0 ], $updateCond );
                $this->commandHandler->handle( $updateCommand );

            } else {

                $updateCountCommand = new UpdateEntity( new Count , ['used' => 0 ],
                    [
                        'idMasse'=> $this->countUnicity->idMasse,
                        'idBlog' => $this->countUnicity->idBlog,
                        'idClient' => $this->countUnicity->idClient,
                        'type' => $model->getType()
                    ]);

                $this->commandHandler->handle( $updateCountCommand );

            }


            $fetchCommand = new FetchEntity( $model, $cond , $this->countUnicity );
            $this->commandHandler->handle( $fetchCommand );
            $row = $fetchCommand->getResponse();
        }

        //mise à jour du compteur
        if( isset( $idClient ) && $model instanceof Clef ) {
            $this->idPhraseClef = $row->getId();
        }

        $count = $row->getCount();
        $count++;

        $updateCommande = new UpdateEntity(
            $model,
            [
                'count' => $count,
                'used' => 1
            ],
            [
                'id' =>$row->getId()
            ]
        )
        ;

        $this->commandHandler->handle( $updateCommande);

        if(( $count = $updateCommande->getCount() ) !== 1 ) {
            throw new \LogicException(sprintf('Update row %s did %s record', $row->getId(), $count));
        }

        if( $isClef ) {

            $incrementCountCommand = new IncrementCount( $row, $this->countUnicity );// and set used.

            $this->commandHandler->handle( $incrementCountCommand );

        }

        return $row;
    }

    protected function getRandomRowAndSetUsed( $model,$idLanguage,$idClient = null, $useCache = false )
    {


        $cacheKey = get_class($model).$idLanguage.(isset($idClient)?'with_client':'without_client');

        if($useCache) {
            return $this->cache->get($cacheKey);
        }

        $row = $this->getPhraseEntity( $model, $idLanguage, $idClient );

        $this->cache->put( $cacheKey, clone $row );

        return $row;

    }

    protected function setTitle($option)
    {
        $entity = null;

        if(self::TITLE_ONLY == $option || self::TITLE_KEY_SENTENCE_AND_TITLE == $option)
        {
            $model = new \Bg\BgBundle\Entity\Titre();
            $entity = $this->getRandomRowAndSetUsed($model,$this->idLanguageTitle);
        }
        if($option == self::TITLE_KEY_SENTENCE || $option == self::TITLE_KEY_SENTENCE_AND_TITLE) {
            $clef = $this->getClefTitle($this->idLanguageTitle,$this->idClient );
        }
        switch($option) {
            case self::TITLE_ONLY:
                $this->title = $entity->getPhrase();
                break;
            case self::TITLE_KEY_SENTENCE :
                $this->title = $clef;
                break;
            case self::TITLE_KEY_SENTENCE_AND_TITLE :
                $this->title = $clef .' : '.$entity->getPhrase();
                break;
        }
        if($entity) {
            $this->em->detach( $entity );
        }

    }

    protected function getClef($idLanguage,$idClient,$isBlank){
        $rowAncre = $this->getRandomRowAndSetUsed( new Ancre(),$idLanguage);
        $useCache = $this->phraseClefCall > 0;
        $rowClef = $this->getRandomRowAndSetUsed( new Clef(),$idLanguage,$idClient,$useCache);
        $this->phraseClefCall ++;
        $phrase = $rowAncre->getPhrase();
        $clef = $rowClef->getPhrase();
        $url = $rowClef->getUrl();
        $blank = '';
        if($isBlank){
            $blank = ' target="_blank" ';
        }
        $this->em->detach( $rowAncre );
        $this->em->detach( $rowClef);
        $link = sprintf('<a href="%s" %s>%s</a>',$url,$blank,$clef);
        return str_replace('[XXXXXX]',$link,$phrase);
    }

    protected function getClefTitle($idLanguage,$idClient){
        $useCache = $this->phraseClefCall > 0;
        $rowClef = $this->getRandomRowAndSetUsed(new Clef(),$idLanguage,$idClient, $useCache);
        $this->phraseClefCall ++ ;


        $phrase = $rowClef->getPhrase();
        $this->em->detach( $rowClef);


        return
            $phrase
            ;
    }


    protected function setContent()
    {
        $tab = array();
        $neutralModel = new Neutre();
        for($i=1;$i<= $this->neutralSentenceNumber;$i++)
        {
            $rowNeutral = $this->getRandomRowAndSetUsed($neutralModel,$this->idLanguageTitle);

            $tab[] = $rowNeutral->getPhrase();
            if($this->anchorPosition == $i)
            {
                $tab[] = $this->getClef($this->idLanguageTitle,$this->idClient,$this->isBlank);
            }

            $this->em->detach($rowNeutral);


        }
        $content = '';
        $space = '';
        foreach($tab as $phrase){
            $content .= $space.$phrase;
            $space = ' ';
        }
        $this->content = $content;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent(){
        return $this->content;
    }

    public function getIdPhraseClef()
    {
        return $this->idPhraseClef;
    }
}
