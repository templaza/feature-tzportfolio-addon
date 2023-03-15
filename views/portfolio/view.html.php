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

class PlgTZ_Portfolio_PlusContentFeatureViewPortfolio extends JViewLegacy{
    protected $item     = null;
    protected $params   = null;
    protected $head             = false;

    public function display($tpl = null){
        $this -> article = $this -> get('Item');
        $this -> items   = $this -> get('FeatureItems');
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;
        if(isset($this -> items) && $this -> items && !$this->head) {
            foreach ($this->items as $_item) {
                if($model  = JModelLegacy::getInstance('Feature','PlgTZ_Portfolio_PlusContentModel',
                    array('ignore_request' => true))) {
                    $model->styleInit($_item -> value);
                }
            }
            $this->head = true;
        }
        parent::display($tpl);
    }
}