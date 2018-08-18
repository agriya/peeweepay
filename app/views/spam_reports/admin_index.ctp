<?php /* SVN: $Id: $ */ ?>
<div class="reportSpams index js-response">
<?php if(empty($this->params['named']['simple_view'])): ?>
<h2><?php echo $this->pageTitle;?></h2>
<?php
	echo $form->create('SpamReport' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal search-form filter-form clearfix ')); //js-ajax-form
	echo $form->input('SpamReport.q', array('label' => __l('Keyword')));
?>
<div class="submit-block clearfix">
	<?php
    	echo $form->submit(__l('Filter'));
	?>
</div>
<?php echo $form->end(); ?>
<?php endif; ?>
<?php echo $this->element('paging_counter');?>
<?php if(empty($this->params['named']['simple_view'])): ?>
<?php echo $form->create('SpamReport' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
<?php endif; ?>
<div class="overflow-block">
<table class="list">
    <tr>

	   	<th rowspan="2"><?php echo __l('Select');?></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Product'), 'Product.title');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Email'), 'email');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Message'), 'message');?></div></th>
		<th rowspan="2">
		         <div class="js-pagination"><?php echo $paginator->sort(__l('IP'), 'ip');?></div>
				 /
		         <div class="js-pagination"><?php echo $paginator->sort(__l('Host'), 'host');?></div>
		</th>
		<th colspan='6'><?php echo __l('Auto detected');?></th>		
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Posted On'),'created');?></div></th>        
    </tr>
	<tr>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Timezone'), 'auto_detected_timezone_id');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Latitude'), 'auto_detected_latitude');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Longitude'), 'auto_detected_longitude');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('City'), 'auto_detected_city_id');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('State'), 'auto_detected_state_id');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'auto_detected_country_id');?></div></th> 
    </tr>
<?php
if (!empty($spamReports)):

$i = 0;
foreach ($spamReports as $spamReport):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
	<?php if(empty($this->params['named']['simple_view'])): ?>
		<td>
			<?php echo $form->input('SpamReport.'.$spamReport['SpamReport']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$spamReport['SpamReport']['id'], 'class' => 'js-checkbox-list', 'label' => false)); ?>
			<div class="actions-block">
			<div class="actions round-5-left">
			<span><?php echo $html->link(__l('Edit'), array('action' => 'edit', $spamReport['SpamReport']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $html->link(__l('Delete'), array('action' => 'delete', $spamReport['SpamReport']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></div></div>
		</td>
		<?php endif; ?>
		<td>
			<?php echo $html->link($html->cText($spamReport['Product']['title']), array('controller'=> 'products', 'action' => 'v', 'slug' => $spamReport['Product']['slug'], 'view_type' => ConstViewType::NormalView, 'admin' => false),  array('escape' => false));?></td>
		<td><?php echo $html->cText($spamReport['SpamReport']['name']);?></td>
		<td><?php echo $html->cText($spamReport['SpamReport']['email']);?></td>
		<td><?php echo $html->cText($spamReport['SpamReport']['message']);?></td>
		<td><?php echo $html->link($html->cText($spamReport['SpamReport']['ip'], false),'http://whois.sc/'.$spamReport['SpamReport']['ip'],array('target' => '_blank'));?>
		<?php if(!empty($spamReport['SpamReport']['ip']) && !empty($spamReport['SpamReport']['host'])): ?>
		  / 
		<?php endif; ?>
		<?php echo $html->cText($spamReport['SpamReport']['host']);?></td>
		<td><?php 
			echo $html->cText($spamReport['Timezone']['name']);
		?></td>
		<td><?php echo $html->cText($spamReport['SpamReport']['auto_detected_latitude']);?></td>
		<td><?php echo $html->cText($spamReport['SpamReport']['auto_detected_longitude']);?></td>
		<td><?php
			echo $html->cText($spamReport['City']['name']);
		?></td>
		<td><?php 
			echo $html->cText($spamReport['State']['name']);
		?></td>
		<td><?php 
			echo $html->cText($spamReport['Country']['name']);
		?></td>
		<td><?php echo $html->cDateTimeHighlight($spamReport['SpamReport']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Spam Reportss available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php if(empty($this->params['named']['simple_view'])): ?>
	<?php if (!empty($spamReports)) {?>
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
	<?php endif; ?>
</div>
