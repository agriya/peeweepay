<?php
    echo $this->requestAction(array('controller' => 'products', 'action' => 'index'), array('product_id'=>$product['Product']['id'], 'user'=>$product['User']['id'], 'view' => 'simple', 'return'));
?>