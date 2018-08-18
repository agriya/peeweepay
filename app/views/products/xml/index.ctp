<?php
$keyword = (!empty($this->data['Product']['q'])) ? $this->data['Product']['q'] : '';
$count = count($products);
echo '<'.Inflector::slug(strtolower(configure::read('site.name'))).'>';
echo '<keywords>';
echo '<keyword>' . $keyword . '</keyword>';
echo '</keywords>';
echo '<count>' . $count . '</count>';
echo '<more_results></more_results>';
echo '<more_srch_time></more_srch_time>';
echo '<results>';
if (!empty($products)):
    foreach($products as $product):
        $product_url = Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $product['Product']['slug'],
            'view_type' => ConstViewType::NormalView,
            'admin' => false
        ) , true);
        echo '<result>';        
        echo '<currency>' . $product['Currency']['code'] . '</currency>';
        echo '<title><![CDATA[' . $product['Product']['title'] . ']]></title>';
        echo '<time>' . strtotime($product['Product']['created']) . '</time>';
        echo '<description><![CDATA[' . $product['Product']['description'] . ']]></description>';
        echo '<longitude>' . $product['Product']['longitude'] . '</longitude>';        
        echo '<has_picture>' . (!empty($product['Product']['product_photo_count'])) ? '1' : '0' . '</has_picture>';
        echo '<country>' . $product['User']['Country']['name'] . '</country>';
        echo '<price>' . $product['Product']['price'] . '</price>';
        echo '<srch_time>1281484898563</srch_time>';
        echo '<shortcode>' . $product['Product']['slug'] . '</shortcode>';
        echo '<latitude>' . $product['Product']['latitude'] . '</latitude>';
        echo '<tags>';
        if (!empty($product['ProductTag'])):
            foreach($product['ProductTag'] as $product_tag):
                echo '<tag>' . $product_tag['name'] . '</tag>';
            endforeach;
        endif;
        echo '</tags>';
        echo '<has_file>' . $product['Product']['is_file'] . '</has_file>';
        echo '<tiny_url>' . $product_url . '</tiny_url>';
        echo '<link>' . $product_url . '</link>';
        echo '<img></img>';
        echo '<qr_code><![CDATA[' . 'http://chart.apis.google.com/chart?chs=' . Configure::read('qr.width') . 'x' . Configure::read('qr.height') . '&cht=qr&chl=' . $product_url . '&choe=UTF-8&chld=L|2' . ']]></qr_code>';
        echo '<seller_md5></seller_md5>';
        echo '<seller>' . $product['User']['fullname'] . '</seller>';
        echo '</result>';
    endforeach;
endif;
echo '</results>';
echo '</'.Inflector::slug(strtolower(configure::read('site.name'))).'>';
?>