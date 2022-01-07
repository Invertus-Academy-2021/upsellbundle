<?php

class AdminUpsellbundleAjaxController extends ModuleAdminController
{
    public function __construct()
    {
        $this->ajax = 1;

        parent::__construct();
    }

    public function ajaxProcessPostData(){
        $target_id = Tools::getValue("target");
        $product_id = Tools::getValue("product");
        $isChecked = (bool) Tools::getValue("isChecked");

        if($isChecked){
           Db::getInstance()->insert('product_oneclickupsell', [
                'id_product' => $target_id,
                'id_chained' => $product_id
            ]);
        } else {
            Db::getInstance()->delete('product_oneclickupsell',
            "id_product = {$target_id} AND
            id_chained = {$product_id}");
        }
        exit();
    }

    /*public function ajaxProcessGetData(){

        $target_id = Tools::getValue("target");
        $product_id = Tools::getValue("product");

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('product_oneclickupsell');
        $sql->where('id_product = '.$target_id.' AND id_chained = '.$product_id);
        $return = array('result' => Db::getInstance()->executeS($sql));
        echo json_encode($return);
        exit();


    }*/
}