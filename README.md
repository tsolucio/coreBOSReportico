coreBOSReportico
================

Integration project for **coreBOS** and **Reportico**

This project adds the opensource [Reportico](http://www.reportico.org/site/index.php) project into [coreBOS](http://corebos.org) for very advanced reporting needs.

Reportico is an advanced reporting tool oriented towards ease of creation for administrators and ease of use for the users. Together with coreBOS, that holds all your data, the tandem is very useful to get those reports the coreBOS system can't generate.

**How to install**
- **You have two ways to install the module:**
	- The classic Vtiger CRM method: click in Release tab and download coreBOSReportico.zip, after go to your coreBOS -> Settings -> Module Manager -> Custom Modules and Import the module cbReportico.
	- New coreBOS method. Copye the url project "https://github.com/tsolucio/coreBOSReportico.git" go to your coreBOS -> Settings -> Module Manager -> Custom Modules -> Import  and paste the url in the field "Install from URL".
- When the importation finish go to reportico.org and download the last version. Actually this integration is working version 4.2
- When you downloaded reportico, please copy reportico folder into you corebos/modules/cbReportico/ with the name **reportico** for the folder
- After that you have to apply the pacth that you can find into modules/cbReportico/install/ called **patchForReporticoCoreBOSintegration.diff** to modify reportico. We need to modify Reportico because this is redeclaring Smarty class. Remember that coreBOS is using Smarty too.
- When you apply the patch, you need to copy the script corebosrun.php into modules/cbReportico/reportico/ . This is our custom run.php of reportico.
- Ok now you can begin tu use the module, but before I want to mention an patch for your coreBOS. In install directory you can find the **addLinkActionInListView.diff** , this patch modify your coreBOS to add action to the cbReportico registers in List view. This action is for open the report directly, but if you don't want to modify you will have to open the registir to clic in **View Report** in detail view action.

**How to use**
- After the installation , you have to go to Settings -> Module Manager -> Reportico Settings. Here you have a menu to access to Reportico Administration. This is for access wit admin user, the first time you have to define your admin password and after that you can create new reportico projects and reports.

- When you create a report you have tu choose in Database Type the value **Framework (e.g. Joomla). Our corebosrun.php define the database values from coreBOS/config.inc.php to set in Reportico project. 
<code>
define('SW_FRAMEWORK_DB_DRIVER',$dbconfig['db_type']);
define('SW_FRAMEWORK_DB_USER', $dbconfig['db_username']);
define('SW_FRAMEWORK_DB_PASSWORD',$dbconfig['db_password']);
...
</code>

- When you create your project and reports, you have to remember the name of these to set the reports in Reportico module.
- Go to Reportico module and create new register. Here you have to indicate the project name and the report name without string **.xml**
We need this to find the project folder into modules/cbReportico/reportico/projects/ and the report name to find the xml that contanins all the report configuration to execute.
- The other parameters into Reportico module is for show or not some parameters into Reportico when report is PREPARE or EXECUTE.
- Why we create registers into Reportico module to execute reports so instead of embedded totally reportico in the module?
We do this because these registers permit us to assign privileges to the reports by the assigned user or group and permit to the user to create more fields to create some filters to sort and find more easy the reports.

**KNOWN PROBLEMS**
- Now if you access like admin user, we have a problems with CSS, because coreBOS replace some classes like ul class defined to show like tabs.



**Thank you** very much for your help and contribution.

*coreBOS Team*
