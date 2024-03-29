<?php
/**
 Admin Page Framework v3.5.10b by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class TextTruncator_AdminPageFramework_Widget_Model extends TextTruncator_AdminPageFramework_Widget_Router {
    function __construct($oProp) {
        parent::__construct($oProp);
        if (did_action('widgets_init')) {
            add_action("set_up_{$this->oProp->sClassName}", array($this, '_replyToRegisterWidget'), 20);
        } else {
            add_action('widgets_init', array($this, '_replyToRegisterWidget'), 20);
        }
    }
    public function _replyToRegisterWidget() {
        global $wp_widget_factory;
        if (!is_object($wp_widget_factory)) {
            return;
        }
        $wp_widget_factory->widgets[$this->oProp->sClassName] = new TextTruncator_AdminPageFramework_Widget_Factory($this, $this->oProp->sWidgetTitle, is_array($this->oProp->aWidgetArguments) ? $this->oProp->aWidgetArguments : array());
        $this->oProp->oWidget = $wp_widget_factory->widgets[$this->oProp->sClassName];
    }
    public function _registerFormElements($aOptions) {
        $this->_loadFieldTypeDefinitions();
        $this->oProp->aOptions = $aOptions;
        $this->oForm->format();
        $this->oForm->applyConditions();
        $this->oForm->setDynamicElements($this->oProp->aOptions);
        $this->_registerFields($this->oForm->aConditionedFields);
    }
}