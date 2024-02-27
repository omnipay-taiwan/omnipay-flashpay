<?php

namespace FlashPay\Lib\obj;

use Exception;
use FlashPay\Lib\Services\UtilService;
use FlashPay\Lib\Traits\HashInfo;

class AesObj
{
    use HashInfo;

    public function __construct($key, $iv)
    {
        $this->setHashKey($key);
        $this->setHashIv($iv);
    }

    private $method = 'aes-256-cbc';

    private function addpadding($string, $blocksize = 16)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);

        return $string;
    }

    public function create_mpg_aes_encrypt($parameter = '')
    {
        if (empty($this->getHashKey()) || empty($this->getHashIv())) {
            throw new Exception('key or vi is null', 400);
        }
        $return_str = $parameter;

        return trim(bin2hex(openssl_encrypt(self::addpadding($return_str),
            $this->method, $this->getHashKey(), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->getHashIv())));
    }

    public function create_mpg_aes_decrypt($parameter = '')
    {
        if (empty($this->getHashKey()) || empty($this->getHashIv())) {
            throw new Exception('key or vi is null', 400);
        }
        $return_str = $parameter;

        return openssl_decrypt(hex2bin($return_str),
            $this->method, $this->getHashKey(), OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->getHashIv());
    }

    public function getHASH($source)
    {
        return hash('sha256', $source);
    }

    public function getEnData($merID, $jsonStr)
    {
        $checkData = $this->getHashKey().$jsonStr.$this->getHashIv();
        $checkKeys = strtoupper($this->getHASH($checkData));
        $tradeData = $this->create_mpg_aes_encrypt($jsonStr);
        $checkInfo = strtoupper($this->getHASH($tradeData));
        $output = [
            'ver' => UtilService::$version,
            'mid' => $merID,
            'dat' => $tradeData,
            'key' => $checkKeys,
            'chk' => $checkInfo,
        ];

        return $output;
    }
}
