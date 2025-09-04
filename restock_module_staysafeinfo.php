<?php

// Global variable for table object
$restock_module_staysafe = NULL;

//
// Table class for restock_module_staysafe
//
class crestock_module_staysafe extends cTable {
	var $code;
	var $date_restocked;
	var $reference_id;
	var $material_name;
	var $type;
	var $capacity;
	var $stock_balance;
	var $quantity;
	var $statuss;
	var $restocked_action;
	var $restocked_comment;
	var $restocked_by;
	var $approver_date;
	var $approver_action;
	var $approver_comment;
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
		$this->TableVar = 'restock_module_staysafe';
		$this->TableName = 'restock_module_staysafe';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`restock_module_staysafe`";
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
		$this->code = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_code', 'code', '`code`', '`code`', 3, -1, FALSE, '`code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->code->Sortable = TRUE; // Allow sort
		$this->code->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['code'] = &$this->code;

		// date_restocked
		$this->date_restocked = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_date_restocked', 'date_restocked', '`date_restocked`', ew_CastDateFieldForLike('`date_restocked`', 17, "DB"), 135, 17, FALSE, '`date_restocked`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_restocked->Sortable = TRUE; // Allow sort
		$this->fields['date_restocked'] = &$this->date_restocked;

		// reference_id
		$this->reference_id = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// material_name
		$this->material_name = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_material_name', 'material_name', '`material_name`', '`material_name`', 3, -1, FALSE, '`material_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->material_name->Sortable = TRUE; // Allow sort
		$this->material_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->material_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['material_name'] = &$this->material_name;

		// type
		$this->type = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_type', 'type', '`type`', '`type`', 200, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->type->Sortable = TRUE; // Allow sort
		$this->fields['type'] = &$this->type;

		// capacity
		$this->capacity = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_capacity', 'capacity', '`capacity`', '`capacity`', 200, -1, FALSE, '`capacity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->capacity->Sortable = TRUE; // Allow sort
		$this->fields['capacity'] = &$this->capacity;

		// stock_balance
		$this->stock_balance = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_stock_balance', 'stock_balance', '`stock_balance`', '`stock_balance`', 200, -1, FALSE, '`stock_balance`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->stock_balance->Sortable = TRUE; // Allow sort
		$this->fields['stock_balance'] = &$this->stock_balance;

		// quantity
		$this->quantity = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_quantity', 'quantity', '`quantity`', '`quantity`', 200, -1, FALSE, '`quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity->Sortable = TRUE; // Allow sort
		$this->fields['quantity'] = &$this->quantity;

		// statuss
		$this->statuss = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_statuss', 'statuss', '`statuss`', '`statuss`', 3, -1, FALSE, '`statuss`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->statuss->Sortable = TRUE; // Allow sort
		$this->statuss->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->statuss->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->statuss->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['statuss'] = &$this->statuss;

		// restocked_action
		$this->restocked_action = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_restocked_action', 'restocked_action', '`restocked_action`', '`restocked_action`', 3, -1, FALSE, '`restocked_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->restocked_action->Sortable = TRUE; // Allow sort
		$this->restocked_action->OptionCount = 2;
		$this->restocked_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['restocked_action'] = &$this->restocked_action;

		// restocked_comment
		$this->restocked_comment = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_restocked_comment', 'restocked_comment', '`restocked_comment`', '`restocked_comment`', 201, -1, FALSE, '`restocked_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->restocked_comment->Sortable = TRUE; // Allow sort
		$this->fields['restocked_comment'] = &$this->restocked_comment;

		// restocked_by
		$this->restocked_by = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_restocked_by', 'restocked_by', '`restocked_by`', '`restocked_by`', 3, -1, FALSE, '`restocked_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->restocked_by->Sortable = TRUE; // Allow sort
		$this->fields['restocked_by'] = &$this->restocked_by;

		// approver_date
		$this->approver_date = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_approver_date', 'approver_date', '`approver_date`', ew_CastDateFieldForLike('`approver_date`', 17, "DB"), 135, 17, FALSE, '`approver_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approver_date->Sortable = TRUE; // Allow sort
		$this->fields['approver_date'] = &$this->approver_date;

