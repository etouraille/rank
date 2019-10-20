<?php

namespace App\Metier\CommandHandler;

use App\Metier\Command\UpdateEntity;

class UpdateEntityCommandHandler {

    private $em;

    public function __construct( $em ) {
        $this->em = $em;
    }

    public function handle(UpdateEntity $command ) {

        $count = $this->_handle($command->getEntity(), $command->getValues(), $command->getConditions(), $command);

        $command->setCount( $count );

    }

    private function _handle( $entity , $values ,$conditions, $command ) {


        $con = $this->em->getConnection();

        $sep = '';
        $set = '';
        foreach( $values as $key => $value ) {

            $set .= sprintf('%s `%s` = \'%s\' ', $sep , $key , $value );
            $sep = ',';

        }

        $cond = '';
        $sep = '';
        $first = 'WHERE';

        foreach( $conditions as $key => $value ) {

            $cond .= $first . sprintf('%s `%s` = \'%s\' ', $sep , $key , $value );
            $sep = 'AND';
            $first = '';

        }

        $table = $this->em->getClassMetadata( get_class($entity) )->getTableName();
        $query = sprintf("UPDATE %s SET %s %s", $table, $set , $cond );
        $stmp = $con->prepare($query);

        $stmp->execute();

        return $stmp->rowCount();
        /*
        $qb = $this->em->createQueryBuilder();
        $q = $qb->update(get_class($entity), 'e');
        $params = [];
        $index = 0;
        foreach( $values as $key => $value ) {
            $q->set('e.'.$key, '?'.$index);
            $params[$index] = $value;
            $index ++;
        }
        $cond = '';
        $sep = '';
        foreach( $conditions as $key => $value ) {

            $cond .= sprintf("%s e.%s = ?%s ", $sep , $key , $index );
            $sep = 'AND';
            $params[$index] = $value;
            $index ++;
        }

        return $q
            ->where($cond)
            ->setParameters( $params )
            ->getQuery()
            ->execute()
        ;
        */


        //$this->em->clear();
    }
}
