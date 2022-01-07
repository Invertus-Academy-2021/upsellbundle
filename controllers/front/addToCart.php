<?php

class UpsellbundleAddToCartModuleFrontController extends ModuleFrontController
{


    public function postProcess()
    {
        $productId = Tools::getValue('id_product');
        $productAttributeId = Tools::getValue('id_product_attribute');
        $this->context->cart->updateQty(1, $productId, $productAttributeId);

        $this->redirectWithNotifications($this->context->link->getPageLink('order'));

        //$this->success[] = 'Successfully added products to the cart!';

        //

    }
}

