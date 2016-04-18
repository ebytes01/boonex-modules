<?
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : June 10, 2012
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Bon Con Special Info
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/
$aConfig = array( 
    'title' => '<span style="color: #008425;">Dolphin Contents With Special Info</span>', 
    'version' => '1.0.0', 
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
        '7.1.5',
    ), 
    'home_dir' => 'EmmetBytes/emmetbytes_bon_con_special_info/', 
    'home_uri' => 'ebBonConSpecialInfo', 
    'db_prefix' => 'emmet_bytes_bon_con_special_info_', 
    'class_prefix' => 'EmmetBytesBonConSpecialInfo', 
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
    'dependencies' => array( 
        'avatar' => 'BoonEx Avatar Module', 
        'photos' => 'BoonEx Photos Module', 
        'videos' => 'BoonEx Videos Module',
        'sounds' => 'BoonEx Sounds Module',
    ), 
    'language_category' => 'EmmetBytes BonConSpecialInfo', 
    'install_permissions' => array(), 
    'uninstall_permissions' => array(), 
    'install_info' => array( 
        'introduction' => '', 
        'conclusion' => 'inst_concl.html'), 
        'uninstall_info' => array( 
            'introduction' => '', 
            'conclusion' => 'uninst_concl.html'
        )
    );
?>
