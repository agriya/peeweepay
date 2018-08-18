<?php
/* SVN FILE: $Id: app_helper.php 195 2009-03-18 06:30:14Z rajesh_04ag02 $ */
/**
 * Short description for file.
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7904 $
 * @modifiedby    $LastChangedBy: mark_story $
 * @lastmodified  $Date: 2008-12-05 22:19:43 +0530 (Fri, 05 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'Helper');
/**
 * This is a placeholder class.
 * Create the same file in app/app_helper.php
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake
 */
class AppHelper extends Helper
{
    public function formGooglemap($product = array() , $size = '320x320')
    {
        $product = !empty($product['Product']) ? $product['Product'] : $product;
        if ((!(is_array($product))) || empty($product)) {
            return false;
        }
        $color_array = array(
            array(
                'A',
                'green'
            ) ,
            array(
                'B',
                'orange'
            ) ,
            array(
                'C',
                'blue'
            ) ,
            array(
                'D',
                'yellow'
            )
        );
        $mapurl = 'http://maps.google.com/maps/api/staticmap?center=';
        $mapcenter[] = str_replace(' ', '+', $product['latitude']) . ',' . $product['longitude'];
        $mapcenter[] = 'zoom=' . (!empty($product['map_zoom_level']) ? $product['map_zoom_level'] : Configure::read('GoogleMap.static_map_zoom_level'));
        $mapcenter[] = 'size=' . $size;
        $mapcenter[] = 'markers=color:pink|label:M|' . $product['latitude'] . ',' . $product['longitude'];
        $mapcenter[] = 'sensor=false';
        return $mapurl . implode('&amp;', $mapcenter);
    }
    function makeUrl($url)
    {
        return ((preg_match("/http/", $url, $matches)) ? '' : 'http://') . $url;
    }
    function cCurrency($str, $cur, $wrap = 'span', $title = false)
    {
        $_precision = 2;
        $changed = (($r = floatval($str)) != $str);
        $rounded = (($rt = round($r, $_precision)) != $r);
        $r = $rt;
        if (is_array($cur)) {
            if (!empty($cur['locale']) && !empty($cur['format_string'])) {
                set_locale($cur['locale']);
                $currency = money_format($cur['format_string'], $r);
            } else {
                $suffix = ($cur['suffix'] == 'USD' || $cur['suffix'] == 'EUR') ? '' : (' ' . $cur['suffix']);
                $currency = $cur['prefix'] . number_format($r, $cur['decimals'], $cur['dec_point'], $cur['thousands_sep']) . $suffix;
            }
            $cur = $cur['code'];
        } else {
            $currency = number_format($r, $_precision, '.', ',');
        }
        if ($wrap) {
            if (!$title) {
                $title = Numbers_Words::toCurrency($r, 'en_US', $cur);
            }
            $r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . $currency . '</' . $wrap . '>';
        }
        return $r;
    }
	function getLanguage()
    {
        App::import('Model', 'Translation');
        $this->Translation = new Translation();
        $languages = $this->Translation->find('all', array(
            'conditions' => array(
                'Language.id !=' => 0
            ) ,
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name',
                'Language.iso2'
            ) ,
            'order' => array(
                'Language.name' => 'ASC'
            )
        ));
        $languageList = array();
        if (!empty($languages)) {
            foreach($languages as $language) {
                $languageList[$language['Language']['iso2']] = $language['Language']['name'];
            }
        }
        return $languageList;
    }

}
?>
