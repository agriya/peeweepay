<?php
if (!empty($products)) :
$user_feed_link = Router::url(array(
					'controller' => 'products',
					'action' => 'index',
					'user' => $products[0]['User']['id'],
					'ext' => 'rss',
					'admin' => false
				) , true);
$logo_url = Router::url('/',true).'img/logo.png';
?>
<title>
<?php
if($this->params['named']['filter'] == 'latest'){
    echo __l('Products - Latest');
}elseif($this->params['named']['filter'] == 'trending'){
    echo __l('Products - New Trends');
}else{
    echo sprintf(__l('%s\'s Products'),$products[0]['User']['fullname']);
}
?>
</title>
<image><title><?php echo sprintf(__l('%s\'s Products'),$products[0]['User']['fullname']); ?></title>

<link><?php echo $user_feed_link; ?></link>
<url><?php echo $logo_url; ?></url>
</image>
<?php 
    foreach($products as $product) :
        $product_image = '';
		if(!empty($product['ProductPhoto'])):
			$product_image = $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false));
        endif;
        $product_image = (!empty($product_image)) ? '<p>'.$product_image.'</p>':'';
		echo $rss->item(array() , array(
            'title' => $product['Product']['title'],
            'link' => array(
                'controller' => 'products',
                'action' => 'v',
                'slug' => $product['Product']['slug'],
                'view_type' => ConstViewType::NormalView
            ) ,
            'description' => $product_image.'<p>'.$html->cHtml($product['Currency']['symbol']).' '.$html->cHtml($product['Product']['price']).'</p><p>' . $html->cHtml($html->truncate($product['Product']['description'])) . '</p> <p>seller: <b>'.$html->cHtml($html->truncate($product['User']['fullname'])) . '</b></p>',			
            'createdDate' => $html->cDateTime($product['Product']['created'], false) ,
        ));
    endforeach;
endif;
?>
