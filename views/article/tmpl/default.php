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
if(isset($this -> items) && $this -> items && $this->params->get('feature_content_show',1)){
    $image_uikit  =   $this -> params ->get('feature_content_uikit',0);
    foreach($this -> items as $_item){
        $item = $_item -> value;
        $feature_width_xl    =   isset($item->feature_width_xl) && $item->feature_width_xl ? $item->feature_width_xl : '';
        $feature_width_lg    =   isset($item->feature_width_lg) && $item->feature_width_lg ? $item->feature_width_lg : '';
        $feature_width_md    =   isset($item->feature_width_md) && $item->feature_width_md ? $item->feature_width_md : '';
        $feature_width_sm    =   isset($item->feature_width_sm) && $item->feature_width_sm ? $item->feature_width_sm : '';
        $feature_width_xs    =   isset($item->feature_width_xs) && $item->feature_width_xs ? $item->feature_width_xs : '';
        if (isset($item->tzportfolio_addon_features) && is_object($item->tzportfolio_addon_features)) :
            $class      =   (isset($item->custom_class) && $item->custom_class) ? ' '.$item->custom_class : '';
            $sectiontitle = (isset($item->title) && $item->title) ? $item->title : '';
            $heading_selector = (isset($item->title_element) && $item->title_element) ? $item->title_element : 'h3';
            $description   = (isset($item->feature_description) && $item->feature_description) ? $item->feature_description : '';
            $text_alignment = (isset($feature_item->feature_description_alignment) && $feature_item->feature_description_alignment) ? ' text-'.$feature_item->feature_description_alignment : '';
        ?>
        <div class="tzportfolio-addon-feature-container<?php echo $class; ?>">
            <?php
            if ($sectiontitle || $description) {
                echo '<div class="tz-section-header'.$text_alignment.'">';
                if ($sectiontitle) {
                    echo '<'.$heading_selector.' class="tz-addon-section-title">';
                    echo $sectiontitle;
                    echo '</'.$heading_selector.'>';
                }
                if ($description) {
                    echo $description;
                }
                echo '</div>';
            }
            ?>
            <div class="row">
                <?php foreach ($item->tzportfolio_addon_features as $key => $feature_item) :
                    $column      =   array();
                    $column[]    =   isset($feature_item->feature_width_xl) && $feature_item->feature_width_xl ? $feature_item->feature_width_xl : $feature_width_xl;
                    $column[]    =   isset($feature_item->feature_width_lg) && $feature_item->feature_width_lg ? $feature_item->feature_width_lg : $feature_width_lg;
                    $column[]    =   isset($feature_item->feature_width_md) && $feature_item->feature_width_md ? $feature_item->feature_width_md : $feature_width_md;
                    $column[]    =   isset($feature_item->feature_width_sm) && $feature_item->feature_width_sm ? $feature_item->feature_width_sm : $feature_width_sm;
                    $column[]    =   isset($feature_item->feature_width_xs) && $feature_item->feature_width_xs ? $feature_item->feature_width_xs : $feature_width_xs;
                    $column      =   implode(' ', $column);
                    ?>
                    <div class="<?php echo $column; ?>" id="<?php echo 'feature-addon-'.$key; ?>">
                        <?php
                        $title = (isset($feature_item->title) && $feature_item->title) ? $feature_item->title : '';
                        $heading_selector = (isset($feature_item->title_element) && $feature_item->title_element) ? $feature_item->title_element : 'h3';

                        $meta = (isset($feature_item->meta) && $feature_item->meta) ? $feature_item->meta : '';
                        $meta_element = (isset($feature_item->meta_element) && $feature_item->meta_element) ? $feature_item->meta_element : 'p';

                        //Options
                        $feature_position = (isset($feature_item->feature_position) && $feature_item->feature_position) ? $feature_item->feature_position : 'before';
                        $feature_type = (isset($feature_item->feature_type) && $feature_item->feature_type) ? $feature_item->feature_type : 'image';
                        $feature_image = (isset($feature_item->feature_image) && $feature_item->feature_image) ? $feature_item->feature_image : '';

                        $title_image_icon_url = (isset($feature_item->title_image_icon_url) && $feature_item->title_image_icon_url) ? $feature_item->title_image_icon_url : '';
                        $target = (isset($feature_item->target) && $feature_item->target) ? $feature_item->target : '_blank';
                        $icon_name  =   (isset($feature_item->feature_icon) && $feature_item->feature_icon) ? $feature_item->feature_icon : '';
                        $icon_name  =   JHtml::_('icon.getIcon',  $icon_name);
                        $text = (isset($feature_item->feature_content) && $feature_item->feature_content) ? $feature_item->feature_content : '';
                        $text_alignment = (isset($feature_item->feature_alignment) && $feature_item->feature_alignment) ? 'text-'.$feature_item->feature_alignment : '';

                        //description responsive
                        $desc_column      =   array();
                        $desc_column[]    =   isset($feature_item->desc_width_xl) && $feature_item->desc_width_xl ? $feature_item->desc_width_xl : '';
                        $desc_column[]    =   isset($feature_item->desc_width_lg) && $feature_item->desc_width_lg ? $feature_item->desc_width_lg : '';
                        $desc_column[]    =   isset($feature_item->desc_width_md) && $feature_item->desc_width_md ? $feature_item->desc_width_md : '';
                        $desc_column[]    =   isset($feature_item->desc_width_sm) && $feature_item->desc_width_sm ? $feature_item->desc_width_sm : '';
                        $desc_column[]    =   isset($feature_item->desc_width_xs) && $feature_item->desc_width_xs ? $feature_item->desc_width_xs : '';
                        $desc_column      =   implode(' ', $desc_column);

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
                                if ($title_image_icon_url) {
                                    $media  .=  '<a href="'.$title_image_icon_url.'" title="'.$title.'" target="'.$target.'">';
                                }
                                $media  .= '<span class="tz-icon-container" aria-label="'.strip_tags($title).'">';

                                $icon_arr = array_filter(explode(' ', $icon_name));
                                if (count($icon_arr) === 1) {
                                    $icon_name = 'icomoon-' . $icon_name;
                                }

                                $media  .= '<i class="' . $icon_name . '" aria-hidden="true"></i>';
                                $media  .= '</span>';
                                if ($title_image_icon_url) {
                                    $media  .=  '</a>';
                                }
                                $media  .= '</div>';
                            }
                        } else {
                            if($feature_image) {
                                $dot = strrpos($feature_image, '.');
                                $feature_image_webp =   '';
                                $data_image_src     =   $feature_image;
                                if ($dot !== false)
                                {
                                    $feature_image_name     = substr($feature_image, 0, $dot);
                                    if ($feature_image_name && file_exists(JPATH_BASE.'/'.$feature_image_name.'.webp')) {
                                        $feature_image_webp     =   $feature_image_name.'.webp';
                                        $data_image_src         =   $feature_image_webp;
                                    }
                                }
                                $media  .= '<span class="tz-img-container">';
                                if ($title_image_icon_url) {
                                    $media  .=  '<a href="'.$title_image_icon_url.'" title="'.$title.'" target="'.$target.'">';
                                }
                                $image_properties   =   false;
                                if (file_exists(JPATH_BASE.'/'.$feature_image)) {
                                    $image_properties   =   getimagesize(JPATH_BASE.'/'.$feature_image);
                                }
                                if ( $feature_image && (!strpos( $feature_image, 'http://' ) !== false && !strpos( $feature_image, 'https://' ) !== false )) {
                                    $feature_image = JURI::root( true ) . '/' . $feature_image;
                                }
                                $data_image_src     =   $feature_image;
                                if ($feature_image_webp) {
                                    if ( $feature_image_webp && (!strpos( $feature_image_webp, 'http://' ) !== false && !strpos( $feature_image_webp, 'https://' ) !== false )) {
                                        $feature_image_webp = JURI::base( true ) . '/' . $feature_image_webp;
                                    }
                                    $data_image_src     =   $feature_image_webp;
                                }
                                if ($image_uikit) {
                                    if (is_array($image_properties) && count($image_properties) > 2) {
                                        $data_image_src = 'data-src="' . $data_image_src . '" data-origin="'.$feature_image.'" data-type="'.(isset($image_properties['mime'])?$image_properties['mime']:'').'" data-width="' . (isset($image_properties[0])?$image_properties[0]:''). '" data-height="' . (isset($image_properties[1])?$image_properties[1]:'') . '" uk-img';
                                    } else {
                                        $data_image_src = 'src="' . $data_image_src . '" data-origin="'.$feature_image.'" data-type="'.(isset($image_properties['mime'])?$image_properties['mime']:'').'"';
                                    }
                                } else {
                                    $data_image_src = 'src="' . $data_image_src . '" data-origin="'.$feature_image.'" data-type="'.(isset($image_properties['mime'])?$image_properties['mime']:'').'"';
                                }
                                $media  .=  '<picture>';
//                                $media  .=  $feature_image_webp ? '<source srcset="'.$feature_image_webp.'" type="image/webp">' : '';
//                                $media  .=  '<source srcset="'.$feature_image.'" type="'.$image_properties['mime'].'">';
                                $media  .=  '<img class="tz-img-responsive" ' . $data_image_src . ' alt="'.strip_tags($title).'">';
                                $media  .=  '</picture>';
                                if ($title_image_icon_url) {
                                    $media  .=  '</a>';
                                }
                                $media  .= '</span>';
                            }
                        }
                        //Title
                        $feature_title = '';
                        if ($title) {
                            $heading_class = '';
                            if( ($icon_image_position=='left') || ($icon_image_position=='right') ) {
                                $heading_class = ' tz-media-heading';
                            }
                            $feature_title .= '<'.$heading_selector.' class="tz-addon-title tz-feature-box-title'. $heading_class .'">';
                            if ($title_image_icon_url) {
                                $feature_title  .=  '<a href="'.$title_image_icon_url.'" title="'.$title.'" target="'.$target.'">';
                            }
                            $feature_title .= $title;
                            if ($title_image_icon_url) {
                                $feature_title  .=  '</a>';
                            }
                            $feature_title .= '</'.$heading_selector.'>';
                        }
                        //Feature Text
                        $feature_text  = '<div class="tz-addon-text">';
                        $feature_text .= $text;
                        $feature_text .= '</div>';

                        //Meta text
                        $feature_meta   =   '';
                        if ($meta) {
                            $feature_meta .= '<'.$meta_element.' class="tz-feature-box-meta">';
                            $feature_meta .= $meta;
                            $feature_meta .= '</'.$meta_element.'>';
                        }

                        //Output
                        $output  = '<div class="tz-addon tz-addon-feature ' . $alignment . $custom_class. '">';
                        $output .= '<div class="tz-addon-content '.$text_alignment.'">';
                        if ($feature_type == 'heading') {
                            $output .=  '<div class="row tz-media-content">';
                            $output .=  '<div class="col">'. $feature_title .$feature_meta.'</div>';
                            $output .=  '<div class="'.$desc_column.'">'.$feature_text.'</div>';
                            $output .=  '</div>';
                        } elseif ($feature_type == 'divider') {
                            $output .=  '<div class="tz-feature-addon-divider tz-media-content"><hr></div>';
                        } else {
                            if ($icon_image_position == 'before') {
                                $output .= ($media) ?? '';
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
                        }


                        $output .= '</div>';
                        $output .= '</div>';
                        echo $output;
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

<?php
        endif;
    }
}