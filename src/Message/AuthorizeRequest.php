<?php

namespace Omnipay\FlashPay\Message;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        return $this->getBaseData();
    }
}
