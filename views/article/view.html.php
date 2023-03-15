<?php
/*------------------------------------------------------------------------

# Grid Gallery Addon

# ------------------------------------------------------------------------

# author    Sonny

# copyright Copyright (C) 2019 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

class PlgTZ_Portfolio_PlusContentFeatureViewArticle extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
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