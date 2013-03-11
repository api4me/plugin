<?php

/**
 *    宝付支付方式插件
 *
 *    @author    Garbin
 *    @usage    none
 */

class BaofooPayment extends BasePayment {
    /* 宝付网关 */
    var $_debug   = true;
    var $_code    = "baofoo";
    var $_gateway = "http://paygate.baofoo.com/PayReceive/payindex.aspx";

    function __construct($payment_info = array()) {
        if ($this->_debug) {
            $this->_gateway = "http://paytest.baofoo.com/PayReceive/payindex.aspx";
            $this->_merchant = 100007;
            $this->_md5key = "992ild0pfk7qbmty";
        }
        parent::__construct($payment_info);
    }
    /**
     *    获取支付表单
     *
     *    @author    Garbin
     *    @param     array $order_info  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($order_info) {
        $params = array(
            // * 商户号
            "MerchantID" => ($this->_debug ? $this->_merchant : $this->_config['baofoo_account']),
            // * 支付渠道
            "PayID" => 1000,
            // * 交易时间
            "TradeDate" => date("Ymdhis"),
            // * 商户流水号
            "TransID" => $this->_get_trade_sn($order_info),
            // * 订单金额 - 订单总金额 以分为单位，至少大于等于1
            "OrderMoney" => $order_info['order_amount'] * 100,

            // 产品名称
            "ProductName" => $this->_get_subject($order_info),
            // 数量
            "Amount" => "",
            // 产品logo - 商品图片的url
            "ProductLogo" => "",
            // 支付用户名
            "Username" => "",
            "Email" => "",
            "Mobile" => "",
            // 订单附加消息
            "AdditionalInfo" => "",

            // * 商户通知地址
            "Merchant_url" => $this->_create_notify_url($order_info['order_id']),
            // * 用户通知地址
            "Return_url" => $this->_create_return_url($order_info['order_id']),
            // * 通知方式
            "NoticeType" => 1,
        );

        // * Md5签名字段
        // MerchantID + PayID + TradeDate + TransID + OrderMoney + Merchant_url + Return_url + NoticeType + Md5Key;
        $params["Md5Sign"] = md5(
            $params["MerchantID"]
            .$params["PayID"]
            .$params["TradeDate"]
            .$params["TransID"]
            .$params["OrderMoney"]
            .$params["Merchant_url"]
            .$params["Return_url"]
            .$params["NoticeType"]
            .($this->_debug ? $this->_md5key : $this->_config['baofoo_key'])
        );
        return $this->_create_payform('POST', $params);
    }

    /**
     *    返回通知结果
     *
     *    @author    Garbin
     *    @param     array $order_info
     *    @param     bool  $strict
     *    @return    array
     */
    function verify_notify($order_info, $strict = false) {
        if (empty($order_info)) {
            $this->_error('order_info_empty');

            return false;
        }

        /* 初始化所需数据 */
        $notify = $this->_get_notify();

        /* 验证通知是否可信 */
        // MerchantID + TransID + Result + resultDesc + factMoney  + additionalInfo + SuccTime + md5key;
        $params["Md5Sign"] = md5(
            $notify["MerchantID"]
            .$notify["TransID"]
            .$notify["Result"]
            .$notify["resultDesc"]
            .$notify["factMoney"]
            .$notify["additionalInfo"]
            .$notify["SuccTime"]
            .($this->_debug ? $this->_md5key : $this->_config['baofoo_key'])
        );

         /* 检查支付的金额是否相符 */
        if ($order_info['order_amount'] != ($notify["factMoney"] / 100)) {
            /* 支付的金额与实际金额不一致 */
            $this->_error('price_inconsistent');

            return false;
        }
        if ($params["Md5Sign"] != $notify["Md5Sign"]) {
            $this->_error('sign_inconsistent');

            return false;
        }

        return array(
            'target' => ORDER_ACCEPTED,
        );

    }
}

?>