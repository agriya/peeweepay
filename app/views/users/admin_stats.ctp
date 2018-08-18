<div class="users stats">
    <div>
        <h2><?php echo __l('Dashboard'); ?></h2>
        <div>
           <table class="list">
			<tr>
				<th colspan='3'>&nbsp;</th>
				<?php foreach($periods as $key => $period){ ?>
				<th>
					<?php echo $period['display']; ?>
				</th>
				<?php } ?>
			</tr>
			<?php
			foreach($models as $unique_model){ ?>
				<?php foreach($unique_model as $model => $fields){
					$aliasName = isset($fields['alias']) ? $fields['alias'] : $model;
				?>
						<?php $element = isset($fields['rowspan']) ? 'rowspan ="'.$fields['rowspan'].'"' : ''; ?>
						<?php $element .= isset($fields['colspan']) ? 'colspan ="'.$fields['colspan'].'"' : ''; ?>
						<?php if(!isset($fields['isSub'])): ?>
							<tr>
							<td class="dr sub-title" <?php echo $element;?>>
								<?php echo $fields['display']; ?>
							</td>
						<?php endif;?>
						<?php if(isset($fields['isSub'])):	?>							
							<?php if(isset($fields['display'])):	?>
								<?php $element = isset($fields['subrowspan']) ? 'rowspan ="'.$fields['subrowspan'].'"' : '';
									  $element .= isset($fields['colspan']) ? 'colspan ="'.$fields['colspan'].'"' : ''; ?>
								<td class="dr"  <?php echo $element;?>>
									<?php echo $fields['display']; ?>
								</td>
							<?php endif; ?>
							<?php if(isset($fields['display1'])):	?>
								<td class="dr">
									<?php echo $fields['display1']; ?>
								</td>
							<?php endif; ?>
						<?php endif; ?>
						<?php if(!isset($fields['rowspan'])): ?>
							<?php foreach($periods as $key => $period) { ?>
									<td>
										<?php
                                            if(empty($fields['type'])) {
                                                $fields['type'] = 'cInt';
                                            }
                                            if (!empty($fields['link'])):                                                
												$fields['link']['stat'] = $key;
												if(!empty($fields['type']) && $fields['type'] == 'cCurrency') {
													echo $html->link($html->{$fields['type']}(${$aliasName.$key}, ${$aliasName.$key}), $fields['link'], array('escape' => false, 'title' => __l('Click to View Details')));
												}else{
													echo $html->link($html->{$fields['type']}(${$aliasName.$key}), $fields['link'], array('escape' => false, 'title' => __l('Click to View Details')));
												}
											else:
                                                echo $html->{$fields['type']}(${$aliasName.$key});
                                            endif;
                                        ?>
									</td>
							<?php } ?>
							</tr>
						<?php endif; ?>

				 <?php } ?>
			<?php } ?>
			</table>
			</div>			 
</div>
</div>