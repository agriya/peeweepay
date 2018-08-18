<?php /* SVN: $Id: $ */ ?>
<div class="paymentGateways form">
<h2><?php echo __l('Edit Payment Gateway');?></h2>
<?php echo $form->create('PaymentGateway', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('name', array('label' => __l('Name')));
		echo $form->input('description', array('label' => __l('Description')));
		echo $form->input('is_test_mode', array('label' => __l('Test Mode?')));	    
		?>		
		<?php
		if($paymentGatewaySettings) {
		?>
			<div class="clearfix">
        			<div class="test-mode-left">
        			 <label for="PaymentGatewaySetting1TestModeValue"><?php echo __l('Test Mode'); ?></label>
        			</div>
        				<div class="test-mode-right">
        				<label for="PaymentGatewaySetting1LiveModeValue"><?php echo __l('Live Mode'); ?></label>
        			</div>
                </div>
		<?php $j =0; $i = 0;$z = 0;
            foreach($paymentGatewaySettings as $paymentGatewaySetting) {
				//pr($paymentGatewaySetting['PaymentGatewaySetting']['key']);
                $options['type'] = $paymentGatewaySetting['PaymentGatewaySetting']['type'];
                    $options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['test_mode_value'];
                    $options['div'] = array('id' => "setting-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
                    if($options['type'] == 'checkbox' && $options['value']):
                        $options['checked'] = 'checked';
                    endif;
                    if($options['type'] == 'select'):
                        $selectOptions = explode(',', $paymentGatewaySetting['PaymentGatewaySetting']['options']);
                        $paymentGatewaySetting['PaymentGatewaySetting']['options'] = array();
                        if(!empty($selectOptions)):
                            foreach($selectOptions as $key => $value):
                                if(!empty($value)):
                                    $paymentGatewaySetting['PaymentGatewaySetting']['options'][trim($value)] = trim($value);
                                endif;
                            endforeach;
                        endif;
                        $options['options'] = $paymentGatewaySetting['PaymentGatewaySetting']['options'];
                    endif;
    			$options['label'] = false;
    			if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description']) && empty($options['after'])):
					$options['help'] = "{$paymentGatewaySetting['PaymentGatewaySetting']['description']}";
				else:
					$options['help'] = "";				
    			endif;
    			?>
				<?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'payee_account' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'receiver_emails'):?>
				
					<?php if($z == 0):?>
					<fieldset class="form-block round-5">
						<legend class="round-5">
							<?php echo __l('Payee Details'); ?>
						</legend>  
						<?php endif;?>				
						<div class="clearfix test-mode-content">
							<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
							<div class="test-mode-left">
							<?php
								echo $form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options);
							?>
							</div>
							<div class="test-mode-right">
								<?php
								$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
								echo $form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
							?>
							</div>
						</div>	
					<?php if($z == 0):?>
					</fieldset>
					<?php endif;?>
				<?php $z++;?>
				<?php endif;?>
				
				<?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_AppID' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_Signature' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_Password' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'adaptive_API_UserName'):?>
				<?php if($j == 0):?>
				   <fieldset class="form-block round-5">
						<legend class="round-5"><?php echo __l('Adaptive Payment Details'); ?></legend>  
						<div class="info-details">
							<p><?php echo __l('Adaptive used to send money to seller.');?></p>
							<p><?php echo __l('Create Adaptive API from paypal profile. Refer').' ';?><a href='https://www.paypal.com/in/cgi-bin/webscr'>https://www.paypal.com/in/cgi-bin/webscr</a></p>
						</div>
					<?php endif;?>
						<div class="clearfix test-mode-content">
							<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
							<div class="test-mode-left">
							<?php
								echo $form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options);
							?>
							</div>
							<div class="test-mode-right">
								<?php
								$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
								echo $form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
							?>
							</div>
						</div>
					<?php if($j == 3):?>
					</fieldset>				
					<?php endif;?>
				<?php $j++;?>
				<?php endif;?>
                <?php
            }
        }
	?>
<?php echo $form->end(__l('Update'));?>
</div>