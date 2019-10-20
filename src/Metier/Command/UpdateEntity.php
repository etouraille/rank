<?php

namespace App\Metier\Command;

class UpdateEntity {

    public $response = [];

    public function __construct( $entity, $values , $cond = [] ) {
        $this->entity = $entity;
        $this->values = $values;
        $this->cond = $cond;
        $this->count = null;
    }

    public function getConditions() {
        return $this->cond;
    }

    public function getEntity() {
        return $this->entity;
    }

    public function getValues() {
        return $this->values;
    }

    public function setCount( $count ) {
        $this->count = $count;
    }

    public function getCount() {
        return $this->count;
    }
}
