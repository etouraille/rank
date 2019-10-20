<?php

namespace App\Metier\CommandHandler;

use App\Metier\Command\FetchRandomEntity;
use Doctrine\ORM\Query\ResultSetMappingBuilder;


class FetchRandomEntityCommandHandler {

    private $em;

    public function __construct( $em ) {
        $this->em = $em;
    }

    public function handle(FetchRandomEntity $command ) {

        $command
            ->setResponse(
                $this->getResponse(
                    $command->getEntity(),
                    $command->getConditions()
                )
            )
        ;

    }

    private function getResponse( $entity , $conditions ) {


        $table = $this->em->getClassMetadata(get_class($entity))->getTableName();

        $cond = '';
        $sep = 'AND';
        foreach( $conditions as $key => $value ) {
            $cond .= sprintf(" %s entity.%s = '%s' ", $sep , $key , $value );
            $sep = 'AND';
        }

        $condInWhere = '';
        $sep = 'WHERE';
        foreach( $conditions as $key => $value ) {
            $condInWhere .= sprintf(" %s %s.%s = '%s' ", $sep , $table,  $key , $value );
            $sep = 'AND';
        }

        $select = "entity.id , entity.count, entity.value as phrase , entity.used ";
        if( $entity->getType() == 'clef') {
            $select .= " , entity.idClient, entity.url ";
        }

        $rsm = new ResultSetMappingBuilder( $this->em );
        $rsm->addRootEntityFromClassMetadata(get_class( $entity ), 'entity');


        $query = sprintf(
            "SELECT MIN(%s.count) as minimum 
            FROM %s 
            %s",
            $table,
            $table,
            $condInWhere
        );


        $con = $this->em->getConnection();
        $stmp = $con->prepare($query );
        $stmp->execute();
        $row = $stmp->fetch();
        $min = $row['minimum'];


        $join1 = '';
        $join2 = '';

        if( isset( $min )) {

            $cond .= sprintf(" AND entity.count = %d ", $min );
            $condInWhere .= sprintf(" WHERE %s.count = %d ", $table, $min );

        }

        $query = sprintf(
            "SELECT %s
  			 FROM %s entity JOIN
       			( SELECT CEIL( RAND() *
                     ( SELECT MAX(%s.id)  
                     	FROM %s
                     	%s
                     	%s
                     )
                 ) AS randid )
        	 AS r2
        	 %s
        	 WHERE entity.id >= r2.randid
			 %s
			 ORDER BY entity.id ASC
			 LIMIT 1
			", $select , $table, $table, $table, $join1, $condInWhere, $join2, $cond );

        //$query = $this->em->createNativeQuery($query, $rsm);

        $row = $con = $this->em->getConnection();
        $stmp = $con->prepare( $query);
        $stmp->execute();
        $row = $stmp->fetch();

        if( !$row ) {
            throw new \Exception();
        } else {
            $class = get_class( $entity );
            $ret = new $class;
        }

        foreach( $row as $key => $value ) {

            if( 'phrase' === $key ) $key = 'value';
            $setter = 'set' . ucfirst($key);
            $ret->$setter($value);

        }

        return $ret;

        //return $query->getSingleResult();


        /*
        Some code for large queries.
        $q = $this->em->createQuery($query);
        $iterableResult = $q->iterate();
        $index = 1;
        $ret = null;
        while (($row = $iterableResult->next()) !== false) {
            //if( $index == $rand ) {
                $ret = $row[0];

            //} else {
            //	$this->em->detach($row[0]);
            //}
            $index ++;
        }
        return $ret;
            */
    }
}
