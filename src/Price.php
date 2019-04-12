<?php

namespace Paddle;

class Price {

    protected $amount = 0;

    protected $currency = NULL;

    public function __construct(string $amount, Currency $currency) {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function __toString() {
        return "{$this->amount}:{$this->currency}";
    }
}
