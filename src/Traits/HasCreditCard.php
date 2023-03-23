<?php

namespace Omnipay\FlashPay\Traits;

trait HasCreditCard
{
    use HasOrdNo;
    use HasAmt;
    use HasTxType;

    /**
     * stage_id "string(64)" "暫存編號" "請輸入空值"
     */
    public function setStageId($value)
    {
        return $this->setParameter('stage_id', $value);
    }

    public function getStageId()
    {
        return $this->getParameter('stage_id');
    }

    /**
     * sto_id "string(32)" "子店編號"
     */
    public function setStoId($value)
    {
        return $this->setParameter('sto_id', $value);
    }

    public function getStoId()
    {
        return $this->getParameter('sto_id');
    }

    /**
     * ord_time "string(32)" "訂單時間" "格式 YYYY-MM-DD hh:mm:ss" "2022-01-15 10:31:37"
     */
    public function setOrdTime($value)
    {
        return $this->setParameter('ord_time', $value);
    }

    public function getOrdTime()
    {
        return $this->getParameter('ord_time');
    }

    /**
     * pay_type "int" "支付類別" "1-信用卡,2-銀聯卡(暫時尚未開放銀聯卡)" 1
     */
    public function setPayType($value)
    {
        return $this->setParameter('pay_type', $value);
    }

    public function getPayType()
    {
        return $this->getParameter('pay_type');
    }

    /**
     * cur "string(8)" "易幣別" "填入交易幣別，如 NTD" "NTD"
     */
    public function setCur($value)
    {
        return $this->setCurrency($value);
    }

    public function getCur()
    {
        return $this->getCurrency();
    }

    /**
     * order_desc "string(256)" "交易內容" "網路商品一批 405"
     */
    public function setOrderDesc($value)
    {
        return $this->setDescription($value);
    }

    public function getOrderDesc()
    {
        return $this->getDescription();
    }

    /**
     * install_period "int" "分期期數" "分期期數若不帶此欄位，或欄位值為空值或空白，則表示不為分期交易。"
     */
    public function setInstallPeriod($value)
    {
        return $this->setParameter('install_period', $value);
    }

    public function getInstallPeriod()
    {
        return $this->getParameter('install_period');
    }

    /**
     * use_redeem "string(1)" "使用紅利折抵" "紅利交易標記 1:紅利交易若不帶此欄位，或欄位值空白或空白，則表示不為紅利交易。"
     */
    public function setUseRedeem($value)
    {
        return $this->setParameter('use_redeem', $value);
    }

    public function getUseRedeem()
    {
        return $this->getParameter('use_redeem');
    }

    /**
     * cell_phone_no "string(10)" "行動電話" "手機號碼"
     */
    public function setCellPhoneNo($value)
    {
        return $this->setParameter('cell_phone_no', $value);
    }

    public function getCellPhoneNo()
    {
        return $this->getParameter('cell_phone_no');
    }

    /**
     * cell_phone_no "string(10)" "行動電話" "手機號碼"
     */
    public function setPhone($value)
    {
        return $this->setCellPhoneNo($value);
    }

    public function getPhone()
    {
        return $this->getCellPhoneNo();
    }

    /**
     * return_url "string(256)" "結果回傳 URL" "交易結束，結果回傳的網址"
     */
    public function setReturnUrl($value)
    {
        return parent::setNotifyUrl($value);
    }

    public function getReturnUrl()
    {
        return parent::getNotifyUrl();
    }

    /**
     * client_url "String(256)" "畫面迴轉 URL" "交易結束，後畫面欲跳轉至的網址"
     */
    public function setClientUrl($value)
    {
        return parent::setReturnUrl($value);
    }

    public function getClientUrl()
    {
        return parent::getReturnUrl();
    }

    public function setNotifyUrl($value)
    {
        return parent::setReturnUrl($value);
    }

    public function getNotifyUrl()
    {
        return parent::getReturnUrl();
    }
}
