<?php

return array(
    'code'      => 'baofoo',
    'name'      => Lang::get('baofoo'),
    'desc'      => Lang::get('baofoo_desc'),
    'is_online' => '1',
    'author'    => 'Fred Zhou',
    'website'   => 'http://www.baofoo.com',
    'version'   => '1.0',
    'currency'  => Lang::get('baofoo_currency'),
    'config'    => array(
        'baofoo_account' => array(        //账号
            'text'  => Lang::get('baofoo_account'),
            'desc'  => Lang::get('baofoo_account_desc'),
            'type'  => 'text',
        ),
        'baofoo_key' => array(        //密钥
            'text'  => Lang::get('baofoo_key'),
            'desc'  => Lang::get('baofoo_key_desc'),
            'type'  => 'text',
        ),
    ),
);

?>