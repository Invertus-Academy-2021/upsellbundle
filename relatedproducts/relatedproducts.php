<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;


if (!defined('_PS_VERSION_')) {
    exit;
}

class relatedproducts extends Module
{
    public function __construct()
    {
        $this->name = 'relatedproducts';
        $this->version = '1.0.0';
        $this->author = '_gg';
        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();
        
        $this->displayName = $this->l('Related products');
        $this->description = $this->l('Displaying products, that are simmilar by category under product information');

        /*if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }*/
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayFooterProduct')) {
            return false;
        }

        if (!$this->registerHook('actionFrontControllerSetMedia')) {
            return false;
        }

        return true;
    }

    public function uninstall(){
        return parent::uninstall() 
        && $this->unregisterHook('displayFooterProduct') 
        && $this->unregisterHook('actionFrontControllerSetMedia');  
    }

    public function hookDisplayFooterProduct($params)
    {
        $products = $this->getProducts();
        $this->context->smarty->assign([
                'products' => $products,
                'allProductsLink' => Context::getContext()->link->getCategoryLink($this->getConfigFieldsValues()['HOME_FEATURED_CAT']),
            ]);

         return $this->fetch('module:'.$this->name.'/views/templates/displayFooterProduct.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'relatedproducts-css',
            'modules/' . $this->name . '/views/css/relatedproducts.css'
        );
        $this->context->controller->registerJavascript(
            'relatedproducts-js',
            'modules/' . $this->name . '/views/js/relatedproducts.js'
        );
    }

    public function getTabs()
    {
        return array(
            array(
                'name' => 'Related products',
                'ParentClassName' => 'CONFIGURE',
                'class_name' => 'AdminRelatedProductsTest',
            )
        );
    }

    
    public function getConfigFieldsValues()
    {
        return array(
            'HOME_FEATURED_CAT' => Tools::getValue('HOME_FEATURED_CAT', (int) Configuration::get('HOME_FEATURED_CAT')),
            'HOME_FEATURED_RANDOMIZE' => Tools::getValue('HOME_FEATURED_RANDOMIZE', (bool) Configuration::get('HOME_FEATURED_RANDOMIZE')),
        );
    }

    protected function getProducts()
    {
        $product = new Product(Tools::getValue('id_product'));
        $defaultCategory = $product->getDefaultCategory();

        $category = new Category($defaultCategory);

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = 4;

        $query->setResultsPerPage($nProducts)->setPage(1);

        if (Configuration::get('HOME_FEATURED_RANDOMIZE')) {
            $query->setSortOrder(SortOrder::random());
        } else {
            $query->setSortOrder(SortOrder::random());
        }

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];

        foreach ($result->getProducts() as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
        }

        return $products_for_template;
    }

}