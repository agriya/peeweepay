<p class="page-counter">
<?php
echo $paginator->counter(array(
'format' => __l('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
));
?></p>
