<?php
/**
 Admin Page Framework v3.5.10b by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class TextTruncator_AdminPageFramework_WPUtility_SiteInformation extends TextTruncator_AdminPageFramework_WPUtility_Post {
    static public function isDebugModeEnabled() {
        return ( bool )defined('WP_DEBUG') && WP_DEBUG;
    }
    static public function isDebugLogEnabled() {
        return ( bool )defined('WP_DEBUG_LOG') && WP_DEBUG_LOG;
    }
    static public function isDebugDisplayEnabled() {
        return ( bool )defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY;
    }
    static public function getSiteLanguage($sDefault = 'en_US') {
        return defined('WPLANG') && WPLANG ? WPLANG : $sDefault;
    }
}