<?php /* SVN: $Id: admin_view.ctp 3 2010-04-07 06:03:46Z siva_063at09 $ */ ?>
<div class="translations view">
<h2><?php echo __l('Translation');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cInt($translation['Translation']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cDateTime($translation['Translation']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cDateTime($translation['Translation']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Language');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->link($html->cText($translation['Language']['name']), array('controller' => 'languages', 'action' => 'view', $translation['Language']['id']), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Key');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cText($translation['Translation']['key']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Lang Text');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cText($translation['Translation']['lang_text']);?></dd>
	</dl>
</div>

