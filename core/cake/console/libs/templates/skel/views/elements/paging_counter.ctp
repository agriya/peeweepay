<p>
<?php
// retain params
$paginator->options(array(
    'url' => array_merge(array(
        'controller' => $this->params['controller'],
        'action' => $this->params['action'],
    ) , $this->params['pass'], $this->params['named'])
));

echo $paginator->counter(array(
'format' => __l('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
));
?></p>
