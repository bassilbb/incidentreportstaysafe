<?php

// Global variable for table object
$maintenance_history = NULL;

//
// Table class for maintenance_history
//
class cmaintenance_history extends cTable {
	var $id;
	var $date_initiated;
	var $reference_id;
	var $staff_id;
	var $staff_name;
	var $department;
	var $branch;
	var $buildings;
	var $floors;
	var $items;
	var $priority;
	var $descrption;
	var $status;
	var $date_maintained;
	var $maintenance_action;
	var $maintenance_comment;
	var $maintained_by;
	var $reviewed_date;
	var $reviewed_action;
	var $reviewed_comment;
	var $reviewed_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'maintenance_history';
		$this->TableName = 'maintenance_history';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`maintenance_history`";
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
		$this->id = new cField('maintenance_history', 'maintenance_history', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// date_initiated
		$this->date_initiated = new cField('maintenance_history', 'maintenance_history', 'x_date_initiated', 'date_initiated', '`date_initiated`', ew_CastDateFieldForLike('`date_initiated`', 0, "DB"), 135, 0, FALSE, '`date_initiated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_initiated->Sortable = TRUE; // Allow sort
		$this->date_initiated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_initiated'] = &$this->date_initiated;

		// reference_id
		$this->reference_id = new cField('maintenance_history', 'maintenance_history', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// staff_id
		$this->staff_id = new cField('maintenance_history', 'maintenance_history', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 200, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->fields['staff_id'] = &$this->staff_id;

		// staff_name
		$this->staff_name = new cField('maintenance_history', 'maintenance_history', 'x_staff_name', 'staff_name', '`staff_name`', '`staff_name`', 3, -1, FALSE, '`staff_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->staff_name->Sortable = TRUE; // Allow sort
		$this->staff_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->staff_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->staff_name->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staff_name'] = &$this->staff_name;

		// department
		$this->department = new cField('maintenance_history', 'maintenance_history', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->fields['department'] = &$this->department;

		// branch
		$this->branch = new cField('maintenance_history', 'maintenance_history', 'x_branch', 'branch', '`branch`', '`branch`', 3, -1, FALSE, '`branch`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->branch->Sortable = TRUE; // Allow sort
		$this->fields['branch'] = &$this->branch;

		// buildings
		$this->buildings = new cField('maintenance_history', 'maintenance_history', 'x_buildings', 'buildings', '`buildings`', '`buildings`', 3, -1, FALSE, '`buildings`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->buildings->Sortable = TRUE; // Allow sort
		$this->buildings->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->buildings->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->buildings->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['buildings'] = &$this->buildings;

		// floors
		$this->floors = new cField('maintenance_history', 'maintenance_history', 'x_floors', 'floors', '`floors`', '`floors`', 3, -1, FALSE, '`floors`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->floors->Sortable = TRUE; // Allow sort
		$this->floors->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->floors->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->floors->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['floors'] = &$this->floors;

		// items
		$this->items = new cField('maintenance_history', 'maintenance_history', 'x_items', 'items', '`items`', '`items`', 3, -1, FALSE, '`items`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->items->Sortable = TRUE; // Allow sort
		$this->items->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['items'] = &$this->items;

		// priority
		$this->priority = new cField('maintenance_history', 'maintenance_history', 'x_priority', 'priority', '`priority`', '`priority`', 3, -1, FALSE, '`priority`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->priority->Sortable = TRUE; // Allow sort
		$this->priority->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->priority->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->priority->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['priority'] = &$this->priority;

		// descrption
		$this->descrption = new cField('maintenance_history', 'maintenance_history', 'x_descrption', 'descrption', '`descrption`', '`descrption`', 201, -1, FALSE, '`descrption`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->descrption->Sortable = TRUE; // Allow sort
		$this->descrption->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['descrption'] = &$this->descrption;

		// status
		$this->status = new cField('maintenance_history', 'maintenance_history', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// date_maintained
		$this->date_maintained = new cField('maintenance_history', 'maintenance_history', 'x_date_maintained', 'date_maintained', '`date_maintained`', ew_CastDateFieldForLike('`date_maintained`', 14, "DB"), 135, 14, FALSE, '`date_maintained`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_maintained->Sortable = TRUE; // Allow sort
		$this->date_maintained->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['date_maintained'] = &$this->date_maintained;

		// maintenance_action
		$this->maintenance_action = new cField('maintenance_history', 'maintenance_history', 'x_maintenance_action', 'maintenance_action', '`maintenance_action`', '`maintenance_action`', 3, -1, FALSE, '`maintenance_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->maintenance_action->Sortable = TRUE; // Allow sort
		$this->maintenance_action->OptionCount = 2;
		$this->maintenance_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maintenance_action'] = &$this->maintenance_action;

		// maintenance_comment
		$this->maintenance_comment = new cField('maintenance_history', 'maintenance_history', 'x_maintenance_comment', 'maintenance_comment', '`maintenance_comment`', '`maintenance_comment`', 201, -1, FALSE, '`maintenance_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->maintenance_comment->Sortable = TRUE; // Allow sort
		$this->fields['maintenance_comment'] = &$this->maintenance_comment;

		// maintained_by
		$this->maintained_by = new cField('maintenance_history', 'maintenance_history', 'x_maintained_by', 'maintained_by', '`maintained_by`', '`maintained_by`', 3, -1, FALSE, '`maintained_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->maintained_by->Sortable = TRUE; // Allow sort
		$this->maintained_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maintained_by'] = &$this->maintained_by;

		// reviewed_date
		$this->reviewed_date = new cField('maintenance_history', 'maintenance_history', 'x_reviewed_date', 'reviewed_date', '`reviewed_date`', ew_CastDateFieldForLike('`reviewed_date`', 14, "DB"), 135, 14, FALSE, '`reviewed_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reviewed_date->Sortable = TRUE; // Allow sort
		$this->reviewed_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['reviewed_date'] = &$this->reviewed_date;

		// reviewed_action
		$this->reviewed_action = new cField('maintenance_history', 'maintenance_history', 'x_reviewed_action', 'reviewed_action', '`reviewed_action`', '`reviewed_action`', 3, -1, FALSE, '`reviewed_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->reviewed_action->Sortable = TRUE; // Allow sort
		$this->reviewed_action->OptionCount = 3;
		$this->reviewed_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['reviewed_action'] = &$this->reviewed_action;

		// reviewed_comment
		$this->reviewed_comment = new cField('maintenance_history', 'maintenance_history', 'x_reviewed_comment', 'reviewed_comment', '`reviewed_comment`', '`reviewed_comment`', 200, -1, FALSE, '`reviewed_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->reviewed_comment->Sortable = TRUE; // Allow sort
		$this->fields['reviewed_comment'] = &$this->reviewed_comment;

		// reviewed_by
		$this->reviewed_by = new cField('maintenance_history', 'maintenance_history', 'x_reviewed_by', 'reviewed_by', '`reviewed_by`', '`reviewed_by`', 3, -1, FALSE, '`reviewed_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reviewed_by->Sortable = TRUE; // Allow sort
		$this->reviewed_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['reviewed_by'] = &$this->reviewed_by;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`maintenance_history`";
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
			return "maintenance_historylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "maintenance_historyview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "maintenance_historyedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "maintenance_historyadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "maintenance_historylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("maintenance_historyview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("maintenance_historyview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "maintenance_historyadd.php?" . $this->UrlParm($parm);
		else
			$url = "maintenance_historyadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("maintenance_historyedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("maintenance_historyadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("maintenance_historydelete.php", $this->UrlParm());
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
		$this->date_initiated->setDbValue($rs->fields('date_initiated'));
		$this->reference_id->setDbValue($rs->fields('reference_id'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->staff_name->setDbValue($rs->fields('staff_name'));
		$this->department->setDbValue($rs->fields('department'));
		$this->branch->setDbValue($rs->fields('branch'));
		$this->buildings->setDbValue($rs->fields('buildings'));
		$this->floors->setDbValue($rs->fields('floors'));
		$this->items->setDbValue($rs->fields('items'));
		$this->priority->setDbValue($rs->fields('priority'));
		$this->descrption->setDbValue($rs->fields('descrption'));
		$this->status->setDbValue($rs->fields('status'));
		$this->date_maintained->setDbValue($rs->fields('date_maintained'));
		$this->maintenance_action->setDbValue($rs->fields('maintenance_action'));
		$this->maintenance_comment->setDbValue($rs->fields('maintenance_comment'));
		$this->maintained_by->setDbValue($rs->fields('maintained_by'));
		$this->reviewed_date->setDbValue($rs->fields('reviewed_date'));
		$this->reviewed_action->setDbValue($rs->fields('reviewed_action'));
		$this->reviewed_comment->setDbValue($rs->fields('reviewed_comment'));
		$this->reviewed_by->setDbValue($rs->fields('reviewed_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// date_initiated
		// reference_id
		// staff_id
		// staff_name
		// department
		// branch
		// buildings
		// floors
		// items
		// priority
		// descrption
		// status
		// date_maintained
		// maintenance_action
		// maintenance_comment
		// maintained_by
		// reviewed_date
		// reviewed_action
		// reviewed_comment
		// reviewed_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staff_id->ViewValue = $this->staff_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
			}
		} else {
			$this->staff_id->ViewValue = NULL;
		}
		$this->staff_id->ViewCustomAttributes = "";

		// staff_name
		if (strval($this->staff_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staff_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->staff_name->ViewValue = $this->staff_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staff_name->ViewValue = $this->staff_name->CurrentValue;
			}
		} else {
			$this->staff_name->ViewValue = NULL;
		}
		$this->staff_name->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->department->ViewValue = $this->department->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->department->ViewValue = $this->department->CurrentValue;
			}
		} else {
			$this->department->ViewValue = NULL;
		}
		$this->department->ViewCustomAttributes = "";

		// branch
		$this->branch->ViewValue = $this->branch->CurrentValue;
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->branch->ViewValue = $this->branch->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->branch->ViewValue = $this->branch->CurrentValue;
			}
		} else {
			$this->branch->ViewValue = NULL;
		}
		$this->branch->ViewCustomAttributes = "";

		// buildings
		if (strval($this->buildings->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->buildings->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `buildings`";
		$sWhereWrk = "";
		$this->buildings->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->buildings, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->buildings->ViewValue = $this->buildings->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->buildings->ViewValue = $this->buildings->CurrentValue;
			}
		} else {
			$this->buildings->ViewValue = NULL;
		}
		$this->buildings->ViewCustomAttributes = "";

		// floors
		if (strval($this->floors->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->floors->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `floors`";
		$sWhereWrk = "";
		$this->floors->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->floors, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->floors->ViewValue = $this->floors->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->floors->ViewValue = $this->floors->CurrentValue;
			}
		} else {
			$this->floors->ViewValue = NULL;
		}
		$this->floors->ViewCustomAttributes = "";

		// items
		if (strval($this->items->CurrentValue) <> "") {
			$arwrk = explode(",", $this->items->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `items`";
		$sWhereWrk = "";
		$this->items->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->items, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->items->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->items->ViewValue .= $this->items->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->items->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->items->ViewValue = $this->items->CurrentValue;
			}
		} else {
			$this->items->ViewValue = NULL;
		}
		$this->items->ViewCustomAttributes = "";

		// priority
		if (strval($this->priority->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->priority->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->priority->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->priority, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->priority->ViewValue = $this->priority->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->priority->ViewValue = $this->priority->CurrentValue;
			}
		} else {
			$this->priority->ViewValue = NULL;
		}
		$this->priority->ViewCustomAttributes = "";

		// descrption
		$this->descrption->ViewValue = $this->descrption->CurrentValue;
		$this->descrption->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `maintained_status`";
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

		// date_maintained
		$this->date_maintained->ViewValue = $this->date_maintained->CurrentValue;
		$this->date_maintained->ViewValue = ew_FormatDateTime($this->date_maintained->ViewValue, 14);
		$this->date_maintained->ViewCustomAttributes = "";

		// maintenance_action
		if (strval($this->maintenance_action->CurrentValue) <> "") {
			$this->maintenance_action->ViewValue = $this->maintenance_action->OptionCaption($this->maintenance_action->CurrentValue);
		} else {
			$this->maintenance_action->ViewValue = NULL;
		}
		$this->maintenance_action->ViewCustomAttributes = "";

		// maintenance_comment
		$this->maintenance_comment->ViewValue = $this->maintenance_comment->CurrentValue;
		$this->maintenance_comment->ViewCustomAttributes = "";

		// maintained_by
		$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
		if (strval($this->maintained_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->maintained_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->maintained_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->maintained_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->maintained_by->ViewValue = $this->maintained_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->maintained_by->ViewValue = $this->maintained_by->CurrentValue;
			}
		} else {
			$this->maintained_by->ViewValue = NULL;
		}
		$this->maintained_by->ViewCustomAttributes = "";

		// reviewed_date
		$this->reviewed_date->ViewValue = $this->reviewed_date->CurrentValue;
		$this->reviewed_date->ViewValue = ew_FormatDateTime($this->reviewed_date->ViewValue, 14);
		$this->reviewed_date->ViewCustomAttributes = "";

		// reviewed_action
		if (strval($this->reviewed_action->CurrentValue) <> "") {
			$this->reviewed_action->ViewValue = $this->reviewed_action->OptionCaption($this->reviewed_action->CurrentValue);
		} else {
			$this->reviewed_action->ViewValue = NULL;
		}
		$this->reviewed_action->ViewCustomAttributes = "";

		// reviewed_comment
		$this->reviewed_comment->ViewValue = $this->reviewed_comment->CurrentValue;
		$this->reviewed_comment->ViewCustomAttributes = "";

		// reviewed_by
		$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
		if (strval($this->reviewed_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->reviewed_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->reviewed_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->reviewed_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->reviewed_by->ViewValue = $this->reviewed_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->reviewed_by->ViewValue = $this->reviewed_by->CurrentValue;
			}
		} else {
			$this->reviewed_by->ViewValue = NULL;
		}
		$this->reviewed_by->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// date_initiated
		$this->date_initiated->LinkCustomAttributes = "";
		$this->date_initiated->HrefValue = "";
		$this->date_initiated->TooltipValue = "";

		// reference_id
		$this->reference_id->LinkCustomAttributes = "";
		$this->reference_id->HrefValue = "";
		$this->reference_id->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// staff_name
		$this->staff_name->LinkCustomAttributes = "";
		$this->staff_name->HrefValue = "";
		$this->staff_name->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// branch
		$this->branch->LinkCustomAttributes = "";
		$this->branch->HrefValue = "";
		$this->branch->TooltipValue = "";

		// buildings
		$this->buildings->LinkCustomAttributes = "";
		$this->buildings->HrefValue = "";
		$this->buildings->TooltipValue = "";

		// floors
		$this->floors->LinkCustomAttributes = "";
		$this->floors->HrefValue = "";
		$this->floors->TooltipValue = "";

		// items
		$this->items->LinkCustomAttributes = "";
		$this->items->HrefValue = "";
		$this->items->TooltipValue = "";

		// priority
		$this->priority->LinkCustomAttributes = "";
		$this->priority->HrefValue = "";
		$this->priority->TooltipValue = "";

		// descrption
		$this->descrption->LinkCustomAttributes = "";
		$this->descrption->HrefValue = "";
		$this->descrption->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// date_maintained
		$this->date_maintained->LinkCustomAttributes = "";
		$this->date_maintained->HrefValue = "";
		$this->date_maintained->TooltipValue = "";

		// maintenance_action
		$this->maintenance_action->LinkCustomAttributes = "";
		$this->maintenance_action->HrefValue = "";
		$this->maintenance_action->TooltipValue = "";

		// maintenance_comment
		$this->maintenance_comment->LinkCustomAttributes = "";
		$this->maintenance_comment->HrefValue = "";
		$this->maintenance_comment->TooltipValue = "";

		// maintained_by
		$this->maintained_by->LinkCustomAttributes = "";
		$this->maintained_by->HrefValue = "";
		$this->maintained_by->TooltipValue = "";

		// reviewed_date
		$this->reviewed_date->LinkCustomAttributes = "";
		$this->reviewed_date->HrefValue = "";
		$this->reviewed_date->TooltipValue = "";

		// reviewed_action
		$this->reviewed_action->LinkCustomAttributes = "";
		$this->reviewed_action->HrefValue = "";
		$this->reviewed_action->TooltipValue = "";

		// reviewed_comment
		$this->reviewed_comment->LinkCustomAttributes = "";
		$this->reviewed_comment->HrefValue = "";
		$this->reviewed_comment->TooltipValue = "";

		// reviewed_by
		$this->reviewed_by->LinkCustomAttributes = "";
		$this->reviewed_by->HrefValue = "";
		$this->reviewed_by->TooltipValue = "";

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

		// date_initiated
		$this->date_initiated->EditAttrs["class"] = "form-control";
		$this->date_initiated->EditCustomAttributes = "";
		$this->date_initiated->EditValue = ew_FormatDateTime($this->date_initiated->CurrentValue, 8);
		$this->date_initiated->PlaceHolder = ew_RemoveHtml($this->date_initiated->FldCaption());

		// reference_id
		$this->reference_id->EditAttrs["class"] = "form-control";
		$this->reference_id->EditCustomAttributes = "";
		$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

		// staff_name
		$this->staff_name->EditAttrs["class"] = "form-control";
		$this->staff_name->EditCustomAttributes = "";

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";
		$this->department->EditValue = $this->department->CurrentValue;
		$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

		// branch
		$this->branch->EditAttrs["class"] = "form-control";
		$this->branch->EditCustomAttributes = "";
		$this->branch->EditValue = $this->branch->CurrentValue;
		$this->branch->PlaceHolder = ew_RemoveHtml($this->branch->FldCaption());

		// buildings
		$this->buildings->EditAttrs["class"] = "form-control";
		$this->buildings->EditCustomAttributes = "";

		// floors
		$this->floors->EditAttrs["class"] = "form-control";
		$this->floors->EditCustomAttributes = "";

		// items
		$this->items->EditCustomAttributes = "";

		// priority
		$this->priority->EditAttrs["class"] = "form-control";
		$this->priority->EditCustomAttributes = "";

		// descrption
		$this->descrption->EditAttrs["class"] = "form-control";
		$this->descrption->EditCustomAttributes = "";
		$this->descrption->EditValue = $this->descrption->CurrentValue;
		$this->descrption->PlaceHolder = ew_RemoveHtml($this->descrption->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";

		// date_maintained
		$this->date_maintained->EditAttrs["class"] = "form-control";
		$this->date_maintained->EditCustomAttributes = "";
		$this->date_maintained->EditValue = ew_FormatDateTime($this->date_maintained->CurrentValue, 14);
		$this->date_maintained->PlaceHolder = ew_RemoveHtml($this->date_maintained->FldCaption());

		// maintenance_action
		$this->maintenance_action->EditCustomAttributes = "";
		$this->maintenance_action->EditValue = $this->maintenance_action->Options(FALSE);

		// maintenance_comment
		$this->maintenance_comment->EditAttrs["class"] = "form-control";
		$this->maintenance_comment->EditCustomAttributes = "";
		$this->maintenance_comment->EditValue = $this->maintenance_comment->CurrentValue;
		$this->maintenance_comment->PlaceHolder = ew_RemoveHtml($this->maintenance_comment->FldCaption());

		// maintained_by
		$this->maintained_by->EditAttrs["class"] = "form-control";
		$this->maintained_by->EditCustomAttributes = "";
		$this->maintained_by->EditValue = $this->maintained_by->CurrentValue;
		$this->maintained_by->PlaceHolder = ew_RemoveHtml($this->maintained_by->FldCaption());

		// reviewed_date
		$this->reviewed_date->EditAttrs["class"] = "form-control";
		$this->reviewed_date->EditCustomAttributes = "";
		$this->reviewed_date->EditValue = ew_FormatDateTime($this->reviewed_date->CurrentValue, 14);
		$this->reviewed_date->PlaceHolder = ew_RemoveHtml($this->reviewed_date->FldCaption());

		// reviewed_action
		$this->reviewed_action->EditCustomAttributes = "";
		$this->reviewed_action->EditValue = $this->reviewed_action->Options(FALSE);

		// reviewed_comment
		$this->reviewed_comment->EditAttrs["class"] = "form-control";
		$this->reviewed_comment->EditCustomAttributes = "";
		$this->reviewed_comment->EditValue = $this->reviewed_comment->CurrentValue;
		$this->reviewed_comment->PlaceHolder = ew_RemoveHtml($this->reviewed_comment->FldCaption());

		// reviewed_by
		$this->reviewed_by->EditAttrs["class"] = "form-control";
		$this->reviewed_by->EditCustomAttributes = "";
		$this->reviewed_by->EditValue = $this->reviewed_by->CurrentValue;
		$this->reviewed_by->PlaceHolder = ew_RemoveHtml($this->reviewed_by->FldCaption());

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
					if ($this->date_initiated->Exportable) $Doc->ExportCaption($this->date_initiated);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->staff_name->Exportable) $Doc->ExportCaption($this->staff_name);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->buildings->Exportable) $Doc->ExportCaption($this->buildings);
					if ($this->floors->Exportable) $Doc->ExportCaption($this->floors);
					if ($this->items->Exportable) $Doc->ExportCaption($this->items);
					if ($this->priority->Exportable) $Doc->ExportCaption($this->priority);
					if ($this->descrption->Exportable) $Doc->ExportCaption($this->descrption);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->date_maintained->Exportable) $Doc->ExportCaption($this->date_maintained);
					if ($this->maintenance_action->Exportable) $Doc->ExportCaption($this->maintenance_action);
					if ($this->maintenance_comment->Exportable) $Doc->ExportCaption($this->maintenance_comment);
					if ($this->maintained_by->Exportable) $Doc->ExportCaption($this->maintained_by);
					if ($this->reviewed_date->Exportable) $Doc->ExportCaption($this->reviewed_date);
					if ($this->reviewed_action->Exportable) $Doc->ExportCaption($this->reviewed_action);
					if ($this->reviewed_comment->Exportable) $Doc->ExportCaption($this->reviewed_comment);
					if ($this->reviewed_by->Exportable) $Doc->ExportCaption($this->reviewed_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date_initiated->Exportable) $Doc->ExportCaption($this->date_initiated);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->staff_name->Exportable) $Doc->ExportCaption($this->staff_name);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->buildings->Exportable) $Doc->ExportCaption($this->buildings);
					if ($this->floors->Exportable) $Doc->ExportCaption($this->floors);
					if ($this->items->Exportable) $Doc->ExportCaption($this->items);
					if ($this->priority->Exportable) $Doc->ExportCaption($this->priority);
					if ($this->descrption->Exportable) $Doc->ExportCaption($this->descrption);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->date_maintained->Exportable) $Doc->ExportCaption($this->date_maintained);
					if ($this->maintenance_action->Exportable) $Doc->ExportCaption($this->maintenance_action);
					if ($this->maintained_by->Exportable) $Doc->ExportCaption($this->maintained_by);
					if ($this->reviewed_date->Exportable) $Doc->ExportCaption($this->reviewed_date);
					if ($this->reviewed_action->Exportable) $Doc->ExportCaption($this->reviewed_action);
					if ($this->reviewed_comment->Exportable) $Doc->ExportCaption($this->reviewed_comment);
					if ($this->reviewed_by->Exportable) $Doc->ExportCaption($this->reviewed_by);
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
						if ($this->date_initiated->Exportable) $Doc->ExportField($this->date_initiated);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->staff_name->Exportable) $Doc->ExportField($this->staff_name);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->buildings->Exportable) $Doc->ExportField($this->buildings);
						if ($this->floors->Exportable) $Doc->ExportField($this->floors);
						if ($this->items->Exportable) $Doc->ExportField($this->items);
						if ($this->priority->Exportable) $Doc->ExportField($this->priority);
						if ($this->descrption->Exportable) $Doc->ExportField($this->descrption);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->date_maintained->Exportable) $Doc->ExportField($this->date_maintained);
						if ($this->maintenance_action->Exportable) $Doc->ExportField($this->maintenance_action);
						if ($this->maintenance_comment->Exportable) $Doc->ExportField($this->maintenance_comment);
						if ($this->maintained_by->Exportable) $Doc->ExportField($this->maintained_by);
						if ($this->reviewed_date->Exportable) $Doc->ExportField($this->reviewed_date);
						if ($this->reviewed_action->Exportable) $Doc->ExportField($this->reviewed_action);
						if ($this->reviewed_comment->Exportable) $Doc->ExportField($this->reviewed_comment);
						if ($this->reviewed_by->Exportable) $Doc->ExportField($this->reviewed_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date_initiated->Exportable) $Doc->ExportField($this->date_initiated);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->staff_name->Exportable) $Doc->ExportField($this->staff_name);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->buildings->Exportable) $Doc->ExportField($this->buildings);
						if ($this->floors->Exportable) $Doc->ExportField($this->floors);
						if ($this->items->Exportable) $Doc->ExportField($this->items);
						if ($this->priority->Exportable) $Doc->ExportField($this->priority);
						if ($this->descrption->Exportable) $Doc->ExportField($this->descrption);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->date_maintained->Exportable) $Doc->ExportField($this->date_maintained);
						if ($this->maintenance_action->Exportable) $Doc->ExportField($this->maintenance_action);
						if ($this->maintained_by->Exportable) $Doc->ExportField($this->maintained_by);
						if ($this->reviewed_date->Exportable) $Doc->ExportField($this->reviewed_date);
						if ($this->reviewed_action->Exportable) $Doc->ExportField($this->reviewed_action);
						if ($this->reviewed_comment->Exportable) $Doc->ExportField($this->reviewed_comment);
						if ($this->reviewed_by->Exportable) $Doc->ExportField($this->reviewed_by);
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
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);
		// Highligh rows in color based on the status

		if (CurrentPageID() == "list") {

			//$this->branch_code->Visible = FALSE;
			if ($this->status->CurrentValue == 1) {
				$this->id->CellCssStyle = "color: orange; text-align: left;";
				$this->date_initiated->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_name->CellCssStyle = "color: orange; text-align: left;";
				$this->maintained_by->CellCssStyle = "color: orange; text-align: left;";
				$this->department->CellCssStyle = "color: orange; text-align: left;";
				$this->branch->CellCssStyle = "color: orange; text-align: left;";
				$this->reference_id->CellCssStyle = "color: orange; text-align: left;";
				$this->priority->CellCssStyle = "color: orange; text-align: left;";
				$this->status->CellCssStyle = "color: orange; text-align: left;";
				$this->buildings->CellCssStyle = "color: orange; text-align: left;";
				$this->floors->CellCssStyle = "color: orange; text-align: left;";
				$this->items->CellCssStyle = "color: orange; text-align: left;";
				$this->status->CellCssStyle = "color: orange; text-align: left;";
				$this->description->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->status->CurrentValue == 2) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->date_initiated->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_name->CellCssStyle = "color: blue; text-align: left;";
				$this->maintained_by->CellCssStyle = "color: blue; text-align: left;";
				$this->department->CellCssStyle = "color: blue; text-align: left;";
				$this->branch->CellCssStyle = "color: blue; text-align: left;";
				$this->reference_id->CellCssStyle = "color: blue; text-align: left;";
				$this->priority->CellCssStyle = "color: blue; text-align: left;";
				$this->buildings->CellCssStyle = "color: blue; text-align: left;";
				$this->floors->CellCssStyle = "color: blue; text-align: left;";
				$this->items->CellCssStyle = "color: blue; text-align: left;";
				$this->status->CellCssStyle = "color: blue; text-align: left;";
				$this->description->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->status->CurrentValue == 3) {
				$this->id->CellCssStyle = "color: green; text-align: left;";
				$this->date_initiated->CellCssStyle = "color: green; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->staff_name->CellCssStyle = "color: green; text-align: left;";
				$this->maintained_by->CellCssStyle = "color: green; text-align: left;";
				$this->department->CellCssStyle = "color: green; text-align: left;";
				$this->branch->CellCssStyle = "color: green; text-align: left;";
				$this->reference_id->CellCssStyle = "color: green; text-align: left;";
				$this->priority->CellCssStyle = "color: green; text-align: left;";
				$this->buildings->CellCssStyle = "color: green; text-align: left;";
				$this->floors->CellCssStyle = "color: green; text-align: left;";
				$this->items->CellCssStyle = "color: green; text-align: left;";
				$this->status->CellCssStyle = "color: green; text-align: left;";
				$this->description->CellCssStyle = "color: green; text-align: left;";
			}
			if ($this->status->CurrentValue == 0) {
				$this->id->CellCssStyle = "color: teal; text-align: left;";
				$this->date_initiated->CellCssStyle = "color: teal; text-align: left;";
				$this->staff_id->CellCssStyle = "color: teal; text-align: left;";
				$this->staff_name->CellCssStyle = "color: teal; text-align: left;";
				$this->maintained_by->CellCssStyle = "color: teal; text-align: left;";
				$this->reference_id->CellCssStyle = "color: teal; text-align: left;";
				$this->department->CellCssStyle = "color: teal; text-align: left;";
				$this->branch->CellCssStyle = "color: teal; text-align: left;";
				$this->priority->CellCssStyle = "color: teal; text-align: left;";
				$this->buildings->CellCssStyle = "color: teal; text-align: left;";
				$this->floors->CellCssStyle = "color: teal; text-align: left;";
				$this->items->CellCssStyle = "color: teal; text-align: left;";
				$this->status->CellCssStyle = "color: teal; text-align: left;";
				$this->descrption->CellCssStyle = "color: teal; text-align: left;";
			}
			/*if ($this->status->CurrentValue == 5) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->date_initiated->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->staff_name->CellCssStyle = "color: green; text-align: left;";
				$this->maintained_by->CellCssStyle = "color: blue; text-align: left;";
				$this->department->CellCssStyle = "color: blue; text-align: left;";
				$this->branch->CellCssStyle = "color: blue; text-align: left;";
				$this->reference_id->CellCssStyle = "color: blue; text-align: left;";
				$this->priority->CellCssStyle = "color: blue; text-align: left;";
				$this->buildings->CellCssStyle = "color: blue; text-align: left;";
				$this->floors->CellCssStyle = "color: blue; text-align: left;";
				$this->items->CellCssStyle = "color: blue; text-align: left;";
			}*/
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
