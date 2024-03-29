<?php
/**
 Admin Page Framework v3.5.10b by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class TextTruncator_AdminPageFramework_UserMeta extends TextTruncator_AdminPageFramework_UserMeta_Controller {
    static protected $_sFieldsType = 'user_meta';
    public function __construct($sCapability = 'edit_user', $sTextDomain = 'admin-page-framework') {
        $this->oProp = new TextTruncator_AdminPageFramework_Property_UserMeta($this, get_class($this), $sCapability, $sTextDomain, self::$_sFieldsType);
        parent::__construct($this->oProp);
        $this->oUtil->addAndDoAction($this, "start_{$this->oProp->sClassName}");
    }
}