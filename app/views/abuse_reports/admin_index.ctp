<?php /* SVN: $Id: $ */ ?>
<div class="reportAbuses index js-response">
<?php if(empty($this->params['named']['simple_view'])): ?>
    <h2><?php echo $this->pageTitle;?></h2>
    <?php
    	echo $form->create('AbuseReport' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal search-form filter-form clearfix ')); //js-ajax-form
    	echo $form->input('AbuseReport.q', array('label' => __l('Keyword')));

    ?>
    <div class="submit-block clearfix">
    	<?php
        	echo $form->submit(__l('Filter'));
    	?>
    </div>
    <?php echo $form->end(); ?>
<?php endif; ?>
<?php echo $this->element('paging_counter');?>

    <?php echo $form->create('AbuseReport' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
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
		         <div class="js-pagination"><?php echo $paginator->sort(__l('Host'), 'host');?></div></th>
		<th colspan='6'><?php echo __l('Auto detected');?></th>	
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Posted On'),'created');?></div></th>
    </tr>
	<tr>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Timezone'), 'Timezone.name');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Latitude'), 'auto_detected_latitude');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Longitude'), 'auto_detected_longitude');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('City'), 'City.name');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('State'), 'State.name');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'Country.name');?></div></th> 
    </tr>
<?php
if (!empty($abuseReports)):

$i = 0;
foreach ($abuseReports as $abuseReport):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	
?>
	<tr<?php echo $class;?>>
        <?php if(empty($this->params['named']['simple_view'])): ?>
		<td>
  			<?php echo $form->input('AbuseReport.'.$abuseReport['AbuseReport']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$abuseReport['AbuseReport']['id'], 'class' => 'js-checkbox-list', 'label' => false)); ?>
			<div class="actions-block">
				<div class="actions round-5-left">
					<span><?php echo $html->link(__l('Edit'), array('action' => 'edit', $abuseReport['AbuseReport']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $html->link(__l('Delete'), array('action' => 'delete', $abuseReport['AbuseReport']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
					</span>
				</div>
			</div>
		</td>
        <?php endif; ?>
		<td>
			<?php echo $html->link($abuseReport['Product']['title'], array('controller' => 'products', 'action' => 'v', 'slug' => $abuseReport['Product']['slug'], 'view_type' => ConstViewType::NormalView, 'admin' => false));?>
		</td>
		<td><?php echo $html->cText($abuseReport['AbuseReport']['name']);?></td>
		<td><?php echo $html->cText($abuseReport['AbuseReport']['email']);?></td>
		<td><?php echo $html->cText($abuseReport['AbuseReport']['message']);?></td>
		<td><?php echo $html->link($html->cText($abuseReport['AbuseReport']['ip'], false),'http://whois.sc/'.$abuseReport['AbuseReport']['ip'],array('target' => '_blank'));?>
		<?php if(!empty($abuseReport['AbuseReport']['ip']) && !empty($abuseReport['AbuseReport']['host'])): ?>
		  / 
		<?php endif; ?>
		<?php echo $html->cText($abuseReport['AbuseReport']['host']);?></td>
		<td><?php 
			echo $html->cText($abuseReport['Timezone']['name']);
		?></td>
		<td><?php echo $html->cText($abuseReport['AbuseReport']['auto_detected_latitude']);?></td>
		<td><?php echo $html->cText($abuseReport['AbuseReport']['auto_detected_longitude']);?></td>
		<td><?php
			echo $html->cText($abuseReport['City']['name']);
		?></td>
		<td><?php 
			echo $html->cText($abuseReport['State']['name']);
		?></td>
		<td><?php 
			echo $html->cText($abuseReport['Country']['name']);
		?></td>		
		<td><?php echo $html->cDateTimeHighlight($abuseReport['AbuseReport']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No Abuse Reports available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
	<?php if (!empty($abuseReports)) {?>
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
