<?php
/*
	Plugin Name:    Text Truncator
	Plugin URI:     http://en.michaeluno.jp/text-truncator
	Description:    Truncates text of specified HTML elements by number of lines.
	Author:         Michael Uno (miunosoft)
	Author URI:     http://michaeluno.jp
	Version:        1
*/

/**
 * Provides the basic information about the plugin.
 * 
 * @since       1       
 */
class TextTruncator_Registry_Base {
 
	const VERSION        = '1';    // <--- DON'T FORGET TO CHANGE THIS AS WELL!!
	const NAME           = 'Text Truncator';
	const DESCRIPTION    = 'Truncates text of specified HTML elements by number of lines.';
	const URI            = 'http://en.michaeluno.jp/text-truncator';
	const AUTHOR         = 'miunosoft (Michael Uno)';
	const AUTHOR_URI     = 'http://en.michaeluno.jp/';
	const PLUGIN_URI     = 'http://en.michaeluno.jp/text-truncator';
	const COPYRIGHT      = 'Copyright (c) 2015, Michael Uno';
	const LICENSE        = 'GPL v2 or later';
	const CONTRIBUTORS   = '';
 
}

// Do not load if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
    return; 
}

/**
 * Provides the common data shared among plugin files.
 * 
 * To use the class, first call the setUp() method, which sets up the necessary properties.
 * 
 * @package     Text Truncator
 * @since       1
*/
final class TextTruncator_Registry extends TextTruncator_Registry_Base {
    
	const TEXT_DOMAIN               = 'text-truncator';
	const TEXT_DOMAIN_PATH          = '/language';
    
    /**
     * The hook slug used for the prefix of action and filter hook names.
     * 
     * @remark      The ending underscore is not necessary.
     */    
	const HOOK_SLUG                 = 'ttc';    // without trailing underscore
    
    /**
     * The transient prefix. 
     * 
     * @remark      This is also accessed from uninstall.php so do not remove.
     * @remark      Up to 8 characters as transient name allows 45 characters or less ( 40 for site transients ) so that md5 (32 characters) can be added
     */    
	const TRANSIENT_PREFIX          = 'TTC_';
    
    /**
     * 
     * @since       1
     */
    static public $sFilePath;  
    
    /**
     * 
     * @since       1
     */    
    static public $sDirPath;    
    
    /**
     * @since        1
     */
    static public $aOptionKeys = array(    
        'setting'           => 'text_truncator', 
    );
        
    /**
     * Used admin pages.
     * @since        1
     */
    static public $aAdminPages = array(
        // key => 'page slug'        
        'setting'           => 'ttc_settings', 
    );
    
    /**
     * Used post types.
     */
    static public $aPostTypes = array(
    );
    
    /**
     * Used post types by meta boxes.
     */
    static public $aMetaBoxPostTypes = array(
    );
    
    /**
     * Used taxonomies.
     * @remark      
     */
    static public $aTaxonomies = array(
    );
    
    /**
     * Used shortcode slugs
     */
    static public $aShortcodes = array(
    );
    
    /**
     * Stores custom database table names.
     * @remark      slug (part of class file name) => table name
     * @since       1
     */
    static public $aDatabaseTables = array(
    );
    /**
     * Stores the database table versions.
     * @since       1
     */
    static public $aDatabaseTableVersions = array(
    );
    
    /**
     * Sets up class properties.
     * @return      void
     */
	static function setUp( $sPluginFilePath ) {
        self::$sFilePath = $sPluginFilePath; 
        self::$sDirPath  = dirname( self::$sFilePath );  
	}	
	
    /**
     * @return      string
     */
	public static function getPluginURL( $sRelativePath='' ) {
		return plugins_url( $sRelativePath, self::$sFilePath );
	}

    /**
     * Requirements.
     * @since           1
     */    
    static public $aRequirements = array(
        'php' => array(
            'version'   => '5.2.4',
            'error'     => 'The plugin requires the PHP version %1$s or higher.',
        ),
        'wordpress'         => array(
            'version'   => '3.3',
            'error'     => 'The plugin requires the WordPress version %1$s or higher.',
        ),
        // 'mysql'             => array(
            // 'version'   => '5.0.3', // uses VARCHAR(2083) 
            // 'error'     => 'The plugin requires the MySQL version %1$s or higher.',
        // ),
        'functions'     => '', // disabled
        // array(
            // e.g. 'mblang' => 'The plugin requires the mbstring extension.',
        // ),
        // 'classes'       => array(
            // 'DOMDocument' => 'The plugin requires the DOMXML extension.',
        // ),
        'constants'     => '', // disabled
        // array(
            // e.g. 'THEADDONFILE' => 'The plugin requires the ... addon to be installed.',
            // e.g. 'APSPATH' => 'The script cannot be loaded directly.',
        // ),
        'files'         => '', // disabled
        // array(
            // e.g. 'home/my_user_name/my_dir/scripts/my_scripts.php' => 'The required script could not be found.',
        // ),
    );        
	
}
TextTruncator_Registry::setUp( __FILE__ );

include( dirname( __FILE__ ).'/include/library/admin-page-framework/admin-page-framework.php' );
include( dirname( __FILE__ ).'/include/class/boot/TextTruncator_Bootstrap.php' );
new TextTruncator_Bootstrap(
    TextTruncator_Registry::$sFilePath,
    TextTruncator_Registry::HOOK_SLUG    // hook prefix    
);