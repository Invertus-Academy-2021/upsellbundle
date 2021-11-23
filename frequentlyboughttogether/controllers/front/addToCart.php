<?php

class frequentlyboughttogetheraddToCartModuleFrontController extends ModuleFrontController {


public function postProcess()
{
    $selectedProducts = Tools::getValue('selected_products');

   

    foreach ($selectedProducts as $key => $value) {
        $this->context->cart->updateQty(1, $value);
    }

    $this->redirectWithNotifications($this->context->link->getPageLink('cart'));

    //$this->success[] = 'Successfully added products to the cart!';

    //

}
}

