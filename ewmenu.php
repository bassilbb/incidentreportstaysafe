<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(17, "mi_home_php", $Language->MenuPhrase("17", "MenuText"), "home.php", -1, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}home.php'), FALSE, TRUE, "far fa-home");
$RootMenu->AddMenuItem(43, "mi_news", $Language->MenuPhrase("43", "MenuText"), "newslist.php", 17, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}news'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(47, "mi_testing_php", $Language->MenuPhrase("47", "MenuText"), "testing.php", 17, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}testing.php'), FALSE, TRUE, "fas fa-indent");
$RootMenu->AddMenuItem(33, "mci_Administrator", $Language->MenuPhrase("33", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "fas fa-user");
$RootMenu->AddMenuItem(1, "mi_branch", $Language->MenuPhrase("1", "MenuText"), "branchlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}branch'), FALSE, FALSE, "fas fa-transgender-alt");
$RootMenu->AddMenuItem(3, "mi_client", $Language->MenuPhrase("3", "MenuText"), "clientlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}client'), FALSE, FALSE, "fas fa-user");
$RootMenu->AddMenuItem(4, "mi_depertment", $Language->MenuPhrase("4", "MenuText"), "depertmentlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}depertment'), FALSE, FALSE, "fad fa-university");
$RootMenu->AddMenuItem(2, "mi_category", $Language->MenuPhrase("2", "MenuText"), "categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}category'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(49, "mi_departments", $Language->MenuPhrase("49", "MenuText"), "departmentslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}departments'), FALSE, FALSE, "fad fa-university");
$RootMenu->AddMenuItem(10, "mi_sub_category", $Language->MenuPhrase("10", "MenuText"), "sub_categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}sub-category'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(53, "mi_sub_sub_category", $Language->MenuPhrase("53", "MenuText"), "sub_sub_categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}sub_sub_category'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(7, "mi_incident_category", $Language->MenuPhrase("7", "MenuText"), "incident_categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}incident-category'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(5, "mi_gender", $Language->MenuPhrase("5", "MenuText"), "genderlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}gender'), FALSE, FALSE, "fas fa-venus-double");
$RootMenu->AddMenuItem(13, "mi_users", $Language->MenuPhrase("13", "MenuText"), "userslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}users'), FALSE, FALSE, "far fa-users");
$RootMenu->AddMenuItem(36, "mi_status", $Language->MenuPhrase("36", "MenuText"), "statuslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}status'), FALSE, FALSE, "fas fa-hourglass-start");
$RootMenu->AddMenuItem(37, "mi_incident_location", $Language->MenuPhrase("37", "MenuText"), "incident_locationlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}incident_location'), FALSE, FALSE, "fas fa-map-marker");
$RootMenu->AddMenuItem(51, "mi_incident_sub_location", $Language->MenuPhrase("51", "MenuText"), "incident_sub_locationlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}incident_sub_location'), FALSE, FALSE, "fas fa-map-marker");
$RootMenu->AddMenuItem(52, "mi_incident_venue", $Language->MenuPhrase("52", "MenuText"), "incident_venuelist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}incident_venue'), FALSE, FALSE, "fas fa-map-marker");
$RootMenu->AddMenuItem(38, "mi_no_of_people", $Language->MenuPhrase("38", "MenuText"), "no_of_peoplelist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}no_of_people'), FALSE, FALSE, "fas fa-user");
$RootMenu->AddMenuItem(39, "mi_type_of_incident", $Language->MenuPhrase("39", "MenuText"), "type_of_incidentlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}type_of_incident'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(48, "mi_audittrail", $Language->MenuPhrase("48", "MenuText"), "audittraillist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}audittrail'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(50, "mi_chart_report_php", $Language->MenuPhrase("50", "MenuText"), "chart_report.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}chart_report.php'), FALSE, TRUE, "");
$RootMenu->AddMenuItem(55, "mi_designation", $Language->MenuPhrase("55", "MenuText"), "designationlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}designation'), FALSE, FALSE, "fad fa-university");
$RootMenu->AddMenuItem(56, "mi_selection_sub_category", $Language->MenuPhrase("56", "MenuText"), "selection_sub_categorylist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}selection_sub_category'), FALSE, FALSE, "fas fa-braille");
$RootMenu->AddMenuItem(57, "mi_reason", $Language->MenuPhrase("57", "MenuText"), "reasonlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}reason'), FALSE, FALSE, "fa-regular fa-comment");
$RootMenu->AddMenuItem(95, "mi_maintained_status", $Language->MenuPhrase("95", "MenuText"), "maintained_statuslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}maintained_status'), FALSE, FALSE, "fas fa-hourglass-start");
$RootMenu->AddMenuItem(195, "mi_action_taken", $Language->MenuPhrase("195", "MenuText"), "action_takenlist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}action_taken'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(148, "mi_dispenser_type", $Language->MenuPhrase("148", "MenuText"), "dispenser_typelist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}dispenser_type'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(147, "mi_dispenser_status", $Language->MenuPhrase("147", "MenuText"), "dispenser_statuslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}dispenser_status'), FALSE, FALSE, "fas fa-hourglass-start");
$RootMenu->AddMenuItem(196, "mi_statuss", $Language->MenuPhrase("196", "MenuText"), "statusslist.php", 33, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}statuss'), FALSE, FALSE, "fas fa-hourglass-start");
$RootMenu->AddMenuItem(34, "mci_Incidents", $Language->MenuPhrase("34", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "fas fa-indent");
$RootMenu->AddMenuItem(8, "mi_report_form", $Language->MenuPhrase("8", "MenuText"), "report_formlist.php", 34, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}report_form'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(54, "mi_report", $Language->MenuPhrase("54", "MenuText"), "reportlist.php", 34, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}report'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(31, "mci_Settings", $Language->MenuPhrase("31", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "fas fa-cogs");
$RootMenu->AddMenuItem(44, "mi_userlevelpermissions", $Language->MenuPhrase("44", "MenuText"), "userlevelpermissionslist.php", 31, "", IsAdmin(), FALSE, FALSE, "fa fa-wrench");
$RootMenu->AddMenuItem(45, "mi_userlevels", $Language->MenuPhrase("45", "MenuText"), "userlevelslist.php", 31, "", IsAdmin(), FALSE, FALSE, "fa fa-cog");
$RootMenu->AddMenuItem(93, "mci_Servicing_Module", $Language->MenuPhrase("93", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa fa-wrench");
$RootMenu->AddMenuItem(58, "mi_buildings", $Language->MenuPhrase("58", "MenuText"), "buildingslist.php", 93, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}buildings'), FALSE, FALSE, "far fa-home");
$RootMenu->AddMenuItem(59, "mi_floors", $Language->MenuPhrase("59", "MenuText"), "floorslist.php", 93, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}floors'), FALSE, FALSE, "fa-light fa-bars");
$RootMenu->AddMenuItem(60, "mi_items", $Language->MenuPhrase("60", "MenuText"), "itemslist.php", 93, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}items'), FALSE, FALSE, "fa-light fa-bars");
$RootMenu->AddMenuItem(94, "mci_maintenances", $Language->MenuPhrase("94", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fas fa-cogs");
$RootMenu->AddMenuItem(61, "mi_maintenance", $Language->MenuPhrase("61", "MenuText"), "maintenancelist.php", 94, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}maintenance'), FALSE, FALSE, "fa fa-wrench");
$RootMenu->AddMenuItem(100, "mi_assign_task", $Language->MenuPhrase("100", "MenuText"), "assign_tasklist.php", -1, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}assign_task'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(145, "mci_Inventory_Module", $Language->MenuPhrase("145", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa fa-cube");
$RootMenu->AddMenuItem(101, "mi_inventory", $Language->MenuPhrase("101", "MenuText"), "inventorylist.php", 145, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}inventory'), FALSE, FALSE, "fa fa-cubes");
$RootMenu->AddMenuItem(102, "mi_inventory_store", $Language->MenuPhrase("102", "MenuText"), "inventory_storelist.php", 145, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}inventory_store'), FALSE, FALSE, "fa fa-cubes");
$RootMenu->AddMenuItem(197, "mi_inventory_report", $Language->MenuPhrase("197", "MenuText"), "inventory_reportlist.php", 145, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}inventory_report'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(198, "mi_store_report", $Language->MenuPhrase("198", "MenuText"), "store_reportlist.php", 145, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}store_report'), FALSE, FALSE, "fas fa-indent");
$RootMenu->AddMenuItem(199, "mi_inventory_record", $Language->MenuPhrase("199", "MenuText"), "inventory_recordlist.php", 145, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}inventory_record'), FALSE, FALSE, "fa fa-cubes");
$RootMenu->AddMenuItem(193, "mci_Dispenser_Module", $Language->MenuPhrase("193", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa fa-futbol-o");
$RootMenu->AddMenuItem(146, "mi_dispenser", $Language->MenuPhrase("146", "MenuText"), "dispenserlist.php", 193, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}dispenser'), FALSE, FALSE, "fa fa-glass");
$RootMenu->AddMenuItem(194, "mi_dispenser_report", $Language->MenuPhrase("194", "MenuText"), "dispenser_reportlist.php", 193, "", AllowListMenu('{DD9080C0-D1CA-431F-831F-CAC8FA61260C}dispenser_report'), FALSE, FALSE, "fas fa-indent");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
