	<div class="check-information-block">
        <h2><?php echo __l('Thank you for your purchase!'); ?></h2>
        <p><?php echo sprintf(__l('Your payment of %s for').' %sx (%s) "%s"'.' '.__l('has been received.'),  $html->cCurrency($transaction['Transaction']['amount'], $transaction['Product']['Currency']) , $html->cText($transaction['Transaction']['quantity']),$html->cCurrency($transaction['Product']['price'], $transaction['Product']['Currency']),$html->cText($transaction['Product']['title'])); ?> </p>
        <p><?php echo sprintf(__l('The seller of this item,').' %s (%s)'.__l(', has received your details and will contact you as soon as possible.'),$html->cText($transaction['Product']['User']['fullname']), $html->cText($transaction['Product']['User']['email'])); ?> </p>
    </div>