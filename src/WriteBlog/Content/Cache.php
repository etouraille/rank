<?php

namespace App\WriteBlog\Content;


class Cache {

    protected $data=array();

    public function put($key, $value) {
        $this->data[$key] = $value;
    }

    public function get($key) {
        if(isset($this->data[$key])) return $this->data[$key];
        else return null;
    }
}
