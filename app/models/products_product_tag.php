<?php
class ProductsProductTag extends AppModel
{
    var $name = 'ProductsProductTag';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ProductTag' => array(
            'className' => 'ProductTag',
            'foreignKey' => 'product_tag_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }
}
?>