<?php
/**
 Admin Page Framework v3.5.10b by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class TextTruncator_AdminPageFramework_Link_PostType extends TextTruncator_AdminPageFramework_Link_Base {
    public function __construct($oProp, $oMsg = null) {
        parent::__construct($oProp, $oMsg);
        if (isset($_GET['post_type']) && $_GET['post_type'] == $this->oProp->sPostType) {
            add_action('get_edit_post_link', array($this, '_replyToAddPostTypeQueryInEditPostLink'), 10, 3);
        }
    }
    public function _replyToAddSettingsLinkInPluginListingPage($aLinks) {
        $_sLinkLabel = isset($this->oProp->aPostTypeArgs['labels']['plugin_listing_table_title_cell_link']) ? $this->oProp->aPostTypeArgs['labels']['plugin_listing_table_title_cell_link'] : $this->oMsg->get('manage');
        if (!$_sLinkLabel) {
            return $aLinks;
        }
        array_unshift($aLinks, '<a ' . $this->generateAttributes(array('href' => esc_url("edit.php?post_type={$this->oProp->sPostType}"), 'class' => 'apf-plugin-title-action-link apf-admin-page',)) . '>' . $_sLinkLabel . "</a>");
        return $aLinks;
    }
    public function _replyToSetFooterInfo() {
        if (!$this->isPostDefinitionPage($this->oProp->sPostType) && !$this->isPostListingPage($this->oProp->sPostType) && !$this->isCustomTaxonomyPage($this->oProp->sPostType)) {
            return;
        }
        parent::_replyToSetFooterInfo();
    }
    public function _replyToAddPostTypeQueryInEditPostLink($sURL, $iPostID = null, $sContext = null) {
        return add_query_arg(array('post' => $iPostID, 'action' => 'edit', 'post_type' => $this->oProp->sPostType), $sURL);
    }
}