		// approver_action
		$this->approver_action = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_approver_action', 'approver_action', '`approver_action`', '`approver_action`', 3, -1, FALSE, '`approver_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approver_action->Sortable = TRUE; // Allow sort
		$this->approver_action->OptionCount = 2;
		$this->approver_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approver_action'] = &$this->approver_action;

		// approver_comment
		$this->approver_comment = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_approver_comment', 'approver_comment', '`approver_comment`', '`approver_comment`', 200, -1, FALSE, '`approver_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approver_comment->Sortable = TRUE; // Allow sort
		$this->fields['approver_comment'] = &$this->approver_comment;

		// approved_by
		$this->approved_by = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->fields['approved_by'] = &$this->approved_by;

		// verified_date
		$this->verified_date = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_verified_date', 'verified_date', '`verified_date`', ew_CastDateFieldForLike('`verified_date`', 17, "DB"), 135, 17, FALSE, '`verified_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verified_date->Sortable = TRUE; // Allow sort
		$this->fields['verified_date'] = &$this->verified_date;

		// verified_action
		$this->verified_action = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_verified_action', 'verified_action', '`verified_action`', '`verified_action`', 3, -1, FALSE, '`verified_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->verified_action->Sortable = TRUE; // Allow sort
		$this->verified_action->OptionCount = 2;
		$this->verified_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['verified_action'] = &$this->verified_action;

