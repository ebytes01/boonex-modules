<?
/************************************************************************************************************
 * EmmetBytes Default Club Cover
 * Default Club Cover For Creating New Boonex Modules
 ************************************************************************************************************/

$aConfig = array(

    'title' => '<span style="color: #008425;">Club Cover</span>', 
    'version' => '1.1.0',
	'vendor' => 'EmmetBytes',
	'update_url' => '',
	
	'compatible_with' => array(
        '7.0.5',
        '7.0.6',
        '7.0.7',
        '7.0.8',
        '7.0.9',
        '7.1.0',
        '7.1.1',
        '7.1.2',
        '7.1.3',
        '7.1.4',
    ),

    /**
	 * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
	 */
	'home_dir' => 'EmmetBytes/emmetbytes_club_cover/',
	'home_uri' => 'ebClubCover',
	
	'db_prefix' => 'emmet_bytes_club_cover_',
	'class_prefix' => 'EmmetBytesClubCover',
	/**
	 * Installation/Uninstallation Section.
	 */
    'install' => array(
        'check_dependencies' => 1,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_main_menu' => 1,
		'recompile_member_menu' => 0,
        'recompile_site_stats' => 1,		
		'recompile_page_builder' => 1,
		'recompile_profile_fields' => 0,
		'recompile_comments' => 1,
		'recompile_member_actions' => 1,
		'recompile_tags' => 1,
		'recompile_votes' => 1,
		'recompile_categories' => 1,
		'clear_db_cache' => 1,
		'recompile_injections' => 0,
		'recompile_permalinks' => 1,
		'recompile_alerts' => 1,
        'clear_db_cache' => 1,
		'show_conclusion' => 1,
	),
    'uninstall' => array (
        'check_dependencies' => 0,
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
		'update_languages' => 1,
		'recompile_main_menu' => 1,
		'recompile_member_menu' => 0,
		'recompile_site_stats' => 1,
		'recompile_page_builder' => 1,
		'recompile_profile_fields' => 0,
		'recompile_comments' => 1,
		'recompile_member_actions' => 1,
		'recompile_tags' => 1,
		'recompile_votes' => 1,
		'recompile_categories' => 1,
		'clear_db_cache' => 1,
		'recompile_injections' => 0,
		'recompile_permalinks' => 1,
		'recompile_alerts' => 1,
        'clear_db_cache' => 1,
		'show_conclusion' => 1,
    ),

	/**
	 * Dependencies Section
	 */
    'dependencies' => array(
        'avatar' => 'BoonEx Avatar Module',
	),

	/**
	 * Category for language keys.
	 */
	'language_category' => 'EmmetBytes ClubCover',

	/**
	 * Permissions Section
	 */
	'install_permissions' => array(),
    'uninstall_permissions' => array(),

	/**
	 * Introduction and Conclusion Section.
	 */
	'install_info' => array(
		'introduction' => '',
		'conclusion' => 'inst_concl.html'
	),
	'uninstall_info' => array(
		'introduction' => '',
		'conclusion' => 'uninst_concl.html'
	)
);
?>
