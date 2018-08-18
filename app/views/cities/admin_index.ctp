<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="cities index js-response">
    <h2><?php echo __l('Cities');?></h2>
    <?php echo $form->create('City', array('type' => 'get', 'class' => 'normal', 'action'=>'index')); ?>
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
            <span><?php	echo __l('Approved Records:').' '; ?> </span>
            <?php echo $html->cInt($approved); ?>
        </div>
        <div>
            <span><?php echo __l('Disapproved Records:').' '; ?></span>
            <?php echo $html->cInt($pending); ?>
        </div>
        <div>
            <span><?php echo __l('Total Records:').' '; ?> </span>
            <?php echo $html->cInt($pending + $approved); ?>
        </div>
    </div>
    <div>
        <div>
            <?php echo $html->link(__l('Add'),array('controller'=>'cities','action'=>'add'),array('title' => __l('Add New City')));?>
        </div>
        <div>
            <?php
            echo $form->create('City', array('action' => 'update','class'=>'normal')); ?>
            <?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
            <?php echo $this->element('paging_counter');?>
            <table class="list">
                <tr>
                    <th><?php echo __l('Select'); ?></th>
                    <th><?php echo __l('Actions');?></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Country'), 'Country.name', array('url'=>array('controller'=>'cities', 'action'=>'index')));?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('State'), 'State.name', array('url'=>array('controller'=>'cities', 'action'=>'index')));?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Latitude'), 'latitude');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Longitude'), 'longitude');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Timezone'), 'timezone');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('County'), 'county');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Code'), 'code');?></div></th>
                </tr>
                <?php
                if (!empty($cities)):
                    $i = 0;
                    foreach ($cities as $city):
                        $class = null;
                        if ($i++ % 2 == 0) :
                            $class = ' class="altrow"';
                        endif;
                        if($city['City']['is_approved'])  :
                            $status_class = 'js-checkbox-active';
                        else:
                            $status_class = 'js-checkbox-inactive';
                        endif;
                    ?>
                        <tr<?php echo $class;?>>
                            <td>
							<div class="actions-block">
							<div class="actions round-5-left">
							<span> <?php echo $html->link(__l('Edit'), array('action'=>'edit', $city['City']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit'))); ?></span>
						    <span><?php echo $html->link(__l('Delete'), array('action'=>'delete', $city['City']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete'))); ?></span>
							</div>
							</div>
                                <?php
                                echo $form->input('City.'.$city['City']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$city['City']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($city['City']['is_approved']):
                                        echo $html->link(__l('Approved'),array('controller'=>'cities','action'=>'update_status',$city['City']['id'],'disapprove'),array('class' =>'approve','title' => __l('Approved')));
                                    else:
                                    	echo $html->link(__l('Disapproved'),array('controller'=>'cities','action'=>'update_status',$city['City']['id'],'approve') ,array('class' =>'pending','title' => __l('Disapproved')));
                                      endif;
                                      
                                ?>
                            </td>
                            <td><?php echo $html->cText($city['Country']['name'], false);?></td>
                            <td><?php echo $html->cText($city['State']['name'], false);?></td>
                            <td><?php echo $html->cText($city['City']['name'], false);?></td>
                            <td><?php echo $html->cFloat($city['City']['latitude']);?></td>
                            <td><?php echo $html->cFloat($city['City']['longitude']);?></td>
                            <td><?php echo $html->cText($city['City']['timezone']);?></td>
                            <td><?php echo $html->cText($city['City']['county']);?></td>
                            <td><?php echo $html->cText($city['City']['code']);?></td>
                        </tr>
                    <?php
                    endforeach;
                    else:
                    ?>
                    <tr>
                        <td class="notice" colspan="10"><?php echo __l('No cities available');?></td>
                    </tr>
                    <?php
                    endif;
                    ?>
            </table>
            <?php
                if (!empty($cities)) :
                    ?>
                    <div>
                        <?php echo __l('Select:'); ?>
                        <?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                        <?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                        <?php echo $html->link(__l('Unapproved'), '#', array('class' => 'js-admin-select-pending','title' => __l('Unapproved'))); ?>
                        <?php echo $html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved','title' => __l('Approved'))); ?>
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
                endif;
            ?>
        <?
        echo $form->end();
        ?>
        </div>
    </div>
</div>