<?php

// Global variable for table object
$spare_part_usage = NULL;

//
// Table class for spare_part_usage
//
class cspare_part_usage extends cTable {
	var $id;
	var $date;
	var $part_name;
	var $maintenance_id;
	var $quantity_in;
	var $quantity_used;
	var $cost;
	var $total_quantity;
	var $total_cost;
	var $maintenance_total_cost;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'spare_part_usage';
		$this->TableName = 'spare_part_usage';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`spare_part_usage`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('spare_part_usage', 'spare_part_usage', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// date
		$this->date = new cField('spare_part_usage', 'spare_part_usage', 'x_date', 'date', '`date`', ew_CastDateFieldForLike('`date`', 0, "DB"), 135, 0, FALSE, '`date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date->Sortable = TRUE; // Allow sort
		$this->date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date'] = &$this->date;

		// part_name
		$this->part_name = new cField('spare_part_usage', 'spare_part_usage', 'x_part_name', 'part_name', '`part_name`', '`part_name`', 200, -1, FALSE, '`part_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->part_name->Sortable = TRUE; // Allow sort
		$this->part_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->part_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->part_name->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['part_name'] = &$this->part_name;

		// maintenance_id
		$this->maintenance_id = new cField('spare_part_usage', 'spare_part_usage', 'x_maintenance_id', 'maintenance_id', '`maintenance_id`', '`maintenance_id`', 3, -1, FALSE, '`maintenance_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->maintenance_id->Sortable = TRUE; // Allow sort
		$this->maintenance_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->maintenance_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->maintenance_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maintenance_id'] = &$this->maintenance_id;

		// quantity_in
		$this->quantity_in = new cField('spare_part_usage', 'spare_part_usage', 'x_quantity_in', 'quantity_in', '`quantity_in`', '`quantity_in`', 200, -1, FALSE, '`quantity_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_in->Sortable = TRUE; // Allow sort
		$this->fields['quantity_in'] = &$this->quantity_in;

		// quantity_used
		$this->quantity_used = new cField('spare_part_usage', 'spare_part_usage', 'x_quantity_used', 'quantity_used', '`quantity_used`', '`quantity_used`', 200, -1, FALSE, '`quantity_used`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_used->Sortable = TRUE; // Allow sort
		$this->fields['quantity_used'] = &$this->quantity_used;

		// cost
		$this->cost = new cField('spare_part_usage', 'spare_part_usage', 'x_cost', 'cost', '`cost`', '`cost`', 131, -1, FALSE, '`cost`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cost->Sortable = TRUE; // Allow sort
		$this->cost->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['cost'] = &$this->cost;

		// total_quantity
		$this->total_quantity = new cField('spare_part_usage', 'spare_part_usage', 'x_total_quantity', 'total_quantity', '`total_quantity`', '`total_quantity`', 200, -1, FALSE, '`total_quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_quantity->Sortable = TRUE; // Allow sort
		$this->fields['total_quantity'] = &$this->total_quantity;

		// total_cost
		$this->total_cost = new cField('spare_part_usage', 'spare_part_usage', 'x_total_cost', 'total_cost', '`total_cost`', '`total_cost`', 131, -1, FALSE, '`total_cost`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_cost->Sortable = TRUE; // Allow sort
		$this->total_cost->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total_cost'] = &$this->total_cost;

		// maintenance_total_cost
		$this->maintenance_total_cost = new cField('spare_part_usage', 'spare_part_usage', 'x_maintenance_total_cost', 'maintenance_total_cost', '`maintenance_total_cost`', '`maintenance_total_cost`', 131, -1, FALSE, '`maintenance_total_cost`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->maintenance_total_cost->Sortable = TRUE; // Allow sort
		$this->maintenance_total_cost->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['maintenance_total_cost'] = &$this->maintenance_total_cost;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`spare_part_usage`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id->setDbValue($conn->Insert_ID());
			$rs['id'] = $this->id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "spare_part_usagelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "spare_part_usageview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "spare_part_usageedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "spare_part_usageadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "spare_part_usagelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("spare_part_usageview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("spare_part_usageview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "spare_part_usageadd.php?" . $this->UrlParm($parm);
		else
			$url = "spare_part_usageadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("spare_part_usageedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("spare_part_usageadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("spare_part_usagedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = $_POST["id"];
			elseif (isset($_GET["id"]))
				$arKeys[] = $_GET["id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->date->setDbValue($rs->fields('date'));
		$this->part_name->setDbValue($rs->fields('part_name'));
		$this->maintenance_id->setDbValue($rs->fields('maintenance_id'));
		$this->quantity_in->setDbValue($rs->fields('quantity_in'));
		$this->quantity_used->setDbValue($rs->fields('quantity_used'));
		$this->cost->setDbValue($rs->fields('cost'));
		$this->total_quantity->setDbValue($rs->fields('total_quantity'));
		$this->total_cost->setDbValue($rs->fields('total_cost'));
		$this->maintenance_total_cost->setDbValue($rs->fields('maintenance_total_cost'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// date
		// part_name
		// maintenance_id
		// quantity_in
		// quantity_used
		// cost
		// total_quantity
		// total_cost
		// maintenance_total_cost
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// part_name
		if (strval($this->part_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->part_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `part_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_module`";
		$sWhereWrk = "";
		$this->part_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->part_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->part_name->ViewValue = $this->part_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->part_name->ViewValue = $this->part_name->CurrentValue;
			}
		} else {
			$this->part_name->ViewValue = NULL;
		}
		$this->part_name->ViewCustomAttributes = "";

		// maintenance_id
		if (strval($this->maintenance_id->CurrentValue) <> "") {
			$sFilterWrk = "`maintenance_id`" . ew_SearchString("=", $this->maintenance_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `maintenance_id`, `generator_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sparepart_view`";
		$sWhereWrk = "";
		$this->maintenance_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintenance_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->maintenance_id->ViewValue = $this->maintenance_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintenance_id->ViewValue = $this->maintenance_id->CurrentValue;
			}
		} else {
			$this->maintenance_id->ViewValue = NULL;
		}
		$this->maintenance_id->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_used
		$this->quantity_used->ViewValue = $this->quantity_used->CurrentValue;
		$this->quantity_used->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// total_cost
		$this->total_cost->ViewValue = $this->total_cost->CurrentValue;
		$this->total_cost->ViewValue = ew_FormatNumber($this->total_cost->ViewValue, 0, -2, -2, -2);
		$this->total_cost->ViewCustomAttributes = "";

		// maintenance_total_cost
		$this->maintenance_total_cost->ViewValue = $this->maintenance_total_cost->CurrentValue;
		$this->maintenance_total_cost->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// date
		$this->date->LinkCustomAttributes = "";
		$this->date->HrefValue = "";
		$this->date->TooltipValue = "";

		// part_name
		$this->part_name->LinkCustomAttributes = "";
		$this->part_name->HrefValue = "";
		$this->part_name->TooltipValue = "";

		// maintenance_id
		$this->maintenance_id->LinkCustomAttributes = "";
		$this->maintenance_id->HrefValue = "";
		$this->maintenance_id->TooltipValue = "";

		// quantity_in
		$this->quantity_in->LinkCustomAttributes = "";
		$this->quantity_in->HrefValue = "";
		$this->quantity_in->TooltipValue = "";

		// quantity_used
		$this->quantity_used->LinkCustomAttributes = "";
		$this->quantity_used->HrefValue = "";
		$this->quantity_used->TooltipValue = "";

		// cost
		$this->cost->LinkCustomAttributes = "";
		$this->cost->HrefValue = "";
		$this->cost->TooltipValue = "";

		// total_quantity
		$this->total_quantity->LinkCustomAttributes = "";
		$this->total_quantity->HrefValue = "";
		$this->total_quantity->TooltipValue = "";

		// total_cost
		$this->total_cost->LinkCustomAttributes = "";
		$this->total_cost->HrefValue = "";
		$this->total_cost->TooltipValue = "";

		// maintenance_total_cost
		$this->maintenance_total_cost->LinkCustomAttributes = "";
		$this->maintenance_total_cost->HrefValue = "";
		$this->maintenance_total_cost->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->EditAttrs["class"] = "form-control";
		$this->date->EditCustomAttributes = "";
		$this->date->EditValue = ew_FormatDateTime($this->date->CurrentValue, 8);
		$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

		// part_name
		$this->part_name->EditAttrs["class"] = "form-control";
		$this->part_name->EditCustomAttributes = "";

		// maintenance_id
		$this->maintenance_id->EditAttrs["class"] = "form-control";
		$this->maintenance_id->EditCustomAttributes = "";

		// quantity_in
		$this->quantity_in->EditAttrs["class"] = "form-control";
		$this->quantity_in->EditCustomAttributes = "";
		$this->quantity_in->EditValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

		// quantity_used
		$this->quantity_used->EditAttrs["class"] = "form-control";
		$this->quantity_used->EditCustomAttributes = "";
		$this->quantity_used->EditValue = $this->quantity_used->CurrentValue;
		$this->quantity_used->PlaceHolder = ew_RemoveHtml($this->quantity_used->FldCaption());

		// cost
		$this->cost->EditAttrs["class"] = "form-control";
		$this->cost->EditCustomAttributes = "";
		$this->cost->EditValue = $this->cost->CurrentValue;
		$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());
		if (strval($this->cost->EditValue) <> "" && is_numeric($this->cost->EditValue)) $this->cost->EditValue = ew_FormatNumber($this->cost->EditValue, -2, -1, -2, 0);

		// total_quantity
		$this->total_quantity->EditAttrs["class"] = "form-control";
		$this->total_quantity->EditCustomAttributes = "";
		$this->total_quantity->EditValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

		// total_cost
		$this->total_cost->EditAttrs["class"] = "form-control";
		$this->total_cost->EditCustomAttributes = "";
		$this->total_cost->EditValue = $this->total_cost->CurrentValue;
		$this->total_cost->PlaceHolder = ew_RemoveHtml($this->total_cost->FldCaption());
		if (strval($this->total_cost->EditValue) <> "" && is_numeric($this->total_cost->EditValue)) $this->total_cost->EditValue = ew_FormatNumber($this->total_cost->EditValue, -2, -2, -2, -2);

		// maintenance_total_cost
		$this->maintenance_total_cost->EditAttrs["class"] = "form-control";
		$this->maintenance_total_cost->EditCustomAttributes = "";
		$this->maintenance_total_cost->EditValue = $this->maintenance_total_cost->CurrentValue;
		$this->maintenance_total_cost->PlaceHolder = ew_RemoveHtml($this->maintenance_total_cost->FldCaption());
		if (strval($this->maintenance_total_cost->EditValue) <> "" && is_numeric($this->maintenance_total_cost->EditValue)) $this->maintenance_total_cost->EditValue = ew_FormatNumber($this->maintenance_total_cost->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
			if (is_numeric($this->total_cost->CurrentValue))
				$this->total_cost->Total += $this->total_cost->CurrentValue; // Accumulate total
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->total_cost->CurrentValue = $this->total_cost->Total;
			$this->total_cost->ViewValue = $this->total_cost->CurrentValue;
			$this->total_cost->ViewValue = ew_FormatNumber($this->total_cost->ViewValue, 0, -2, -2, -2);
			$this->total_cost->ViewCustomAttributes = "";
			$this->total_cost->HrefValue = ""; // Clear href value

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->part_name->Exportable) $Doc->ExportCaption($this->part_name);
					if ($this->maintenance_id->Exportable) $Doc->ExportCaption($this->maintenance_id);
					if ($this->quantity_in->Exportable) $Doc->ExportCaption($this->quantity_in);
					if ($this->quantity_used->Exportable) $Doc->ExportCaption($this->quantity_used);
					if ($this->cost->Exportable) $Doc->ExportCaption($this->cost);
					if ($this->total_quantity->Exportable) $Doc->ExportCaption($this->total_quantity);
					if ($this->total_cost->Exportable) $Doc->ExportCaption($this->total_cost);
					if ($this->maintenance_total_cost->Exportable) $Doc->ExportCaption($this->maintenance_total_cost);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->part_name->Exportable) $Doc->ExportCaption($this->part_name);
					if ($this->maintenance_id->Exportable) $Doc->ExportCaption($this->maintenance_id);
					if ($this->quantity_in->Exportable) $Doc->ExportCaption($this->quantity_in);
					if ($this->quantity_used->Exportable) $Doc->ExportCaption($this->quantity_used);
					if ($this->cost->Exportable) $Doc->ExportCaption($this->cost);
					if ($this->total_quantity->Exportable) $Doc->ExportCaption($this->total_quantity);
					if ($this->total_cost->Exportable) $Doc->ExportCaption($this->total_cost);
					if ($this->maintenance_total_cost->Exportable) $Doc->ExportCaption($this->maintenance_total_cost);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->part_name->Exportable) $Doc->ExportField($this->part_name);
						if ($this->maintenance_id->Exportable) $Doc->ExportField($this->maintenance_id);
						if ($this->quantity_in->Exportable) $Doc->ExportField($this->quantity_in);
						if ($this->quantity_used->Exportable) $Doc->ExportField($this->quantity_used);
						if ($this->cost->Exportable) $Doc->ExportField($this->cost);
						if ($this->total_quantity->Exportable) $Doc->ExportField($this->total_quantity);
						if ($this->total_cost->Exportable) $Doc->ExportField($this->total_cost);
						if ($this->maintenance_total_cost->Exportable) $Doc->ExportField($this->maintenance_total_cost);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->part_name->Exportable) $Doc->ExportField($this->part_name);
						if ($this->maintenance_id->Exportable) $Doc->ExportField($this->maintenance_id);
						if ($this->quantity_in->Exportable) $Doc->ExportField($this->quantity_in);
						if ($this->quantity_used->Exportable) $Doc->ExportField($this->quantity_used);
						if ($this->cost->Exportable) $Doc->ExportField($this->cost);
						if ($this->total_quantity->Exportable) $Doc->ExportField($this->total_quantity);
						if ($this->total_cost->Exportable) $Doc->ExportField($this->total_cost);
						if ($this->maintenance_total_cost->Exportable) $Doc->ExportField($this->maintenance_total_cost);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
				if ($this->id->Exportable) $Doc->ExportAggregate($this->id, '');
				if ($this->date->Exportable) $Doc->ExportAggregate($this->date, '');
				if ($this->part_name->Exportable) $Doc->ExportAggregate($this->part_name, '');
				if ($this->maintenance_id->Exportable) $Doc->ExportAggregate($this->maintenance_id, '');
				if ($this->quantity_in->Exportable) $Doc->ExportAggregate($this->quantity_in, '');
				if ($this->quantity_used->Exportable) $Doc->ExportAggregate($this->quantity_used, '');
				if ($this->cost->Exportable) $Doc->ExportAggregate($this->cost, '');
				if ($this->total_quantity->Exportable) $Doc->ExportAggregate($this->total_quantity, '');
				if ($this->total_cost->Exportable) $Doc->ExportAggregate($this->total_cost, 'TOTAL');
				if ($this->maintenance_total_cost->Exportable) $Doc->ExportAggregate($this->maintenance_total_cost, '');
				$Doc->EndExportRow();
			}
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		ew_Execute("UPDATE `sparepart_module` SET `quantity`= (`quantity` - " . $this->quantity_used->CurrentValue . ") WHERE `id`= ".$this->part_name->CurrentValue."");

	// Get the maintenance_id of the updated row
	$maintenanceId = $rsnew["maintenance_id"];

	// Sum all total_cost for this maintenance ID
	$sql = "SELECT SUM(total_cost) AS total_sum 
			FROM spare_part_usage 
			WHERE maintenance_id = " . intval($maintenanceId);
	$total = ew_ExecuteScalar($sql);

	// Update all rows for this maintenance_id
	/*$updateSql = "UPDATE spare_part_usage 
				  SET maintenance_total_cost = " . ($total ?: 0) . "
				  WHERE maintenance_id = " . intval($maintenanceId);
	ew_Execute($updateSql);*/

	// Update only the last row for this maintenance_id
	$updateSql = "UPDATE spare_part_usage
				  SET maintenance_total_cost = " . ($total ?: 0) . "
				  WHERE id = (
					  SELECT id FROM (
						  SELECT id
						  FROM spare_part_usage
						  WHERE maintenance_id = " . intval($maintenanceId) . "
						  ORDER BY id DESC
						  LIMIT 1
					  ) AS subquery
				  )";
	ew_Execute($updateSql);

	// Get the maintenance_id for this record
	$maintenanceId = $rsnew["maintenance_id"];

	// Calculate total spare part cost for this maintenance
	$sql = "SELECT SUM(total_cost) AS total_sum
			FROM spare_part_usage
			WHERE maintenance_id = " . intval($maintenanceId);
	$totalCost = ew_ExecuteScalar($sql);

	// Ensure NULL becomes 0
	if ($totalCost === null) {
		$totalCost = 0;
	}

	// Update gen_maintenance with the new total
	$updateSql = "UPDATE gen_maintenance
				  SET cost = " . floatval($totalCost) . "
				  WHERE id = " . intval($maintenanceId);
	ew_Execute($updateSql);
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	// Get the maintenance_id of the updated row

	$maintenanceId = $rsnew["maintenance_id"];

	// Sum all total_cost for this maintenance ID
	$sql = "SELECT SUM(total_cost) AS total_sum 
			FROM spare_part_usage 
			WHERE maintenance_id = " . intval($maintenanceId);
	$total = ew_ExecuteScalar($sql);

	// Update all rows for this maintenance_id
	$updateSql = "UPDATE spare_part_usage 
				  SET maintenance_total_cost = " . ($total ?: 0) . "
				  WHERE maintenance_id = " . intval($maintenanceId);
	ew_Execute($updateSql);

	// Get the maintenance_id for this record
	$maintenanceId = $rsnew["maintenance_id"];

	// Calculate total spare part cost for this maintenance
	$sql = "SELECT SUM(total_cost) AS total_sum
			FROM spare_part_usage
			WHERE maintenance_id = " . intval($maintenanceId);
	$totalCost = ew_ExecuteScalar($sql);

	// Ensure NULL becomes 0
	if ($totalCost === null) {
		$totalCost = 0;
	}

	// Update gen_maintenance with the new total
	$updateSql = "UPDATE gen_maintenance
				  SET cost = " . floatval($totalCost) . "
				  WHERE id = " . intval($maintenanceId);
	ew_Execute($updateSql);
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
		if ((CurrentPageID() == "add" || CurrentPageID() == "edit"))  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date->EditValue = $this->date->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
