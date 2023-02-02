<?php

namespace Omnipay\FlashPay\Traits;

use Omnipay\Common\Exception\InvalidRequestException;

trait HasDecode
{
    /**
     * @throws InvalidRequestException
     */
    private function decode($input)
    {
        $output = substr($input, 0, strrpos($input, '}') + 1);
        $data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidRequestException(json_last_error_msg().': '.$input);
        }

        return $data;
    }
}
