<?php
/*------------------------------------------------------------------------

# Music Addon

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2016 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
if(isset($this -> items) && $this -> items && $this->params->get('cat_feature_content_show',0)){
    foreach($this -> items as $_item){
        $item = $_item -> value;
        $class      =   array();
        $class[]    =   isset($item->feature_width_md) && $item->feature_width_md ? $item->feature_width_md : '';
        $class[]    =   isset($item->feature_width_sm) && $item->feature_width_sm ? $item->feature_width_sm : '';
        $class[]    =   isset($item->feature_width_xs) && $item->feature_width_xs ? $item->feature_width_xs : '';
        $class      =   implode(' ', $class);
        if (isset($item->tzportfolio_addon_features) && is_object($item->tzportfolio_addon_features)) :
            ?>
            <div class="tzportfolio-addon-feature-container row">
                <?php foreach ($item->tzportfolio_addon_features as $key => $feature_item) : ?>
                    <div class="<?php echo $class; ?>" id="<?php echo 'feature-addon-'.$key; ?>">
                        <?php
                        $title = (isset($feature_item->title) && $feature_item->title) ? $feature_item->title : '';
                        $heading_selector = (isset($feature_item->title_element) && $feature_item->title_element) ? $feature_item->title_element : 'h3';

                        //Options
                        $feature_position = (isset($feature_item->feature_position) && $feature_item->feature_position) ? $feature_item->feature_position : 'before';
                        $feature_type = (isset($feature_item->feature_type) && $feature_item->feature_type) ? $feature_item->feature_type : 'icon';
                        $feature_image = (isset($feature_item->feature_image) && $feature_item->feature_image) ? $feature_item->feature_image : '';
                        $icon_name  =   (isset($feature_item->feature_icon) && $feature_item->feature_icon) ? $feature_item->feature_icon : '';
                        $icon_name  =   JHtml::_('icon.getIcon',  $icon_name);
                        $text = (isset($feature_item->feature_content) && $feature_item->feature_content) ? $feature_item->feature_content : '';
                        $text_alignment = (isset($feature_item->feature_alignment) && $feature_item->feature_alignment) ? 'text-'.$feature_item->feature_alignment : '';

                        //Button options
                        $btn_text = (isset($feature_item->feature_button_text) && trim($feature_item->feature_button_text)) ? $feature_item->feature_button_text : '';
                        $attribs = (isset($feature_item->feature_button_url) && $feature_item->feature_button_url) ? ' href="' . $feature_item->feature_button_url . '"' : '';

                        //Custom Class
                        $custom_class = (isset($feature_item->feature_class) && trim($feature_item->feature_class)) ? ' '.$feature_item->feature_class : '';

                        //Reset Alignment for left and right style
                        $alignment='';
                        if( ($feature_position=='left') || ($feature_position=='right') ) {
                            $alignment = 'text-' . $feature_position;
                        }
                        //Image or icon position
                        $icon_image_position = '';
                        if($feature_position == 'before') {
                            $icon_image_position = 'after';
                        } else if($feature_position == 'after') {
                            $icon_image_position = 'before';
                        } else {
                            $icon_image_position = $feature_position;
                        }
                        //Icon or Image
                        $media = '';
                        if($feature_type == 'icon') {
                            if($icon_name) {
                                $media  .= '<div class="tz-icon">';
                                $media  .= '<span class="tz-icon-container" aria-label="'.strip_tags($title).'">';

                                $icon_arr = array_filter(explode(' ', $icon_name));
                                if (count($icon_arr) === 1) {
                                    $icon_name = 'icomoon-' . $icon_name;
                                }

                                $media  .= '<i class="' . $icon_name . '" aria-hidden="true"></i>';
                                $media  .= '</span>';
                                $media  .= '</div>';
                            }
                        } else {
                            if($feature_image) {
                                $media  .= '<span class="tz-img-container">';
                                $media  .= '<img class="tz-img-responsive" src="' . $feature_image . '" alt="'.strip_tags($title).'">';
                                $media  .= '</span>';
                            }
                        }
                        //Title
                        $feature_title = '';
                        if($title) {
                            $heading_class = '';
                            if( ($icon_image_position=='left') || ($icon_image_position=='right') ) {
                                $heading_class = ' tz-media-heading';
                            }

                            $feature_title .= '<'.$heading_selector.' class="tz-addon-title tz-feature-box-title'. $heading_class .'">';
                            $feature_title .= $title;
                            $feature_title .= '</'.$heading_selector.'>';
                        }
                        //Feature Text
                        $feature_text  = '<div class="tz-addon-text">';
                        $feature_text .= $text;
                        $feature_text .= '</div>';

                        //Output
                        $output  = '<div class="tz-addon tz-addon-feature ' . $alignment . $custom_class. '">';
                        $output .= '<div class="tz-addon-content '.$text_alignment.'">';

                        if ($icon_image_position == 'before') {
                            $output .= ($media) ? $media : '';
                            $output .= '<div class="tz-media-content">';
                            $output .= ($title) ? $feature_title : '';
                            $output .= $feature_text;
                            if($btn_text){
                                $output .= '<a' . $attribs . ' class="btn tz-btn">' . $btn_text . '</a>';
                            }
                            $output .= '</div>';
                        } else if ($icon_image_position == 'after') {
                            $output .= ($title) ? $feature_title : '';
                            $output .= ($media) ? $media : '';
                            $output .= '<div class="tz-media-content">';
                            $output .= $feature_text;
                            if($btn_text){
                                $output .= '<a' . $attribs . ' class="btn tz-btn">' . $btn_text . '</a>';
                            }
                            $output .= '</div>';
                        } else {
                            if($media) {
                                $output .= '<div class="tz-media-'.$feature_type.'">';
                                $output .= '<div class="pull-'. $icon_image_position .' '.$feature_type.'">';
                                $output .= $media;
                                $output .= '</div>';
                                $output .= '<div class="tz-media-body">';
                                $output .= '<div class="tz-media-content">';
                                $output .= ($title) ? $feature_title : '';
                                $output .= $feature_text;
                                if($btn_text){
                                    $output .= '<a' . $attribs . ' class="btn tz-btn">' . $btn_text . '</a>';
                                }
                                $output .= '</div>';//.tz-media-content
                                $output .= '</div>';
                                $output .= '</div>';
                            }
                        }

                        $output .= '</div>';
                        $output .= '</div>';
                        echo $output;
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php
        endif;
    }
}