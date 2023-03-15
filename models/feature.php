<?php

/**
 * Created by PhpStorm.
 * User: Ngoc Tu
 * Date: 5/16/2016
 * Time: 11:57 AM
 */

// No direct access
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusContentModelFeature extends JModelList
{

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();
        $input = $app->input;

        $this->setState('filter.catid', null);
        $this->setState('filter.content_id', null);
//        $this->setState('list.music_order', 'rdate');

        parent::populateState($ordering, $direction);

    }
    protected function getStoreId($id = '')
    {
        // Add the list state to the store id.
        $id .= ':' . $this->getState('list.start');
        $id .= ':' . $this->getState('list.limit');
        $id .= ':' . $this->getState('filter.content_id');
        $id .= ':' . serialize($this->getState('filter.catid'));
//        $id .= ':' . $this->getState('list.music_order');
        $id .= ':' . $this->getState('list.ordering');
        $id .= ':' . $this->getState('list.direction');

        return md5($this->context . ':' . $id);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('DISTINCT d.*');
        $query->from($db->quoteName('#__tz_portfolio_plus_addon_data').' AS d');
//        $query -> join('INNER', '#__tz_portfolio_plus_content AS c ON FIND_IN_SET(c.id, substring_index(substring_index(d.value, '
//            .$db -> quote('"contentid":"').', -1), '.$db -> quote('"').',1)'.')');
        $query -> join('INNER', '#__tz_portfolio_plus_content AS c ON c.id = d.content_id');
        $query ->join('INNER', '#__tz_portfolio_plus_content_category_map AS cm ON cm.contentid = c.id');
        $query ->join('INNER', '#__tz_portfolio_plus_categories AS cc ON cc.id = cm.catid');
        $query -> join('INNER', '#__tz_portfolio_plus_extensions AS e ON e.id = d.extension_id');

        if($addon = TZ_Portfolio_PlusPluginHelper::getPlugin('content', 'feature')) {
            $query->where('d.extension_id =' .(int) $addon -> id);
        }
        $query -> where('d.element ='.$db -> quote('feature'));

//        $query->where('FIND_IN_SET( ' . $this->getState('filter.contentid')
//            . ', substring_index( substring_index( d.value, '
//            . $db->quote('"contentid":"') . ', -1 ) , '
//            . $db->quote('","') . ', 1 ) ) >0');
        if($content_id = $this -> getState('filter.content_id')){
            $query -> where('d.content_id = '.$content_id);
        }
        $query -> where('d.published = 1');

        if($catid = $this -> getState('filter.catid', null)) {
            if(is_array($catid)){
                $query -> where('cc.id IN('.implode(',', $catid).')');
            }else{
                $query -> where('cc.id = '.(int) $catid);
            }
        }
//        var_dump($query -> dump());

        return $query;
    }

    public function getItems()
    {
        if ($items = parent::getItems()) {
            $data = array();
            foreach ($items as &$item) {
                if ($item->value && is_string($item->value)) {
                    $value = json_decode($item->value);
//                    if (isset($value->file_names) && $value->file_names) {
//                        $value->file_names = explode('|', $value->file_names);
//                    }
                    $item->value = $value;
                }
            }
            return $items;
        }
        return false;
    }

    public function styleInit($item) {
        $doc = JFactory::getDocument();
        $addon_id = '.tzportfolio-addon-feature-container';
        $css    =   '';
        $title_margin_top = (isset($item->title_margin_top) && $item->title_margin_top) ? $item->title_margin_top : '';
        $title_margin_bottom	= (isset($item->title_margin_bottom) && $item->title_margin_bottom) ? $item->title_margin_bottom : '';
        $title_color	= (isset($item->title_color) && $item->title_color) ? $item->title_color : '';

        $title_style    =   '';
        if (isset($item->title_font) && $item->title_font) {
            $title_style     .=      TZ_Portfolio_PlusContentHelper::font_style($item->title_font);
        }
        if ($title_margin_top) {
            $title_style    .=  'margin-top:'.$title_margin_top.'px;';
        }
        if ($title_margin_bottom) {
            $title_style    .=  'margin-bottom:'.$title_margin_bottom.'px;';
        }
        if ($title_color) {
            $title_style    .=  'color:'.$title_color.';';
        }

        if($title_style) {
            $css .= $addon_id . ' .tz-section-header .tz-addon-section-title {';
            $css .= $title_style;
            $css .= '}';
        }

        $text_style = '';

        if (isset($item->feature_description_font) && $item->feature_description_font) {
            $text_style     .=      TZ_Portfolio_PlusContentHelper::font_style($item->feature_description_font);
        }
        $text_style	.= (isset($item->feature_description_color) && $item->feature_description_color) ? 'color:'.$item->feature_description_color.';' : '';
        $text_style_sm   =   '';
        $text_style_xs   =   '';

        if (isset($item->feature_description_padding) && $item->feature_description_padding) {
            $padding        =   TZ_Portfolio_PlusContentHelper::padding_margin($item->feature_description_padding, 'padding');
            if ($padding) {
                $text_style      .=  $padding->md;
                $text_style_sm   .=  $padding->sm;
                $text_style_xs   .=  $padding->xs;
            }
        }

        if($text_style) {
            $css .= $addon_id . ' .tz-section-header {';
            $css .= $text_style;
            $css .= '}';
        }
        if($text_style_sm) {
            $css .= '@media (min-width: 768px) and (max-width: 991px) {';
            if(!empty($text_style_sm)) {
                $css .= $addon_id . ' .tz-section-header {';
                $css .= $text_style_sm;
                $css .= '}';
            }
            $css .= '}';
        }

        if($text_style_xs) {
            $css .= '@media (max-width: 767px) {';
            if(!empty($text_style_xs)) {
                $css .= $addon_id . ' .tz-section-header {';
                $css .= $text_style_xs;
                $css .= '}';
            }
            $css .= '}';
        }
        $doc->addStyleDeclaration($css);
        if (isset($item->tzportfolio_addon_features) && is_object($item->tzportfolio_addon_features)) :
            $doc->addStyleSheet(JUri::root().'/media/tz_portfolio_plus/icomoon/style.css');
            foreach ($item->tzportfolio_addon_features as $key => $feature_item) :
                $addon_id = '#feature-addon-' . $key;
                //icon css
                $icon_color	= (isset($feature_item->feature_icon_color) && $feature_item->feature_icon_color) ? $feature_item->feature_icon_color : '';
                $icon_size = (isset($feature_item->feature_icon_size) && $feature_item->feature_icon_size) ? $feature_item->feature_icon_size : '';
                $icon_border_color = (isset($feature_item->feature_icon_border_color) && $feature_item->feature_icon_border_color) ? $feature_item->feature_icon_border_color : '';
                $icon_background = (isset($feature_item->feature_icon_background) && $feature_item->feature_icon_background) ? $feature_item->feature_icon_background : '';
                $icon_margin_top = (isset($feature_item->feature_icon_margin_top) && $feature_item->feature_icon_margin_top) ? $feature_item->feature_icon_margin_top : '';
                $icon_margin_bottom	= (isset($feature_item->feature_icon_margin_bottom) && $feature_item->feature_icon_margin_bottom) ? $feature_item->feature_icon_margin_bottom : '';
                $icon_padding = (isset($feature_item->feature_icon_padding) && $feature_item->feature_icon_padding) ? $feature_item->feature_icon_padding : '';
                $icon_border_width = (isset($feature_item->feature_icon_border_width) && $feature_item->feature_icon_border_width) ? $feature_item->feature_icon_border_width : '';
                $icon_border_radius	= (isset($feature_item->feature_icon_border_radius) && $feature_item->feature_icon_border_radius) ? $feature_item->feature_icon_border_radius : '';
                $feature_type = (isset($feature_item->feature_type) && $feature_item->feature_type) ? $feature_item->feature_type : 'icon';

                $feature_image = (isset($feature_item->feature_image) && $feature_item->feature_image) ? $feature_item->feature_image : '';
                $icon_name = (isset($feature_item->feature_icon) && $feature_item->feature_icon) ? $feature_item->feature_icon : '';
                $title_position = (isset($feature_item->feature_position) && $feature_item->feature_position) ? $feature_item->feature_position : '';
                $title_margin_top = (isset($feature_item->title_margin_top) && $feature_item->title_margin_top) ? $feature_item->title_margin_top : '';
                $title_margin_bottom	= (isset($feature_item->title_margin_bottom) && $feature_item->title_margin_bottom) ? $feature_item->title_margin_bottom : '';
                $title_color	= (isset($feature_item->title_color) && $feature_item->title_color) ? $feature_item->title_color : '';

                $meta_margin_top = (isset($feature_item->meta_margin_top) && $feature_item->meta_margin_top) ? $feature_item->meta_margin_top : '';
                $meta_margin_bottom	= (isset($feature_item->meta_margin_bottom) && $feature_item->meta_margin_bottom) ? $feature_item->meta_margin_bottom : '';
                $meta_color	= (isset($feature_item->meta_color) && $feature_item->meta_color) ? $feature_item->meta_color : '';

                //Divider
                $divider_color	= (isset($feature_item->divider_color) && $feature_item->divider_color) ? $feature_item->divider_color : '';
                $divider_width	= (isset($feature_item->divider_width) && $feature_item->divider_width) ? $feature_item->divider_width : '';

                //Css start
                $css = '';

                $title_style    =   '';
                if (isset($feature_item->title_font) && $feature_item->title_font) {
                    $title_style     .=      TZ_Portfolio_PlusContentHelper::font_style($feature_item->title_font);
                }
                if ($title_margin_top) {
                    $title_style    .=  'margin-top:'.$title_margin_top.'px;';
                }
                if ($title_margin_bottom) {
                    $title_style    .=  'margin-bottom:'.$title_margin_bottom.'px;';
                }
                if ($title_color) {
                    $title_style    .=  'color:'.$title_color.';';
                }

                if($title_style) {
                    $css .= $addon_id . ' .tz-feature-box-title {';
                    $css .= $title_style;
                    $css .= '}';
                }

                $divider_style    =   '';
                if ($divider_color) {
                    $divider_style    .=  'border-color:'.$divider_color.';';
                }
                if ($divider_width) {
                    $divider_style    .=  'border-width:'.$divider_width.'px;';
                }
                if($divider_style) {
                    $css .= $addon_id . ' .tz-feature-addon-divider > hr {';
                    $css .= $divider_style;
                    $css .= '}';
                }

                $meta_style    =   '';
                if (isset($feature_item->meta_font) && $feature_item->meta_font) {
                    $meta_style     .=      TZ_Portfolio_PlusContentHelper::font_style($feature_item->meta_font);
                }
                if ($meta_margin_top) {
                    $meta_style    .=  'margin-top:'.$meta_margin_top.'px;';
                }
                if ($meta_margin_bottom) {
                    $meta_style    .=  'margin-bottom:'.$meta_margin_bottom.'px;';
                }
                if ($meta_color) {
                    $meta_style    .=  'color:'.$meta_color.';';
                }

                if($meta_style) {
                    $css .= $addon_id . ' .tz-feature-box-meta {';
                    $css .= $meta_style;
                    $css .= '}';
                }

                $text_style = '';

                if (isset($feature_item->feature_content_font) && $feature_item->feature_content_font) {
                    $text_style     .=      TZ_Portfolio_PlusContentHelper::font_style($feature_item->feature_content_font);
                }

                $content_style = (isset($feature_item->feature_content_background) && $feature_item->feature_content_background) ? "background-color: " . $feature_item->feature_content_background . ";" : "";
                $content_style_sm   =   '';
                $content_style_xs   =   '';

                if (isset($feature_item->feature_content_padding) && $feature_item->feature_content_padding) {
                    $padding        =   TZ_Portfolio_PlusContentHelper::padding_margin($feature_item->feature_content_padding, 'padding');
                    if ($padding) {
                        $content_style      .=  $padding->md;
                        $content_style_sm   .=  $padding->sm;
                        $content_style_xs   .=  $padding->xs;
                    }
                }

                $image_margin = '';
                $image_margin_sm = "";
                $image_margin_xs = "";
                if (isset($feature_item->feature_image_margin) && trim($feature_item->feature_image_margin)) {
                    $margin        =   TZ_Portfolio_PlusContentHelper::padding_margin($feature_item->feature_image_margin, 'margin');
                    if ($margin) {
                        $image_margin       .=  $margin->md;
                        $image_margin_sm    .=  $margin->sm;
                        $image_margin_xs    .=  $margin->xs;
                    }
                }

                if($text_style) {
                    $css .= $addon_id . ' .tz-addon-text {';
                    $css .= $text_style;
                    $css .= '}';
                }
                if(!empty($content_style)) {
                    $css .= $addon_id . ' .tz-media-content {';
                    $css .= $content_style;
                    $css .= '}';
                }

                if($content_style_sm) {
                    $css .= '@media (min-width: 768px) and (max-width: 991px) {';
                    if(!empty($content_style_sm)) {
                        $css .= $addon_id . ' .tz-media-content {';
                        $css .= $content_style_sm;
                        $css .= '}';
                    }
                    $css .= '}';
                }

                if($content_style_xs) {
                    $css .= '@media (max-width: 767px) {';
                    if(!empty($content_style_xs)) {
                        $css .= $addon_id . ' .tz-media-content {';
                        $css .= $content_style_xs;
                        $css .= '}';
                    }
                    $css .= '}';
                }

                if($feature_type == 'icon') {
                    if($icon_name) {
                        $style = 'display:inline-block;text-align:center;';
                        $style_sm = '';
                        $style_xs = '';


                        $icon_margin_tp = ($icon_margin_top) ? 'margin-top:' . (int) $icon_margin_top . 'px;' : '';

                        $icon_margin_btm = ($icon_margin_bottom) ? 'margin-bottom:' . (int) $icon_margin_bottom . 'px;' : '';

                        $icon_padding_md = '';
                        $icon_padding_sm = '';
                        $icon_padding_xs = '';
                        if ($icon_padding) {
                            $padding        =   TZ_Portfolio_PlusContentHelper::padding_margin($icon_padding,'padding');
                            if ($padding) {
                                $icon_padding_md        .=  $padding->md;
                                $icon_padding_sm        .=  $padding->sm;
                                $icon_padding_xs        .=  $padding->xs;
                            }
                        }

                        $style .= $icon_padding_md;
                        $style_sm .= $icon_padding_sm;
                        $style_xs .= $icon_padding_xs;

                        $style .= ($icon_color) ? 'color:' . $icon_color  . ';' : '';
                        $style .= ($icon_background) ? 'background-color:' . $icon_background  . ';' : '';
                        $style .= ($icon_border_color) ? 'border-style:solid;border-color:' . $icon_border_color  . ';' : '';

                        $style .= ($icon_border_width) ? 'border-width:' . (int) $icon_border_width . 'px;' : 'border-width:0px;';

                        $style .= ($icon_border_radius) ? 'border-radius:' . (int) $icon_border_radius  . 'px;' : '';

                        $font_size 	= (isset($icon_size) && $icon_size) ? 'font-size:' . (int) $icon_size . 'px;width:' . (int) $icon_size . 'px;height:' . (int) $icon_size . 'px;line-height:' . (int) $icon_size . 'px;' : '';


                        if($icon_margin_tp || $icon_margin_btm) {
                            $css .= $addon_id . ' .tz-icon {';
                            $css .= $icon_margin_tp;
                            $css .= $icon_margin_btm;
                            $css .= '}';
                        }

                        if($style) {
                            $css .= $addon_id . ' .tz-icon .tz-icon-container {';
                            $css .= $style;
                            $css .= '}';
                        }

                        if($font_size) {
                            $css .= $addon_id . ' .tz-icon .tz-icon-container > i {';
                            $css .= $font_size;
                            $css .= '}';
                        }
                        if(!empty($style_sm)){
                            $css .= '@media (min-width: 768px) and (max-width: 991px) {';
                            if($style_sm) {
                                $css .= $addon_id . ' .tz-icon .tz-icon-container {';
                                $css .= $style_sm;
                                $css .= '}';
                            }
                            $css .= '}';
                        }

                        if(!empty($style_xs) ){
                            $css .= '@media (max-width: 767px) {';
                            if($style_xs) {
                                $css .= $addon_id . ' .tz-icon .tz-icon-container {';
                                $css .= $style_xs;
                                $css .= '}';
                            }
                            $css .= '}';
                        }
                    }
                }
                if($feature_image && $feature_type =='image') {
                    $img_style = 'display:block;';

                    if($img_style) {
                        $css .= $addon_id . ' .tz-img-container {';
                        $css .= $img_style;
                        $css .= '}';
                    }
                    if(!empty($image_margin) && ($title_position == 'left' || $title_position == 'right')){
                        $css .= $addon_id . ' .tz-media .pull-left, '. $addon_id .' .tz-media .pull-right {';
                        $css .= $image_margin;
                        $css .= '}';
                    }
                    if(!empty($image_margin) && ($title_position == 'after' || $title_position == 'before')) {
                        $css .= $addon_id . ' .tz-img-container {';
                        $css .= $image_margin;
                        $css .= '}';
                    }
                }

                //Button style
                $button_color = (isset($feature_item->feature_button_color) && $feature_item->feature_button_color) ? 'color:'.$feature_item->feature_button_color.';' : '';
                $button_color_hover = (isset($feature_item->feature_button_color_hover) && $feature_item->feature_button_color_hover) ? 'color:'.$feature_item->feature_button_color_hover.';' : '';
                $button_background_color = (isset($feature_item->feature_button_background) && $feature_item->feature_button_background) ? 'background-color:'.$feature_item->feature_button_background.';' : '';
                $button_background_color_hover = (isset($feature_item->feature_button_background_hover) && $feature_item->feature_button_background_hover) ? 'background-color:'.$feature_item->feature_button_background_hover.';' : '';
                $button_border_radius = (isset($feature_item->feature_button_border_radius) && $feature_item->feature_button_border_radius != '') ? 'border-radius:'.$feature_item->feature_button_border_radius.';' : '';
                $button_style = '';

                if (isset($feature_item->feature_button_font) && $feature_item->feature_button_font) {
                    $button_style       .=      TZ_Portfolio_PlusContentHelper::font_style($feature_item->feature_button_font);
                }

                //Button Margin

                $button_margin = '';
                $button_margin_sm = "";
                $button_margin_xs = "";
                if (isset($feature_item->feature_button_margin) && trim($feature_item->feature_button_margin)) {
                    $margin        =   TZ_Portfolio_PlusContentHelper::padding_margin($feature_item->feature_button_margin,'margin');
                    if ($margin) {
                        $button_margin       .=  $margin->md;
                        $button_margin_sm    .=  $margin->sm;
                        $button_margin_xs    .=  $margin->xs;
                    }
                }

                //Button Padding
                $button_padding = '';
                $button_padding_sm = '';
                $button_padding_xs = '';
                if (isset($feature_item->feature_button_padding) && trim($feature_item->feature_button_padding)) {
                    $padding        =   TZ_Portfolio_PlusContentHelper::padding_margin($feature_item->feature_button_padding, 'padding');
                    if ($padding) {
                        $button_padding         .=  $padding->md;
                        $button_padding_sm      .=  $padding->sm;
                        $button_padding_xs      .=  $padding->xs;
                    }
                }

                if ($button_style || $button_color || $button_background_color || $button_padding || $button_margin) {
                    $css .= $addon_id . ' .tz-media-content .tz-btn {';
                    $css .= $button_color;
                    $css .= $button_background_color;
                    $css .= $button_border_radius;
                    $css .= $button_style;
                    if ($button_padding) {
                        $css .= $button_padding;
                    }
                    if ($button_margin) {
                        $css .= $button_margin;
                    }
                    $css .= '}';
                }

                $css .= '@media (min-width: 768px) and (max-width: 991px) {';
                if(!empty($image_margin_sm) && ($title_position == 'left' || $title_position == 'right')){
                    $css .= $addon_id . ' .tz-media .pull-left, '. $addon_id .' .tz-media .pull-right {';
                    $css .= $image_margin_sm;
                    $css .= '}';
                }
                if(!empty($image_margin_sm) && ($title_position == 'after' || $title_position == 'before')) {
                    $css .= $addon_id . ' .tz-img-container {';
                    $css .= $image_margin_sm;
                    $css .= '}';
                }
                if ($button_margin_sm) {
                    $css .= $addon_id . ' .tz-media-content .tz-btn {';
                    $css .= $button_margin_sm;
                    $css .= '}';
                }
                if ($button_padding_sm) {
                    $css .= $addon_id . ' .tz-media-content .tz-btn {';
                    $css .= $button_padding_sm;
                    $css .= '}';
                }
                $css .= '}';


                $css .= '@media (max-width: 767px) {';
                if(!empty($image_margin_xs) && ($title_position == 'left' || $title_position == 'right')){
                    $css .= $addon_id . ' .tz-media .pull-left, '. $addon_id .' .tz-media .pull-right {';
                    $css .= $image_margin_xs;
                    $css .= '}';
                }
                if(!empty($image_margin_xs) && ($title_position == 'after' || $title_position == 'before')) {
                    $css .= $addon_id . ' .tz-img-container {';
                    $css .= $image_margin_xs;
                    $css .= '}';
                }
                if ($button_margin_xs) {
                    $css .= $addon_id . ' .tz-media-content .tz-btn {';
                    $css .= $button_margin_xs;
                    $css .= '}';
                }
                if ($button_padding_xs) {
                    $css .= $addon_id . ' .tz-media-content .tz-btn {';
                    $css .= $button_padding_xs;
                    $css .= '}';
                }
                $css .= '}';

                //Hover options
                $addon_hover = '';
                $addon_hover .= (isset($feature_item->feature_content_background_hover) && $feature_item->feature_content_background_hover) ? 'background:'.$feature_item->feature_content_background_hover.';' : '';
                $addon_hover .= (isset($feature_item->feature_content_color_hover) && $feature_item->feature_content_color_hover) ? 'color:'.$feature_item->feature_content_color_hover.';' : '';

                if(!empty($addon_hover)) {
                    $css .= $addon_id . '{';
                    $css .= 'transition:.3s;';
                    $css .= '}';
                    $css .= $addon_id . ':hover{';
                    $css .= $addon_hover;
                    $css .= '}';
                }

                if(isset($feature_item->title_color_hover) && $feature_item->title_color_hover) {
                    $css .= $addon_id . ' .tz-feature-box-title{';
                    $css .= 'transition:.3s;';
                    $css .='}';
                    $css .= $addon_id . ':hover .tz-feature-box-title {';
                    $css .= 'color:'.$feature_item->title_color_hover.';';
                    $css .='}';
                }

                if(isset($feature_item->meta_color_hover) && $feature_item->meta_color_hover) {
                    $css .= $addon_id . ' .tz-feature-box-meta{';
                    $css .= 'transition:.3s;';
                    $css .='}';
                    $css .= $addon_id . ':hover .tz-feature-box-meta {';
                    $css .= 'color:'.$feature_item->meta_color_hover.';';
                    $css .='}';
                }

                if((isset($feature_item->feature_icon_background_hover) && $feature_item->feature_icon_background_hover) || (isset($feature_item->feature_icon_color_hover) && $feature_item->feature_icon_color_hover) || (isset($feature_item->feature_icon_border_color_hover) && $feature_item->feature_icon_border_color_hover)) {
                    $css .= $addon_id . ' .tz-icon .tz-icon-container{';
                    $css .= 'transition:.3s;';
                    $css .= '}';
                    $css .= $addon_id . ':hover .tz-icon .tz-icon-container{';
                    if(isset($feature_item->feature_icon_background_hover) && $feature_item->feature_icon_background_hover){
                        $css .= 'background:'.$feature_item->feature_icon_background_hover.';';
                    }
                    if(isset($feature_item->feature_icon_color_hover) && $feature_item->feature_icon_color_hover){
                        $css .= 'color:'.$feature_item->feature_icon_color_hover.';';
                    }
                    if(isset($feature_item->feature_icon_border_color_hover) && $feature_item->feature_icon_border_color_hover){
                        $css .= 'border-color:'.$feature_item->feature_icon_border_color_hover.';';
                    }
                    $css .= '}';
                }

                if($button_color_hover || $button_background_color_hover) {
                    $css .= $addon_id . ' .tz-media-content .tz-btn {';
                    $css .= 'transition:.3s;';
                    $css .='}';
                    $css .= $addon_id . ':hover .tz-media-content .tz-btn {';
                    $css .= $button_color_hover.$button_background_color_hover;
                    $css .='}';
                }
                $doc->addStyleDeclaration($css);
            endforeach;
        endif;
    }
}