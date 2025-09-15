<?php

// Global variable for table object
$requisition_report = NULL;

//
// Table class for requisition_report
//
class crequisition_report extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $code;
	var $date;
	var $reference;
	var $staff_id;
	var $outward_location;
	var $delivery_point;
	var $name;
	var $organization;
	var $designation;
	var $department;
	var $item_description;
	var $driver_name;
	var $vehicle_no;
	var $requester_action;
	var $requester_comment;
	var $date_authorized;
	var $authorizer_name;
	var $authorizer_action;
	var $authorizer_comment;
	var $status;
	var $rep_date;
	var $rep_name;
	var $outward_datetime;
	var $rep_action;
	var $rep_comment;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'requisition_report';
		$this->TableName = 'requisition_report';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`requisition_report`";
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

		// code
		$this->code = new cField('requisition_report', 'requisition_report', 'x_code', 'code', '`code`', '`code`', 3, -1, FALSE, '`code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->code->Sortable = TRUE; // Allow sort
		$this->code->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['code'] = &$this->code;

		// date
		$this->date = new cField('requisition_report', 'requisition_report', 'x_date', 'date', '`date`', ew_CastDateFieldForLike('`date`', 14, "DB"), 133, 14, FALSE, '`date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date->Sortable = TRUE; // Allow sort
		$this->fields['date'] = &$this->date;

		// reference
		$this->reference = new cField('requisition_report', 'requisition_report', 'x_reference', 'reference', '`reference`', '`reference`', 200, -1, FALSE, '`reference`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference->Sortable = TRUE; // Allow sort
		$this->fields['reference'] = &$this->reference;

		// staff_id
		$this->staff_id = new cField('requisition_report', 'requisition_report', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->fields['staff_id'] = &$this->staff_id;

		// outward_location
		$this->outward_location = new cField('requisition_report', 'requisition_report', 'x_outward_location', 'outward_location', '`outward_location`', '`outward_location`', 200, -1, FALSE, '`outward_location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->outward_location->Sortable = TRUE; // Allow sort
		$this->fields['outward_location'] = &$this->outward_location;

		// delivery_point
		$this->delivery_point = new cField('requisition_report', 'requisition_report', 'x_delivery_point', 'delivery_point', '`delivery_point`', '`delivery_point`', 200, -1, FALSE, '`delivery_point`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->delivery_point->Sortable = TRUE; // Allow sort
		$this->fields['delivery_point'] = &$this->delivery_point;

		// name
		$this->name = new cField('requisition_report', 'requisition_report', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['name'] = &$this->name;

		// organization
		$this->organization = new cField('requisition_report', 'requisition_report', 'x_organization', 'organization', '`organization`', '`organization`', 200, -1, FALSE, '`organization`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->organization->Sortable = TRUE; // Allow sort
		$this->organization->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->organization->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['organization'] = &$this->organization;

		// designation
		$this->designation = new cField('requisition_report', 'requisition_report', 'x_designation', 'designation', '`designation`', '`designation`', 200, -1, FALSE, '`designation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->designation->Sortable = TRUE; // Allow sort
		$this->fields['designation'] = &$this->designation;

		// department
		$this->department = new cField('requisition_report', 'requisition_report', 'x_department', 'department', '`department`', '`department`', 200, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->fields['department'] = &$this->department;

		// item_description
		$this->item_description = new cField('requisition_report', 'requisition_report', 'x_item_description', 'item_description', '`item_description`', '`item_description`', 200, -1, FALSE, '`item_description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->item_description->Sortable = TRUE; // Allow sort
		$this->fields['item_description'] = &$this->item_description;

		// driver_name
		$this->driver_name = new cField('requisition_report', 'requisition_report', 'x_driver_name', 'driver_name', '`driver_name`', '`driver_name`', 200, -1, FALSE, '`driver_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->driver_name->Sortable = TRUE; // Allow sort
		$this->fields['driver_name'] = &$this->driver_name;

		// vehicle_no
		$this->vehicle_no = new cField('requisition_report', 'requisition_report', 'x_vehicle_no', 'vehicle_no', '`vehicle_no`', '`vehicle_no`', 200, -1, FALSE, '`vehicle_no`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->vehicle_no->Sortable = TRUE; // Allow sort
		$this->fields['vehicle_no'] = &$this->vehicle_no;

		// requester_action
		$this->requester_action = new cField('requisition_report', 'requisition_report', 'x_requester_action', 'requester_action', '`requester_action`', '`requester_action`', 3, -1, FALSE, '`requester_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->requester_action->Sortable = TRUE; // Allow sort
		$this->requester_action->OptionCount = 2;
		$this->requester_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['requester_action'] = &$this->requester_action;

		// requester_comment
		$this->requester_comment = new cField('requisition_report', 'requisition_report', 'x_requester_comment', 'requester_comment', '`requester_comment`', '`requester_comment`', 200, -1, FALSE, '`requester_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->requester_comment->Sortable = TRUE; // Allow sort
		$this->fields['requester_comment'] = &$this->requester_comment;

		// date_authorized
		$this->date_authorized = new cField('requisition_report', 'requisition_report', 'x_date_authorized', 'date_authorized', '`date_authorized`', ew_CastDateFieldForLike('`date_authorized`', 17, "DB"), 135, 17, FALSE, '`date_authorized`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_authorized->Sortable = TRUE; // Allow sort
		$this->fields['date_authorized'] = &$this->date_authorized;

		// authorizer_name
		$this->authorizer_name = new cField('requisition_report', 'requisition_report', 'x_authorizer_name', 'authorizer_name', '`authorizer_name`', '`authorizer_name`', 200, -1, FALSE, '`authorizer_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->authorizer_name->Sortable = TRUE; // Allow sort
		$this->fields['authorizer_name'] = &$this->authorizer_name;

		// authorizer_action
		$this->authorizer_action = new cField('requisition_report', 'requisition_report', 'x_authorizer_action', 'authorizer_action', '`authorizer_action`', '`authorizer_action`', 3, -1, FALSE, '`authorizer_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->authorizer_action->Sortable = TRUE; // Allow sort
		$this->authorizer_action->OptionCount = 2;
		$this->authorizer_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['authorizer_action'] = &$this->authorizer_action;

		// authorizer_comment
		$this->authorizer_comment = new cField('requisition_report', 'requisition_report', 'x_authorizer_comment', 'authorizer_comment', '`authorizer_comment`', '`authorizer_comment`', 200, -1, FALSE, '`authorizer_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->authorizer_comment->Sortable = TRUE; // Allow sort
		$this->fields['authorizer_comment'] = &$this->authorizer_comment;

		// status
		$this->status = new cField('requisition_report', 'requisition_report', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// rep_date
		$this->rep_date = new cField('requisition_report', 'requisition_report', 'x_rep_date', 'rep_date', '`rep_date`', ew_CastDateFieldForLike('`rep_date`', 17, "DB"), 133, 17, FALSE, '`rep_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rep_date->Sortable = TRUE; // Allow sort
		$this->rep_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['rep_date'] = &$this->rep_date;

		// rep_name
		$this->rep_name = new cField('requisition_report', 'requisition_report', 'x_rep_name', 'rep_name', '`rep_name`', '`rep_name`', 200, -1, FALSE, '`rep_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rep_name->Sortable = TRUE; // Allow sort
		$this->fields['rep_name'] = &$this->rep_name;

		// outward_datetime
		$this->outward_datetime = new cField('requisition_report', 'requisition_report', 'x_outward_datetime', 'outward_datetime', '`outward_datetime`', ew_CastDateFieldForLike('`outward_datetime`', 17, "DB"), 135, 17, FALSE, '`outward_datetime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->outward_datetime->Sortable = TRUE; // Allow sort
		$this->outward_datetime->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['outward_datetime'] = &$this->outward_datetime;

		// rep_action
		$this->rep_action = new cField('requisition_report', 'requisition_report', 'x_rep_action', 'rep_action', '`rep_action`', '`rep_action`', 3, -1, FALSE, '`rep_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->rep_action->Sortable = TRUE; // Allow sort
		$this->rep_action->OptionCount = 2;
		$this->rep_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['rep_action'] = &$this->rep_action;

		// rep_comment
		$this->rep_comment = new cField('requisition_report', 'requisition_report', 'x_rep_comment', 'rep_comment', '`rep_comment`', '`rep_comment`', 200, -1, FALSE, '`rep_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->rep_comment->Sortable = TRUE; // Allow sort
		$this->fields['rep_comment'] = &$this->rep_comment;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`requisition_report`";
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
			$this->code->setDbValue($conn->Insert_ID());
			$rs['code'] = $this->code->DbValue;
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
			$fldname = 'code';
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
			if (array_key_exists('code', $rs))
				ew_AddFilter($where, ew_QuotedName('code', $this->DBID) . '=' . ew_QuotedValue($rs['code'], $this->code->FldDataType, $this->DBID));
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
		return "`code` = @code@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->code->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->code->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@code@", ew_AdjustSql($this->code->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "requisition_reportlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "requisition_reportview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "requisition_reportedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "requisition_reportadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "requisition_reportlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("requisition_reportview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("requisition_reportview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "requisition_reportadd.php?" . $this->UrlParm($parm);
		else
			$url = "requisition_reportadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("requisition_reportedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("requisition_reportadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("requisition_reportdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "code:" . ew_VarToJson($this->code->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->code->CurrentValue)) {
			$sUrl .= "code=" . urlencode($this->code->CurrentValue);
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
			if ($isPost && isset($_POST["code"]))
				$arKeys[] = $_POST["code"];
			elseif (isset($_GET["code"]))
				$arKeys[] = $_GET["code"];
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
			$this->code->CurrentValue = $key;
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
		$this->code->setDbValue($rs->fields('code'));
		$this->date->setDbValue($rs->fields('date'));
		$this->reference->setDbValue($rs->fields('reference'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->outward_location->setDbValue($rs->fields('outward_location'));
		$this->delivery_point->setDbValue($rs->fields('delivery_point'));
		$this->name->setDbValue($rs->fields('name'));
		$this->organization->setDbValue($rs->fields('organization'));
		$this->designation->setDbValue($rs->fields('designation'));
		$this->department->setDbValue($rs->fields('department'));
		$this->item_description->setDbValue($rs->fields('item_description'));
		$this->driver_name->setDbValue($rs->fields('driver_name'));
		$this->vehicle_no->setDbValue($rs->fields('vehicle_no'));
		$this->requester_action->setDbValue($rs->fields('requester_action'));
		$this->requester_comment->setDbValue($rs->fields('requester_comment'));
		$this->date_authorized->setDbValue($rs->fields('date_authorized'));
		$this->authorizer_name->setDbValue($rs->fields('authorizer_name'));
		$this->authorizer_action->setDbValue($rs->fields('authorizer_action'));
		$this->authorizer_comment->setDbValue($rs->fields('authorizer_comment'));
		$this->status->setDbValue($rs->fields('status'));
		$this->rep_date->setDbValue($rs->fields('rep_date'));
		$this->rep_name->setDbValue($rs->fields('rep_name'));
		$this->outward_datetime->setDbValue($rs->fields('outward_datetime'));
		$this->rep_action->setDbValue($rs->fields('rep_action'));
		$this->rep_comment->setDbValue($rs->fields('rep_comment'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// code
		// date
		// reference
		// staff_id
		// outward_location
		// delivery_point
		// name
		// organization
		// designation
		// department
		// item_description
		// driver_name
		// vehicle_no
		// requester_action
		// requester_comment
		// date_authorized
		// authorizer_name
		// authorizer_action
		// authorizer_comment
		// status
		// rep_date
		// rep_name
		// outward_datetime
		// rep_action
		// rep_comment
		// code

		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 14);
		$this->date->ViewCustomAttributes = "";

		// reference
		$this->reference->ViewValue = $this->reference->CurrentValue;
		$this->reference->ViewCustomAttributes = "";

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

		// outward_location
		$this->outward_location->ViewValue = $this->outward_location->CurrentValue;
		$this->outward_location->ViewCustomAttributes = "";

		// delivery_point
		$this->delivery_point->ViewValue = $this->delivery_point->CurrentValue;
		$this->delivery_point->ViewCustomAttributes = "";

		// name
		if (strval($this->name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->name->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`', "dx3" => '`staffno`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->name->ViewValue = $this->name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->name->ViewValue = $this->name->CurrentValue;
			}
		} else {
			$this->name->ViewValue = NULL;
		}
		$this->name->ViewCustomAttributes = "";

		// organization
		if (strval($this->organization->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->organization->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->organization->LookupFilters = array("dx1" => '`branch_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->organization, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->organization->ViewValue = $this->organization->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->organization->ViewValue = $this->organization->CurrentValue;
			}
		} else {
			$this->organization->ViewValue = NULL;
		}
		$this->organization->ViewCustomAttributes = "";

		// designation
		$this->designation->ViewValue = $this->designation->CurrentValue;
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->designation->ViewValue = $this->designation->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->designation->ViewValue = $this->designation->CurrentValue;
			}
		} else {
			$this->designation->ViewValue = NULL;
		}
		$this->designation->ViewCustomAttributes = "";

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

		// item_description
		$this->item_description->ViewValue = $this->item_description->CurrentValue;
		$this->item_description->ViewCustomAttributes = "";

		// driver_name
		$this->driver_name->ViewValue = $this->driver_name->CurrentValue;
		$this->driver_name->ViewCustomAttributes = "";

		// vehicle_no
		$this->vehicle_no->ViewValue = $this->vehicle_no->CurrentValue;
		$this->vehicle_no->ViewCustomAttributes = "";

		// requester_action
		if (strval($this->requester_action->CurrentValue) <> "") {
			$this->requester_action->ViewValue = $this->requester_action->OptionCaption($this->requester_action->CurrentValue);
		} else {
			$this->requester_action->ViewValue = NULL;
		}
		$this->requester_action->ViewCustomAttributes = "";

		// requester_comment
		$this->requester_comment->ViewValue = $this->requester_comment->CurrentValue;
		$this->requester_comment->ViewCustomAttributes = "";

		// date_authorized
		$this->date_authorized->ViewValue = $this->date_authorized->CurrentValue;
		$this->date_authorized->ViewValue = ew_FormatDateTime($this->date_authorized->ViewValue, 17);
		$this->date_authorized->ViewCustomAttributes = "";

		// authorizer_name
		$this->authorizer_name->ViewValue = $this->authorizer_name->CurrentValue;
		if (strval($this->authorizer_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->authorizer_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->authorizer_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->authorizer_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->authorizer_name->ViewValue = $this->authorizer_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->authorizer_name->ViewValue = $this->authorizer_name->CurrentValue;
			}
		} else {
			$this->authorizer_name->ViewValue = NULL;
		}
		$this->authorizer_name->ViewCustomAttributes = "";

		// authorizer_action
		if (strval($this->authorizer_action->CurrentValue) <> "") {
			$this->authorizer_action->ViewValue = $this->authorizer_action->OptionCaption($this->authorizer_action->CurrentValue);
		} else {
			$this->authorizer_action->ViewValue = NULL;
		}
		$this->authorizer_action->ViewCustomAttributes = "";

		// authorizer_comment
		$this->authorizer_comment->ViewValue = $this->authorizer_comment->CurrentValue;
		$this->authorizer_comment->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_ssf`";
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

		// rep_date
		$this->rep_date->ViewValue = $this->rep_date->CurrentValue;
		$this->rep_date->ViewValue = ew_FormatDateTime($this->rep_date->ViewValue, 17);
		$this->rep_date->ViewCustomAttributes = "";

		// rep_name
		$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
		if (strval($this->rep_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->rep_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->rep_name->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rep_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->rep_name->ViewValue = $this->rep_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rep_name->ViewValue = $this->rep_name->CurrentValue;
			}
		} else {
			$this->rep_name->ViewValue = NULL;
		}
		$this->rep_name->ViewCustomAttributes = "";

		// outward_datetime
		$this->outward_datetime->ViewValue = $this->outward_datetime->CurrentValue;
		$this->outward_datetime->ViewValue = ew_FormatDateTime($this->outward_datetime->ViewValue, 17);
		$this->outward_datetime->ViewCustomAttributes = "";

		// rep_action
		if (strval($this->rep_action->CurrentValue) <> "") {
			$this->rep_action->ViewValue = $this->rep_action->OptionCaption($this->rep_action->CurrentValue);
		} else {
			$this->rep_action->ViewValue = NULL;
		}
		$this->rep_action->ViewCustomAttributes = "";

		// rep_comment
		$this->rep_comment->ViewValue = $this->rep_comment->CurrentValue;
		$this->rep_comment->ViewCustomAttributes = "";

		// code
		$this->code->LinkCustomAttributes = "";
		$this->code->HrefValue = "";
		$this->code->TooltipValue = "";

		// date
		$this->date->LinkCustomAttributes = "";
		$this->date->HrefValue = "";
		$this->date->TooltipValue = "";

		// reference
		$this->reference->LinkCustomAttributes = "";
		$this->reference->HrefValue = "";
		$this->reference->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// outward_location
		$this->outward_location->LinkCustomAttributes = "";
		$this->outward_location->HrefValue = "";
		$this->outward_location->TooltipValue = "";

		// delivery_point
		$this->delivery_point->LinkCustomAttributes = "";
		$this->delivery_point->HrefValue = "";
		$this->delivery_point->TooltipValue = "";

		// name
		$this->name->LinkCustomAttributes = "";
		$this->name->HrefValue = "";
		$this->name->TooltipValue = "";

		// organization
		$this->organization->LinkCustomAttributes = "";
		$this->organization->HrefValue = "";
		$this->organization->TooltipValue = "";

		// designation
		$this->designation->LinkCustomAttributes = "";
		$this->designation->HrefValue = "";
		$this->designation->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// item_description
		$this->item_description->LinkCustomAttributes = "";
		$this->item_description->HrefValue = "";
		$this->item_description->TooltipValue = "";

		// driver_name
		$this->driver_name->LinkCustomAttributes = "";
		$this->driver_name->HrefValue = "";
		$this->driver_name->TooltipValue = "";

		// vehicle_no
		$this->vehicle_no->LinkCustomAttributes = "";
		$this->vehicle_no->HrefValue = "";
		$this->vehicle_no->TooltipValue = "";

		// requester_action
		$this->requester_action->LinkCustomAttributes = "";
		$this->requester_action->HrefValue = "";
		$this->requester_action->TooltipValue = "";

		// requester_comment
		$this->requester_comment->LinkCustomAttributes = "";
		$this->requester_comment->HrefValue = "";
		$this->requester_comment->TooltipValue = "";

		// date_authorized
		$this->date_authorized->LinkCustomAttributes = "";
		$this->date_authorized->HrefValue = "";
		$this->date_authorized->TooltipValue = "";

		// authorizer_name
		$this->authorizer_name->LinkCustomAttributes = "";
		$this->authorizer_name->HrefValue = "";
		$this->authorizer_name->TooltipValue = "";

		// authorizer_action
		$this->authorizer_action->LinkCustomAttributes = "";
		$this->authorizer_action->HrefValue = "";
		$this->authorizer_action->TooltipValue = "";

		// authorizer_comment
		$this->authorizer_comment->LinkCustomAttributes = "";
		$this->authorizer_comment->HrefValue = "";
		$this->authorizer_comment->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// rep_date
		$this->rep_date->LinkCustomAttributes = "";
		$this->rep_date->HrefValue = "";
		$this->rep_date->TooltipValue = "";

		// rep_name
		$this->rep_name->LinkCustomAttributes = "";
		$this->rep_name->HrefValue = "";
		$this->rep_name->TooltipValue = "";

		// outward_datetime
		$this->outward_datetime->LinkCustomAttributes = "";
		$this->outward_datetime->HrefValue = "";
		$this->outward_datetime->TooltipValue = "";

		// rep_action
		$this->rep_action->LinkCustomAttributes = "";
		$this->rep_action->HrefValue = "";
		$this->rep_action->TooltipValue = "";

		// rep_comment
		$this->rep_comment->LinkCustomAttributes = "";
		$this->rep_comment->HrefValue = "";
		$this->rep_comment->TooltipValue = "";

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

		// code
		$this->code->EditAttrs["class"] = "form-control";
		$this->code->EditCustomAttributes = "";
		$this->code->EditValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date
		$this->date->EditAttrs["class"] = "form-control";
		$this->date->EditCustomAttributes = "";
		$this->date->EditValue = ew_FormatDateTime($this->date->CurrentValue, 14);
		$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

		// reference
		$this->reference->EditAttrs["class"] = "form-control";
		$this->reference->EditCustomAttributes = "";
		$this->reference->EditValue = $this->reference->CurrentValue;
		$this->reference->PlaceHolder = ew_RemoveHtml($this->reference->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

		// outward_location
		$this->outward_location->EditAttrs["class"] = "form-control";
		$this->outward_location->EditCustomAttributes = "";
		$this->outward_location->EditValue = $this->outward_location->CurrentValue;
		$this->outward_location->PlaceHolder = ew_RemoveHtml($this->outward_location->FldCaption());

		// delivery_point
		$this->delivery_point->EditAttrs["class"] = "form-control";
		$this->delivery_point->EditCustomAttributes = "";
		$this->delivery_point->EditValue = $this->delivery_point->CurrentValue;
		$this->delivery_point->PlaceHolder = ew_RemoveHtml($this->delivery_point->FldCaption());

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";

		// organization
		$this->organization->EditAttrs["class"] = "form-control";
		$this->organization->EditCustomAttributes = "";

		// designation
		$this->designation->EditAttrs["class"] = "form-control";
		$this->designation->EditCustomAttributes = "";
		$this->designation->EditValue = $this->designation->CurrentValue;
		$this->designation->PlaceHolder = ew_RemoveHtml($this->designation->FldCaption());

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";
		$this->department->EditValue = $this->department->CurrentValue;
		$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

		// item_description
		$this->item_description->EditAttrs["class"] = "form-control";
		$this->item_description->EditCustomAttributes = "";
		$this->item_description->EditValue = $this->item_description->CurrentValue;
		$this->item_description->PlaceHolder = ew_RemoveHtml($this->item_description->FldCaption());

		// driver_name
		$this->driver_name->EditAttrs["class"] = "form-control";
		$this->driver_name->EditCustomAttributes = "";
		$this->driver_name->EditValue = $this->driver_name->CurrentValue;
		$this->driver_name->PlaceHolder = ew_RemoveHtml($this->driver_name->FldCaption());

		// vehicle_no
		$this->vehicle_no->EditAttrs["class"] = "form-control";
		$this->vehicle_no->EditCustomAttributes = "";
		$this->vehicle_no->EditValue = $this->vehicle_no->CurrentValue;
		$this->vehicle_no->PlaceHolder = ew_RemoveHtml($this->vehicle_no->FldCaption());

		// requester_action
		$this->requester_action->EditCustomAttributes = "";
		$this->requester_action->EditValue = $this->requester_action->Options(FALSE);

		// requester_comment
		$this->requester_comment->EditAttrs["class"] = "form-control";
		$this->requester_comment->EditCustomAttributes = "";
		$this->requester_comment->EditValue = $this->requester_comment->CurrentValue;
		$this->requester_comment->PlaceHolder = ew_RemoveHtml($this->requester_comment->FldCaption());

		// date_authorized
		$this->date_authorized->EditAttrs["class"] = "form-control";
		$this->date_authorized->EditCustomAttributes = "";
		$this->date_authorized->EditValue = ew_FormatDateTime($this->date_authorized->CurrentValue, 17);
		$this->date_authorized->PlaceHolder = ew_RemoveHtml($this->date_authorized->FldCaption());

		// authorizer_name
		$this->authorizer_name->EditAttrs["class"] = "form-control";
		$this->authorizer_name->EditCustomAttributes = "";
		$this->authorizer_name->EditValue = $this->authorizer_name->CurrentValue;
		$this->authorizer_name->PlaceHolder = ew_RemoveHtml($this->authorizer_name->FldCaption());

		// authorizer_action
		$this->authorizer_action->EditCustomAttributes = "";
		$this->authorizer_action->EditValue = $this->authorizer_action->Options(FALSE);

		// authorizer_comment
		$this->authorizer_comment->EditAttrs["class"] = "form-control";
		$this->authorizer_comment->EditCustomAttributes = "";
		$this->authorizer_comment->EditValue = $this->authorizer_comment->CurrentValue;
		$this->authorizer_comment->PlaceHolder = ew_RemoveHtml($this->authorizer_comment->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";

		// rep_date
		$this->rep_date->EditAttrs["class"] = "form-control";
		$this->rep_date->EditCustomAttributes = "";
		$this->rep_date->EditValue = ew_FormatDateTime($this->rep_date->CurrentValue, 17);
		$this->rep_date->PlaceHolder = ew_RemoveHtml($this->rep_date->FldCaption());

		// rep_name
		$this->rep_name->EditAttrs["class"] = "form-control";
		$this->rep_name->EditCustomAttributes = "";
		$this->rep_name->EditValue = $this->rep_name->CurrentValue;
		$this->rep_name->PlaceHolder = ew_RemoveHtml($this->rep_name->FldCaption());

		// outward_datetime
		$this->outward_datetime->EditAttrs["class"] = "form-control";
		$this->outward_datetime->EditCustomAttributes = "";
		$this->outward_datetime->EditValue = ew_FormatDateTime($this->outward_datetime->CurrentValue, 17);
		$this->outward_datetime->PlaceHolder = ew_RemoveHtml($this->outward_datetime->FldCaption());

		// rep_action
		$this->rep_action->EditCustomAttributes = "";
		$this->rep_action->EditValue = $this->rep_action->Options(FALSE);

		// rep_comment
		$this->rep_comment->EditAttrs["class"] = "form-control";
		$this->rep_comment->EditCustomAttributes = "";
		$this->rep_comment->EditValue = $this->rep_comment->CurrentValue;
		$this->rep_comment->PlaceHolder = ew_RemoveHtml($this->rep_comment->FldCaption());

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
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->reference->Exportable) $Doc->ExportCaption($this->reference);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->outward_location->Exportable) $Doc->ExportCaption($this->outward_location);
					if ($this->delivery_point->Exportable) $Doc->ExportCaption($this->delivery_point);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->organization->Exportable) $Doc->ExportCaption($this->organization);
					if ($this->designation->Exportable) $Doc->ExportCaption($this->designation);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->item_description->Exportable) $Doc->ExportCaption($this->item_description);
					if ($this->driver_name->Exportable) $Doc->ExportCaption($this->driver_name);
					if ($this->vehicle_no->Exportable) $Doc->ExportCaption($this->vehicle_no);
					if ($this->requester_action->Exportable) $Doc->ExportCaption($this->requester_action);
					if ($this->requester_comment->Exportable) $Doc->ExportCaption($this->requester_comment);
					if ($this->date_authorized->Exportable) $Doc->ExportCaption($this->date_authorized);
					if ($this->authorizer_name->Exportable) $Doc->ExportCaption($this->authorizer_name);
					if ($this->authorizer_action->Exportable) $Doc->ExportCaption($this->authorizer_action);
					if ($this->authorizer_comment->Exportable) $Doc->ExportCaption($this->authorizer_comment);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->rep_date->Exportable) $Doc->ExportCaption($this->rep_date);
					if ($this->rep_name->Exportable) $Doc->ExportCaption($this->rep_name);
					if ($this->outward_datetime->Exportable) $Doc->ExportCaption($this->outward_datetime);
					if ($this->rep_action->Exportable) $Doc->ExportCaption($this->rep_action);
					if ($this->rep_comment->Exportable) $Doc->ExportCaption($this->rep_comment);
				} else {
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->reference->Exportable) $Doc->ExportCaption($this->reference);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->outward_location->Exportable) $Doc->ExportCaption($this->outward_location);
					if ($this->delivery_point->Exportable) $Doc->ExportCaption($this->delivery_point);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->organization->Exportable) $Doc->ExportCaption($this->organization);
					if ($this->designation->Exportable) $Doc->ExportCaption($this->designation);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->item_description->Exportable) $Doc->ExportCaption($this->item_description);
					if ($this->driver_name->Exportable) $Doc->ExportCaption($this->driver_name);
					if ($this->vehicle_no->Exportable) $Doc->ExportCaption($this->vehicle_no);
					if ($this->requester_action->Exportable) $Doc->ExportCaption($this->requester_action);
					if ($this->requester_comment->Exportable) $Doc->ExportCaption($this->requester_comment);
					if ($this->date_authorized->Exportable) $Doc->ExportCaption($this->date_authorized);
					if ($this->authorizer_name->Exportable) $Doc->ExportCaption($this->authorizer_name);
					if ($this->authorizer_action->Exportable) $Doc->ExportCaption($this->authorizer_action);
					if ($this->authorizer_comment->Exportable) $Doc->ExportCaption($this->authorizer_comment);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->rep_date->Exportable) $Doc->ExportCaption($this->rep_date);
					if ($this->rep_name->Exportable) $Doc->ExportCaption($this->rep_name);
					if ($this->outward_datetime->Exportable) $Doc->ExportCaption($this->outward_datetime);
					if ($this->rep_action->Exportable) $Doc->ExportCaption($this->rep_action);
					if ($this->rep_comment->Exportable) $Doc->ExportCaption($this->rep_comment);
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
						if ($this->code->Exportable) $Doc->ExportField($this->code);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->reference->Exportable) $Doc->ExportField($this->reference);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->outward_location->Exportable) $Doc->ExportField($this->outward_location);
						if ($this->delivery_point->Exportable) $Doc->ExportField($this->delivery_point);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->organization->Exportable) $Doc->ExportField($this->organization);
						if ($this->designation->Exportable) $Doc->ExportField($this->designation);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->item_description->Exportable) $Doc->ExportField($this->item_description);
						if ($this->driver_name->Exportable) $Doc->ExportField($this->driver_name);
						if ($this->vehicle_no->Exportable) $Doc->ExportField($this->vehicle_no);
						if ($this->requester_action->Exportable) $Doc->ExportField($this->requester_action);
						if ($this->requester_comment->Exportable) $Doc->ExportField($this->requester_comment);
						if ($this->date_authorized->Exportable) $Doc->ExportField($this->date_authorized);
						if ($this->authorizer_name->Exportable) $Doc->ExportField($this->authorizer_name);
						if ($this->authorizer_action->Exportable) $Doc->ExportField($this->authorizer_action);
						if ($this->authorizer_comment->Exportable) $Doc->ExportField($this->authorizer_comment);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->rep_date->Exportable) $Doc->ExportField($this->rep_date);
						if ($this->rep_name->Exportable) $Doc->ExportField($this->rep_name);
						if ($this->outward_datetime->Exportable) $Doc->ExportField($this->outward_datetime);
						if ($this->rep_action->Exportable) $Doc->ExportField($this->rep_action);
						if ($this->rep_comment->Exportable) $Doc->ExportField($this->rep_comment);
					} else {
						if ($this->code->Exportable) $Doc->ExportField($this->code);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->reference->Exportable) $Doc->ExportField($this->reference);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->outward_location->Exportable) $Doc->ExportField($this->outward_location);
						if ($this->delivery_point->Exportable) $Doc->ExportField($this->delivery_point);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->organization->Exportable) $Doc->ExportField($this->organization);
						if ($this->designation->Exportable) $Doc->ExportField($this->designation);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->item_description->Exportable) $Doc->ExportField($this->item_description);
						if ($this->driver_name->Exportable) $Doc->ExportField($this->driver_name);
						if ($this->vehicle_no->Exportable) $Doc->ExportField($this->vehicle_no);
						if ($this->requester_action->Exportable) $Doc->ExportField($this->requester_action);
						if ($this->requester_comment->Exportable) $Doc->ExportField($this->requester_comment);
						if ($this->date_authorized->Exportable) $Doc->ExportField($this->date_authorized);
						if ($this->authorizer_name->Exportable) $Doc->ExportField($this->authorizer_name);
						if ($this->authorizer_action->Exportable) $Doc->ExportField($this->authorizer_action);
						if ($this->authorizer_comment->Exportable) $Doc->ExportField($this->authorizer_comment);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->rep_date->Exportable) $Doc->ExportField($this->rep_date);
						if ($this->rep_name->Exportable) $Doc->ExportField($this->rep_name);
						if ($this->outward_datetime->Exportable) $Doc->ExportField($this->outward_datetime);
						if ($this->rep_action->Exportable) $Doc->ExportField($this->rep_action);
						if ($this->rep_comment->Exportable) $Doc->ExportField($this->rep_comment);
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
		$table = 'requisition_report';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'requisition_report';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['code'];

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
		$table = 'requisition_report';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['code'];

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
		$table = 'requisition_report';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['code'];

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
				$this->code->CellCssStyle = "color: orange; text-align: left;";
				$this->date->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->outward_location->CellCssStyle = "color: orange; text-align: left;";
				$this->delivery_point->CellCssStyle = "color: orange; text-align: left;";
				$this->name->CellCssStyle = "color: orange; text-align: left;";
				$this->organization->CellCssStyle = "color: orange; text-align: left;";
				$this->reference->CellCssStyle = "color: orange; text-align: left;";
				$this->designation->CellCssStyle = "color: orange; text-align: left;";
				$this->department->CellCssStyle = "color: orange; text-align: left;";
				$this->status->CellCssStyle = "color: orange; text-align: left;";
				$this->item_description->CellCssStyle = "color: orange; text-align: left;";
				$this->driver_name->CellCssStyle = "color: orange; text-align: left;";
				$this->vehicle_no->CellCssStyle = "color: orange; text-align: left;";			
				$this->requester_action->CellCssStyle = "color: orange; text-align: left;";
				$this->requester_comment->CellCssStyle = "color: orange; text-align: left;";
				$this->authorizer_action->CellCssStyle = "color: orange; text-align: left;";
				$this->authorizer_comment->CellCssStyle = "color: orange; text-align: left;";
				$this->authorizer_name->CellCssStyle = "color: orange; text-align: left;";
				$this->authorized_date->CellCssStyle = "color: orange; text-align: left;";
				$this->rep_date->CellCssStyle = "color: orange; text-align: left;";
				$this->rep_name->CellCssStyle = "color: orange; text-align: left;";
				$this->rep_action->CellCssStyle = "color: orange; text-align: left;";
				$this->rep_comment->CellCssStyle = "color: orange; text-align: left;";
				$this->outward_datetime->CellCssStyle = "color: orange; text-align: left;";			
			}
			if ($this->status->CurrentValue == 2) {
				$this->code->CellCssStyle = "color: red; text-align: left;";
				$this->date->CellCssStyle = "color: red; text-align: left;";
				$this->staff_id->CellCssStyle = "color: red; text-align: left;";
				$this->outward_location->CellCssStyle = "color: red; text-align: left;";
				$this->delivery_point->CellCssStyle = "color: red; text-align: left;";
				$this->name->CellCssStyle = "color: red; text-align: left;";
				$this->organization->CellCssStyle = "color: red; text-align: left;";
				$this->reference->CellCssStyle = "color: red; text-align: left;";
				$this->designation->CellCssStyle = "color: red; text-align: left;";
				$this->department->CellCssStyle = "color: red; text-align: left;";
				$this->status->CellCssStyle = "color: red; text-align: left;";
				$this->item_description->CellCssStyle = "color: red; text-align: left;";
				$this->driver_name->CellCssStyle = "color: red; text-align: left;";
				$this->vehicle_no->CellCssStyle = "color: red; text-align: left;";			
				$this->requester_action->CellCssStyle = "color: red; text-align: left;";
				$this->requester_comment->CellCssStyle = "color: red; text-align: left;";
				$this->authorizer_action->CellCssStyle = "color: red; text-align: left;";
				$this->authorizer_comment->CellCssStyle = "color: red; text-align: left;";
				$this->authorizer_name->CellCssStyle = "color: red; text-align: left;";
				$this->authorized_date->CellCssStyle = "color: red; text-align: left;";
				$this->rep_date->CellCssStyle = "color: red; text-align: left;";
				$this->rep_name->CellCssStyle = "color: red; text-align: left;";
				$this->rep_action->CellCssStyle = "color: red; text-align: left;";
				$this->rep_comment->CellCssStyle = "color: red; text-align: left;";
				$this->outward_datetime->CellCssStyle = "color: red; text-align: left;";			
			}
			if ($this->status->CurrentValue == 3) {
				$this->code->CellCssStyle = "color: blue; text-align: left;";
				$this->date->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->outward_location->CellCssStyle = "color: blue; text-align: left;";
				$this->delivery_point->CellCssStyle = "color: blue; text-align: left;";
				$this->name->CellCssStyle = "color: blue; text-align: left;";
				$this->organization->CellCssStyle = "color: blue; text-align: left;";
				$this->reference->CellCssStyle = "color: blue; text-align: left;";
				$this->designation->CellCssStyle = "color: blue; text-align: left;";
				$this->department->CellCssStyle = "color: blue; text-align: left;";
				$this->status->CellCssStyle = "color: blue; text-align: left;";
				$this->item_description->CellCssStyle = "color: blue; text-align: left;";
				$this->driver_name->CellCssStyle = "color: blue; text-align: left;";
				$this->vehicle_no->CellCssStyle = "color: blue; text-align: left;";			
				$this->requester_action->CellCssStyle = "color: blue; text-align: left;";
				$this->requester_comment->CellCssStyle = "color: blue; text-align: left;";
				$this->authorizer_action->CellCssStyle = "color: blue; text-align: left;";
				$this->authorizer_comment->CellCssStyle = "color: blue; text-align: left;";
				$this->authorizer_name->CellCssStyle = "color: blue; text-align: left;";
				$this->authorized_date->CellCssStyle = "color: blue; text-align: left;";
				$this->rep_date->CellCssStyle = "color: blue; text-align: left;";
				$this->rep_name->CellCssStyle = "color: blue; text-align: left;";
				$this->rep_action->CellCssStyle = "color: blue; text-align: left;";
				$this->rep_comment->CellCssStyle = "color: blue; text-align: left;";
				$this->outward_datetime->CellCssStyle = "color: blue; text-align: left;";			
			}
			if ($this->status->CurrentValue == 4) {
				$this->code->CellCssStyle = "color: green; text-align: left;";
				$this->date->CellCssStyle = "color: green; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->outward_location->CellCssStyle = "color: green; text-align: left;";
				$this->delivery_point->CellCssStyle = "color: green; text-align: left;";
				$this->name->CellCssStyle = "color: green; text-align: left;";
				$this->organization->CellCssStyle = "color: green; text-align: left;";
				$this->reference->CellCssStyle = "color: green; text-align: left;";
				$this->designation->CellCssStyle = "color: green; text-align: left;";
				$this->department->CellCssStyle = "color: green; text-align: left;";
				$this->status->CellCssStyle = "color: green; text-align: left;";
				$this->item_description->CellCssStyle = "color: green; text-align: left;";
				$this->driver_name->CellCssStyle = "color: green; text-align: left;";
				$this->vehicle_no->CellCssStyle = "color: green; text-align: left;";			
				$this->requester_action->CellCssStyle = "color: green; text-align: left;";
				$this->requester_comment->CellCssStyle = "color: green; text-align: left;";
				$this->authorizer_action->CellCssStyle = "color: green; text-align: left;";
				$this->authorizer_comment->CellCssStyle = "color: green; text-align: left;";
				$this->authorizer_name->CellCssStyle = "color: green; text-align: left;";
				$this->authorized_date->CellCssStyle = "color: green; text-align: left;";
				$this->rep_date->CellCssStyle = "color: green; text-align: left;";
				$this->rep_name->CellCssStyle = "color: green; text-align: left;";
				$this->rep_action->CellCssStyle = "color: green; text-align: left;";
				$this->rep_comment->CellCssStyle = "color: green; text-align: left;";
				$this->outward_datetime->CellCssStyle = "color: green; text-align: left;";			
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
