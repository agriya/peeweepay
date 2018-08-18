<?php
// helper to wrap
$_wrapMethods = array(
    'string' => 'cText',
    'text' => 'cText',
    'integer' => 'cInt',
    'date' => 'cDate',
    'datetime' => 'cDateTime',
    'time' => 'cTime',
    'float' => 'cFloat',
    'boolean' => 'cBool'
);
$_currencyFields = array(
    'amount',
    'price',
    'currency',
    'amt',
    'commission'
);
?>