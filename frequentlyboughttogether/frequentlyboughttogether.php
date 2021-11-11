<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class frequentlyboughttogether extends Module
{
    public function __construct()
    {
        $this->name = 'frequentlyboughttogether';
        $this->version = '1.0.0';
        $this->author = '_gg';
        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();
        $this->displayName = $this->l('Frequently Bought Together');
        $this->description = $this->l('Displaying products, that are frequently bought together');
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayAdminProductsExtra')) {
            return false;
        }

        return true;
    }

    public function uninstall(){
        return parent::uninstall() 
        && $this->unregisterHook('displayAdminProductsExtra');
    }

    public function hookDisplayAdminProductsExtra(){
        $this->context->smarty->assign(array(
            'products' => Product::getProducts($this->context->language->id,0,0,'id_product','ASC')
        ));
        return $this->fetch('module:'.$this->name.'/views/templates/displayAdminProductsExtra.tpl');
    }

}