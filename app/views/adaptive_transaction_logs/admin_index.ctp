<?php /* SVN: $Id: $ */ ?>
<div class="adaptiveTransactionLogs index">
<h2><?php echo __l('Adaptive Transaction Logs');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>        
        <th><?php echo $paginator->sort('id');?></th>
        <th><?php echo $paginator->sort(__l('Created'), 'created');?></th>
		<th><?php echo $paginator->sort(__l('Transaction'), 'transaction_id');?></th>
        <th><?php echo $paginator->sort(__l('Amount'), 'amount');?></th>
        <th><?php echo $paginator->sort(__l('Currency Code'), 'currency_code');?></th>
        <th><?php echo $paginator->sort(__l('Email'), 'email');?></th>
        <th><?php echo $paginator->sort(__l('Primary'), 'primary');?></th>
        <th><?php echo $paginator->sort(__l('Invoice'), 'invoice_id');?></th>
        <th><?php echo $paginator->sort(__l('Refunded Amount'), 'refunded_amount');?></th>
        <th><?php echo $paginator->sort(__l('Pending Refund'), 'pending_refund');?></th>
        <th><?php echo $paginator->sort(__l('Sender Transaction'), 'sender_transaction_id');?></th>
        <th><?php echo $paginator->sort(__l('Sender Transaction Status'), 'sender_transaction_status');?></th>
        <th><?php echo $paginator->sort(__l('Timestamp'), 'timestamp');?></th>
        <th><?php echo $paginator->sort(__l('Acknowledgment'), 'ack');?></th>
        <th><?php echo $paginator->sort(__l('Correlation'), 'correlation_id');?></th>
        <th><?php echo $paginator->sort(__l('Build'), 'build');?></th>
        <th><?php echo $paginator->sort(__l('Sender Email'), 'sender_email');?></th>
        <th><?php echo $paginator->sort(__l('Status'), 'status');?></th>
        <th><?php echo $paginator->sort(__l('Tracking'), 'tracking_id');?></th>
        <th><?php echo $paginator->sort(__l('Pay Key'), 'pay_key');?></th>
        <th><?php echo $paginator->sort(__l('Action Type'), 'action_type');?></th>
        <th><?php echo $paginator->sort(__l('Fees Payer'), 'fees_payer');?></th>
		<th><?php echo $paginator->sort(__l('Memo'), 'memo');?></th>
        <th><?php echo $paginator->sort(__l('Reverse All Parallel Payments On Error'), 'reverse_all_parallel_payments_on_error');?></th>
        <th><?php echo $paginator->sort(__l('Refund Status'), 'refund_status');?></th>
        <th><?php echo $paginator->sort(__l('Refund Net Amount'), 'refund_net_amount');?></th>
        <th><?php echo $paginator->sort(__l('Refund Fee Amount'), 'refund_fee_amount');?></th>
        <th><?php echo $paginator->sort(__l('Refund Gross Amount'), 'refund_gross_amount');?></th>
        <th><?php echo $paginator->sort(__l('Total Of Alll Refunds'), 'total_of_alll_refunds');?></th>
        <th><?php echo $paginator->sort(__l('Refund Has Become Full'), 'refund_has_become_full');?></th>
        <th><?php echo $paginator->sort(__l('Encryoted Refund Transaction'), 'encrypted_refund_transaction_id');?></th>
        <th><?php echo $paginator->sort(__l('Refund Transaction Status'), 'refund_transaction_status');?></th>
    </tr>
<?php
if (!empty($adaptiveTransactionLogs)):

$i = 0;
foreach ($adaptiveTransactionLogs as $adaptiveTransactionLog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
		<div class="actions-block">
				<div class="actions round-5-left"><span><?php echo $html->link(__l('View'), array('controller' => 'adaptive_transaction_logs', 'action' => 'view', $adaptiveTransactionLog['AdaptiveTransactionLog']['id']), array('class' => 'view', 'title' => __l('View')));?></span>
				</div>
				</div>
				<?php echo $html->cInt($adaptiveTransactionLog['AdaptiveTransactionLog']['id']);?></td>
		<td><?php echo $html->cDateTimeHighlight($adaptiveTransactionLog['AdaptiveTransactionLog']['created']);?></td>		
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['transaction_id']);?></td>
		<td><?php echo $html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['amount'],$adaptiveTransactionLog['AdaptiveTransactionLog']['currency_code']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['currency_code']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['email']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['primary']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['invoice_id']);?></td>
		<td><?php echo $html->cFloat($adaptiveTransactionLog['AdaptiveTransactionLog']['refunded_amount']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['pending_refund']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_transaction_id']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_transaction_status']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['timestamp']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['ack']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['correlation_id']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['build']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_email']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['status']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['tracking_id']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['pay_key']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['action_type']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['fees_payer']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['memo']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['reverse_all_parallel_payments_on_error']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_status']);?></td>
		<td><?php echo $html->cFloat($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_net_amount']);?></td>
		<td><?php echo $html->cFloat($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_fee_amount']);?></td>
		<td><?php echo $html->cFloat($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_gross_amount']);?></td>
		<td><?php echo $html->cFloat($adaptiveTransactionLog['AdaptiveTransactionLog']['total_of_alll_refunds']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_has_become_full']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['encrypted_refund_transaction_id']);?></td>
		<td><?php echo $html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_transaction_status']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="36" class="notice"><?php echo __l('No Adaptive Transaction Logs available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($adaptiveTransactionLogs)) {
    echo $this->element('paging_links');
}
?>
</div>