		// verified_comment
		$this->verified_comment = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_verified_comment', 'verified_comment', '`verified_comment`', '`verified_comment`', 200, -1, FALSE, '`verified_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->verified_comment->Sortable = TRUE; // Allow sort
		$this->fields['verified_comment'] = &$this->verified_comment;

		// verified_by
		$this->verified_by = new cField('restock_module_staysafe', 'restock_module_staysafe', 'x_verified_by', 'verified_by', '`verified_by`', '`verified_by`', 3, -1, FALSE, '`verified_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verified_by->Sortable = TRUE; // Allow sort
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`restock_module_staysafe`";
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
			return "restock_module_staysafelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "restock_module_staysafeview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "restock_module_staysafeedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "restock_module_staysafeadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "restock_module_staysafelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("restock_module_staysafeview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("restock_module_staysafeview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "restock_module_staysafeadd.php?" . $this->UrlParm($parm);
		else
			$url = "restock_module_staysafeadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("restock_module_staysafeedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("restock_module_staysafeadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("restock_module_staysafedelete.php", $this->UrlParm());
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
		$this->date_restocked->setDbValue($rs->fields('date_restocked'));
		$this->reference_id->setDbValue($rs->fields('reference_id'));
		$this->material_name->setDbValue($rs->fields('material_name'));
		$this->type->setDbValue($rs->fields('type'));
		$this->capacity->setDbValue($rs->fields('capacity'));
		$this->stock_balance->setDbValue($rs->fields('stock_balance'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->statuss->setDbValue($rs->fields('statuss'));
		$this->restocked_action->setDbValue($rs->fields('restocked_action'));
		$this->restocked_comment->setDbValue($rs->fields('restocked_comment'));
		$this->restocked_by->setDbValue($rs->fields('restocked_by'));
		$this->approver_date->setDbValue($rs->fields('approver_date'));
		$this->approver_action->setDbValue($rs->fields('approver_action'));
		$this->approver_comment->setDbValue($rs->fields('approver_comment'));
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
		// code
		// date_restocked
		// reference_id
		// material_name
		// type
		// capacity
		// stock_balance
		// quantity
		// statuss
		// restocked_action
		// restocked_comment
		// restocked_by
		// approver_date
		// approver_action
		// approver_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by
		// code

		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_restocked
		$this->date_restocked->ViewValue = $this->date_restocked->CurrentValue;
		$this->date_restocked->ViewValue = ew_FormatDateTime($this->date_restocked->ViewValue, 17);
		$this->date_restocked->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `inventory_staysafe`";
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

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// stock_balance
		$this->stock_balance->ViewValue = $this->stock_balance->CurrentValue;
		$this->stock_balance->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// statuss
		if (strval($this->statuss->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->statuss->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `statuss`";
		$sWhereWrk = "";
		$this->statuss->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->statuss, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->statuss->ViewValue = $this->statuss->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->statuss->ViewValue = $this->statuss->CurrentValue;
			}
		} else {
			$this->statuss->ViewValue = NULL;
		}
		$this->statuss->ViewCustomAttributes = "";

		// restocked_action
		if (strval($this->restocked_action->CurrentValue) <> "") {
			$this->restocked_action->ViewValue = $this->restocked_action->OptionCaption($this->restocked_action->CurrentValue);
		} else {
			$this->restocked_action->ViewValue = NULL;
		}
		$this->restocked_action->ViewCustomAttributes = "";

		// restocked_comment
		$this->restocked_comment->ViewValue = $this->restocked_comment->CurrentValue;
		$this->restocked_comment->ViewCustomAttributes = "";

		// restocked_by
		$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
		if (strval($this->restocked_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->restocked_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->restocked_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->restocked_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->restocked_by->ViewValue = $this->restocked_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->restocked_by->ViewValue = $this->restocked_by->CurrentValue;
			}
		} else {
			$this->restocked_by->ViewValue = NULL;
		}
		$this->restocked_by->ViewCustomAttributes = "";

		// approver_date
		$this->approver_date->ViewValue = $this->approver_date->CurrentValue;
		$this->approver_date->ViewValue = ew_FormatDateTime($this->approver_date->ViewValue, 17);
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
		$this->verified_date->ViewValue = ew_FormatDateTime($this->verified_date->ViewValue, 17);
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

		// code
		$this->code->LinkCustomAttributes = "";
		$this->code->HrefValue = "";
		$this->code->TooltipValue = "";

		// date_restocked
		$this->date_restocked->LinkCustomAttributes = "";
		$this->date_restocked->HrefValue = "";
		$this->date_restocked->TooltipValue = "";

		// reference_id
		$this->reference_id->LinkCustomAttributes = "";
		$this->reference_id->HrefValue = "";
		$this->reference_id->TooltipValue = "";

		// material_name
		$this->material_name->LinkCustomAttributes = "";
		$this->material_name->HrefValue = "";
		$this->material_name->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// capacity
		$this->capacity->LinkCustomAttributes = "";
		$this->capacity->HrefValue = "";
		$this->capacity->TooltipValue = "";

		// stock_balance
		$this->stock_balance->LinkCustomAttributes = "";
		$this->stock_balance->HrefValue = "";
		$this->stock_balance->TooltipValue = "";

		// quantity
		$this->quantity->LinkCustomAttributes = "";
		$this->quantity->HrefValue = "";
		$this->quantity->TooltipValue = "";

		// statuss
		$this->statuss->LinkCustomAttributes = "";
		$this->statuss->HrefValue = "";
		$this->statuss->TooltipValue = "";

		// restocked_action
		$this->restocked_action->LinkCustomAttributes = "";
		$this->restocked_action->HrefValue = "";
		$this->restocked_action->TooltipValue = "";

		// restocked_comment
		$this->restocked_comment->LinkCustomAttributes = "";
		$this->restocked_comment->HrefValue = "";
		$this->restocked_comment->TooltipValue = "";

		// restocked_by
		$this->restocked_by->LinkCustomAttributes = "";
		$this->restocked_by->HrefValue = "";
		$this->restocked_by->TooltipValue = "";

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

		// code
		$this->code->EditAttrs["class"] = "form-control";
		$this->code->EditCustomAttributes = "";
		$this->code->EditValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// date_restocked
		$this->date_restocked->EditAttrs["class"] = "form-control";
		$this->date_restocked->EditCustomAttributes = "";
		$this->date_restocked->EditValue = ew_FormatDateTime($this->date_restocked->CurrentValue, 17);
		$this->date_restocked->PlaceHolder = ew_RemoveHtml($this->date_restocked->FldCaption());

		// reference_id
		$this->reference_id->EditAttrs["class"] = "form-control";
		$this->reference_id->EditCustomAttributes = "";
		$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

		// material_name
		$this->material_name->EditAttrs["class"] = "form-control";
		$this->material_name->EditCustomAttributes = "";

		// type
		$this->type->EditAttrs["class"] = "form-control";
		$this->type->EditCustomAttributes = "";
		$this->type->EditValue = $this->type->CurrentValue;
		$this->type->PlaceHolder = ew_RemoveHtml($this->type->FldCaption());

		// capacity
		$this->capacity->EditAttrs["class"] = "form-control";
		$this->capacity->EditCustomAttributes = "";
		$this->capacity->EditValue = $this->capacity->CurrentValue;
		$this->capacity->PlaceHolder = ew_RemoveHtml($this->capacity->FldCaption());

		// stock_balance
		$this->stock_balance->EditAttrs["class"] = "form-control";
		$this->stock_balance->EditCustomAttributes = "";
		$this->stock_balance->EditValue = $this->stock_balance->CurrentValue;
		$this->stock_balance->PlaceHolder = ew_RemoveHtml($this->stock_balance->FldCaption());

		// quantity
		$this->quantity->EditAttrs["class"] = "form-control";
		$this->quantity->EditCustomAttributes = "";
		$this->quantity->EditValue = $this->quantity->CurrentValue;
		$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

		// statuss
		$this->statuss->EditAttrs["class"] = "form-control";
		$this->statuss->EditCustomAttributes = "";

		// restocked_action
		$this->restocked_action->EditCustomAttributes = "";
		$this->restocked_action->EditValue = $this->restocked_action->Options(FALSE);

		// restocked_comment
		$this->restocked_comment->EditAttrs["class"] = "form-control";
		$this->restocked_comment->EditCustomAttributes = "";
		$this->restocked_comment->EditValue = $this->restocked_comment->CurrentValue;
		$this->restocked_comment->PlaceHolder = ew_RemoveHtml($this->restocked_comment->FldCaption());

		// restocked_by
		$this->restocked_by->EditAttrs["class"] = "form-control";
		$this->restocked_by->EditCustomAttributes = "";
		$this->restocked_by->EditValue = $this->restocked_by->CurrentValue;
		$this->restocked_by->PlaceHolder = ew_RemoveHtml($this->restocked_by->FldCaption());

		// approver_date
		$this->approver_date->EditAttrs["class"] = "form-control";
		$this->approver_date->EditCustomAttributes = "";
		$this->approver_date->EditValue = ew_FormatDateTime($this->approver_date->CurrentValue, 17);
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

		// verified_date
		$this->verified_date->EditAttrs["class"] = "form-control";
		$this->verified_date->EditCustomAttributes = "";
		$this->verified_date->EditValue = ew_FormatDateTime($this->verified_date->CurrentValue, 17);
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
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
					if ($this->date_restocked->Exportable) $Doc->ExportCaption($this->date_restocked);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->capacity->Exportable) $Doc->ExportCaption($this->capacity);
					if ($this->stock_balance->Exportable) $Doc->ExportCaption($this->stock_balance);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->statuss->Exportable) $Doc->ExportCaption($this->statuss);
					if ($this->restocked_action->Exportable) $Doc->ExportCaption($this->restocked_action);
					if ($this->restocked_comment->Exportable) $Doc->ExportCaption($this->restocked_comment);
					if ($this->restocked_by->Exportable) $Doc->ExportCaption($this->restocked_by);
					if ($this->approver_date->Exportable) $Doc->ExportCaption($this->approver_date);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approver_comment->Exportable) $Doc->ExportCaption($this->approver_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->verified_date->Exportable) $Doc->ExportCaption($this->verified_date);
					if ($this->verified_action->Exportable) $Doc->ExportCaption($this->verified_action);
					if ($this->verified_comment->Exportable) $Doc->ExportCaption($this->verified_comment);
					if ($this->verified_by->Exportable) $Doc->ExportCaption($this->verified_by);
				} else {
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
					if ($this->date_restocked->Exportable) $Doc->ExportCaption($this->date_restocked);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->capacity->Exportable) $Doc->ExportCaption($this->capacity);
					if ($this->stock_balance->Exportable) $Doc->ExportCaption($this->stock_balance);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->statuss->Exportable) $Doc->ExportCaption($this->statuss);
					if ($this->restocked_action->Exportable) $Doc->ExportCaption($this->restocked_action);
					if ($this->restocked_by->Exportable) $Doc->ExportCaption($this->restocked_by);
					if ($this->approver_date->Exportable) $Doc->ExportCaption($this->approver_date);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approver_comment->Exportable) $Doc->ExportCaption($this->approver_comment);
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
						if ($this->code->Exportable) $Doc->ExportField($this->code);
						if ($this->date_restocked->Exportable) $Doc->ExportField($this->date_restocked);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->capacity->Exportable) $Doc->ExportField($this->capacity);
						if ($this->stock_balance->Exportable) $Doc->ExportField($this->stock_balance);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->statuss->Exportable) $Doc->ExportField($this->statuss);
						if ($this->restocked_action->Exportable) $Doc->ExportField($this->restocked_action);
						if ($this->restocked_comment->Exportable) $Doc->ExportField($this->restocked_comment);
						if ($this->restocked_by->Exportable) $Doc->ExportField($this->restocked_by);
						if ($this->approver_date->Exportable) $Doc->ExportField($this->approver_date);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approver_comment->Exportable) $Doc->ExportField($this->approver_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->verified_date->Exportable) $Doc->ExportField($this->verified_date);
						if ($this->verified_action->Exportable) $Doc->ExportField($this->verified_action);
						if ($this->verified_comment->Exportable) $Doc->ExportField($this->verified_comment);
						if ($this->verified_by->Exportable) $Doc->ExportField($this->verified_by);
					} else {
						if ($this->code->Exportable) $Doc->ExportField($this->code);
						if ($this->date_restocked->Exportable) $Doc->ExportField($this->date_restocked);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->capacity->Exportable) $Doc->ExportField($this->capacity);
						if ($this->stock_balance->Exportable) $Doc->ExportField($this->stock_balance);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->statuss->Exportable) $Doc->ExportField($this->statuss);
						if ($this->restocked_action->Exportable) $Doc->ExportField($this->restocked_action);
						if ($this->restocked_by->Exportable) $Doc->ExportField($this->restocked_by);
						if ($this->approver_date->Exportable) $Doc->ExportField($this->approver_date);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approver_comment->Exportable) $Doc->ExportField($this->approver_comment);
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

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
		if (CurrentUserLevel() == 9) {
			ew_AddFilter($filter, "`statuss` in (0,2)");
		}
		if (CurrentUserLevel() == 10) {
			ew_AddFilter($filter, "`statuss` in (1)");
		}
		if (CurrentUserLevel() == 11) {
			ew_AddFilter($filter, "`statuss` in (3)");
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

		if (CurrentPageID() == "add" && CurrentUserLevel() == 9) {

			// Save and forward
			if ($this->restocked_action->CurrentValue == 1) {
				$rsnew["statuss"] = 1;
				$rsnew["restocked_action"] = 1;
				$rsnew["restocked_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->recieved_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["recieved_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 10) {

			// Save and forward
			if ($this->restocked_action->CurrentValue == 1) {
				$rsnew["statuss"] = 1;
				$rsnew["restocked_action"] = 1;
				$rsnew["restocked_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->recieved_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["recieved_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
			ew_Execute("UPDATE `inventory_staysafe` SET `quantity`= (`quantity` + " . $this->quantity->CurrentValue . ") WHERE `id`= ".$this->material_name->CurrentValue."");
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();

		// Officer Only
		//	if (CurrentPageID() == "edit" && (CurrentUserLevel() == 9 || CurrentUserLevel() == 2)) {

			if (CurrentPageID() == "edit" && CurrentUserLevel() == 9) {

			// Save and forward
			if ($this->restocked_action->CurrentValue == 1 && $this->statuss->CurrentValue == 0) {
				$rsnew["statuss"] = 1;
				$rsnew["restocked_action"] = 1;
				$rsnew["approver_action"] = NULL;
				$rsnew["approver_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Restoked Items sent for Review and Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->restocked_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["restocked_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}

		// Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4)) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["date_approved"] = $now->format('Y-m-d H:i:s');
			$rsnew["approved_by"] = $_SESSION['Staff_ID'];
		}

		// Administartor - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 10 || CurrentUserLevel() == 4)) {
			$rsnew["code"] = $rsold["code"];
			$rsnew["date_restocked"] = $rsold["date_restocked"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["material_name"] = $rsold["material_name"];
			$rsnew["quantity"] = $rsold["quantity"];
			$rsnew["type"] = $rsold["type"];
			$rsnew["capacity"] = $rsold["capacity"];
			$rsnew["stock_balance"] = $rsold["stock_balance"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["restocked_action"] = $rsold["restocked_action"];
			$rsnew["restocked_comment"] = $rsold["restocked_comment"];
			$rsnew["restocked_by"] = $rsold["restocked_by"];

			//$rsnew["reviewed_action"] = $rsold["reviewed_action"];
			//$rsnew["reviewed_comment"] = $rsold["reviewed_comment"];

		}

		// Supervisor
		   if(CurrentPageID() == "edit" && CurrentUserLevel() == 11) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
			$rsnew["verified_by"] = $_SESSION['Staff_ID'];
		}

		// Verified By Guard - Don't change field values captured by tenant
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 11) {
			$rsnew["code"] = $rsold["code"];
			$rsnew["date_restocked"] = $rsold["date_restocked"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["material_name"] = $rsold["material_name"];
			$rsnew["quantity"] = $rsold["quantity"];
			$rsnew["type"] = $rsold["type"];
			$rsnew["capacity"] = $rsold["capacity"];
			$rsnew["stock_balance"] = $rsold["stock_balance"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["restocked_action"] = $rsold["restocked_action"];
			$rsnew["restocked_comment"] = $rsold["restocked_comment"];
			$rsnew["restocked_by"] = $rsold["restocked_by"];
			$rsnew["approver_action"] = $rsold["approver_action"];
			$rsnew["approver_comment"] = $rsold["approver_comment"];
		}

			// Approved by Administrators
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 10 || CurrentUserLevel() == 12) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
				$rsnew["date_approved"] = $now->format('Y-m-d H:i:s');
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];

			   	// Approved by Administrators
				if ($this->approver_action->CurrentValue == 0) {

					// New
					if ($this->statuss->CurrentValue == 1) {
						$rsnew["statuss"] = 1;					
						$rsnew["approver_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Record Save Only &#x2714;");
				}

				// Approved by Administrators
				if ($this->approver_action->CurrentValue == 1 ) {

					// New
					if ($this->statuss->CurrentValue == 1) {
						$rsnew["statuss"] = 3;					
						$rsnew["approver_action"] = 1;
					}
					$this->setSuccessMessage("&#x25C9; Restocked Items successfully Approved and Verification &#x2714;");
				}
			}

			// Verified by Guard=========================================================================================
			if (CurrentPageID() == "edit" && CurrentUserLevel() == 11) {
				$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
				$rsnew["verified_by"] = $_SESSION['Staff_ID'];

			   	// Verified by Guard
				if ($this->verified_action->CurrentValue == 0) {

					// New
					if ($this->statuss->CurrentValue == 3) {
						$rsnew["statuss"] = 3;					
						$rsnew["verified_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Record Saved &#x2714;");
				}

				// Verified by Guard
				if ($this->verified_action->CurrentValue == 1) {

					// New
					if ($this->statuss->CurrentValue == 3) {
						$rsnew["statuss"] = 4;					
						$rsnew["verified_action"] = 1;

						//$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
					}
					$this->setSuccessMessage("&#x25C9; Restocked Items successfully  Verified &#x2714;");
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
		if (CurrentPageID() == "add")  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_restocked->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_restocked->EditValue = $this->date_restocked->CurrentValue;
			$this->reference_id->CurrentValue = $_SESSION['RSTO_ID'];
			$this->reference_id->EditValue = $this->reference_id->CurrentValue;
			$this->restocked_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->restocked_by->EditValue = $this->restocked_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 10 ) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->approver_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->approver_date->EditValue = $this->approver_date->CurrentValue;
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 11 ) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
		 	$this->verified_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->verified_date->EditValue = $this->verified_date->CurrentValue;
			$this->verified_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->verified_by->EditValue = $this->verified_by->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

			if (CurrentPageID() == "add") {
				if (CurrentUserLevel() == 9) {
		            $this->date_restocked->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->material_name->Visible = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->stock_balance->ReadOnly = TRUE;
					$this->restocked_by->ReadOnly = TRUE;
					$this->restocked_action->Visible = TRUE;
					$this->restocked_comment->Visible = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
					}
					if (CurrentUserLevel() == 10) {
		            $this->date_restocked->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->material_name->Visible = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->stock_balance->ReadOnly = TRUE;
					$this->restocked_by->ReadOnly = TRUE;
					$this->restocked_action->Visible = TRUE;
					$this->restocked_comment->Visible = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
					}
				}

				// Edit Page
			if (CurrentPageID() == "edit") {
				if (CurrentUserLevel() == 9) {
					$this->date_restocked->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->material_name->Visible = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->stock_balance->ReadOnly = TRUE;
					$this->restocked_by->ReadOnly = TRUE;
					$this->restocked_action->Visible = TRUE;
					$this->restocked_comment->Visible = TRUE;
					$this->approver_date->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 10) {
					$this->date_restocked->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->material_name->ReadOnly = TRUE;
					$this->quantity->ReadOnly = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->stock_balance->ReadOnly = TRUE;
					$this->restocked_by->ReadOnly = TRUE;
					$this->restocked_action->ReadOnly = TRUE;
					$this->restocked_comment->ReadOnly = TRUE;
					$this->approver_date->ReadOnly = TRUE;
					$this->approver_action->Visible = TRUE;
					$this->approver_comment->Visible = TRUE;

					//$this->approveed_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 11) {
					$this->date_restocked->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->material_name->ReadOnly = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->stock_balance->ReadOnly = TRUE;
					$this->restocked_by->ReadOnly = TRUE;
					$this->restocked_action->ReadOnly = TRUE;
					$this->restocked_comment->ReadOnly = TRUE;
					$this->approver_date->ReadOnly = TRUE;
					$this->approver_action->ReadOnly = TRUE;
					$this->approver_comment->ReadOnly = TRUE;

					//$this->approveed_by->Visible = FALSE;
					$this->verified_date->ReadOnly = TRUE;
					$this->verified_action->Visible = TRUE;
					$this->verified_comment->Visible = TRUE;

					//$this->verified_by->Visible = FALSE;
				}
			}

		// Highligh rows in color based on the status
		if (CurrentPageID() == "list") {

			//$this->branch_code->Visible = FALSE;
			if ($this->statuss->CurrentValue == 1) {
				$this->code->CellCssStyle = "color: orange; text-align: left;";
				$this->date_restocked->CellCssStyle = "color: orange; text-align: left;";

				//$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->material_name->CellCssStyle = "color: orange; text-align: left;";
				$this->restocked_by->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity->CellCssStyle = "color: orange; text-align: left;";
				$this->type->CellCssStyle = "color: orange; text-align: left;";
				$this->reference_id->CellCssStyle = "color: orange; text-align: left;";
				$this->capacity->CellCssStyle = "color: orange; text-align: left;";
				$this->stock_balance->CellCssStyle = "color: orange; text-align: left;";
				$this->statuss->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_date->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_action->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: orange; text-align: left;";
				$this->approved_by->CellCssStyle = "color: orange; text-align: left;";
				$this->verified_by->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->statuss->CurrentValue == 2) {
				$this->code->CellCssStyle = "color: red; text-align: left;";
				$this->date_restocked->CellCssStyle = "color: red; text-align: left;";

				//$this->staff_id->CellCssStyle = "color: red; text-align: left;";
				$this->material_name->CellCssStyle = "color: red; text-align: left;";
				$this->restocked_by->CellCssStyle = "color: red; text-align: left;";
				$this->quantity->CellCssStyle = "color: red; text-align: left;";
				$this->type->CellCssStyle = "color: red; text-align: left;";
				$this->reference_id->CellCssStyle = "color: red; text-align: left;";
				$this->capacity->CellCssStyle = "color: red; text-align: left;";
				$this->stock_balance->CellCssStyle = "color: red; text-align: left;";
				$this->statuss->CellCssStyle = "color: red; text-align: left;";
				$this->approver_date->CellCssStyle = "color: red; text-align: left;";
				$this->approver_action->CellCssStyle = "color: red; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: red; text-align: left;";
				$this->approved_by->CellCssStyle = "color: red; text-align: left;";
				$this->verified_by->CellCssStyle = "color: red; text-align: left;";
			}
			if ($this->statuss->CurrentValue == 3) {
				$this->code->CellCssStyle = "color: blue; text-align: left;";
				$this->date_restocked->CellCssStyle = "color: blue; text-align: left;";

				//$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->material_name->CellCssStyle = "color: blue; text-align: left;";
				$this->restocked_by->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity->CellCssStyle = "color: blue; text-align: left;";
				$this->type->CellCssStyle = "color: blue; text-align: left;";
				$this->reference_id->CellCssStyle = "color: blue; text-align: left;";
				$this->capacity->CellCssStyle = "color: blue; text-align: left;";
				$this->stock_balance->CellCssStyle = "color: blue; text-align: left;";
				$this->statuss->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_date->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_action->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: blue; text-align: left;";
				$this->approved_by->CellCssStyle = "color: blue; text-align: left;";
				$this->verified_by->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->statuss->CurrentValue == 4) {
				$this->code->CellCssStyle = "color: green; text-align: left;";
				$this->date_restocked->CellCssStyle = "color: green; text-align: left;";

				//$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->material_name->CellCssStyle = "color: green; text-align: left;";
				$this->restocked_by->CellCssStyle = "color: green; text-align: left;";
				$this->quantity->CellCssStyle = "color: green; text-align: left;";
				$this->type->CellCssStyle = "color: green; text-align: left;";
				$this->reference_id->CellCssStyle = "color: green; text-align: left;";
				$this->capacity->CellCssStyle = "color: green; text-align: left;";
				$this->stock_balance->CellCssStyle = "color: green; text-align: left;";
				$this->statuss->CellCssStyle = "color: green; text-align: left;";
				$this->approver_date->CellCssStyle = "color: green; text-align: left;";
				$this->approver_action->CellCssStyle = "color: green; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: green; text-align: left;";
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
