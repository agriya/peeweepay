<?php
    echo $this->requestAction(array('controller' => 'products', 'action' => 'index'), array('user'=>$user_id, 'view' => 'dashboard', 'return'));
?>