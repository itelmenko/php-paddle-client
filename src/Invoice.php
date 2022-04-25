<?php

namespace Paddle;

/**
 * Class Invoice
 * @package Paddle
 */
class Invoice {

    /**
     * All parameters
     * @var array
     */
    protected $data = [];

    /**
     * The Paddle Product ID that you want to base this custom checkout on. Or subscription's plan
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId) {
        $this->data['product_id'] = $productId;
        return $this;
    }

    /**
     * The name of the product/title of the checkout.
     * @param string $value
     * @return $this
     */
    public function setTitle(string $value) {
        $this->data['title'] = $value;
        return $this;
    }

    /**
     * Ancillary meta-data you wish to store with the checkout. Will be sent alongside all webhooks associated with the order.
     * @param string $value
     * @return $this
     */
    public function setPassthrough(string $value) {
        $this->data['passthrough'] = $value;
        return $this;
    }

    /**
     * A URL to redirect to once the checkout is completed.
     * @param string $url
     * @return $this
     */
    public function setReturnUrl(string $url) {
        $this->data['return_url'] = $url;
        return $this;
    }

    /**
     * A URL for webhooks.
     * @param string $url
     * @return $this
     */
    public function setWebHookUrl(string $url) {
        $this->data['webhook_url'] = $url;
        return $this;
    }

    /**
     * Pre-fills the Quantity selector on the checkout.
     * @param int $count
     * @param bool $variable Specifies if the user is allowed to alter the quantity of the checkout, accepts 0 or 1 (default: 1).
     * @return $this
     */
    public function setQuantity(int $count, $variable = FALSE) {
        $this->data['quantity'] = $count;
        $this->data['quantity_variable'] = $variable ? 1 : 0;
        return $this;
    }

    /**
     * Specifies if the checkout link should expire, the generated checkout URL will be accessible until this date
     * @param int $timestamp
     * @return $this
     */
    public function setExpires(int $timestamp) {
        $this->data['expires'] = date("Y-m-d", $timestamp);
        return $this;
    }

    /**
     * Pre-fills the customer ‘Email’ field on the checkout.
     * @param string $email
     * @return $this
     */
    public function setCustomerEmail(string $email) {
        $this->data['customer_email'] = $email;
        return $this;
    }

    /**
     * The number of days before Paddle starts charging the customer the recurring price.
     * If you leave this field empty, the trial days of the Plan will be used.
     * @param int $count
     * @return $this
     */
    public function setTrialDays(int $count) {
        $this->data['trial_days'] = $count;
        return $this;
    }

    /**
     * A short message displayed below the product name on the checkout.
     * @param string $message
     * @return $this
     */
    public function setCustomMessage(string $message) {
        $this->data['custom_message'] = $message;
        return $this;
    }

    /**
     * Specifies if a coupon can be applied to the checkout
     * @param boolean $discountable
     * @return $this
     */
    public function setDiscountable(bool $discountable) {
        $this->data['discountable'] = $discountable ? 1 : 0;
        return $this;
    }

    /**
     * Price(s) of the checkout for a one-time purchase or initial payment of a subscription.
     * @param Price $price
     * @return $this
     */
    public function addPrice(Price $price) {
        if(!isset($this->data['prices'])) {
            $this->data['prices'] = [];
        }
        $this->data['prices'][] = (string) $price;
        return $this;
    }

    /**
     * Recurring price(s) of the checkout (excluding the initial payment) if the product_id specified is a subscription.
     * @param Price $price
     * @return $this
     */
    public function addRecurringPrice(Price $price) {
        if(!isset($this->data['recurring_prices'])) {
            $this->data['recurring_prices'] = [];
        }
        $this->data['recurring_prices'][] = (string) $price;
        return $this;
    }

    /**
     * Get all attached parameters
     * @return array
     */
    public function getData() {
        return $this->data;
    }
}
