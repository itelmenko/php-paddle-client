<?php

namespace Paddle;

class Invoice {

    /**
     * @var array
     */
    protected $data = [];

    public function setPassthrough(string $value) {
        $this->data['passthrough'] = $value;
        return $this;
    }

    public function setReturnUrl(string $url) {
        $this->data['return_url'] = $url;
        return $this;
    }

    public function setQuantity(int $count, $variable = FALSE) {
        $this->data['quantity'] = $count;
        $this->data['quantity_variable'] = $variable ? 1 : 0;
        return $this;
    }

    public function setExpires(int $timestamp) {
        $this->data['expires'] = $timestamp;
        return $this;
    }

    public function setCustomerEmail(string $email) {
        $this->data['customer_email'] = $email;
        return $this;
    }

    public function setTrialDays(int $count) {
        $this->data['trial_days'] = $count;
        return $this;
    }

    public function addPrice(Price $price) {
        if(!isset($this->data['prices'])) {
            $this->data['prices'] = [];
        }
        $this->data['prices'][] = (string) $price;
        return $this;
    }

    public function addRecurringPrice(Price $price) {
        if(!isset($this->data['recurring_prices'])) {
            $this->data['recurring_prices'] = [];
        }
        $this->data['recurring_prices'][] = (string) $price;
        return $this;
    }

    public function getData() {
        return $this->data;
    }
}