<?php
/**
 Admin Page Framework v3.5.10b by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/text-truncator>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class TextTruncator_AdminPageFramework_Message {
    public $aMessages = array();
    public $aDefaults = array('option_updated' => 'The options have been updated.', 'option_cleared' => 'The options have been cleared.', 'export' => 'Export', 'export_options' => 'Export Options', 'import_options' => 'Import', 'import_options' => 'Import Options', 'submit' => 'Submit', 'import_error' => 'An error occurred while uploading the import file.', 'uploaded_file_type_not_supported' => 'The uploaded file type is not supported: %1$s', 'could_not_load_importing_data' => 'Could not load the importing data.', 'imported_data' => 'The uploaded file has been imported.', 'not_imported_data' => 'No data could be imported.', 'upload_image' => 'Upload Image', 'use_this_image' => 'Use This Image', 'insert_from_url' => 'Insert from URL', 'reset_options' => 'Are you sure you want to reset the options?', 'confirm_perform_task' => 'Please confirm your action.', 'specified_option_been_deleted' => 'The specified options have been deleted.', 'nonce_verification_failed' => 'A problem occurred while processing the form data. Please try again.', 'send_email' => 'Is it okay to send the email?', 'email_sent' => 'The email has been sent.', 'email_scheduled' => 'The email has been scheduled.', 'email_could_not_send' => 'There was a problem sending the email', 'title' => 'Title', 'author' => 'Author', 'categories' => 'Categories', 'tags' => 'Tags', 'comments' => 'Comments', 'date' => 'Date', 'show_all' => 'Show All', 'powered_by' => 'Powered by', 'settings' => 'Settings', 'manage' => 'Manage', 'select_image' => 'Select Image', 'upload_file' => 'Upload File', 'use_this_file' => 'Use This File', 'select_file' => 'Select File', 'remove_value' => 'Remove Value', 'select_all' => 'Select All', 'select_none' => 'Select None', 'no_term_found' => 'No term found.', 'select' => 'Select', 'insert' => 'Insert', 'use_this' => 'Use This', 'return_to_library' => 'Return to Library', 'queries_in_seconds' => '%1$s queries in %2$s seconds.', 'out_of_x_memory_used' => '%1$s out of %2$s MB (%3$s) memory used.', 'peak_memory_usage' => 'Peak memory usage %1$s MB.', 'initial_memory_usage' => 'Initial memory usage  %1$s MB.', 'allowed_maximum_number_of_fields' => 'The allowed maximum number of fields is {0}.', 'allowed_minimum_number_of_fields' => 'The allowed minimum number of fields is {0}.', 'add' => 'Add', 'remove' => 'Remove', 'allowed_maximum_number_of_sections' => 'The allowed maximum number of sections is {0}', 'allowed_minimum_number_of_sections' => 'The allowed minimum number of sections is {0}', 'add_section' => 'Add Section', 'remove_section' => 'Remove Section', 'toggle_all' => 'Toggle All', 'toggle_all_collapsible_sections' => 'Toggle all collapsible sections', 'reset' => 'Reset', 'yes' => 'Yes', 'no' => 'No', 'on' => 'On', 'off' => 'Off', 'enabled' => 'Enabled', 'disabled' => 'Disabled', 'supported' => 'Supported', 'not_supported' => 'Not Supported', 'functional' => 'Functional', 'not_functional' => 'Not Functional', 'too_long' => 'Too Long', 'acceptable' => 'Acceptable', 'no_log_found' => 'No log found.',);
    protected $_sTextDomain = 'text-truncator';
    static private $_aInstancesByTextDomain = array();
    public static function getInstance($sTextDomain = 'text-truncator') {
        $_oInstance = isset(self::$_aInstancesByTextDomain[$sTextDomain]) && (self::$_aInstancesByTextDomain[$sTextDomain] instanceof TextTruncator_AdminPageFramework_Message) ? self::$_aInstancesByTextDomain[$sTextDomain] : new TextTruncator_AdminPageFramework_Message($sTextDomain);
        self::$_aInstancesByTextDomain[$sTextDomain] = $_oInstance;
        return self::$_aInstancesByTextDomain[$sTextDomain];
    }
    public static function instantiate($sTextDomain = 'text-truncator') {
        return self::getInstance($sTextDomain);
    }
    public function __construct($sTextDomain = 'text-truncator') {
        $this->_sTextDomain = $sTextDomain;
        $this->aMessages = array_fill_keys(array_keys($this->aDefaults), null);
    }
    public function getTextDomain() {
        return $this->_sTextDomain;
    }
    public function get($sKey) {
        return isset($this->aMessages[$sKey]) ? __($this->aMessages[$sKey], $this->_sTextDomain) : __($this->{$sKey}, $this->_sTextDomain);
    }
    public function output($sKey) {
        echo $this->get($sKey);
    }
    public function __($sKey) {
        return $this->get($sKey);
    }
    public function _e($sKey) {
        $this->output($sKey);
    }
    public function __get($sPropertyName) {
        return isset($this->aDefaults[$sPropertyName]) ? $this->aDefaults[$sPropertyName] : $sPropertyName;
    }
    private function __doDummy() {
        __('The options have been updated.', 'text-truncator');
        __('The options have been cleared.', 'text-truncator');
        __('Export', 'text-truncator');
        __('Export Options', 'text-truncator');
        __('Import', 'text-truncator');
        __('Import Options', 'text-truncator');
        __('Submit', 'text-truncator');
        __('An error occurred while uploading the import file.', 'text-truncator');
        __('The uploaded file type is not supported: %1$s', 'text-truncator');
        __('Could not load the importing data.', 'text-truncator');
        __('The uploaded file has been imported.', 'text-truncator');
        __('No data could be imported.', 'text-truncator');
        __('Upload Image', 'text-truncator');
        __('Use This Image', 'text-truncator');
        __('Insert from URL', 'text-truncator');
        __('Are you sure you want to reset the options?', 'text-truncator');
        __('Please confirm your action.', 'text-truncator');
        __('The specified options have been deleted.', 'text-truncator');
        __('A problem occurred while processing the form data. Please try again.', 'text-truncator');
        __('Is it okay to send the email?', 'text-truncator');
        __('The email has been sent.', 'text-truncator');
        __('The email has been scheduled.', 'text-truncator');
        __('There was a problem sending the email', 'text-truncator');
        __('Title', 'text-truncator');
        __('Author', 'text-truncator');
        __('Categories', 'text-truncator');
        __('Tags', 'text-truncator');
        __('Comments', 'text-truncator');
        __('Date', 'text-truncator');
        __('Show All', 'text-truncator');
        __('Powered by', 'text-truncator');
        __('Settings', 'text-truncator');
        __('Manage', 'text-truncator');
        __('Select Image', 'text-truncator');
        __('Upload File', 'text-truncator');
        __('Use This File', 'text-truncator');
        __('Select File', 'text-truncator');
        __('Remove Value', 'text-truncator');
        __('Select All', 'text-truncator');
        __('Select None', 'text-truncator');
        __('No term found.', 'text-truncator');
        __('Select', 'text-truncator');
        __('Insert', 'text-truncator');
        __('Use This', 'text-truncator');
        __('Return to Library', 'text-truncator');
        __('%1$s queries in %2$s seconds.', 'text-truncator');
        __('%1$s out of %2$s MB (%3$s) memory used.', 'text-truncator');
        __('Peak memory usage %1$s MB.', 'text-truncator');
        __('Initial memory usage  %1$s MB.', 'text-truncator');
        __('The allowed maximum number of fields is {0}.', 'text-truncator');
        __('The allowed minimum number of fields is {0}.', 'text-truncator');
        __('Add', 'text-truncator');
        __('Remove', 'text-truncator');
        __('The allowed maximum number of sections is {0}', 'text-truncator');
        __('The allowed minimum number of sections is {0}', 'text-truncator');
        __('Add Section', 'text-truncator');
        __('Remove Section', 'text-truncator');
        __('Toggle All', 'text-truncator');
        __('Toggle all collapsible sections', 'text-truncator');
        __('Reset', 'text-truncator');
        __('Yes', 'text-truncator');
        __('No', 'text-truncator');
        __('On', 'text-truncator');
        __('Off', 'text-truncator');
        __('Enabled', 'text-truncator');
        __('Disabled', 'text-truncator');
        __('Supported', 'text-truncator');
        __('Not Supported', 'text-truncator');
        __('Functional', 'text-truncator');
        __('Not Functional', 'text-truncator');
        __('Too Long', 'text-truncator');
        __('Acceptable', 'text-truncator');
        __('No log found.', 'text-truncator');
    }
}