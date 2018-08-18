<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="states index js-response">
    <h2><?php echo __l('States');?></h2>
    <?php echo $form->create('State', array('type' => 'get', 'class' => 'normal', 'action'=>'index')); ?>
	<div class="filter-section">
		<div>
			<?php echo $form->input('filter_id',array('empty' => __l('Please Select'))); ?>
            <?php echo $form->input('q', array('label' => __l('Keyword'))); ?>
        </div>
		<div>
			<?php echo $form->submit(__l('Search'));?>
		</div>
	</div>
	<?php echo $form->end(); ?>
    <div  class="record-info">
        <div>
            <span><?php echo __l('Approved Records:').' '; ?> </span>
            <?php echo $html->cInt($approved); ?>
        </div>
        <div>
            <span><?php	echo __l('Disapproved Records:').' '; ?></span>
            <?php echo $html->cInt($pending); ?>
        </div>
        <div>
            <span><?php	echo __l('Total Records:').' '; ?></span>
            <?php echo $html->cInt($pending + $approved); ?>
        </div>
    </div>
    <div>
    <div>
        <?php echo $html->link(__l('Add'),array('controller'=>'states','action'=>'add'),array('title' => __l('Add New State')));?>
    </div>
    <div>
        <?php
        echo $form->create('State' , array('action' => 'update','class'=>'normal'));?>
        <?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
        <?php echo $this->element('paging_counter');?>
        <div class="overflow-block">
        <table class="list">
            <tr>
                <th><?php echo __l('Select'); ?></th>
                <th><?php echo __l('Actions');?></th>
                <th><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'Country.name');?></div></th>
                <th><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
                <th><div class="js-pagination"><?php echo $paginator->sort(__l('Code'), 'code');?></div></th>
                <th><div class="js-pagination"><?php echo $paginator->sort(__l('Adm1code'), 'adm1code');?></div></th>
            </tr>
            <?php
                if (!empty($states)):
                $i = 0;
                    foreach ($states as $state):
                        $class = null;
                        if ($i++ % 2 == 0) :
                            $class = ' class="altrow"';
                        endif;
                        if($state['State']['is_approved'])  :
                            $status_class = 'js-checkbox-active';
                        else:
                            $status_class = 'js-checkbox-inactive';
                        endif;
                        ?>
                        <tr<?php echo $class;?>>
                            <td>
							<div class="actions-block">
		<div class="actions round-5-left"><span>
							 <?php echo $html->link(__l('Edit'), array('action'=>'edit', $state['State']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span><span><?php echo $html->link(__l('Delete'), array('action'=>'delete', $state['State']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></div></div>
                                <?php
                                    echo $form->input('State.'.$state['State']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$state['State']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
                                ?>
                            </td>
                            <td>
                                <?php if($state['State']['is_approved']):?>
                                <?php echo $html->link(__l('Approved'),array('controller'=>'states','action'=>'update_status',$state['State']['id'],'disapprove'),array('class' =>'approve','title' => __l('Approved')));?>
                                <?php else:?>
                                <?php echo $html->link(__l('Disapproved'),array('controller'=>'states','action'=>'update_status',$state['State']['id'],'approve') ,array('class' =>'pending','title' => __l('Disapproved')));?>
                                <?php endif; ?>
                               
                            </td>
                            <td><?php echo $html->cText($state['Country']['name']);?></td>
                            <td><?php echo $html->cText($state['State']['name']);?></td>
                            <td><?php echo $html->cText($state['State']['code']);?></td>
                            <td><?php echo $html->cText($state['State']['adm1code']);?></td>
                        </tr>
                        <?php
                    endforeach;
            else:
                ?>
                <tr>
                    <td class="notice" colspan="6"><?php echo __l('No states available');?></td>
                </tr>
                <?php
            endif;
            ?>
        </table>
        </div>
        <?php
         if (!empty($states)) : ?>
            <div>
                <?php echo __l('Select:'); ?>
                <?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title'=>__l('All'))); ?>
                <?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title'=>__l('None'))); ?>
                <?php echo $html->link(__l('Unapproved'), '#', array('class' => 'js-admin-select-pending','title'=>__l('Unapproved'))); ?>
                <?php echo $html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved','title'=>__l('Approved'))); ?>
            </div>
            <div class="js-pagination">
            <?php  echo $this->element('paging_links'); ?>
            </div>
            <div>
                 <?php echo $form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
            <div class="hide">
                <?php echo $form->submit(__l('Submit'));  ?>
            </div>
            <?php
         endif; ?>
        <?php echo $form->end();?>
        </div>
    </div>
</div>