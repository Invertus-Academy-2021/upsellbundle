<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class producttopdf extends Module
{
    private $product;

    public function __construct()
    {
        $this->name = 'producttopdf';
        $this->version = '1.0.0';
        $this->author = '_gg';
        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;
        $this->product = new Product(Tools::getValue('id_product'));
        parent::__construct();
        
        $this->displayName = $this->l('Product to pdf');
        $this->description = $this->l('Save product details to pdf');

        /*if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }*/
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('displayProductAdditionalInfo')) {
            return false;
        }

        if (!$this->registerHook('actionFrontControllerSetMedia')) {
            return false;
        }

        return true;
    }

    public function uninstall(){
        return parent::uninstall() 
        && $this->unregisterHook('displayProductAdditionalInfo') 
        && $this->unregisterHook('actionFrontControllerSetMedia');  
    }
    
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducttopdfConfiguration'));
    }

    public function getTabs()
    {
        return [
            [
                'name' => 'Product To PDF',
                'class_name' => 'AdminProducttopdfTest',
                'ParentClassName' => 'AdminParentModulesSf',
                'visible' => false,
            ],
            [
                'name' => 'Configuration',
                'ParentClassName' => 'AdminProducttopdfTest',
                'class_name' => 'AdminProducttopdfConfiguration'
            ]
        ];
    }

    public function getBase64Image(){
        $image = Image::getCover(Tools::getValue('id_product'));
        $imagePath = $this->context->link->getImageLink($this->product->link_rewrite[$this->context->language->id], $image['id_image'], 'home_default');
        $data = file_get_contents($imagePath);
        $base64 = 'data:image/jpg;base64,' . base64_encode($data);
        return $base64;
    }

    public function getRoundedPrice(){
        $price = $this->product->getPrice();
        $decimals = 2;
        $roundedPrice = Tools::ps_round($price, $decimals);
        return $roundedPrice;
    }
    
    public function getProductDetails(){
        $productDetails = array(
            'category' => $this->product->id_category_default,
            'description' => $this->product->description[1],
            'imageBase64' => $this->getBase64Image(),
            'productname' =>$this->product->name[1],
            'price' => $this->getRoundedPrice(),
            'currency' => $this->context->currency->symbol,
            'attributes' => $this->product->getAttributesInformationsByProduct(Tools::getValue('id_product')),
            'features' => $this->product->getFrontFeaturesStatic(Tools::getValue('id_lang'),Tools::getValue('id_product')),
            'isPriceDisplayed' => Configuration::get('SHOW_PRICE'),
            'isImageDisplayed' => Configuration::get('SHOW_IMAGE'),
            'isFeaturesDisplayed' => Configuration::get('SHOW_FEATURES'),
            'isAttributesDisplayed' => Configuration::get('SHOW_ATTRIBUTES')
        );
        return $productDetails;
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
         return $this->fetch('module:'.$this->name.'/views/templates/displayAdditionalInfo.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'producttopdf-css',
            'modules/' . $this->name . '/views/css/producttopdf.css'
        );
        $this->context->controller->registerJavascript(
            'producttopdf-js',
            'modules/' . $this->name . '/views/js/producttopdf.js'
        );
        $this->context->controller->registerJavascript(
            'jsPDF',
            'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js',
            array('server' => 'remote', 'position' => 'bottom', 'priority' => 150)
        );
        $this->context->controller->registerJavascript(
            'html2pdf',
            'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js',
            array('server' => 'remote', 'position' => 'bottom', 'priority' => 150)
        );
        Media::addJsDef($this->getProductDetails());
    }
}