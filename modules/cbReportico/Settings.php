<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('Smarty_setup.php');
require_once("include/utils/utils.php");
require_once("modules/com_vtiger_workflow/VTWorkflowUtils.php");

global $mod_strings, $app_strings, $theme, $adb;
$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", "$theme");
$smarty->assign("IMAGE_PATH", "themes/$theme/images/");

// Operation to be restricted for non-admin users.
global $current_user;
if(!is_admin($current_user)) {	
	$smarty->display(vtlib_getModuleTemplate('Vtiger','OperationNotPermitted.tpl'));	
} else {
	$module = vtlib_purify($_REQUEST['formodule']);

	$menu_array = Array();
        
        //Reportico Administrator
        $menu_array['Tooltip']['location'] = "index.php?module=cbReportico&parenttab=Analytics&action=ReporticoView&admin=yes&clear_session=1";
        $menu_array['Tooltip']['image_src'] = "modules/cbReportico/reportico/images/reportico100.png";
        $menu_array['Tooltip']['desc'] = getTranslatedString("Reportico Administrator to create/edit projects",'cbReportico');
        $menu_array['Tooltip']['label'] = getTranslatedString("Reportico Administrator",'cbReportico');

	
	//if(layout editor is permitted)
	$menu_array['LayoutEditor']['location'] = 'index.php?module=Settings&action=LayoutBlockList&parenttab=Settings&formodule='.$module;
	$menu_array['LayoutEditor']['image_src'] = 'themes/images/orgshar.gif';
	$menu_array['LayoutEditor']['desc'] = getTranslatedString('LBL_LAYOUT_EDITOR_DESCRIPTION');
	$menu_array['LayoutEditor']['label'] = getTranslatedString('LBL_LAYOUT_EDITOR');
	
	
	if(VTWorkflowUtils::checkModuleWorkflow($module)){
		$sql_result = $adb->pquery("SELECT * FROM vtiger_settings_field WHERE name = ? AND active=0",array('LBL_WORKFLOW_LIST'));
			if($adb->num_rows($sql_result) > 0) {
				$menu_array['Workflow']['location'] = $adb->query_result($sql_result, 0, 'linkto').'&list_module='.$module;
				$menu_array['Workflow']['image_src'] = vtiger_imageurl($adb->query_result($sql_result, 0, 'iconpath'), $theme);
				$menu_array['Workflow']['desc'] = getTranslatedString($adb->query_result($sql_result, 0, 'description'),'com_vtiger_workflow');
				$menu_array['Workflow']['label'] = getTranslatedString($adb->query_result($sql_result, 0, 'name'),'com_vtiger_workflow');
			}
	}

	//add blanks for 3-column layout
	$count = count($menu_array)%3;
	if($count>0) {
		for($i=0;$i<3-$count;$i++) {
			$menu_array[] = array();
		}
	}
	
	$smarty->assign('MODULE',$module);
	$smarty->assign('MODULE_LBL',getTranslatedString($module,$module));
	$smarty->assign('MENU_ARRAY', $menu_array);

	$smarty->display(vtlib_getModuleTemplate('Vtiger','Settings.tpl'));
}
?>