<?php /* SVN: $Id: $ */ ?>
<div class="currencies index js-response">
<h2><?php echo __l('Currencies');?></h2>

<?php
	echo $form->create('Currency' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal filter-form clearfix ')); //js-ajax-form
	echo $form->input('Currency.q', array('label' => __l('Keyword')));

?>
<div class="submit-block clearfix">
	<?php
    	echo $form->submit(__l('Filter'));
	?>
</div>
<?php echo $form->end(); ?>

 <div class="add-block">
	<?php echo $html->link(__l('Add'),array('controller'=>'currencies','action'=>'add'),array('title' => __l('Add New Currency'), 'class' => 'admin-add'));?>
	<?php echo $this->element('paging_counter');?>
</div>

<?php echo $form->create('Currency' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
<div class="overflow-block set-scroll">
<table class="list">
    <tr>
     	<th><?php echo __l('Select');?></th>            
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Code'), 'code');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Symbol'), 'symbol');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Added On'),'created');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Prefix'), 'prefix');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Suffix'), 'suffix');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Decimals'), 'decimals');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Dec Point'), 'dec_point');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Thousands Sep'), 'thousands_sep');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Local'), 'locale');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Format String'), 'format_string');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Grouping Algorithm Callback'), 'grouping_algorithm_callback');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Enabled?'),'is_enabled');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Use graphic symbol?'),'is_use_graphic_symbol');?></div></th>

    </tr>
<?php
if (!empty($currencies)):

$i = 0;
foreach ($currencies as $currency):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
 			<?php echo $form->input('Currency.'.$currency['Currency']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$currency['Currency']['id'], 'class' => 'js-checkbox-list', 'label' => false)); ?>
			<div class="actions-block">
				<div class="actions round-5-left">
					<span><?php echo $html->link(__l('Edit'), array('action' => 'edit', $currency['Currency']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $html->link(__l('Delete'), array('action' => 'delete', $currency['Currency']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
					</span>
				</div>
			</div>
		</td>
		<td>
			<?php echo $html->cText($currency['Currency']['name']);?>
		</td>
		<td><?php echo $html->cText($currency['Currency']['code']);?></td>
		<td><?php echo $html->cText($currency['Currency']['symbol']);?></td>
		<td><?php echo $html->cDateTimeHighlight($currency['Currency']['created']);?></td>
		<td><?php echo $html->cText($currency['Currency']['prefix']);?></td>
		<td><?php echo $html->cText($currency['Currency']['suffix']);?></td>
		<td><?php echo $html->cText($currency['Currency']['decimals']);?></td>
		<td><?php echo $html->cText($currency['Currency']['dec_point']);?></td>
		<td><?php echo $html->cText($currency['Currency']['thousands_sep']);?></td>
		<td><?php echo $html->cText($currency['Currency']['locale']);?></td>
		<td><?php echo $html->cText($currency['Currency']['format_string']);?></td>
		<td><?php echo $html->cText($currency['Currency']['grouping_algorithm_callback']);?></td>
		<td><span class="round-5 bool-<?php echo $currency['Currency']['is_enabled']; ?>"><?php echo $html->cBool($currency['Currency']['is_enabled']);?></span></td>
		<td><span class="round-5 bool-<?php echo $currency['Currency']['is_use_graphic_symbol']; ?>"><?php echo $html->cBool($currency['Currency']['is_use_graphic_symbol']);?></span></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Currencies available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php if (!empty($currencies)) {?>
	<div class="admin-select-block">
		<div>
			<?php echo __l('Select:'); ?>
			<?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
			<?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
		</div>
		<div class="admin-checkbox-button">
			<?php echo $form->input('more_action_id', array('options' => $moreActions, 'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
		</div>
	</div>
	<div class="js-pagination">
		<?php echo $this->element('paging_links');?>
	</div>
	<div class="hide">
		<?php echo $form->submit(__l('Submit'));  ?>
	</div>
	<?php echo $form->end(); ?>
	<?php }?>
</div>
