<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 20/10/19
 * Time: 13:06
 */

namespace App\WriteBlog\Content;
use App\Metier\Command\UpdateEntity;
use App\Metier\Command\FetchRandomEntity as FetchEntity;
use App\Entity\Sentence;
use Doctrine\ORM\EntityManagerInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

class Provider
{

    /*
     * cette classe a pour vocation de créer un texte aléatoire à partir des sentences
     */
    public function __construct( EntityManagerInterface $em , CommandBus $commandHandler ) {

        $this->em = $em;
        $this->commandHandler = $commandHandler;
    }

    protected function getContent( $n )
    {
        $tab = array();
        $neutralModel = new Sentence();
        for($i=1;$i<= $n;$i++)
        {
            $rowNeutral = $this->getRandomRowAndSetUsed($neutralModel);

            $tab[] = $rowNeutral->getValue();

            $this->em->detach($rowNeutral);


        }
        $content = '';
        $space = '';
        foreach($tab as $phrase){
            $content .= $space.$phrase;
            $space = ' ';
        }
        return $content;
    }

    public function getText( $n ) {

        return $this->getContent( $n );

    }

    protected function getRandomRowAndSetUsed( $model )
    {

        $row = $this->getPhraseEntity( $model );

        return $row;

    }

    protected function getPhraseEntity( $model ) {


        $cond = [];

        try {

            $fetchCommand = new FetchEntity( $model, $cond );
            $this->commandHandler->handle( $fetchCommand );
            $row = $fetchCommand->getResponse();

        } catch( \Exception $e ) {

            $updateCond = $cond;
            unset($updateCond['used']);
            $updateCommand = new UpdateEntity( $model,['used' => 0 ], $updateCond );
            $this->commandHandler->handle( $updateCommand );

            $fetchCommand = new FetchEntity( $model, $cond  );
            $this->commandHandler->handle( $fetchCommand );
            $row = $fetchCommand->getResponse();
        }

        //mise à jour du compteur
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

        return $row;
    }


}
