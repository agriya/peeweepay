	<div class="check-information-block">
<?php if(!empty($is_verified)) : ?>
<h2><?php echo __l('Your product has already verified');?></h2>
<p><?php echo __l('Your product page already placed.');?></p>
<?php else: ?>
<h2><?php echo __l('Your product has been verified');?></h2>
<p><?php echo __l('Your product page for"').' '; echo $html->cText($product['Product']['title']); echo __l('" has been placed and short link has sent to your e-mail address.');?></p>
<?php endif; ?>
</div>