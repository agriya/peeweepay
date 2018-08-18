<?php /* SVN: $Id: admin_index.ctp 1152 2010-04-01 12:41:18Z siva_063at09 $ */ ?>
<div class="languages index js-response">
    <h2><?php echo __l('Languages');?></h2>
    <?php 
          echo $form->create('Language', array('type' => 'get', 'class' => 'normal filter-form clearfix', 'action'=>'index'));
          echo $form->input('filter_id',array('type'=>'select', 'empty' => __l('Please Select'))); ?>
          <div class="submit-block"><?php echo $form->submit(__l('Search')); ?></div>
          <?php echo $form->end();
    ?>
    <?php echo $form->create('Language' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
    <?php echo $this->element('paging_counter');?>
    <div class="overflow-block">
    <table class="list">
        <tr>
            <th><?php echo __l('Select'); ?></th>
            <th><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
            <th><div class="js-pagination"><?php echo $paginator->sort(__l('ISO2'), 'iso2');?></div></th>
            <th><div class="js-pagination"><?php echo $paginator->sort(__l('ISO3'), 'iso3');?></div></th>
            <th><div class="js-pagination"><?php echo $paginator->sort(__l('Status'), 'is_active'); ?></div></th>
        </tr>
        <?php
        if (!empty($languages)):
            $i = 0;
            foreach ($languages as $language):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td><?php echo $form->input('Language.'.$language['Language']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$language['Language']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
                    <td><?php echo $html->cText($language['Language']['name']);?></td>
                    <td><?php echo $html->cText($language['Language']['iso2']);?></td>
                    <td><?php echo $html->cText($language['Language']['iso3']);?></td>
                    <td><?php echo ($language['Language']['is_active']) ? __l('Active') : __l('Inactive'); ?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="5" class="notice"><?php echo __l('No Languages available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($languages)) :
        ?>
        <div class="admin-select-block">
        <div>
    		<?php echo __l('Select:'); ?>
    		<?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
    		<?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
    		<?php echo $html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Inactive'))); ?>
    		<?php echo $html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Active'))); ?>
    	</div>
    
        <div class="admin-checkbox-button">
            <?php echo $form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        </div>
        	<div class="js-pagination">
            <?php echo $this->element('paging_links'); ?>
        </div>
        <div class="hide">
            <?php echo $form->submit(__l('Submit'));  ?>
        </div>
        <?php
    endif;
    echo $form->end();
    ?>
</div>