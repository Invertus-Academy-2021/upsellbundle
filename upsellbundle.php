<?php
if (!defined('_PS_VERSION_')) {
    exit;
}


class upsellbundle extends Module
{
    const CONTROLLER_CONFIG = 'AdminUpsellbundleParent';
    const CONTROLLER_UPSELL = 'AdminUpsellbundleOneClickUpsell';

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
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayAdminProductsExtra')) {
            return false;
        }

        if(!$this->registerHook('displayAboveCartSummary')){
            return false;
        }

        if(!$this->registerHook('actionFrontControllerSetMedia')){
            return false;
        }

        if (!$this->createTables()) {
            return false;
        }

        return true;
    }

    public function uninstall(){
        return parent::uninstall()
            && $this->sqlUninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink(self::CONTROLLER_UPSELL));
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'upsellbundle-css',
            'modules/' .  $this->name .'/views/css/style.css',
            [
                'media' => 'all',
                'priority' => 200,
            ]
        );
    }

    public function hookDisplayAdminProductsExtra($params){
        $this->context->smarty->assign([
            'url' => $this->context->link->getAdminLink('AdminUpsellbundleOneClickUpsell'),
            'product_id' => $params['id_product']
            ]);

        return $this->fetch('module:'.$this->name.'/views/templates/admin/displayAdminProductsExtra.tpl');
    }

    public function hookDisplayAboveCartSummary($params){

        $productData = array(0, '', '', 0, 0, 0);
        $visibilityOfPoster = 0;
        if(($result = $this->checkIfCartHasTargetProducts()) != 0) {
            $visibilityOfPoster = 1;
            $productData = $this->getTargetProductData($result);
        }

        $this->context->smarty->assign([
            'visible' => $visibilityOfPoster,
            'productId' => $productData[0],
            'imageURL' => $productData[1],
            'productName' => $productData[2],
            'productDefaultAttributeId' => $productData[3],
            'productPrice' => $productData[4],
            'discountedProductPrice' => $productData[5],
            'url' => $this->context->link->getAdminLink('AdminUpsellbundleOneClickUpsell'),
        ]);

        return $this->fetch('module:'.$this->name.'/views/templates/front/displayInfoBox.tpl');
    }

    private function getTargetProductData($productId){
        $oldBaseURLToReplace = str_replace("http://", "", _PS_BASE_URL_  . __PS_BASE_URI__);
        //$oldBaseURLToReplace = str_replace("https://", "", _PS_BASE_URL_  . __PS_BASE_URI__);
        $newBaseURL = _PS_BASE_URL_  . __PS_BASE_URI__;
        $image = Image::getCover($productId);
        $product = new Product($productId, false, Context::getContext()->language->id);
        $link = new Link;
        $imagePath = $newBaseURL . str_replace($oldBaseURLToReplace, "", $link->getImageLink($product->link_rewrite, $image['id_image'], 'small_default'));
        $cardId = $this->context->cart->id;
        list($oldPrice, $discountedPrice) = $this->getOriginalAndDiscountedPrices($productId, 0.2, $cardId);//Product::getPriceStatic($productId);
        $productData = array($productId, $imagePath,
                            $product->name, $product->cache_default_attribute,
                            $oldPrice, $discountedPrice);
        return $productData;
    }

    //set specific price for product discount
    //return original and discounted prices
    private function getOriginalAndDiscountedPrices($productId, $discountPercentage, $cartId){
        $specificPrice = SpecificPrice::getSpecificPrice($productId, 1, 0, 0, 0, 1, 0, 0, $cartId);
        if (is_array($specificPrice) && isset($specificPrice['id_specific_price'])) {
            $specificPrice = new SpecificPrice((int)$specificPrice['id_specific_price']);
            //$specificPrice->delete();
        }else{
            $specificPrice = new SpecificPrice();
            $specificPrice->id_product = $productId;
            $specificPrice->id_product_attribute = 0;
            $specificPrice->id_shop = 1;
            $specificPrice->id_cart = $cartId;
            $specificPrice->id_currency = 0;
            $specificPrice->id_country = 0;
            $specificPrice->id_group = 0;
            $specificPrice->id_customer = 0;
            $specificPrice->price = -1;
            $specificPrice->from_quantity = 1;
            $specificPrice->reduction = $discountPercentage;
            $specificPrice->reduction_type = 'percentage';
            $specificPrice->reduction_tax = 1;
            $specificPrice->from = "0000-00-00 00:00:00";
            $specificPrice->to = "0000-00-00 00:00:00";
            $specificPrice->save();
        }

        $priceWithNoDiscount = Product::getPriceStatic($productId);

        return [$priceWithNoDiscount, ($priceWithNoDiscount * (1 - $specificPrice->reduction))];
    }

    private function getTargetProducts($cartProductIds){
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'product_oneclickupsell`
         WHERE `id_chained` IN (' . implode(',', array_map('intval', $cartProductIds)) . ')';
        return Db::getInstance()->executeS($sql);
    }

    private function checkIfCartHasTargetProducts(){
        $cartProductIds = $this->getCartProductIds();
        $targetProducts = $this->getTargetProducts($cartProductIds);
        $resultIds = [];

        foreach($targetProducts as $result){
            $upsellProduct = (int) $result['id_product'];
            if(in_array($upsellProduct, $cartProductIds)) {
                $resultIds = [];
                break;
            }
            $resultIds[] = (int) $result['id_product'];
        }

        if(!empty($resultIds)){
            return $resultIds[0];
        }
        return 0;
    }

    private function getCartProductIds(){
        $cartProducts = $this->context->cart->getProducts();
        $productIds = [];
        foreach($cartProducts as $cartProduct){
            $productIds[] = (int)$cartProduct['id_product'];
        }
        return $productIds;
    }

    protected function createTables(){
        $return =  Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS '._DB_PREFIX_. 'product_oneclickupsell' . '(
            `id_product` INTEGER(10) DEFAULT 0,
            `id_chained` INTEGER(10) DEFAULT 0
                ) ENGINE='. _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');

        return $return;
    }

    protected function sqlUninstall(){
        $sql = Db::getInstance()->execute("DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "product_oneclickupsell`");
        return $sql;
    }

    public function getTabs()
    {
        return array(
            array(
                'name' => 'Upsell bundle',
                'ParentClassName' => 'AdminParentModulesSf',
                'class_name' => self::CONTROLLER_CONFIG,
                'visible' => false
            ),
            array(
                  'name' => 'One click upsell',
                  'ParentClassName' => self::CONTROLLER_CONFIG,
                  'class_name' => self::CONTROLLER_UPSELL,
            ),
        );
    }
}