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

        if (!$this->registerHook('displayFooterProduct')) {
            return false;
        }

        if (!$this->registerHook('actionProductSave')) {
            return false;
        }

        if (!$this->registerHook('actionFrontControllerSetMedia')) {
            return false;
        }

        if (!$this->sqlInstall()) {
            return false;
        }
        
        return true;
    }

    public function uninstall(){
        return parent::uninstall() 
        && $this->sqlUninstall();  
    }

    public function hookDisplayFooterProduct()
    {
        $this->context->smarty->assign([
            'checkedProducts' => $this->getCheckedProducts($this->getCheckedProductIds(Tools::getValue('id_product')))
        ]);
        return $this->context->smarty->fetch('module:frequentlyboughttogether/views/templates/front/app.tpl');
    }

    public function hookDisplayAdminProductsExtra($params){
        $productsList = Product::getProducts($this->context->language->id,0,0,'id_product','ASC');
       
        $this->context->smarty->assign(array(
            'products' => $this->excludeCurrentPageProduct($params['id_product']-1,$productsList),
            'checkedProducts' => $this->getCheckedProductIds($params['id_product'])
        ));

        return $this->fetch('module:'.$this->name.'/views/templates/displayAdminProductsExtra.tpl');
    }

    public function getContent()
    {
        $this->context->smarty->assign([
            'pathApp' => $this->getPathUri() . 'views/js/app.js',
            'chunkVendor' => $this->getPathUri() . 'views/js/chunk-vendors.js',
        ]);

        return $this->context->smarty->fetch('module:frequentlyboughttogether/views/templates/admin/app.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'frequentlyboughttogether-css',
            'modules/' . $this->name . '/views/css/frequentlyboughttogether.css'
        );
        $this->context->controller->registerJavascript(
            'frequentlyboughttogether-js',
            'modules/' . $this->name . '/views/js/frequentlyboughttogether.js'
        );
    }

    public function hookActionProductSave($params){
        if (!isset($_POST['bulk_action_selected_products'])){
            $this->deleteProductAssociations($params["id_product"]);
            return true;

        }
        $selectedproductslist = $_POST['bulk_action_selected_products'];

        if (sizeof($selectedproductslist) > 3){
            Tools::displayError('No more than 3 products can be associated at one time!');
            return true;
        }

        if (!empty($selectedproductslist)) {
            $this->deleteProductAssociations($params["id_product"]);
            foreach ($selectedproductslist as $selectedproduct) {
                $this->addAssociatedProducts($params["id_product"], $selectedproduct);
            }
        }
    }

    protected function sqlInstall(){
        $sqlCreate =  "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "frequentlyboughttogether` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `product_id`  varchar(255) DEFAULT NULL,
            `associated_product_id` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        return Db::getInstance()->execute($sqlCreate);
    }

    protected function sqlUninstall(){
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "frequentlyboughttogether`";
        
        return Db::getInstance()->execute($sql);
    }

    public function addAssociatedProducts($productid, $associatedproductid){
        $productassociation = new ProductAssociation();
        $productassociation->product_id = $productid;
        $productassociation->associated_product_id = $associatedproductid;
        $productassociation->save();
    }

    public function excludeCurrentPageProduct($selectedproductid, $productsList){
        unset($productsList[$selectedproductid]);
        return $productsList;
    }

    public function deleteProductAssociations($productid){
        $sqlDelete =  "DELETE FROM `" . _DB_PREFIX_ . "frequentlyboughttogether` 
                        WHERE `product_id` = ". $productid;
        return Db::getInstance()->execute($sqlDelete);
    }

    public function getCheckedProductIds($productid){
        $checkedProducts = 'SELECT `associated_product_id`   FROM `' . _DB_PREFIX_ . 'frequentlyboughttogether`
        WHERE `product_id` = '. $productid;
        $result = Db::getInstance()->executeS($checkedProducts);
        $checkedProductIds = [];
        foreach ($result as $checkedProduct) {
            $checkedProductIds[] = (int) $checkedProduct["associated_product_id"];
        }
        return $checkedProductIds;
    }

    public function getCheckedProducts($checkedProductsIds){
        $checkedProducts = [];
        $lang_id = (int) Configuration::get('PS_LANG_DEFAULT');

        foreach ($checkedProductsIds as $checkedProductId) {
            $product = new Product($checkedProductId, false, $lang_id);
            $checkedProducts[] = $product;
        }
        return $checkedProducts;
    }

}