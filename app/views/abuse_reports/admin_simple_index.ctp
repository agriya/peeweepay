<?php /* SVN: $Id: admin_simple_index.ctp 1405 2011-03-03 06:35:48Z usha_111at09 $ */ ?>
<div class="reportAbuses index js-response">
<table class="list">
    <tr>
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
		<td colspan="12" class="notice"><?php echo __l('No Abuse Reports available');?></td>
	</tr>
<?php
endif;
?>
</table>
	<?php if (!empty($abuseReports)) {?>
		<div class="js-pagination">
			<?php echo $this->element('paging_links');?>
		</div>
    <?php } ?>
</div>
