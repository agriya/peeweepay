<div class="paging">
<?php
echo $paginator->prev('&laquo; ' . __l('Prev') , array(
    'class' => 'prev',
    'escape' => false
) , null, array(
    'tag' => 'span',
    'escape' => false,
    'class' => 'prev'
)), "\n";
echo $paginator->numbers(array(
    'modulus' => 2,
    'skip' => '<span class="skip">&hellip;.</span>',
    'separator' => " \n",
    'before' => null,
    'after' => null,
    'escape' => false
));
echo $paginator->next(__l('Next') . ' &raquo;', array(
    'class' => 'next',
    'escape' => false
) , null, array(
    'tag' => 'span',
    'escape' => false,
    'class' => 'next'
)), "\n";
?>
</div>
