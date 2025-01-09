<?php

// Global variable for table object
$inventory_store = NULL;

//
// Table class for inventory_store
//
class cinventory_store extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id;
	var $date;
	var $reference_id;
	var $staff_id;
	var $material_name;
	var $quantity_in;
	var $quantity_type;
	var $quantity_out;
	var $total_quantity;
	var $treated_by;
	var $status;
	var $issued_action;
	var $issued_comment;
	var $issued_by;
	var $approver_date;
	var $approver_action;
	var $approved_comment;
	var $approved_by;
	var $verified_date;
	var $verified_action;
	var $verified_comment;
	var $verified_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'inventory_store';
		$this->TableName = 'inventory_store';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`inventory_store`";
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
		$this->id = new cField('inventory_store', 'inventory_store', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// date
		$this->date = new cField('inventory_store', 'inventory_store', 'x_date', 'date', '`date`', ew_CastDateFieldForLike('`date`', 0, "DB"), 135, 0, FALSE, '`date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date->Sortable = TRUE; // Allow sort
		$this->date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date'] = &$this->date;

		// reference_id
		$this->reference_id = new cField('inventory_store', 'inventory_store', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// staff_id
		$this->staff_id = new cField('inventory_store', 'inventory_store', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staff_id'] = &$this->staff_id;

		// material_name
		$this->material_name = new cField('inventory_store', 'inventory_store', 'x_material_name', 'material_name', '`material_name`', '`material_name`', 200, -1, FALSE, '`material_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->material_name->Sortable = TRUE; // Allow sort
		$this->material_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->material_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['material_name'] = &$this->material_name;

		// quantity_in
		$this->quantity_in = new cField('inventory_store', 'inventory_store', 'x_quantity_in', 'quantity_in', '`quantity_in`', '`quantity_in`', 200, -1, FALSE, '`quantity_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_in->Sortable = TRUE; // Allow sort
		$this->fields['quantity_in'] = &$this->quantity_in;

		// quantity_type
		$this->quantity_type = new cField('inventory_store', 'inventory_store', 'x_quantity_type', 'quantity_type', '`quantity_type`', '`quantity_type`', 200, -1, FALSE, '`quantity_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_type->Sortable = TRUE; // Allow sort
		$this->fields['quantity_type'] = &$this->quantity_type;

		// quantity_out
		$this->quantity_out = new cField('inventory_store', 'inventory_store', 'x_quantity_out', 'quantity_out', '`quantity_out`', '`quantity_out`', 200, -1, FALSE, '`quantity_out`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_out->Sortable = TRUE; // Allow sort
		$this->fields['quantity_out'] = &$this->quantity_out;

		// total_quantity
		$this->total_quantity = new cField('inventory_store', 'inventory_store', 'x_total_quantity', 'total_quantity', '`total_quantity`', '`total_quantity`', 200, -1, FALSE, '`total_quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_quantity->Sortable = TRUE; // Allow sort
		$this->fields['total_quantity'] = &$this->total_quantity;

		// treated_by
		$this->treated_by = new cField('inventory_store', 'inventory_store', 'x_treated_by', 'treated_by', '`treated_by`', '`treated_by`', 3, -1, FALSE, '`treated_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->treated_by->Sortable = TRUE; // Allow sort
		$this->treated_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['treated_by'] = &$this->treated_by;

		// status
		$this->status = new cField('inventory_store', 'inventory_store', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// issued_action
		$this->issued_action = new cField('inventory_store', 'inventory_store', 'x_issued_action', 'issued_action', '`issued_action`', '`issued_action`', 3, -1, FALSE, '`issued_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->issued_action->Sortable = TRUE; // Allow sort
		$this->issued_action->OptionCount = 2;
		$this->issued_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['issued_action'] = &$this->issued_action;

		// issued_comment
		$this->issued_comment = new cField('inventory_store', 'inventory_store', 'x_issued_comment', 'issued_comment', '`issued_comment`', '`issued_comment`', 200, -1, FALSE, '`issued_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->issued_comment->Sortable = TRUE; // Allow sort
		$this->fields['issued_comment'] = &$this->issued_comment;

		// issued_by
		$this->issued_by = new cField('inventory_store', 'inventory_store', 'x_issued_by', 'issued_by', '`issued_by`', '`issued_by`', 3, -1, FALSE, '`issued_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->issued_by->Sortable = TRUE; // Allow sort
		$this->issued_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['issued_by'] = &$this->issued_by;

		// approver_date
		$this->approver_date = new cField('inventory_store', 'inventory_store', 'x_approver_date', 'approver_date', '`approver_date`', ew_CastDateFieldForLike('`approver_date`', 0, "DB"), 135, 0, FALSE, '`approver_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approver_date->Sortable = TRUE; // Allow sort
		$this->approver_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['approver_date'] = &$this->approver_date;

		// approver_action
		$this->approver_action = new cField('inventory_store', 'inventory_store', 'x_approver_action', 'approver_action', '`approver_action`', '`approver_action`', 3, -1, FALSE, '`approver_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approver_action->Sortable = TRUE; // Allow sort
		$this->approver_action->OptionCount = 2;
		$this->approver_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approver_action'] = &$this->approver_action;

		// approved_comment
		$this->approved_comment = new cField('inventory_store', 'inventory_store', 'x_approved_comment', 'approved_comment', '`approved_comment`', '`approved_comment`', 200, -1, FALSE, '`approved_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approved_comment->Sortable = TRUE; // Allow sort
		$this->fields['approved_comment'] = &$this->approved_comment;

		// approved_by
		$this->approved_by = new cField('inventory_store', 'inventory_store', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->approved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approved_by'] = &$this->approved_by;

		// verified_date
		$this->verified_date = new cField('inventory_store', 'inventory_store', 'x_verified_date', 'verified_date', '`verified_date`', ew_CastDateFieldForLike('`verified_date`', 0, "DB"), 135, 0, FALSE, '`verified_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verified_date->Sortable = TRUE; // Allow sort
		$this->verified_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['verified_date'] = &$this->verified_date;

		// verified_action
		$this->verified_action = new cField('inventory_store', 'inventory_store', 'x_verified_action', 'verified_action', '`verified_action`', '`verified_action`', 3, -1, FALSE, '`verified_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->verified_action->Sortable = TRUE; // Allow sort
		$this->verified_action->OptionCount = 2;
		$this->verified_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['verified_action'] = &$this->verified_action;

		// verified_comment
		$this->verified_comment = new cField('inventory_store', 'inventory_store', 'x_verified_comment', 'verified_comment', '`verified_comment`', '`verified_comment`', 200, -1, FALSE, '`verified_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->verified_comment->Sortable = TRUE; // Allow sort
		$this->fields['verified_comment'] = &$this->verified_comment;

		// verified_by
		$this->verified_by = new cField('inventory_store', 'inventory_store', 'x_verified_by', 'verified_by', '`verified_by`', '`verified_by`', 3, -1, FALSE, '`verified_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verified_by->Sortable = TRUE; // Allow sort
		$this->verified_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['verified_by'] = &$this->verified_by;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`inventory_store`";
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
			return "inventory_storelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "inventory_storeview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "inventory_storeedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "inventory_storeadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "inventory_storelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("inventory_storeview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("inventory_storeview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "inventory_storeadd.php?" . $this->UrlParm($parm);
		else
			$url = "inventory_storeadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("inventory_storeedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("inventory_storeadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("inventory_storedelete.php", $this->UrlParm());
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
		$this->reference_id->setDbValue($rs->fields('reference_id'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->material_name->setDbValue($rs->fields('material_name'));
		$this->quantity_in->setDbValue($rs->fields('quantity_in'));
		$this->quantity_type->setDbValue($rs->fields('quantity_type'));
		$this->quantity_out->setDbValue($rs->fields('quantity_out'));
		$this->total_quantity->setDbValue($rs->fields('total_quantity'));
		$this->treated_by->setDbValue($rs->fields('treated_by'));
		$this->status->setDbValue($rs->fields('status'));
		$this->issued_action->setDbValue($rs->fields('issued_action'));
		$this->issued_comment->setDbValue($rs->fields('issued_comment'));
		$this->issued_by->setDbValue($rs->fields('issued_by'));
		$this->approver_date->setDbValue($rs->fields('approver_date'));
		$this->approver_action->setDbValue($rs->fields('approver_action'));
		$this->approved_comment->setDbValue($rs->fields('approved_comment'));
		$this->approved_by->setDbValue($rs->fields('approved_by'));
		$this->verified_date->setDbValue($rs->fields('verified_date'));
		$this->verified_action->setDbValue($rs->fields('verified_action'));
		$this->verified_comment->setDbValue($rs->fields('verified_comment'));
		$this->verified_by->setDbValue($rs->fields('verified_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// date
		// reference_id
		// staff_id
		// material_name
		// quantity_in
		// quantity_type
		// quantity_out
		// total_quantity
		// treated_by
		// status
		// issued_action
		// issued_comment
		// issued_by
		// approver_date
		// approver_action
		// approved_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

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

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory`";
		$sWhereWrk = "";
		$this->material_name->LookupFilters = array("dx1" => '`material_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->material_name, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->material_name->ViewValue = $this->material_name->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->material_name->ViewValue = $this->material_name->CurrentValue;
			}
		} else {
			$this->material_name->ViewValue = NULL;
		}
		$this->material_name->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_type
		$this->quantity_type->ViewValue = $this->quantity_type->CurrentValue;
		$this->quantity_type->ViewCustomAttributes = "";

		// quantity_out
		$this->quantity_out->ViewValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// treated_by
		$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
		if (strval($this->treated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->treated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->treated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->treated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->treated_by->ViewValue = $this->treated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->treated_by->ViewValue = $this->treated_by->CurrentValue;
			}
		} else {
			$this->treated_by->ViewValue = NULL;
		}
		$this->treated_by->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
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

		// issued_action
		if (strval($this->issued_action->CurrentValue) <> "") {
			$this->issued_action->ViewValue = $this->issued_action->OptionCaption($this->issued_action->CurrentValue);
		} else {
			$this->issued_action->ViewValue = NULL;
		}
		$this->issued_action->ViewCustomAttributes = "";

		// issued_comment
		$this->issued_comment->ViewValue = $this->issued_comment->CurrentValue;
		$this->issued_comment->ViewCustomAttributes = "";

		// issued_by
		$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
		if (strval($this->issued_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->issued_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->issued_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->issued_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->issued_by->ViewValue = $this->issued_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->issued_by->ViewValue = $this->issued_by->CurrentValue;
			}
		} else {
			$this->issued_by->ViewValue = NULL;
		}
		$this->issued_by->ViewCustomAttributes = "";

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

		// approved_comment
		$this->approved_comment->ViewValue = $this->approved_comment->CurrentValue;
		$this->approved_comment->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		if (strval($this->approved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

		// verified_date
		$this->verified_date->ViewValue = $this->verified_date->CurrentValue;
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 0);
		$this->verified_date->ViewCustomAttributes = "";

		// verified_action
		if (strval($this->verified_action->CurrentValue) <> "") {
			$this->verified_action->ViewValue = $this->verified_action->OptionCaption($this->verified_action->CurrentValue);
		} else {
			$this->verified_action->ViewValue = NULL;
		}
		$this->verified_action->ViewCustomAttributes = "";

		// verified_comment
		$this->verified_comment->ViewValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->ViewCustomAttributes = "";

		// verified_by
		$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
		if (strval($this->verified_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->verified_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->verified_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->verified_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->verified_by->ViewValue = $this->verified_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->verified_by->ViewValue = $this->verified_by->CurrentValue;
			}
		} else {
			$this->verified_by->ViewValue = NULL;
		}
		$this->verified_by->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// date
		$this->date->LinkCustomAttributes = "";
		$this->date->HrefValue = "";
		$this->date->TooltipValue = "";

		// reference_id
		$this->reference_id->LinkCustomAttributes = "";
		$this->reference_id->HrefValue = "";
		$this->reference_id->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// material_name
		$this->material_name->LinkCustomAttributes = "";
		$this->material_name->HrefValue = "";
		$this->material_name->TooltipValue = "";

		// quantity_in
		$this->quantity_in->LinkCustomAttributes = "";
		$this->quantity_in->HrefValue = "";
		$this->quantity_in->TooltipValue = "";

		// quantity_type
		$this->quantity_type->LinkCustomAttributes = "";
		$this->quantity_type->HrefValue = "";
		$this->quantity_type->TooltipValue = "";

		// quantity_out
		$this->quantity_out->LinkCustomAttributes = "";
		$this->quantity_out->HrefValue = "";
		$this->quantity_out->TooltipValue = "";

		// total_quantity
		$this->total_quantity->LinkCustomAttributes = "";
		$this->total_quantity->HrefValue = "";
		$this->total_quantity->TooltipValue = "";

		// treated_by
		$this->treated_by->LinkCustomAttributes = "";
		$this->treated_by->HrefValue = "";
		$this->treated_by->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// issued_action
		$this->issued_action->LinkCustomAttributes = "";
		$this->issued_action->HrefValue = "";
		$this->issued_action->TooltipValue = "";

		// issued_comment
		$this->issued_comment->LinkCustomAttributes = "";
		$this->issued_comment->HrefValue = "";
		$this->issued_comment->TooltipValue = "";

		// issued_by
		$this->issued_by->LinkCustomAttributes = "";
		$this->issued_by->HrefValue = "";
		$this->issued_by->TooltipValue = "";

		// approver_date
		$this->approver_date->LinkCustomAttributes = "";
		$this->approver_date->HrefValue = "";
		$this->approver_date->TooltipValue = "";

		// approver_action
		$this->approver_action->LinkCustomAttributes = "";
		$this->approver_action->HrefValue = "";
		$this->approver_action->TooltipValue = "";

		// approved_comment
		$this->approved_comment->LinkCustomAttributes = "";
		$this->approved_comment->HrefValue = "";
		$this->approved_comment->TooltipValue = "";

		// approved_by
		$this->approved_by->LinkCustomAttributes = "";
		$this->approved_by->HrefValue = "";
		$this->approved_by->TooltipValue = "";

		// verified_date
		$this->verified_date->LinkCustomAttributes = "";
		$this->verified_date->HrefValue = "";
		$this->verified_date->TooltipValue = "";

		// verified_action
		$this->verified_action->LinkCustomAttributes = "";
		$this->verified_action->HrefValue = "";
		$this->verified_action->TooltipValue = "";

		// verified_comment
		$this->verified_comment->LinkCustomAttributes = "";
		$this->verified_comment->HrefValue = "";
		$this->verified_comment->TooltipValue = "";

		// verified_by
		$this->verified_by->LinkCustomAttributes = "";
		$this->verified_by->HrefValue = "";
		$this->verified_by->TooltipValue = "";

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

		// material_name
		$this->material_name->EditAttrs["class"] = "form-control";
		$this->material_name->EditCustomAttributes = "";

		// quantity_in
		$this->quantity_in->EditAttrs["class"] = "form-control";
		$this->quantity_in->EditCustomAttributes = "";
		$this->quantity_in->EditValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

		// quantity_type
		$this->quantity_type->EditAttrs["class"] = "form-control";
		$this->quantity_type->EditCustomAttributes = "";
		$this->quantity_type->EditValue = $this->quantity_type->CurrentValue;
		$this->quantity_type->PlaceHolder = ew_RemoveHtml($this->quantity_type->FldCaption());

		// quantity_out
		$this->quantity_out->EditAttrs["class"] = "form-control";
		$this->quantity_out->EditCustomAttributes = "";
		$this->quantity_out->EditValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->PlaceHolder = ew_RemoveHtml($this->quantity_out->FldCaption());

		// total_quantity
		$this->total_quantity->EditAttrs["class"] = "form-control";
		$this->total_quantity->EditCustomAttributes = "";
		$this->total_quantity->EditValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->PlaceHolder = ew_RemoveHtml($this->total_quantity->FldCaption());

		// treated_by
		$this->treated_by->EditAttrs["class"] = "form-control";
		$this->treated_by->EditCustomAttributes = "";
		$this->treated_by->EditValue = $this->treated_by->CurrentValue;
		$this->treated_by->PlaceHolder = ew_RemoveHtml($this->treated_by->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// issued_action
		$this->issued_action->EditCustomAttributes = "";
		$this->issued_action->EditValue = $this->issued_action->Options(FALSE);

		// issued_comment
		$this->issued_comment->EditAttrs["class"] = "form-control";
		$this->issued_comment->EditCustomAttributes = "";
		$this->issued_comment->EditValue = $this->issued_comment->CurrentValue;
		$this->issued_comment->PlaceHolder = ew_RemoveHtml($this->issued_comment->FldCaption());

		// issued_by
		$this->issued_by->EditAttrs["class"] = "form-control";
		$this->issued_by->EditCustomAttributes = "";
		$this->issued_by->EditValue = $this->issued_by->CurrentValue;
		$this->issued_by->PlaceHolder = ew_RemoveHtml($this->issued_by->FldCaption());

		// approver_date
		$this->approver_date->EditAttrs["class"] = "form-control";
		$this->approver_date->EditCustomAttributes = "";
		$this->approver_date->EditValue = ew_FormatDateTime($this->approver_date->CurrentValue, 8);
		$this->approver_date->PlaceHolder = ew_RemoveHtml($this->approver_date->FldCaption());

		// approver_action
		$this->approver_action->EditCustomAttributes = "";
		$this->approver_action->EditValue = $this->approver_action->Options(FALSE);

		// approved_comment
		$this->approved_comment->EditAttrs["class"] = "form-control";
		$this->approved_comment->EditCustomAttributes = "";
		$this->approved_comment->EditValue = $this->approved_comment->CurrentValue;
		$this->approved_comment->PlaceHolder = ew_RemoveHtml($this->approved_comment->FldCaption());

		// approved_by
		$this->approved_by->EditAttrs["class"] = "form-control";
		$this->approved_by->EditCustomAttributes = "";
		$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

		// verified_date
		$this->verified_date->EditAttrs["class"] = "form-control";
		$this->verified_date->EditCustomAttributes = "";
		$this->verified_date->EditValue = ew_FormatDateTime($this->verified_date->CurrentValue, 8);
		$this->verified_date->PlaceHolder = ew_RemoveHtml($this->verified_date->FldCaption());

		// verified_action
		$this->verified_action->EditCustomAttributes = "";
		$this->verified_action->EditValue = $this->verified_action->Options(FALSE);

		// verified_comment
		$this->verified_comment->EditAttrs["class"] = "form-control";
		$this->verified_comment->EditCustomAttributes = "";
		$this->verified_comment->EditValue = $this->verified_comment->CurrentValue;
		$this->verified_comment->PlaceHolder = ew_RemoveHtml($this->verified_comment->FldCaption());

		// verified_by
		$this->verified_by->EditAttrs["class"] = "form-control";
		$this->verified_by->EditCustomAttributes = "";
		$this->verified_by->EditValue = $this->verified_by->CurrentValue;
		$this->verified_by->PlaceHolder = ew_RemoveHtml($this->verified_by->FldCaption());

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
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->quantity_in->Exportable) $Doc->ExportCaption($this->quantity_in);
					if ($this->quantity_type->Exportable) $Doc->ExportCaption($this->quantity_type);
					if ($this->quantity_out->Exportable) $Doc->ExportCaption($this->quantity_out);
					if ($this->total_quantity->Exportable) $Doc->ExportCaption($this->total_quantity);
					if ($this->treated_by->Exportable) $Doc->ExportCaption($this->treated_by);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->issued_action->Exportable) $Doc->ExportCaption($this->issued_action);
					if ($this->issued_comment->Exportable) $Doc->ExportCaption($this->issued_comment);
					if ($this->issued_by->Exportable) $Doc->ExportCaption($this->issued_by);
					if ($this->approver_date->Exportable) $Doc->ExportCaption($this->approver_date);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approved_comment->Exportable) $Doc->ExportCaption($this->approved_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->verified_date->Exportable) $Doc->ExportCaption($this->verified_date);
					if ($this->verified_action->Exportable) $Doc->ExportCaption($this->verified_action);
					if ($this->verified_comment->Exportable) $Doc->ExportCaption($this->verified_comment);
					if ($this->verified_by->Exportable) $Doc->ExportCaption($this->verified_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date->Exportable) $Doc->ExportCaption($this->date);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->quantity_in->Exportable) $Doc->ExportCaption($this->quantity_in);
					if ($this->quantity_type->Exportable) $Doc->ExportCaption($this->quantity_type);
					if ($this->quantity_out->Exportable) $Doc->ExportCaption($this->quantity_out);
					if ($this->total_quantity->Exportable) $Doc->ExportCaption($this->total_quantity);
					if ($this->treated_by->Exportable) $Doc->ExportCaption($this->treated_by);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->issued_action->Exportable) $Doc->ExportCaption($this->issued_action);
					if ($this->issued_comment->Exportable) $Doc->ExportCaption($this->issued_comment);
					if ($this->issued_by->Exportable) $Doc->ExportCaption($this->issued_by);
					if ($this->approver_date->Exportable) $Doc->ExportCaption($this->approver_date);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approved_comment->Exportable) $Doc->ExportCaption($this->approved_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->verified_date->Exportable) $Doc->ExportCaption($this->verified_date);
					if ($this->verified_action->Exportable) $Doc->ExportCaption($this->verified_action);
					if ($this->verified_comment->Exportable) $Doc->ExportCaption($this->verified_comment);
					if ($this->verified_by->Exportable) $Doc->ExportCaption($this->verified_by);
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
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->quantity_in->Exportable) $Doc->ExportField($this->quantity_in);
						if ($this->quantity_type->Exportable) $Doc->ExportField($this->quantity_type);
						if ($this->quantity_out->Exportable) $Doc->ExportField($this->quantity_out);
						if ($this->total_quantity->Exportable) $Doc->ExportField($this->total_quantity);
						if ($this->treated_by->Exportable) $Doc->ExportField($this->treated_by);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->issued_action->Exportable) $Doc->ExportField($this->issued_action);
						if ($this->issued_comment->Exportable) $Doc->ExportField($this->issued_comment);
						if ($this->issued_by->Exportable) $Doc->ExportField($this->issued_by);
						if ($this->approver_date->Exportable) $Doc->ExportField($this->approver_date);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approved_comment->Exportable) $Doc->ExportField($this->approved_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->verified_date->Exportable) $Doc->ExportField($this->verified_date);
						if ($this->verified_action->Exportable) $Doc->ExportField($this->verified_action);
						if ($this->verified_comment->Exportable) $Doc->ExportField($this->verified_comment);
						if ($this->verified_by->Exportable) $Doc->ExportField($this->verified_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date->Exportable) $Doc->ExportField($this->date);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->quantity_in->Exportable) $Doc->ExportField($this->quantity_in);
						if ($this->quantity_type->Exportable) $Doc->ExportField($this->quantity_type);
						if ($this->quantity_out->Exportable) $Doc->ExportField($this->quantity_out);
						if ($this->total_quantity->Exportable) $Doc->ExportField($this->total_quantity);
						if ($this->treated_by->Exportable) $Doc->ExportField($this->treated_by);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->issued_action->Exportable) $Doc->ExportField($this->issued_action);
						if ($this->issued_comment->Exportable) $Doc->ExportField($this->issued_comment);
						if ($this->issued_by->Exportable) $Doc->ExportField($this->issued_by);
						if ($this->approver_date->Exportable) $Doc->ExportField($this->approver_date);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approved_comment->Exportable) $Doc->ExportField($this->approved_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->verified_date->Exportable) $Doc->ExportField($this->verified_date);
						if ($this->verified_action->Exportable) $Doc->ExportField($this->verified_action);
						if ($this->verified_comment->Exportable) $Doc->ExportField($this->verified_comment);
						if ($this->verified_by->Exportable) $Doc->ExportField($this->verified_by);
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
		$table = 'inventory_store';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'inventory_store';

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
		$table = 'inventory_store';

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
		$table = 'inventory_store';

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
		//if (CurrentUserLevel() == 1) {
			//ew_AddFilter($filter, "`status` in (0,2)");
		//}

		if (CurrentUserLevel() == 1) {
			ew_AddFilter($filter, "`status` in (2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}
		if (CurrentUserLevel() == 2) {
			ew_AddFilter($filter, "`status` in (2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}

	//	if (CurrentUserLevel() == 2) {
			//ew_AddFilter($filter, "`status` in (0,2,4)");
		//}

		if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "`status` in (1)");
		}
		if (CurrentUserLevel() == 5) {
			ew_AddFilter($filter, "`status` in (3)");
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

		if (CurrentPageID() == "add" && CurrentUserLevel() == 1) {

			// Save and forward
			if ($this->issued_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["issued_action"] = 1;
				$rsnew["issued_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->issued_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["issued_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 2) {

			// Save and forward
			if ($this->issued_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["issued_action"] = 1;
				$rsnew["issued_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->issued_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["issued_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 3 ) {

			// Save and forward
			if ($this->issued_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["issued_action"] = 1;
				$rsnew["issued_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->issued_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["issued_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 4 ) {

			// Save and forward
			if ($this->issued_action->CurrentValue == 1) {
				$rsnew["status"] = 1;
				$rsnew["issued_action"] = 1;
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->issued_action->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["issued_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
			//[the error was i did not add quantity_out] ew_Execute("UPDATE `inventory` SET `quantity`= (`quantity` - " . $rsnew["quantity"] . ") WHERE `id`= ".$rsnew["material_name"]."");

			ew_Execute("UPDATE `inventory` SET `quantity`= (`quantity` - " . $rsnew["quantity_out"] . ") WHERE `id`= ".$rsnew["material_name"]."");
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

			date_default_timezone_set('Africa/Lagos');
		$now = new DateTime();
		$this->date_approved->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->date_approved->EditValue = $this->date_approved->CurrentValue;
		$this->verified_date->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->verified_date->EditValue = $this->verified_date->CurrentValue;
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
		}	

		// Officer Only
				//if (CurrentPageID() == "edit" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2)) {

			if (CurrentPageID() == "edit" && CurrentUserLevel() == 2) {

			// Save and forward
			if ($this->issued_action->CurrentValue == 1 && ($this->statuss->CurrentValue == 0 || $this->statuss->CurrentValue == 2)) {
				$rsnew["status"] = 1;
				$rsnew["issued_action"] = 1;
				$rsnew["approver_action"] = NULL;
				$rsnew["approved_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issued Items sent for Review and Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->issued_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["issued_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}

		// Manager Only
			if (CurrentPageID() == "edit" && CurrentUserLevel() == 3) {

			// Save and forward
			if ($this->issued_action->CurrentValue == 1 && ($this->statuss->CurrentValue == 0 || $this->statuss->CurrentValue == 2)) {
				$rsnew["status"] = 1;
				$rsnew["issued_action"] = 1;
				$rsnew["approver_action"] = NULL;
				$rsnew["approved_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issued Items sent for Review and Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->issued_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["issued_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}

		 // Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["date_approved"] = $now->format('Y-m-d H:i:s');
			$rsnew["approved_by"] = $_SESSION['Staff_ID'];
		}

		// Administartor - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["date"] = $rsold["date"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["material_name"] = $rsold["material_name"];
			$rsnew["quantity_in"] = $rsold["quantity_in"];
			$rsnew["quantity_type"] = $rsold["quantity_type"];
			$rsnew["quantity_out"] = $rsold["quantity_out"];
			$rsnew["treated_by"] = $rsold["treated_by"];

			//$rsnew["priority"] = $rsold["priority"];
			//$rsnew["description"] = $rsold["description"];
			//$rsnew["maintained_by"] = $rsold["maintained_by"];
			//$rsnew["date_maintained"] = $rsold["date_maintained"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["issued_action"] = $rsold["issued_action"];
			$rsnew["issued_comment"] = $rsold["issued_comment"];

			//$rsnew["reviewed_action"] = $rsold["reviewed_action"];
			//$rsnew["reviewed_comment"] = $rsold["reviewed_comment"];

		}

		 // Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 5) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
			$rsnew["verified_by"] = $_SESSION['Staff_ID'];
		}

		// Verified By Guard - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 5) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["date"] = $rsold["date"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["material_name"] = $rsold["material_name"];
			$rsnew["quantity_in"] = $rsold["quantity_in"];
			$rsnew["quantity_type"] = $rsold["quantity_type"];
			$rsnew["quantity_out"] = $rsold["quantity_out"];
			$rsnew["treated_by"] = $rsold["treated_by"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["issued_action"] = $rsold["issued_action"];
			$rsnew["issued_comment"] = $rsold["issued_comment"];
			$rsnew["approver_action"] = $rsold["approver_action"];
			$rsnew["approved_comment"] = $rsold["approved_comment"];
		}

			// Approved by Administrators
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
				$rsnew["date_approved"] = $now->format('Y-m-d H:i:s');
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Approved by Administrators
				if ($this->approver_action->CurrentValue == 0 && $this->status->CurrentValue == 1 ) {

					// New
					if ($this->statuss->CurrentValue == 2) {
						$rsnew["status"] = 0;					
						$rsnew["approver_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Issued Items Decliend &#x2714;");
				}

				// Approved by Administrators
				if ($this->approver_action->CurrentValue == 1 ) {

					// New
					if ($this->status->CurrentValue == 1) {
						$rsnew["status"] = 3;					
						$rsnew["approver_action"] = 1;
						$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
					}
					$this->setSuccessMessage("&#x25C9; Issued Items successfully Reviewed and Approved &#x2714;");
				}

			// Verified by Guard=========================================================================================
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 5 && $this->status->CurrentValue == 3)) {
				$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
				$rsnew["verified_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Verified by Guard
				//if ($this->verified_action->CurrentValue == 0 && $this->status->CurrentValue == 3 ) {
					// New
					//if ($this->statuss->CurrentValue == 3) {
					//	$rsnew["statuss"] = 3;					
						//$rsnew["verified_action"] = 0;
					//}
					//$this->setSuccessMessage("&#x25C9; Record Saved &#x2714;");
				//}
				// Verified by Guard

				if ($this->verified_action->CurrentValue == 1 ) {

					// New
					if ($this->status->CurrentValue == 3 && CurrentUserLevel() == 5 ) {
						$rsnew["status"] = 4;					
						$rsnew["verified_action"] = 1;

						//$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
					}

					//$this->setSuccessMessage("&#x25C9; Recieved Items successfully  Verified &#x2714;");
				}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		//ew_Execute("UPDATE inventory SET quantity=".$rsnew["remainder"]." WHERE id=".$rsnew["item_name"]."");
		//ew_Execute("UPDATE `inventory` SET `quantity`= ((`quantity` + " . $rsold["quantity"] . ") - " . $rsnew["quantity"] . ") WHERE `id`= ".$rsold["material_name"]."");

		ew_Execute("UPDATE `inventory` SET `quantity`= (`quantity` - " . $rsnew["quantity_out"] . ") WHERE `id`= ".$rsnew["material_name"]."");
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
		if (CurrentPageID() == "add" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2 || CurrentUserLevel()   == 3 || CurrentUserLevel() == 4)) {
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->treated_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->treated_by->EditValue = $this->treated_by->CurrentValue;
			$this->issued_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->issued_by->EditValue = $this->issued_by->CurrentValue;
		}
		if (CurrentPageID() == "add")  {
			$this->reference_id->CurrentValue = $_SESSION['INS_ID'];
			$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		}
			if (CurrentPageID() == "edit" && (CurrentUserLevel() == 3 )) {
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		}
			if (CurrentPageID() == "edit" && (CurrentUserLevel() == 5 )) {
			$this->verified_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->verified_by->EditValue = $this->verified_by->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

		if (CurrentPageID() == "add") {
				if (CurrentUserLevel() == 1) {
					$this->date->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_type->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->issued_action->Visible = TRUE;
					$this->issued_comment->Visible = TRUE;
					$this->issued_by->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approved_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 2) {
					$this->date->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_type->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->issued_action->Visible = TRUE;
					$this->issued_comment->Visible = TRUE;
					$this->issued_by->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approved_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 3) {
					$this->date->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_type->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->issued_action->Visible = FALSE;
					$this->issued_comment->Visible = FALSE;
					$this->issued_by->Visible = FALSE;

					//$this->approver_date->ReadOnly = TRUE;
					$this->verified_date->Visible = FALSE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approved_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
			}

				// Edit Page
			if (CurrentPageID() == "edit") {
				if ((CurrentUserLevel() == 1||CurrentUserLevel() == 2)) {
					$this->date->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_type->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->issued_action->Visible = TRUE;
					$this->issued_comment->Visible = TRUE;
					$this->issued_by->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approved_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 3) {
					$this->date->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_type->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->issued_action->ReadOnly = TRUE;
					$this->issued_comment->ReadOnly = TRUE;
					$this->issued_by->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = TRUE;
					$this->approved_comment->Visible = TRUE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 5) {
					$this->date->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_type->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->issued_action->ReadOnly = TRUE;
					$this->issued_comment->ReadOnly = TRUE;
					$this->issued_by->ReadOnly = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->ReadOnly = TRUE;
					$this->approved_comment->ReadOnly = TRUE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = TRUE;
					$this->verified_comment->Visible = TRUE;
					$this->verified_by->Visible = FALSE;
				}
			}

				// Highligh rows in color based on the status
		if (CurrentPageID() == "list") {

			//$this->branch_code->Visible = FALSE;
			if ($this->status->CurrentValue == 1) {
				$this->id->CellCssStyle = "color: orange; text-align: left;";
				$this->date->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->material_name->CellCssStyle = "color: orange; text-align: left;";
				$this->issued_by->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity_type->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: orange; text-align: left;";
				$this->reference_id->CellCssStyle = "color: orange; text-align: left;";

				//$this->capacity->CellCssStyle = "color: orange; text-align: left;";
				$this->status->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_date->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_action->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: orange; text-align: left;";
				$this->approved_by->CellCssStyle = "color: orange; text-align: left;";
				$this->verified_by->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->status->CurrentValue == 2) {
				$this->id->CellCssStyle = "color: red; text-align: left;";
				$this->date->CellCssStyle = "color: red; text-align: left;";
				$this->staff_id->CellCssStyle = "color: red; text-align: left;";
				$this->material_name->CellCssStyle = "color: red; text-align: left;";
				$this->issued_by->CellCssStyle = "color: red; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: red; text-align: left;";
				$this->quantity_type->CellCssStyle = "color: red; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: red; text-align: left;";
				$this->reference_id->CellCssStyle = "color: red; text-align: left;";

				//$this->capacity->CellCssStyle = "color: red; text-align: left;";
				$this->status->CellCssStyle = "color: red; text-align: left;";
				$this->approver_date->CellCssStyle = "color: red; text-align: left;";
				$this->approver_action->CellCssStyle = "color: red; text-align: left;";
				$this->approved_comment->CellCssStyle = "color: red; text-align: left;";
				$this->approved_by->CellCssStyle = "color: red; text-align: left;";
				$this->verified_by->CellCssStyle = "color: red; text-align: left;";
			}
			if ($this->status->CurrentValue == 3) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->date_recieved->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->material_name->CellCssStyle = "color: blue; text-align: left;";
				$this->issued_by->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity_type->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: blue; text-align: left;";
				$this->reference_id->CellCssStyle = "color: blue; text-align: left;";

				//$this->capacity->CellCssStyle = "color: blue; text-align: left;";
				$this->statuss->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_date->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_action->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: blue; text-align: left;";
				$this->approved_by->CellCssStyle = "color: blue; text-align: left;";
				$this->verified_by->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->status->CurrentValue == 4) {
				$this->id->CellCssStyle = "color: green; text-align: left;";
				$this->date->CellCssStyle = "color: green; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->material_name->CellCssStyle = "color: green; text-align: left;";
				$this->issued_by->CellCssStyle = "color: green; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: green; text-align: left;";
				$this->quantity_type->CellCssStyle = "color: green; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: green; text-align: left;";
				$this->reference_id->CellCssStyle = "color: green; text-align: left;";

				//$this->capacity->CellCssStyle = "color: green; text-align: left;";
				$this->status->CellCssStyle = "color: green; text-align: left;";
				$this->approver_date->CellCssStyle = "color: green; text-align: left;";
				$this->approver_action->CellCssStyle = "color: green; text-align: left;";
				$this->approved_comment->CellCssStyle = "color: green; text-align: left;";
				$this->approved_by->CellCssStyle = "color: green; text-align: left;";
				$this->verified_by->CellCssStyle = "color: green; text-align: left;";
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
