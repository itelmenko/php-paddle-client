<?php

namespace Paddle;

/**
 * Class Currency
 * @package Paddle
 */
class Currency {

    protected $code;

    public function __construct($code) {
        $this->code = strtoupper($code);
    }

    public function __toString() {
        return $this->code;
    }

}