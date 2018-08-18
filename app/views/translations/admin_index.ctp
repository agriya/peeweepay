<?php /* SVN: $Id: admin_index.ctp 17696 2010-08-05 12:37:07Z boopathi_026ac09 $ */ ?>
<div class="translations index">
<h2><?php echo __l('Translations');?></h2>
<div class="clearfix add-block1">
	<?php echo $html->link(__l('Make New Translation'), array('controller' => 'translations', 'action' => 'add'), array('class' => 'admin-add', 'title'=>__l('Make New Translation'))); ?>
	<?php echo $html->link(__l('Add New Text'), array('controller' => 'translations', 'action' => 'add_text'), array('class' => 'admin-add', 'title'=>__l('Add New Text'))); ?>
</div>
<div class = "notice">
	<?php echo __l('To make new translation default translated language English should be available.');?>
</div>
<h3><?php echo __l('Available Translations');?></h3>
<div class="overflow-block">
<table class="list">
    <tr>
		<th><?php echo __l('Language');?></th>
		<th><?php echo __l('Percentage');?></th>
		<th><?php echo __l('Verified');?></th>
		<th><?php echo __l('Not Verified');?></th>
		<th class="actions"><?php echo __l('Manage');?></th>
    </tr>
<?php
if (!empty($translations)):

$i = 0;
foreach ($translations as $language_id => $translation):
	$class = null;
	if ($i++ % 2 == 0):
		$class = ' class="altrow"';
    endif;
?>
	<tr<?php echo $class;?>>
		<td><?php echo $html->cText($translation['name']);?></td>
		<td><?php echo __l('Verified:');?> <?php $total = $translation['verified'] + $translation['not_verified'];
			echo round($translation['verified']*100/$total, 3).'% <br>'.__l('Not Verified: ');
			echo round($translation['not_verified']*100/$total, 3)."% "; ?></td>
		<td><?php 
			if($translation['verified']){
				echo $html->link($translation['verified'], array('action' => 'manage', 'filter' => 'verified', 'language_id' => $language_id));
			} else {
				echo $html->cText($translation['verified']);
			}
			;?></td>
		<td><?php 
			if($translation['not_verified']){
				echo $html->link($translation['not_verified'], array('action' => 'manage', 'filter' => 'unverified', 'language_id' => $language_id));
			} else {
				echo $html->cText($translation['not_verified']);
			}
			;?></td>
		<td class="actions">
			<span><?php echo $html->link(__l('Manage'), array('action' => 'manage', 'language_id' => $language_id), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span>
			<?php if($language_id != '38'):?>
				<span><?php echo $html->link(__l('Delete'), array('action' => 'index', 'remove_language_id' => $language_id), array('class' => 'delete js-delete', 'title' => __l('Delete Translation')));?></span>
			<?php endif;?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7" class="notice"><?php echo __l('No Translations available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
</div>