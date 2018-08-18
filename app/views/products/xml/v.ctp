<?php
$product_url = Router::url(array(
    'controller' => 'products',
    'action' => 'v',
    'slug' => $product['Product']['slug'],
    'view_type' => ConstViewType::NormalView,
    'admin' => false
) , true);
$embed_url = Router::url(array(
    'controller' => 'products',
    'action' => 'v',
    'slug' => $product['Product']['slug'],
    'view_type' => ConstViewType::NormalView,
    'admin' => false
) , true);
$qr_url = 'http://chart.apis.google.com/chart?chs=' . Configure::read('qr.width') . 'x' . Configure::read('qr.height') . '&cht=qr&chl=' . $product_url . '&choe=UTF-8&chld=L|2';
echo '<'.Inflector::slug(strtolower(configure::read('site.name'))).'>';
echo '<shortcode>' . $product['Product']['slug'] . '</shortcode>';
echo '<link>' . $product_url . '</link>';
echo '<tiny_url>' . $product_url . '</tiny_url>';
echo '<title><![CDATA[' . $product['Product']['title'] . ']]></title>';
echo '<description><![CDATA[' . $product['Product']['description'] . ']]></description>';
echo '<short_description><![CDATA[' . $product['Product']['description'] . ']]></short_description>';
echo '<price>' . $product['Product']['price'] . '</price>';
echo '<currency>' . $product['Currency']['code'] . '</currency>';
echo '<seller>' . $product['User']['fullname'] . '</seller>';
if (!empty($product['Product']['is_display_quantity'])):
    if ($product['Product']['quantity'] > 0):
        echo '<seller_items>' . $product['Product']['quantity'] . '</seller_items>';
    else:
        echo '<seller_items>' . __l('unlimited') . '</seller_items>';
    endif;
endif;
echo '<seller_md5></seller_md5>';
echo '<embed_button>' . $embed_url . '</embed_button>';
echo '<qr_code><![CDATA[' . $qr_url . ']]></qr_code>';
echo '<country>' . $product['User']['Country']['name'] . '</country>';
echo '<time>' . strtotime($product['Product']['created']) . '</time>';
echo '<micro_time>'.strtotime($product['Product']['created']).'</micro_time>';
$has_picture = (!empty($product['Product']['product_photo_count'])) ? '1' : '0';
echo '<has_picture>' . $has_picture . '</has_picture>';
echo '<show_quantity>' . $product['Product']['is_display_quantity'] . '</show_quantity>';
echo '<show_views>' . $product['Product']['is_display_page_views'] . '</show_views>';
echo '<address_required>' . $product['Product']['is_shipment_cost_required'] . '</address_required>';
echo '<latitude>' . $product['Product']['latitude'] . '</latitude>';
echo '<longitude>' . $product['Product']['longitude'] . '</longitude>';
$status = (!empty($product['Product']['is_verified'])) ? 'OK' : '';
echo '<status>' . $status . '</status>';
echo '<has_file>' . $product['Product']['is_file'] . '</has_file>';
echo '<tags>';
if (!empty($product['ProductTag'])):
    foreach($product['ProductTag'] as $product_tag):
        echo '<tag>' . $product_tag['name'] . '</tag>';
    endforeach;
endif;
echo '</tags>';
echo '<image_1><sizes><xxs></xxs><xs></xs><s></s><m></m><l></l><xl></xl><xxl></xxl></sizes><title></title></image_1><image_2></image_2><image_3></image_3>';
echo '<last_update>' . strtotime($product['Product']['modified']) . '</last_update>';
if (!empty($product['Product']['is_display_quantity'])):
    if ($product['Product']['quantity'] > 0):
        echo '<quantity>' . $product['Product']['quantity'] . '</quantity>';
    else:
        echo '<quantity>' . __l('unlimited') . '</quantity>';
    endif;
endif;
echo '<page_views>' . $product['Product']['product_view_count'] . '</page_views>';
echo '</'.Inflector::slug(strtolower(configure::read('site.name'))).'>';
?>