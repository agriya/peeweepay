<?php /* SVN: $Id: admin_simple_index.ctp 1405 2011-03-03 06:35:48Z usha_111at09 $ */ ?>
<div class="contactSellers index js-response">
<?php echo $this->element('paging_counter');?>
<div class="overflow-block">
<table class="list">
        <tr>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Email'), 'email');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Subject'), 'subject');?></div></th>
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
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Timezone'), 'Timezone.name');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Latitude'), 'auto_detected_latitude');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Longitude'), 'auto_detected_longitude');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('City'), 'City.name');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('State'), 'State.name');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'Country.name');?></div></th>
    </tr>
<?php
if (!empty($contactSellers)):

$i = 0;
foreach ($contactSellers as $contactSeller):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $html->cText($contactSeller['ContactSeller']['name']);?></td>
		<td><?php echo $html->cText($contactSeller['ContactSeller']['email']);?></td>
		<td><?php echo $html->cText($contactSeller['ContactSeller']['subject']);?></td>
		<td><?php echo $html->cText($contactSeller['ContactSeller']['message']);?></td>
		<td><?php echo $html->link($html->cText($contactSeller['ContactSeller']['ip'], false),'http://whois.sc/'.$contactSeller['ContactSeller']['ip'],array('target' => '_blank'));?>
		<?php if(!empty($contactSeller['ContactSeller']['ip']) && !empty($contactSeller['ContactSeller']['host'])): ?>
		  /
		<?php endif; ?>
		<?php echo $html->cText($contactSeller['ContactSeller']['host']);?></td>
		<td><?php
			echo $html->cText($contactSeller['Timezone']['name']);
		?></td>
		<td><?php echo $html->cText($contactSeller['ContactSeller']['auto_detected_latitude']);?></td>
		<td><?php echo $html->cText($contactSeller['ContactSeller']['auto_detected_longitude']);?></td>
		<td><?php
			echo $html->cText($contactSeller['City']['name']);
		?></td>
		<td><?php
			echo $html->cText($contactSeller['State']['name']);
		?></td>
		<td><?php
			echo $html->cText($contactSeller['Country']['name']);
		?></td>
		<td><?php echo $html->cDateTimeHighlight($contactSeller['ContactSeller']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="13" class="notice"><?php echo __l('No Seller contacts available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php if (!empty($contactSellers)) {?>
    	<div class="js-pagination">
    		<?php echo $this->element('paging_links');?>
    	</div>
<?php } ?>
</div>
