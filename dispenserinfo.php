<?php

// Global variable for table object
$dispenser = NULL;

//
// Table class for dispenser
//
class cdispenser extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id;
	var $date_initiated;
	var $referrence_id;
	var $staff_id;
	var $fullname;
	var $department;
	var $location;
	var $sub_location;
	var $venue;
	var $type;
	var $action_taken;
	var $initiator_action;
	var $initiator_comment;
	var $status;
	var $initiated_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'dispenser';
		$this->TableName = 'dispenser';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`dispenser`";
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
		$this->id = new cField('dispenser', 'dispenser', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// date_initiated
		$this->date_initiated = new cField('dispenser', 'dispenser', 'x_date_initiated', 'date_initiated', '`date_initiated`', ew_CastDateFieldForLike('`date_initiated`', 0, "DB"), 133, 0, FALSE, '`date_initiated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_initiated->Sortable = TRUE; // Allow sort
		$this->date_initiated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_initiated'] = &$this->date_initiated;

		// referrence_id
		$this->referrence_id = new cField('dispenser', 'dispenser', 'x_referrence_id', 'referrence_id', '`referrence_id`', '`referrence_id`', 200, -1, FALSE, '`referrence_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->referrence_id->Sortable = TRUE; // Allow sort
		$this->fields['referrence_id'] = &$this->referrence_id;

		// staff_id
		$this->staff_id = new cField('dispenser', 'dispenser', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->fields['staff_id'] = &$this->staff_id;

		// fullname
		$this->fullname = new cField('dispenser', 'dispenser', 'x_fullname', 'fullname', '`fullname`', '`fullname`', 200, -1, FALSE, '`fullname`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fullname->Sortable = TRUE; // Allow sort
		$this->fields['fullname'] = &$this->fullname;

		// department
		$this->department = new cField('dispenser', 'dispenser', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->fields['department'] = &$this->department;

		// location
		$this->location = new cField('dispenser', 'dispenser', 'x_location', 'location', '`location`', '`location`', 3, -1, FALSE, '`location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->location->Sortable = TRUE; // Allow sort
		$this->location->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->location->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->location->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['location'] = &$this->location;

		// sub_location
		$this->sub_location = new cField('dispenser', 'dispenser', 'x_sub_location', 'sub_location', '`sub_location`', '`sub_location`', 3, -1, FALSE, '`sub_location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sub_location->Sortable = TRUE; // Allow sort
		$this->sub_location->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sub_location->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sub_location->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sub_location'] = &$this->sub_location;

		// venue
		$this->venue = new cField('dispenser', 'dispenser', 'x_venue', 'venue', '`venue`', '`venue`', 3, -1, FALSE, '`venue`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->venue->Sortable = TRUE; // Allow sort
		$this->venue->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->venue->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->venue->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['venue'] = &$this->venue;

		// type
		$this->type = new cField('dispenser', 'dispenser', 'x_type', 'type', '`type`', '`type`', 3, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->type->Sortable = TRUE; // Allow sort
		$this->type->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->type->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['type'] = &$this->type;

		// action_taken
		$this->action_taken = new cField('dispenser', 'dispenser', 'x_action_taken', 'action_taken', '`action_taken`', '`action_taken`', 3, -1, FALSE, '`action_taken`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->action_taken->Sortable = TRUE; // Allow sort
		$this->action_taken->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->action_taken->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->action_taken->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['action_taken'] = &$this->action_taken;

		// initiator_action
		$this->initiator_action = new cField('dispenser', 'dispenser', 'x_initiator_action', 'initiator_action', '`initiator_action`', '`initiator_action`', 3, -1, FALSE, '`initiator_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->initiator_action->Sortable = TRUE; // Allow sort
		$this->initiator_action->OptionCount = 4;
		$this->initiator_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['initiator_action'] = &$this->initiator_action;

		// initiator_comment
		$this->initiator_comment = new cField('dispenser', 'dispenser', 'x_initiator_comment', 'initiator_comment', '`initiator_comment`', '`initiator_comment`', 201, -1, FALSE, '`initiator_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->initiator_comment->Sortable = TRUE; // Allow sort
		$this->fields['initiator_comment'] = &$this->initiator_comment;

		// status
		$this->status = new cField('dispenser', 'dispenser', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->AdvancedSearch->SearchValueDefault = 0;
		$this->status->AdvancedSearch->SearchOperatorDefault = "=";
		$this->status->AdvancedSearch->SearchOperatorDefault2 = "";
		$this->status->AdvancedSearch->SearchConditionDefault = "AND";
		$this->fields['status'] = &$this->status;

		// initiated_by
		$this->initiated_by = new cField('dispenser', 'dispenser', 'x_initiated_by', 'initiated_by', '`initiated_by`', '`initiated_by`', 3, -1, FALSE, '`initiated_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->initiated_by->Sortable = TRUE; // Allow sort
		$this->fields['initiated_by'] = &$this->initiated_by;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`dispenser`";
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
			return "dispenserlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "dispenserview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "dispenseredit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "dispenseradd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "dispenserlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("dispenserview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("dispenserview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "dispenseradd.php?" . $this->UrlParm($parm);
		else
			$url = "dispenseradd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("dispenseredit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("dispenseradd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("dispenserdelete.php", $this->UrlParm());
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
		$this->referrence_id->setDbValue($rs->fields('referrence_id'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->fullname->setDbValue($rs->fields('fullname'));
		$this->department->setDbValue($rs->fields('department'));
		$this->location->setDbValue($rs->fields('location'));
		$this->sub_location->setDbValue($rs->fields('sub_location'));
		$this->venue->setDbValue($rs->fields('venue'));
		$this->type->setDbValue($rs->fields('type'));
		$this->action_taken->setDbValue($rs->fields('action_taken'));
		$this->initiator_action->setDbValue($rs->fields('initiator_action'));
		$this->initiator_comment->setDbValue($rs->fields('initiator_comment'));
		$this->status->setDbValue($rs->fields('status'));
		$this->initiated_by->setDbValue($rs->fields('initiated_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// date_initiated
		// referrence_id
		// staff_id
		// fullname
		// department
		// location
		// sub_location
		// venue
		// type
		// action_taken
		// initiator_action
		// initiator_comment
		// status
		// initiated_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_initiated
		$this->date_initiated->ViewValue = $this->date_initiated->CurrentValue;
		$this->date_initiated->ViewValue = ew_FormatDateTime($this->date_initiated->ViewValue, 0);
		$this->date_initiated->ViewCustomAttributes = "";

		// referrence_id
		$this->referrence_id->ViewValue = $this->referrence_id->CurrentValue;
		$this->referrence_id->ViewCustomAttributes = "";

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

		// fullname
		$this->fullname->ViewValue = $this->fullname->CurrentValue;
		if (strval($this->fullname->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->fullname->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->fullname->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->fullname, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->fullname->ViewValue = $this->fullname->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->fullname->ViewValue = $this->fullname->CurrentValue;
			}
		} else {
			$this->fullname->ViewValue = NULL;
		}
		$this->fullname->ViewCustomAttributes = "";

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

		// location
		if (strval($this->location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->location->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->location->ViewValue = $this->location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->location->ViewValue = $this->location->CurrentValue;
			}
		} else {
			$this->location->ViewValue = NULL;
		}
		$this->location->ViewCustomAttributes = "";

		// sub_location
		if (strval($this->sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_sub` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_location->ViewValue = $this->sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_location->ViewValue = $this->sub_location->CurrentValue;
			}
		} else {
			$this->sub_location->ViewValue = NULL;
		}
		$this->sub_location->ViewCustomAttributes = "";

		// venue
		if (strval($this->venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->venue->ViewValue = $this->venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->venue->ViewValue = $this->venue->CurrentValue;
			}
		} else {
			$this->venue->ViewValue = NULL;
		}
		$this->venue->ViewCustomAttributes = "";

		// type
		if (strval($this->type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, `serial_no` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_type`";
		$sWhereWrk = "";
		$this->type->LookupFilters = array("dx1" => '`description`', "dx2" => '`serial_no`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->type->ViewValue = $this->type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->type->ViewValue = $this->type->CurrentValue;
			}
		} else {
			$this->type->ViewValue = NULL;
		}
		$this->type->ViewCustomAttributes = "";

		// action_taken
		if (strval($this->action_taken->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->action_taken->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `action_taken`";
		$sWhereWrk = "";
		$this->action_taken->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->action_taken, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->action_taken->ViewValue = $this->action_taken->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->action_taken->ViewValue = $this->action_taken->CurrentValue;
			}
		} else {
			$this->action_taken->ViewValue = NULL;
		}
		$this->action_taken->ViewCustomAttributes = "";

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

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dispenser_status`";
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

		// initiated_by
		$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
		if (strval($this->initiated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->initiated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->initiated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->initiated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->initiated_by->ViewValue = $this->initiated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->initiated_by->ViewValue = $this->initiated_by->CurrentValue;
			}
		} else {
			$this->initiated_by->ViewValue = NULL;
		}
		$this->initiated_by->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// date_initiated
		$this->date_initiated->LinkCustomAttributes = "";
		$this->date_initiated->HrefValue = "";
		$this->date_initiated->TooltipValue = "";

		// referrence_id
		$this->referrence_id->LinkCustomAttributes = "";
		$this->referrence_id->HrefValue = "";
		$this->referrence_id->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// fullname
		$this->fullname->LinkCustomAttributes = "";
		$this->fullname->HrefValue = "";
		$this->fullname->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// location
		$this->location->LinkCustomAttributes = "";
		$this->location->HrefValue = "";
		$this->location->TooltipValue = "";

		// sub_location
		$this->sub_location->LinkCustomAttributes = "";
		$this->sub_location->HrefValue = "";
		$this->sub_location->TooltipValue = "";

		// venue
		$this->venue->LinkCustomAttributes = "";
		$this->venue->HrefValue = "";
		$this->venue->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// action_taken
		$this->action_taken->LinkCustomAttributes = "";
		$this->action_taken->HrefValue = "";
		$this->action_taken->TooltipValue = "";

		// initiator_action
		$this->initiator_action->LinkCustomAttributes = "";
		$this->initiator_action->HrefValue = "";
		$this->initiator_action->TooltipValue = "";

		// initiator_comment
		$this->initiator_comment->LinkCustomAttributes = "";
		$this->initiator_comment->HrefValue = "";
		$this->initiator_comment->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// initiated_by
		$this->initiated_by->LinkCustomAttributes = "";
		$this->initiated_by->HrefValue = "";
		$this->initiated_by->TooltipValue = "";

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

		// referrence_id
		$this->referrence_id->EditAttrs["class"] = "form-control";
		$this->referrence_id->EditCustomAttributes = "";
		$this->referrence_id->EditValue = $this->referrence_id->CurrentValue;
		$this->referrence_id->PlaceHolder = ew_RemoveHtml($this->referrence_id->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

		// fullname
		$this->fullname->EditAttrs["class"] = "form-control";
		$this->fullname->EditCustomAttributes = "";
		$this->fullname->EditValue = $this->fullname->CurrentValue;
		$this->fullname->PlaceHolder = ew_RemoveHtml($this->fullname->FldCaption());

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";
		$this->department->EditValue = $this->department->CurrentValue;
		$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

		// location
		$this->location->EditAttrs["class"] = "form-control";
		$this->location->EditCustomAttributes = "";

		// sub_location
		$this->sub_location->EditCustomAttributes = "";

		// venue
		$this->venue->EditAttrs["class"] = "form-control";
		$this->venue->EditCustomAttributes = "";

		// type
		$this->type->EditAttrs["class"] = "form-control";
		$this->type->EditCustomAttributes = "";

		// action_taken
		$this->action_taken->EditAttrs["class"] = "form-control";
		$this->action_taken->EditCustomAttributes = "";

		// initiator_action
		$this->initiator_action->EditCustomAttributes = "";
		$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

		// initiator_comment
		$this->initiator_comment->EditAttrs["class"] = "form-control";
		$this->initiator_comment->EditCustomAttributes = "";
		$this->initiator_comment->EditValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// initiated_by
		$this->initiated_by->EditAttrs["class"] = "form-control";
		$this->initiated_by->EditCustomAttributes = "";
		$this->initiated_by->EditValue = $this->initiated_by->CurrentValue;
		$this->initiated_by->PlaceHolder = ew_RemoveHtml($this->initiated_by->FldCaption());

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
					if ($this->date_initiated->Exportable) $Doc->ExportCaption($this->date_initiated);
					if ($this->referrence_id->Exportable) $Doc->ExportCaption($this->referrence_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->fullname->Exportable) $Doc->ExportCaption($this->fullname);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->location->Exportable) $Doc->ExportCaption($this->location);
					if ($this->sub_location->Exportable) $Doc->ExportCaption($this->sub_location);
					if ($this->venue->Exportable) $Doc->ExportCaption($this->venue);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->action_taken->Exportable) $Doc->ExportCaption($this->action_taken);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiated_by->Exportable) $Doc->ExportCaption($this->initiated_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date_initiated->Exportable) $Doc->ExportCaption($this->date_initiated);
					if ($this->referrence_id->Exportable) $Doc->ExportCaption($this->referrence_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->fullname->Exportable) $Doc->ExportCaption($this->fullname);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->location->Exportable) $Doc->ExportCaption($this->location);
					if ($this->sub_location->Exportable) $Doc->ExportCaption($this->sub_location);
					if ($this->venue->Exportable) $Doc->ExportCaption($this->venue);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->action_taken->Exportable) $Doc->ExportCaption($this->action_taken);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiated_by->Exportable) $Doc->ExportCaption($this->initiated_by);
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
						if ($this->date_initiated->Exportable) $Doc->ExportField($this->date_initiated);
						if ($this->referrence_id->Exportable) $Doc->ExportField($this->referrence_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->fullname->Exportable) $Doc->ExportField($this->fullname);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->location->Exportable) $Doc->ExportField($this->location);
						if ($this->sub_location->Exportable) $Doc->ExportField($this->sub_location);
						if ($this->venue->Exportable) $Doc->ExportField($this->venue);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->action_taken->Exportable) $Doc->ExportField($this->action_taken);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiated_by->Exportable) $Doc->ExportField($this->initiated_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date_initiated->Exportable) $Doc->ExportField($this->date_initiated);
						if ($this->referrence_id->Exportable) $Doc->ExportField($this->referrence_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->fullname->Exportable) $Doc->ExportField($this->fullname);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->location->Exportable) $Doc->ExportField($this->location);
						if ($this->sub_location->Exportable) $Doc->ExportField($this->sub_location);
						if ($this->venue->Exportable) $Doc->ExportField($this->venue);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->action_taken->Exportable) $Doc->ExportField($this->action_taken);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiated_by->Exportable) $Doc->ExportField($this->initiated_by);
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
		$table = 'dispenser';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'dispenser';

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
		$table = 'dispenser';

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
		$table = 'dispenser';

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
		if (CurrentUserLevel() == 1) {
			ew_AddFilter($filter, "`status` in (0,2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}
		if (CurrentUserLevel() == 2) {
			ew_AddFilter($filter, "`status` in (0,2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}
		if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "`status` in (0,2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
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
		    //$rsnew["last_updated_date"] = ew_CurrentDateTime();
		 	 //$rsnew["last_updated_by"] = $_SESSION['Staff_ID'];

		 	 $rsnew["initiated_by"] = $_SESSION['Staff_ID'];

		// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 1 && ($this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || (CurrentUserLevel() == 2 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])||(CurrentUserLevel() == 3 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;
				$rsnew["initiated_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Dispenser Has Been Cleaned &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && CurrentUserLevel() == 1) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Dispenser Has Not Been Cleaned &#x2714;");
			}			
		}

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 1 ||CurrentUserLevel() == 2 || CurrentUserLevel() == 3) {

					// Save and forward
			if ($this->initiator_action->CurrentValue == 2 && $this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) {
				$rsnew["status"] = 2;
				$rsnew["initiator_action"] = 2;
				$this->setSuccessMessage("&#x25C9; Dispenser Under Maintenance &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && CurrentUserLevel() == 2 || CurrentUserLevel() == 3 || CurrentUserLevel() == 1) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Dispenser Has Not Been Cleaned &#x2714;");
			}			
		}

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 1 ||CurrentUserLevel() == 2 || CurrentUserLevel() == 3) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 3 && $this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 3;
				$this->setSuccessMessage("&#x25C9; Dispenser Maintenance completed &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Dispenser Has Not Been Cleaned &#x2714;");
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
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 4) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
			$rsnew["datet_initiated"] = $now->format('Y-m-d H:i:s');

			//$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
			//$rsnew["datetime_approved"] = $now->format('Y-m-d H:i:s');

			$rsnew["initiated_by"] = $_SESSION['Staff_ID'];
		}	

		// Officer Only
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && $this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;
				$this->setSuccessMessage("&#x25C9; Dispenser Has Been Cleaned &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && CurrentUserLevel() == 1) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Dispenser Has Not Been Cleaned &#x2714;");
			}
		}

		// Officer Only
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && $this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;
				$this->setSuccessMessage("&#x25C9; Dispenser Has Been Cleaned &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && CurrentUserLevel() == 2) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Dispenser Has Not Been Cleaned &#x2714;");
			}
		}

		// Officer Only
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && $this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) {
				$rsnew["status"] = 1;
				$rsnew["initiator_action"] = 1;
				$this->setSuccessMessage("&#x25C9; Dispenser Has Been Cleaned &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Dispenser Has Not Been Cleaned &#x2714;");
			}
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
		if ((CurrentPageID() == "add" || CurrentPageID() == "edit") && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 1))  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_initiated->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_initiated->EditValue = $this->date_initiated->CurrentValue;		
		}
		if (CurrentPageID() == "add" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2 || CurrentUserLevel()   == 3 || CurrentUserLevel() == 4)) {
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->fullname->CurrentValue = $_SESSION['Staff_ID'];
			$this->fullname->EditValue = $this->fullname->CurrentValue;
			$this->initiated_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->initiated_by->EditValue = $this->initiated_by->CurrentValue;
			$this->department->CurrentValue = $_SESSION['Department'];
			$this->department->EditValue = $this->department->CurrentValue;
		}
		if (CurrentPageID() == "add")  {
			$this->referrence_id->CurrentValue = $_SESSION['REFN_ID'];
			$this->referrence_id->EditValue = $this->referrence_id->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);   

		if (CurrentPageID() == "add") {
				if (CurrentUserLevel() == 1) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->fullname->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->referrence_id->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
				}
				if (CurrentUserLevel() == 2) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->fullname->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->referrence_id->ReadOnly = TRUE;

					//$this->initiated_by->Visible = FALSE;
					$this->department->ReadOnly = TRUE;
				}
				if (CurrentUserLevel() == 3) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->fullname->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->referrence_id->ReadOnly = TRUE;

					//$this->initiated_by->Visible = FALSE;
					$this->department->ReadOnly = TRUE;
				}
			}

			// Edit Page
			   if (CurrentPageID() == "edit") {
					if (CurrentUserLevel() == 1 && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->fullname->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->referrence_id->ReadOnly = TRUE;

					//$this->initiated_by->Visible = FALSE;
					$this->department->ReadOnly = TRUE;
				  }
				  if (CurrentUserLevel() == 2 && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->fullname->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->referrence_id->ReadOnly = TRUE;

					//$this->initiated_by->Visible = FALSE;
					$this->department->ReadOnly = TRUE;
				  }
				  if (CurrentUserLevel() == 3 && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
					$this->date_initiated->ReadOnly = TRUE;
					$this->fullname->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->referrence_id->ReadOnly = TRUE;

					//$this->initiated_by->Visible = FALSE;
					$this->department->ReadOnly = TRUE;
				  }
			  }
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
