<?php /* SVN: $Id: $ */ ?>
<div class="users index js-response">
<h2><?php echo __l('Users');?></h2>
<?php
	echo $form->create('User' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal filter-form clearfix ')); //js-ajax-form
	echo $form->input('User.q', array('label' => __l('Keyword')));

?>
<div class="submit-block clearfix">
	<?php
    	echo $form->submit(__l('Filter'));
	?>
</div>
<?php echo $form->end(); ?>
<?php echo $this->element('paging_counter');?>

<?php echo $form->create('User' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>

<div class="overflow-block">
<table class="list">
    <tr>
		<th rowspan="2"><?php echo __l('Select');?></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Full Name'),'fullname');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Email'), 'email');?></div></th>
        <th colspan='2'><?php echo __l('Products');?></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'Country.name');?></div></th> 
        <th colspan='6'><?php echo __l('Auto detected');?></th>		
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Added On'),'created');?></div></th>
    </tr>
	<tr>
		<th>
			<div class="js-pagination">
				<?php echo $paginator->sort(__l('Verified'),'product_verified_count');?>
				<?php echo '/';?>
				<?php echo $paginator->sort(__l('Unverified'),'product_unverified_count');?>
			</div>
		</th>
		
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Total'), 'product_count');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Timezone'), 'AutoDetectedTimezone.name');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Latitude'), 'auto_detected_latitude');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Longitude'), 'auto_detected_longitude');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('City'), 'AutoDetectedCity.name');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('State'), 'AutoDetectedState.name');?></div></th> 
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'AutoDetectedCountry.name');?></div></th> 
    </tr>
<?php
if (!empty($users)):

$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if($user['User']['is_active']):
		$status_class= 'js-checkbox-active';
	else:
		$status_class= 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
		
		<td>
			<?php echo $form->input('User.'.$user['User']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$user['User']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?>
			<div class="actions-block">
			<div class="actions round-5-left">
			<span><?php echo $html->link(__l('Edit'), array('action' => 'edit', $user['User']['id']), array('class' => 'edit', 'title' => __l('Edit')));?></span><span><?php echo $html->link(__l('Delete'), array('action' => 'delete', $user['User']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></div></div>
		</td>
		<td>
		<?php echo $html->cText($user['User']['fullname']);?></td>
		<td><?php echo $html->cText($user['User']['email']);?></td>
		<td>
			<?php echo $html->link($html->cInt($user['User']['product_verified_count'], false), array('controller' => 'products', 'action' => 'index', 'user_id' => $user['User']['id']), array('title' => $html->cInt($user['User']['product_verified_count'], false)));?>
			<?php echo '/';?>
			<?php echo $html->cInt($user['User']['product_unverified_count'], false);?>			
		</td>
		<td>
			<?php echo $html->cInt(($user['User']['product_verified_count']+$user['User']['product_unverified_count']), false)?>
		</td>
		<td><?php echo $html->cText($user['Country']['name']);?></td>
		<td><?php
			echo $html->cText($user['AutoDetectedTimezone']['name']);
		?></td>
		<td><?php echo $html->cText($user['User']['auto_detected_latitude']);?></td>
		<td><?php echo $html->cText($user['User']['auto_detected_longitude']);?></td>
		<td><?php echo $html->cText($user['AutoDetectedCity']['name']);?></td>
		<td><?php echo $html->cText($user['AutoDetectedState']['name']);?></td>
		<td><?php echo $html->cText($user['AutoDetectedCountry']['name']);?></td>
		<td><?php echo $html->cDateTimeHighlight($user['User']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="15" class="notice"><?php echo __l('No Users available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php if (!empty($users)) {?>
	<div class="admin-select-block">
		<div>
			<?php echo __l('Select:'); ?>
			<?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
			<?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
			<?//php echo $html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved','title' => __l('Active'))); ?>
			<?//php echo $html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending','title' => __l('Inactive'))); ?>
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
<?php     echo $form->end();
}?>
</div>