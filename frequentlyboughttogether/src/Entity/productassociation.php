<?php

class ProductAssociation extends ObjectModel
{
    public $product_id;

    public $associated_product_id;

    public static $definition = array(
        'table' => 'frequentlyboughttogether',
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
            'product_id' => array('type' => self::TYPE_INT,  'validate' => 'isInt', 'required' => true, 'size' => 128),
            'associated_product_id' => array('type' => self::TYPE_INT,  'validate' => 'isInt', 'required' => true, 'size' => 128)
        ),
    );
}
