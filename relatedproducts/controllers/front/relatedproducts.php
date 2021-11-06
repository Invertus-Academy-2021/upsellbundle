<?php

class RelatedProductsModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:relatedproducts/views/templates/front/relatedproductsfront.tpl');
    }

    public function setMedia()
    {
        parent::setMedia();
        $this->registerStylesheet(
            'relatedproducts-test',
             'modules/relatedproducts/views/css/relatedproducts.css'
        );

        $this->registerJavascript(
            'relatedproducts-test',
            'modules/relatedproducts/views/js/relatedproducts.js'
        );
    }

}