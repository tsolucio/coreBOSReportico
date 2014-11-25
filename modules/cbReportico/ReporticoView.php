<?php
/*************************************************************************************************
 * Copyright 2013 JPL TSolucio, S.L. -- This file is a part of Reportico-vtigerCRM Integration.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : Reportico-vtigerCRM Integration
 *  Version      : 5.4.0
 *  Author       : JPL TSolucio, S. L.
 *************************************************************************************************/
global $adb,$log,$current_user,$root_directory,$site_URL;

   
    if(isset($_REQUEST['record']) && $_REQUEST['record'] != '')
    {
        $recordid = $_REQUEST['record'];
        
        $res = $adb->pquery("SELECT * FROM vtiger_cbreportico WHERE cbreporticoid = ?",array($recordid));
        
        $reportico_no = $adb->query_result($res,0,'reportico_no');
        $project = $adb->query_result($res,0,'project');
        $report = $adb->query_result($res,0,'report');
        $initial_execute_mode = $adb->query_result($res,0,'ini_execute_mode');
        $initial_output_format = $adb->query_result($res,0,'ini_output_format');        
        $initial_show_detail = $adb->query_result($res,0,'ini_show_detail') == 1 ? 'show':'hide';
        $initial_show_graph = $adb->query_result($res,0,'ini_show_graph') == 1 ? 'show':'hide';
        $initial_show_group_headers = $adb->query_result($res,0,'ini_show_group_headers') == 1 ? 'show':'hide';
        $initial_show_group_trailers = $adb->query_result($res,0,'ini_show_group_trailers') == 1 ? 'show':'hide';
        $initial_show_column_headers = $adb->query_result($res,0,'ini_show_column_headers') == 1 ? 'show':'hide';
        $initial_show_criteria = $adb->query_result($res,0,'ini_show_criteria') == 1 ? 'show':'hide';
        $password = $project;
    }
    



    ob_start();

    set_include_path(get_include_path(). PATH_SEPARATOR ."../../../");
    require_once('Smarty_setup.php');
    require_once($root_directory.'modules/cbReportico/reportico/reportico.php');
	

   
    //date_default_timezone_set(@date_default_timezone_get());

    $reportico_url_path = $site_URL.'/modules/cbReportico/reportico/';
    $_SESSION['reportico_url_path'] = $reportico_url_path;
    
    error_reporting(E_ERROR);
    
    $q = new reportico();
    define('SW_FRAMEWORK_DB_DRIVER',$adb->dbType);
    define('SW_FRAMEWORK_DB_USER', $adb->userName);
    define('SW_FRAMEWORK_DB_PASSWORD',$adb->userPassword);
    define('SW_FRAMEWORK_DB_HOST',$adb->dbHostName); // Use ip:port to specifiy a non standard port
    define('SW_FRAMEWORK_DB_DATABASE',$adb->dbName);
	
    if(isset($_REQUEST['admin']) && $_REQUEST['admin'] == 'yes')
    {
        $g_project = true;
        $q = new reportico();
        $q->allow_debug = true;
        $q->reportico_ajax_mode = true;
        $q->embedded_report = true;
        $q->initial_execute_mode = 'ADMIN';
        $q->access_mode = 'FULL';
        $q->session_namespace = "vtigeradmin";
        $q->reportico_url_path = $reportico_url_path;
        $q->show_refresh_button = true;
		$q->bootstrap_styles = "3";
		$q->bootstrap_preloaded = true;
        $q->reportico_ajax_script_url = $reportico_url_path.'corebosrun.php';
        $q->url_path_to_reportico_runner = $reportico_url_path.'corebosrun.php';
        $q->url_path_to_calling_script = $reportico_url_path.'corebosrun.php';
        $q->execute();
    }
    else
    {
        $q->allow_debug = false;
        $q->reportico_ajax_mode = true;
        $q->initial_project = $project;
        $q->initial_project_password = $password;
        $q->initial_report = $report.".xml";
        $q->initial_execute_mode = $initial_execute_mode;
        $q->initial_show_detail = $initial_show_detail;
        $q->initial_show_graph = $initial_show_graph;
        $q->initial_output_format = $initial_output_format;
        $q->initial_show_group_headers=$initial_show_group_headers;
        $q->initial_show_group_trailers=$initial_show_group_trailers;
        $q->initial_show_column_headers = $initial_show_column_headers;
        $q->initial_show_criteria = $initial_show_criteria;        
        $q->access_mode = 'ONEREPORT';
        $q->embedded_report = true;
        $q->session_namespace = $reportico_no;
        $q->reportico_url_path = $reportico_url_path;
        $q->show_refresh_button = true;

        $q->reportico_ajax_script_url = $reportico_url_path.'corebosrun.php';
        $q->url_path_to_reportico_runner = $reportico_url_path.'corebosrun.php';
        $q->url_path_to_calling_script = $reportico_url_path.'corebosrun.php';
        $q->execute();
    }


    // print out footer information
    ob_end_flush();
?>
