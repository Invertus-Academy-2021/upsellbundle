<?php

class ProducttopdfModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:producttopdf/views/templates/front/test.tpl');
    }
}