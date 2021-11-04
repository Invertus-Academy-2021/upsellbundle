<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class upsellbundle extends Module
{
    public function __construct()
    {
        $this->name = 'upsellbundle';
        $this->version = '1.0.0';
        $this->author = '_gg';
        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();
        
        $this->displayName = $this->l('Upsell bundle');
        $this->description = $this->l('Contents: Cart offer (upsell products by category in checkout), Frequently bought together (offer additional products with discounts), Related produts (displaying products, that are simmilar by category under product information)');

        /*if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }*/
    }

    public function install(){
        if (!parent::install()){
            return false;
        }

        if(!$this->registerHook()){
            return false;
        }

        return true;
    }
}