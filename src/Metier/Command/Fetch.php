<?php

namespace App\Metier\Command;

class Fetch {

    public $response = [];

    public function __construct( $entity, $cond = [] ) {
        $this->entity = $entity;
        $this->cond = $cond;

    }

    public function getConditions() {
        return $this->cond;
    }

    public function getEntity() {
        return $this->entity;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse( $response ) {
        $this->response = $response;
    }
}
