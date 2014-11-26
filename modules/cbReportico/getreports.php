<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************
*  Module       : cbReportico
*  Version      : 5.5.0
*  Author       : MSLOKHAT
*************************************************************************************************/


global $mod_strings, $app_strings, $currentModule, $theme, $singlepane_view;
global $adb,$log,$current_user,$root_directory,$site_URL;

include_once 'modules/cbReportico/cbReportico.php';

$reportico_url_path = $root_directory.'/modules/cbReportico/reportico/projects/';
if(!$current_user->is_admin)
{
		echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
		echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>

		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
		<tbody><tr>
		<td rowspan='2' width='11%'><img src='". vtiger_imageurl('denied.gif', $theme) ."' ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span clas
		s='genHeaderSmall'>$app_strings[LBL_PERMISSION]</span></td>
		</tr>
		<tr>
		<td class='small' align='right' nowrap='nowrap'>
		<a href='javascript:window.history.back();'>$app_strings[LBL_GO_BACK]</a><br>
		</td>
		</tr>
		</tbody></table>
		</div>";
		echo "</td></tr></table>";
		exit;
}
$d = dir_list($reportico_url_path); 
if($d) 
	foreach($d as $f) {
		if($f == 'admin' || $f == 'tutorials' || $f == 'northwind' || preg_match('#^\..*#s', $f))
			continue;
		$n = file_list($reportico_url_path .'/' . $f,".xml"); 
		if($n) 
			foreach($n as $fi) {
				$file = basename($fi,'.xml');
				$cbQuery = $adb->pquery("SELECT cbreporticoid FROM vtiger_cbreportico 
										INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_cbreportico.cbreporticoid
										WHERE deleted = 0 AND project=? AND xmlreport=?", array($f,$file));
				$cbFieldsCount = $adb->num_rows($cbQuery);

				$fixml = simplexml_load_file($reportico_url_path.$f.'/'.$fi);
				$reporttitle = (string)$fixml->ReportQuery->Format->ReportTitle;
				$report_description = (string)$fixml->ReportQuery->Format->ReportDescription;
				if ($cbFieldsCount ==0) {
				
					$focus = new cbReportico();
					$focus->mode = ''; // create
					$_REQUEST['assigntype'] = 'U';
					$focus->column_fields['assigned_user_id'] = $current_user->id;
					$focus->column_fields['project'] = $f;
					$focus->column_fields['report'] = $reporttitle;
					$focus->column_fields['xmlreport'] = $file;
					$focus->column_fields['ini_execute_mode'] = 'PREPARE';
					$focus->column_fields['ini_output_format'] = 'HTML';
					$focus->column_fields['ini_show_detail'] = '1';
					$focus->column_fields['ini_show_graph'] = '0';
					$focus->column_fields['ini_show_group_headers'] = '1';
					$focus->column_fields['ini_show_group_trailers'] = '1';
					$focus->column_fields['ini_show_column_headers'] = '1';
					$focus->column_fields['ini_show_criteria'] = '1';
					$focus->column_fields['description'] = $report_description;
					$focus->save('cbReportico');
				}
			}
	}


header("Location: index.php?module=cbReportico&action=index");

function file_list($d,$x){ 
       foreach(array_diff(scandir($d),array('.','..')) as $f)if(is_file($d.'/'.$f)&&(($x)?ereg($x.'$',$f):1))$l[]=$f; 
       return $l; 
} 

function dir_list($d){ 
       foreach(array_diff(scandir($d),array('.','..')) as $f)if(is_dir($d.'/'.$f))$l[]=$f; 
       return $l; 
} 
