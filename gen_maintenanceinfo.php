<?php

// Global variable for table object
$gen_maintenance = NULL;

//
// Table class for gen_maintenance
//
class cgen_maintenance extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id;
	var $datetime;
	var $gen_name;
	var $maintenance_type;
	var $running_hours;
	var $cost;
	var $labour_fee;
	var $total;
	var $staff_id;
	var $status;
	var $initiator_action;
	var $initiator_comment;
	var $approver_date;
	var $approver_action;
	var $approver_comment;
	var $approved_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'gen_maintenance';
		$this->TableName = 'gen_maintenance';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`gen_maintenance`";
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
		$this->id = new cField('gen_maintenance', 'gen_maintenance', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// datetime
		$this->datetime = new cField('gen_maintenance', 'gen_maintenance', 'x_datetime', 'datetime', '`datetime`', ew_CastDateFieldForLike('`datetime`', 0, "DB"), 135, 0, FALSE, '`datetime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime->Sortable = TRUE; // Allow sort
		$this->datetime->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['datetime'] = &$this->datetime;

		// gen_name
		$this->gen_name = new cField('gen_maintenance', 'gen_maintenance', 'x_gen_name', 'gen_name', '`gen_name`', '`gen_name`', 3, -1, FALSE, '`gen_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->gen_name->Sortable = TRUE; // Allow sort
		$this->gen_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->gen_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['gen_name'] = &$this->gen_name;

		// maintenance_type
		$this->maintenance_type = new cField('gen_maintenance', 'gen_maintenance', 'x_maintenance_type', 'maintenance_type', '`maintenance_type`', '`maintenance_type`', 16, -1, FALSE, '`maintenance_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->maintenance_type->Sortable = TRUE; // Allow sort
		$this->maintenance_type->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->maintenance_type->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->maintenance_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maintenance_type'] = &$this->maintenance_type;

		// running_hours
		$this->running_hours = new cField('gen_maintenance', 'gen_maintenance', 'x_running_hours', 'running_hours', '`running_hours`', '`running_hours`', 200, -1, FALSE, '`running_hours`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->running_hours->Sortable = TRUE; // Allow sort
		$this->fields['running_hours'] = &$this->running_hours;

		// cost
		$this->cost = new cField('gen_maintenance', 'gen_maintenance', 'x_cost', 'cost', '`cost`', '`cost`', 131, -1, FALSE, '`cost`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cost->Sortable = TRUE; // Allow sort
		$this->cost->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['cost'] = &$this->cost;

		// labour_fee
		$this->labour_fee = new cField('gen_maintenance', 'gen_maintenance', 'x_labour_fee', 'labour_fee', '`labour_fee`', '`labour_fee`', 131, -1, FALSE, '`labour_fee`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->labour_fee->Sortable = TRUE; // Allow sort
		$this->labour_fee->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['labour_fee'] = &$this->labour_fee;

		// total
		$this->total = new cField('gen_maintenance', 'gen_maintenance', 'x_total', 'total', '`total`', '`total`', 131, -1, FALSE, '`total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total->Sortable = TRUE; // Allow sort
		$this->total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total'] = &$this->total;

		// staff_id
		$this->staff_id = new cField('gen_maintenance', 'gen_maintenance', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->staff_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['staff_id'] = &$this->staff_id;

		// status
		$this->status = new cField('gen_maintenance', 'gen_maintenance', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['status'] = &$this->status;

		// initiator_action
		$this->initiator_action = new cField('gen_maintenance', 'gen_maintenance', 'x_initiator_action', 'initiator_action', '`initiator_action`', '`initiator_action`', 3, -1, FALSE, '`initiator_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->initiator_action->Sortable = TRUE; // Allow sort
		$this->initiator_action->OptionCount = 2;
		$this->initiator_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['initiator_action'] = &$this->initiator_action;

		// initiator_comment
		$this->initiator_comment = new cField('gen_maintenance', 'gen_maintenance', 'x_initiator_comment', 'initiator_comment', '`initiator_comment`', '`initiator_comment`', 200, -1, FALSE, '`initiator_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->initiator_comment->Sortable = TRUE; // Allow sort
		$this->fields['initiator_comment'] = &$this->initiator_comment;

		// approver_date
		$this->approver_date = new cField('gen_maintenance', 'gen_maintenance', 'x_approver_date', 'approver_date', '`approver_date`', ew_CastDateFieldForLike('`approver_date`', 0, "DB"), 135, 0, FALSE, '`approver_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approver_date->Sortable = TRUE; // Allow sort
		$this->approver_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['approver_date'] = &$this->approver_date;

		// approver_action
		$this->approver_action = new cField('gen_maintenance', 'gen_maintenance', 'x_approver_action', 'approver_action', '`approver_action`', '`approver_action`', 3, -1, FALSE, '`approver_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approver_action->Sortable = TRUE; // Allow sort
		$this->approver_action->OptionCount = 2;
		$this->approver_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approver_action'] = &$this->approver_action;

		// approver_comment
		$this->approver_comment = new cField('gen_maintenance', 'gen_maintenance', 'x_approver_comment', 'approver_comment', '`approver_comment`', '`approver_comment`', 200, -1, FALSE, '`approver_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approver_comment->Sortable = TRUE; // Allow sort
		$this->fields['approver_comment'] = &$this->approver_comment;

		// approved_by
		$this->approved_by = new cField('gen_maintenance', 'gen_maintenance', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->approved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approved_by'] = &$this->approved_by;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`gen_maintenance`";
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
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
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
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'id';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsold, $rsaudit);
		}
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
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
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
			return "gen_maintenancelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "gen_maintenanceview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "gen_maintenanceedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "gen_maintenanceadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "gen_maintenancelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("gen_maintenanceview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("gen_maintenanceview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "gen_maintenanceadd.php?" . $this->UrlParm($parm);
		else
			$url = "gen_maintenanceadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("gen_maintenanceedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("gen_maintenanceadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("gen_maintenancedelete.php", $this->UrlParm());
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
		$this->datetime->setDbValue($rs->fields('datetime'));
		$this->gen_name->setDbValue($rs->fields('gen_name'));
		$this->maintenance_type->setDbValue($rs->fields('maintenance_type'));
		$this->running_hours->setDbValue($rs->fields('running_hours'));
		$this->cost->setDbValue($rs->fields('cost'));
		$this->labour_fee->setDbValue($rs->fields('labour_fee'));
		$this->total->setDbValue($rs->fields('total'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->status->setDbValue($rs->fields('status'));
		$this->initiator_action->setDbValue($rs->fields('initiator_action'));
		$this->initiator_comment->setDbValue($rs->fields('initiator_comment'));
		$this->approver_date->setDbValue($rs->fields('approver_date'));
		$this->approver_action->setDbValue($rs->fields('approver_action'));
		$this->approver_comment->setDbValue($rs->fields('approver_comment'));
		$this->approved_by->setDbValue($rs->fields('approved_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// datetime
		// gen_name
		// maintenance_type
		// running_hours
		// cost
		// labour_fee
		// total
		// staff_id
		// status
		// initiator_action
		// initiator_comment
		// approver_date
		// approver_action
		// approver_comment
		// approved_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime
		$this->datetime->ViewValue = $this->datetime->CurrentValue;
		$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 0);
		$this->datetime->ViewCustomAttributes = "";

		// gen_name
		if (strval($this->gen_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->gen_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `gen_name` AS `DispFld`, `location` AS `Disp2Fld`, `kva` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `generator_registration`";
		$sWhereWrk = "";
		$this->gen_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gen_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->gen_name->ViewValue = $this->gen_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gen_name->ViewValue = $this->gen_name->CurrentValue;
			}
		} else {
			$this->gen_name->ViewValue = NULL;
		}
		$this->gen_name->ViewCustomAttributes = "";

		// maintenance_type
		if (strval($this->maintenance_type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintenance_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintenance_type`";
		$sWhereWrk = "";
		$this->maintenance_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintenance_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->maintenance_type->ViewValue = $this->maintenance_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintenance_type->ViewValue = $this->maintenance_type->CurrentValue;
			}
		} else {
			$this->maintenance_type->ViewValue = NULL;
		}
		$this->maintenance_type->ViewCustomAttributes = "";

		// running_hours
		$this->running_hours->ViewValue = $this->running_hours->CurrentValue;
		$this->running_hours->ViewCustomAttributes = "";

		// cost
		$this->cost->ViewValue = $this->cost->CurrentValue;
		$this->cost->ViewCustomAttributes = "";

		// labour_fee
		$this->labour_fee->ViewValue = $this->labour_fee->CurrentValue;
		$this->labour_fee->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewCustomAttributes = "";

		// staff_id
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_id->ViewValue = $this->staff_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
			}
		} else {
			$this->staff_id->ViewValue = NULL;
		}
		$this->staff_id->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gen_status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->status->ViewValue = $this->status->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->status->ViewValue = $this->status->CurrentValue;
			}
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

		// initiator_action
		if (strval($this->initiator_action->CurrentValue) <> "") {
			$this->initiator_action->ViewValue = $this->initiator_action->OptionCaption($this->initiator_action->CurrentValue);
		} else {
			$this->initiator_action->ViewValue = NULL;
		}
		$this->initiator_action->ViewCustomAttributes = "";

		// initiator_comment
		$this->initiator_comment->ViewValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->ViewCustomAttributes = "";

		// approver_date
		$this->approver_date->ViewValue = $this->approver_date->CurrentValue;
		$this->approver_date->ViewValue = ew_FormatDateTime($this->approver_date->ViewValue, 0);
		$this->approver_date->ViewCustomAttributes = "";

		// approver_action
		if (strval($this->approver_action->CurrentValue) <> "") {
			$this->approver_action->ViewValue = $this->approver_action->OptionCaption($this->approver_action->CurrentValue);
		} else {
			$this->approver_action->ViewValue = NULL;
		}
		$this->approver_action->ViewCustomAttributes = "";

		// approver_comment
		$this->approver_comment->ViewValue = $this->approver_comment->CurrentValue;
		$this->approver_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		if (strval($this->approved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->approved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->approved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// datetime
		$this->datetime->LinkCustomAttributes = "";
		$this->datetime->HrefValue = "";
		$this->datetime->TooltipValue = "";

		// gen_name
		$this->gen_name->LinkCustomAttributes = "";
		$this->gen_name->HrefValue = "";
		$this->gen_name->TooltipValue = "";

		// maintenance_type
		$this->maintenance_type->LinkCustomAttributes = "";
		$this->maintenance_type->HrefValue = "";
		$this->maintenance_type->TooltipValue = "";

		// running_hours
		$this->running_hours->LinkCustomAttributes = "";
		$this->running_hours->HrefValue = "";
		$this->running_hours->TooltipValue = "";

		// cost
		$this->cost->LinkCustomAttributes = "";
		$this->cost->HrefValue = "";
		$this->cost->TooltipValue = "";

		// labour_fee
		$this->labour_fee->LinkCustomAttributes = "";
		$this->labour_fee->HrefValue = "";
		$this->labour_fee->TooltipValue = "";

		// total
		$this->total->LinkCustomAttributes = "";
		$this->total->HrefValue = "";
		$this->total->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// initiator_action
		$this->initiator_action->LinkCustomAttributes = "";
		$this->initiator_action->HrefValue = "";
		$this->initiator_action->TooltipValue = "";

		// initiator_comment
		$this->initiator_comment->LinkCustomAttributes = "";
		$this->initiator_comment->HrefValue = "";
		$this->initiator_comment->TooltipValue = "";

		// approver_date
		$this->approver_date->LinkCustomAttributes = "";
		$this->approver_date->HrefValue = "";
		$this->approver_date->TooltipValue = "";

		// approver_action
		$this->approver_action->LinkCustomAttributes = "";
		$this->approver_action->HrefValue = "";
		$this->approver_action->TooltipValue = "";

		// approver_comment
		$this->approver_comment->LinkCustomAttributes = "";
		$this->approver_comment->HrefValue = "";
		$this->approver_comment->TooltipValue = "";

		// approved_by
		$this->approved_by->LinkCustomAttributes = "";
		$this->approved_by->HrefValue = "";
		$this->approved_by->TooltipValue = "";

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

		// datetime
		$this->datetime->EditAttrs["class"] = "form-control";
		$this->datetime->EditCustomAttributes = "";
		$this->datetime->EditValue = ew_FormatDateTime($this->datetime->CurrentValue, 8);
		$this->datetime->PlaceHolder = ew_RemoveHtml($this->datetime->FldCaption());

		// gen_name
		$this->gen_name->EditAttrs["class"] = "form-control";
		$this->gen_name->EditCustomAttributes = "";

		// maintenance_type
		$this->maintenance_type->EditAttrs["class"] = "form-control";
		$this->maintenance_type->EditCustomAttributes = "";

		// running_hours
		$this->running_hours->EditAttrs["class"] = "form-control";
		$this->running_hours->EditCustomAttributes = "";
		$this->running_hours->EditValue = $this->running_hours->CurrentValue;
		$this->running_hours->PlaceHolder = ew_RemoveHtml($this->running_hours->FldCaption());

		// cost
		$this->cost->EditAttrs["class"] = "form-control";
		$this->cost->EditCustomAttributes = "";
		$this->cost->EditValue = $this->cost->CurrentValue;
		$this->cost->PlaceHolder = ew_RemoveHtml($this->cost->FldCaption());
		if (strval($this->cost->EditValue) <> "" && is_numeric($this->cost->EditValue)) $this->cost->EditValue = ew_FormatNumber($this->cost->EditValue, -2, -1, -2, 0);

		// labour_fee
		$this->labour_fee->EditAttrs["class"] = "form-control";
		$this->labour_fee->EditCustomAttributes = "";
		$this->labour_fee->EditValue = $this->labour_fee->CurrentValue;
		$this->labour_fee->PlaceHolder = ew_RemoveHtml($this->labour_fee->FldCaption());
		if (strval($this->labour_fee->EditValue) <> "" && is_numeric($this->labour_fee->EditValue)) $this->labour_fee->EditValue = ew_FormatNumber($this->labour_fee->EditValue, -2, -1, -2, 0);

		// total
		$this->total->EditAttrs["class"] = "form-control";
		$this->total->EditCustomAttributes = "";
		$this->total->EditValue = $this->total->CurrentValue;
		$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
		if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -1, -2, 0);

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";

		// initiator_action
		$this->initiator_action->EditCustomAttributes = "";
		$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

		// initiator_comment
		$this->initiator_comment->EditAttrs["class"] = "form-control";
		$this->initiator_comment->EditCustomAttributes = "";
		$this->initiator_comment->EditValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

		// approver_date
		$this->approver_date->EditAttrs["class"] = "form-control";
		$this->approver_date->EditCustomAttributes = "";
		$this->approver_date->EditValue = ew_FormatDateTime($this->approver_date->CurrentValue, 8);
		$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

		// approver_action
		$this->approver_action->EditCustomAttributes = "";
		$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

		// approver_comment
		$this->approver_comment->EditAttrs["class"] = "form-control";
		$this->approver_comment->EditCustomAttributes = "";
		$this->approver_comment->EditValue = $this->approver_comment->CurrentValue;
		$this->approver_comment->PlaceHolder = ew_RemoveHtml($this->approver_comment->FldCaption());

		// approved_by
		$this->approved_by->EditAttrs["class"] = "form-control";
		$this->approved_by->EditCustomAttributes = "";
		$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

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
					if ($this->datetime->Exportable) $Doc->ExportCaption($this->datetime);
					if ($this->gen_name->Exportable) $Doc->ExportCaption($this->gen_name);
					if ($this->maintenance_type->Exportable) $Doc->ExportCaption($this->maintenance_type);
					if ($this->running_hours->Exportable) $Doc->ExportCaption($this->running_hours);
					if ($this->cost->Exportable) $Doc->ExportCaption($this->cost);
					if ($this->labour_fee->Exportable) $Doc->ExportCaption($this->labour_fee);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->approver_date->Exportable) $Doc->ExportCaption($this->approver_date);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approver_comment->Exportable) $Doc->ExportCaption($this->approver_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->datetime->Exportable) $Doc->ExportCaption($this->datetime);
					if ($this->gen_name->Exportable) $Doc->ExportCaption($this->gen_name);
					if ($this->maintenance_type->Exportable) $Doc->ExportCaption($this->maintenance_type);
					if ($this->running_hours->Exportable) $Doc->ExportCaption($this->running_hours);
					if ($this->cost->Exportable) $Doc->ExportCaption($this->cost);
					if ($this->labour_fee->Exportable) $Doc->ExportCaption($this->labour_fee);
					if ($this->total->Exportable) $Doc->ExportCaption($this->total);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->approver_date->Exportable) $Doc->ExportCaption($this->approver_date);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approver_comment->Exportable) $Doc->ExportCaption($this->approver_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
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

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->datetime->Exportable) $Doc->ExportField($this->datetime);
						if ($this->gen_name->Exportable) $Doc->ExportField($this->gen_name);
						if ($this->maintenance_type->Exportable) $Doc->ExportField($this->maintenance_type);
						if ($this->running_hours->Exportable) $Doc->ExportField($this->running_hours);
						if ($this->cost->Exportable) $Doc->ExportField($this->cost);
						if ($this->labour_fee->Exportable) $Doc->ExportField($this->labour_fee);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->approver_date->Exportable) $Doc->ExportField($this->approver_date);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approver_comment->Exportable) $Doc->ExportField($this->approver_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->datetime->Exportable) $Doc->ExportField($this->datetime);
						if ($this->gen_name->Exportable) $Doc->ExportField($this->gen_name);
						if ($this->maintenance_type->Exportable) $Doc->ExportField($this->maintenance_type);
						if ($this->running_hours->Exportable) $Doc->ExportField($this->running_hours);
						if ($this->cost->Exportable) $Doc->ExportField($this->cost);
						if ($this->labour_fee->Exportable) $Doc->ExportField($this->labour_fee);
						if ($this->total->Exportable) $Doc->ExportField($this->total);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->approver_date->Exportable) $Doc->ExportField($this->approver_date);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approver_comment->Exportable) $Doc->ExportField($this->approver_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'gen_maintenance';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'gen_maintenance';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'gen_maintenance';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'gen_maintenance';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
		if (CurrentUserLevel() == 2) {

			//ew_AddFilter($filter, "`status` in (0) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
			ew_AddFilter($filter, "`status` in (0)");
		}
		if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "`status` in (1)");
		}
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
			// Officer Only

		if (CurrentPageID() == "add" && CurrentUserLevel() == 2) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;

				//$rsnew["issued_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Maintenance Complated sent for Approved &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Maintenance Initiated and Saved &#x2714;");
			}			
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		date_default_timezone_set('Africa/Lagos');
		$now = new DateTime();
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 2) {
			$this->datetime->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->datetime->EditValue = $this->datetime->CurrentValue;

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 3)) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;
				$rsnew["approver_action"] = NULL;
				$rsnew["approved_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Maintenance Items sent for Review and Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}

		 // Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["datetime"] = $now->format('Y-m-d H:i:s');
			$rsnew["approved_by"] = $_SESSION['Staff_ID'];
		}

			// Administartor - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime"] = $rsold["datetime"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["gen_name"] = $rsold["gen_name"];
			$rsnew["cost"] = $rsold["cost"];
			$rsnew["maintenance_type"] = $rsold["maintenance_type"];
			$rsnew["labour_fee"] = $rsold["labour_fee"];
			$rsnew["total"] = $rsold["total"];
			$rsnew["staff_id"] = $rsold["staff_id"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
		}

			// Approved by Administrators
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4)) {
				$rsnew["date"] = $now->format('Y-m-d H:i:s');
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Approved by Administrators
				if ($this->approver_action->CurrentValue == 0 && $this->status->CurrentValue == 1 ) {

					// New
					if ($this->status->CurrentValue == 1) {
						$rsnew["status"] = 0;					
						$rsnew["approver_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Record Decliend &#x2714;");
				}

				// Approved by Administrators
				if ($this->approver_action->CurrentValue == 1 ) {

					// New
					if ($this->status->CurrentValue == 1) {
						$rsnew["status"] = 2;					
						$rsnew["approver_action"] = 1;
						$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
					}
					$this->setSuccessMessage("&#x25C9; Approved successfully Reviewed and Closed &#x2714;");
				}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
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
			$this->datetime->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->datetime->EditValue = $this->datetime->CurrentValue;
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		}
		if (CurrentPageID() == "edit" && (CurrentUserLevel() == 3 )) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->approver_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->approver_date->EditValue = $this->approver_date->CurrentValue;
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

			if (CurrentPageID() == "add") {
				if (CurrentUserLevel() == 2) {
					$this->datetime->ReadOnly = TRUE;
					$this->gen_name->Visible = TRUE;
					$this->maintenance_type->Visible = TRUE;
					$this->running_hours->Visible = TRUE;
					$this->cost->ReadOnly = TRUE;
					$this->labour_fee->Visible = TRUE;
					$this->total->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
				}
			}

				// Edit Page
			if (CurrentPageID() == "edit") {
				if ((CurrentUserLevel() == 2||CurrentUserLevel() == 12)) {
					$this->datetime->ReadOnly = TRUE;
					$this->gen_name->ReadOnly = TRUE;
					$this->maintenance_type->ReadOnly = TRUE;
					$this->running_hours->ReadOnly = TRUE;
					$this->cost->ReadOnly = TRUE;
					$this->labour_fee->Visible = TRUE;
					$this->total->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 3) {
					$this->datetime->ReadOnly = TRUE;
					$this->gen_name->ReadOnly = TRUE;
					$this->maintenance_type->ReadOnly = TRUE;
					$this->running_hours->ReadOnly = TRUE;
					$this->cost->ReadOnly = TRUE;
					$this->labour_fee->ReadOnly = TRUE;
					$this->total->ReadOnly = TRUE;
					$this->initiator_action->ReadOnly = FALSE;
					$this->initiator_comment->ReadOnly = TRUE;
					$this->approver_date->ReadOnly = TRUE;
					$this->approver_action->Visible = TRUE;
					$this->approver_comment->Visible = TRUE;

					//$this->approved_by->Visible = FALSE;
				}
			}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
