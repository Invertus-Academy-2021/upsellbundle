<?php

class UpsellbundleDisplayincheckoutModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        $this->bootstrap = true;
        /**
         * Connecting Controller with object and database
         */
        $this->table = 'product';
        $this->className = 'Product';
        $this->lang = true;
        /**
         * Adding row actions without which we cant edit or delete
         * Custom row actions can be added
         */
//        $this->addRowAction('edit');
//        $this->addRowAction('delete');

//        $this->_defaultOrderBy = 'id_training_article';
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->initList();
        //$this->initForm();
    }

    private function initList()
    {
        /**
         * Those are needed if you want to add more data to select then table of object of this controller
         */
        $this->_select = ' IF(po.`id_chained` IS NOT NULL, 1, 0) AS "active"';
        //$this->_join = 'LEFT JOIN `'._DB_PREFIX_.'product_lang` `pl` ON pl.`id_product` = a.`id_training_article` AND pl.`id_lang` = ' . (int)$this->context->language->id;
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'product_oneclickupsell` `po` 
        ON po.`id_product` = '.(int)Tools::getValue('product_id').'
        AND po.`id_chained` = a.`id_product`' ;
        $this->_where = 'AND a.`id_product` != '.(int)Tools::getValue('product_id');
        $this->list_no_link = true;
        $this->fields_list =  array(
            'id_product' => array(
                'title' => $this->module->l('Id'),
                'width' => 100,
                'class' => 'my-custom-class'
            ),
            'name' => array(
                'title' => $this->module->l('Name'),
                'width' => 80,
            ),
            'active' => array(
                'title' => $this->l('Is active?'),
                //'type' => "bool",
                'callback' => /*$this->module->l('Id')*/"chainUpsell"
            ),
        );
    }
}