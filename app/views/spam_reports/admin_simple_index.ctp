<?php /* SVN: $Id: admin_simple_index.ctp 1405 2011-03-03 06:35:48Z usha_111at09 $ */ ?>
<div class="reportSpams index js-response">
<?php echo $this->element('paging_counter');?>
<table class="list">
     <tr>
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
		<td>
			<?php echo $html->link($html->cText($spamReport['Product']['title']), array('controller'=> 'products', 'action'=>'v', 'slug' => $spamReport['Product']['slug'], 'view_type' => ConstViewType::NormalView, 'admin' => false),  array('escape' => false));?></td>
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
		<td colspan="12" class="notice"><?php echo __l('No Spam Reportss available');?></td>
	</tr>
<?php
endif;
?>
</table>
	<?php if (!empty($spamReports)) {?>
		<div class="js-pagination">
			<?php echo $this->element('paging_links');?>
		</div>
    <?php } ?>
</div>
