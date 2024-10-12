<?php

namespace Omnipay\FlashPay\Traits;

use Omnipay\Common\Exception\InvalidResponseException;

trait HasDecode
{
    /**
     * @param  string  $input
     * @return array
     *
     * @throws InvalidResponseException
     */
    private function decode($input)
    {
        $output = substr($input, 0, strrpos($input, '}') + 1);
        $data = json_decode($output, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidResponseException(json_last_error_msg().': '.$input);
        }

        if ($data['ret_code'] !== '00') {
            throw new InvalidResponseException($data['ret_msg']);
        }

        return $data;
    }
}
