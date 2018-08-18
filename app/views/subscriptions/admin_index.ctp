<?php /* SVN: $Id: $ */ ?>
<div class="subscriptions index js-response">
<h2><?php echo __l('Newsletter subscriptions');?></h2>
<?php
	echo $form->create('Subscription' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal search-form filter-form clearfix ')); //js-ajax-form
	echo $form->input('Subscription.q', array('label' => __l('Keyword')));

?>
<div class="submit-block clearfix">
	<?php
    	echo $form->submit(__l('Filter'));
	?>
</div>
<?php echo $form->end(); ?>
<?php echo $this->element('paging_counter');?>
<div class="overflow-block">
<table class="list">
    <tr>        
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Email'), 'email');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Added On'),'created');?></div></th>
    </tr>
<?php
if (!empty($subscriptions)):

$i = 0;
foreach ($subscriptions as $subscription):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
		<div class="actions-block">
		<div class="actions round-5-left"><span><?php echo $html->link(__l('Delete'), array('action' => 'delete', $subscription['Subscription']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></div></div><?php echo $html->cText($subscription['Subscription']['name']);?></td>
		<td><?php echo $html->cText($subscription['Subscription']['email']);?></td>
		<td><?php echo $html->cDateTimeHighlight($subscription['Subscription']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6" class="notice"><?php echo __l('No Subscriptions available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>

<?php
if (!empty($subscriptions)) {
    echo $this->element('paging_links');
}
?>
</div>
