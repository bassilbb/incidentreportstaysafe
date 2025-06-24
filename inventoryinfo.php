<?php

// Global variable for table object
$inventory = NULL;

//
// Table class for inventory
//
class cinventory extends cTable {
	var $id;
	var $date_recieved;
	var $reference_id;
	var $staff_id;
	var $material_name;
	var $quantity;
	var $type;
	var $capacity;
	var $recieved_by;
	var $statuss;
	var $recieved_action;
	var $recieved_comment;
	var $date_approved;
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
		$this->TableVar = 'inventory';
		$this->TableName = 'inventory';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`inventory`";
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
		$this->id = new cField('inventory', 'inventory', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// date_recieved
		$this->date_recieved = new cField('inventory', 'inventory', 'x_date_recieved', 'date_recieved', '`date_recieved`', ew_CastDateFieldForLike('`date_recieved`', 17, "DB"), 135, 17, FALSE, '`date_recieved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_recieved->Sortable = TRUE; // Allow sort
		$this->date_recieved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['date_recieved'] = &$this->date_recieved;

		// reference_id
		$this->reference_id = new cField('inventory', 'inventory', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// staff_id
		$this->staff_id = new cField('inventory', 'inventory', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 200, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->fields['staff_id'] = &$this->staff_id;

		// material_name
		$this->material_name = new cField('inventory', 'inventory', 'x_material_name', 'material_name', '`material_name`', '`material_name`', 200, -1, FALSE, '`material_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->material_name->Sortable = TRUE; // Allow sort
		$this->fields['material_name'] = &$this->material_name;

		// quantity
		$this->quantity = new cField('inventory', 'inventory', 'x_quantity', 'quantity', '`quantity`', '`quantity`', 200, -1, FALSE, '`quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity->Sortable = TRUE; // Allow sort
		$this->fields['quantity'] = &$this->quantity;

		// type
		$this->type = new cField('inventory', 'inventory', 'x_type', 'type', '`type`', '`type`', 200, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->type->Sortable = TRUE; // Allow sort
		$this->fields['type'] = &$this->type;

		// capacity
		$this->capacity = new cField('inventory', 'inventory', 'x_capacity', 'capacity', '`capacity`', '`capacity`', 200, -1, FALSE, '`capacity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->capacity->Sortable = TRUE; // Allow sort
		$this->fields['capacity'] = &$this->capacity;

		// recieved_by
		$this->recieved_by = new cField('inventory', 'inventory', 'x_recieved_by', 'recieved_by', '`recieved_by`', '`recieved_by`', 3, -1, FALSE, '`recieved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->recieved_by->Sortable = TRUE; // Allow sort
		$this->fields['recieved_by'] = &$this->recieved_by;

		// statuss
		$this->statuss = new cField('inventory', 'inventory', 'x_statuss', 'statuss', '`statuss`', '`statuss`', 3, -1, FALSE, '`statuss`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->statuss->Sortable = TRUE; // Allow sort
		$this->statuss->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->statuss->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->statuss->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['statuss'] = &$this->statuss;

		// recieved_action
		$this->recieved_action = new cField('inventory', 'inventory', 'x_recieved_action', 'recieved_action', '`recieved_action`', '`recieved_action`', 3, -1, FALSE, '`recieved_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->recieved_action->Sortable = TRUE; // Allow sort
		$this->recieved_action->OptionCount = 2;
		$this->fields['recieved_action'] = &$this->recieved_action;

		// recieved_comment
		$this->recieved_comment = new cField('inventory', 'inventory', 'x_recieved_comment', 'recieved_comment', '`recieved_comment`', '`recieved_comment`', 200, -1, FALSE, '`recieved_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->recieved_comment->Sortable = TRUE; // Allow sort
		$this->fields['recieved_comment'] = &$this->recieved_comment;

		// date_approved
		$this->date_approved = new cField('inventory', 'inventory', 'x_date_approved', 'date_approved', '`date_approved`', ew_CastDateFieldForLike('`date_approved`', 17, "DB"), 135, 17, FALSE, '`date_approved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_approved->Sortable = TRUE; // Allow sort
		$this->date_approved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['date_approved'] = &$this->date_approved;

		// approver_action
		$this->approver_action = new cField('inventory', 'inventory', 'x_approver_action', 'approver_action', '`approver_action`', '`approver_action`', 3, -1, FALSE, '`approver_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approver_action->Sortable = TRUE; // Allow sort
		$this->approver_action->OptionCount = 2;
		$this->approver_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approver_action'] = &$this->approver_action;

		// approver_comment
		$this->approver_comment = new cField('inventory', 'inventory', 'x_approver_comment', 'approver_comment', '`approver_comment`', '`approver_comment`', 200, -1, FALSE, '`approver_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approver_comment->Sortable = TRUE; // Allow sort
		$this->fields['approver_comment'] = &$this->approver_comment;

		// approved_by
		$this->approved_by = new cField('inventory', 'inventory', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->fields['approved_by'] = &$this->approved_by;

		// verified_date
		$this->verified_date = new cField('inventory', 'inventory', 'x_verified_date', 'verified_date', '`verified_date`', ew_CastDateFieldForLike('`verified_date`', 17, "DB"), 135, 17, FALSE, '`verified_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verified_date->Sortable = TRUE; // Allow sort
		$this->verified_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['verified_date'] = &$this->verified_date;

		// verified_action
		$this->verified_action = new cField('inventory', 'inventory', 'x_verified_action', 'verified_action', '`verified_action`', '`verified_action`', 3, -1, FALSE, '`verified_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->verified_action->Sortable = TRUE; // Allow sort
		$this->verified_action->OptionCount = 2;
		$this->verified_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['verified_action'] = &$this->verified_action;

		// verified_comment
		$this->verified_comment = new cField('inventory', 'inventory', 'x_verified_comment', 'verified_comment', '`verified_comment`', '`verified_comment`', 200, -1, FALSE, '`verified_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->verified_comment->Sortable = TRUE; // Allow sort
		$this->fields['verified_comment'] = &$this->verified_comment;

		// verified_by
		$this->verified_by = new cField('inventory', 'inventory', 'x_verified_by', 'verified_by', '`verified_by`', '`verified_by`', 3, -1, FALSE, '`verified_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`inventory`";
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
			return "inventorylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "inventoryview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "inventoryedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "inventoryadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "inventorylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("inventoryview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("inventoryview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "inventoryadd.php?" . $this->UrlParm($parm);
		else
			$url = "inventoryadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("inventoryedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("inventoryadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("inventorydelete.php", $this->UrlParm());
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
		$this->date_recieved->setDbValue($rs->fields('date_recieved'));
		$this->reference_id->setDbValue($rs->fields('reference_id'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->material_name->setDbValue($rs->fields('material_name'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->type->setDbValue($rs->fields('type'));
		$this->capacity->setDbValue($rs->fields('capacity'));
		$this->recieved_by->setDbValue($rs->fields('recieved_by'));
		$this->statuss->setDbValue($rs->fields('statuss'));
		$this->recieved_action->setDbValue($rs->fields('recieved_action'));
		$this->recieved_comment->setDbValue($rs->fields('recieved_comment'));
		$this->date_approved->setDbValue($rs->fields('date_approved'));
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
		// id
		// date_recieved
		// reference_id
		// staff_id
		// material_name
		// quantity
		// type
		// capacity
		// recieved_by
		// statuss
		// recieved_action
		// recieved_comment
		// date_approved
		// approver_action
		// approver_comment
		// approved_by
		// verified_date
		// verified_action
		// verified_comment
		// verified_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->ViewValue = $this->date_recieved->CurrentValue;
		$this->date_recieved->ViewValue = ew_FormatDateTime($this->date_recieved->ViewValue, 17);
		$this->date_recieved->ViewCustomAttributes = "";

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
		$this->material_name->ViewValue = $this->material_name->CurrentValue;
		$this->material_name->ViewCustomAttributes = "";

		// quantity
		$this->quantity->ViewValue = $this->quantity->CurrentValue;
		$this->quantity->ViewCustomAttributes = "";

		// type
		$this->type->ViewValue = $this->type->CurrentValue;
		$this->type->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// recieved_by
		$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
		if (strval($this->recieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->recieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->recieved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->recieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

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

		// recieved_action
		if (strval($this->recieved_action->CurrentValue) <> "") {
			$this->recieved_action->ViewValue = $this->recieved_action->OptionCaption($this->recieved_action->CurrentValue);
		} else {
			$this->recieved_action->ViewValue = NULL;
		}
		$this->recieved_action->ViewCustomAttributes = "";

		// recieved_comment
		$this->recieved_comment->ViewValue = $this->recieved_comment->CurrentValue;
		$this->recieved_comment->ViewCustomAttributes = "";

		// date_approved
		$this->date_approved->ViewValue = $this->date_approved->CurrentValue;
		$this->date_approved->ViewValue = ew_FormatDateTime($this->date_approved->ViewValue, 17);
		$this->date_approved->ViewCustomAttributes = "";

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
		$sSqlWrk .= " ORDER BY `id`";
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

		// date_recieved
		$this->date_recieved->LinkCustomAttributes = "";
		$this->date_recieved->HrefValue = "";
		$this->date_recieved->TooltipValue = "";

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

		// quantity
		$this->quantity->LinkCustomAttributes = "";
		$this->quantity->HrefValue = "";
		$this->quantity->TooltipValue = "";

		// type
		$this->type->LinkCustomAttributes = "";
		$this->type->HrefValue = "";
		$this->type->TooltipValue = "";

		// capacity
		$this->capacity->LinkCustomAttributes = "";
		$this->capacity->HrefValue = "";
		$this->capacity->TooltipValue = "";

		// recieved_by
		$this->recieved_by->LinkCustomAttributes = "";
		$this->recieved_by->HrefValue = "";
		$this->recieved_by->TooltipValue = "";

		// statuss
		$this->statuss->LinkCustomAttributes = "";
		$this->statuss->HrefValue = "";
		$this->statuss->TooltipValue = "";

		// recieved_action
		$this->recieved_action->LinkCustomAttributes = "";
		$this->recieved_action->HrefValue = "";
		$this->recieved_action->TooltipValue = "";

		// recieved_comment
		$this->recieved_comment->LinkCustomAttributes = "";
		$this->recieved_comment->HrefValue = "";
		$this->recieved_comment->TooltipValue = "";

		// date_approved
		$this->date_approved->LinkCustomAttributes = "";
		$this->date_approved->HrefValue = "";
		$this->date_approved->TooltipValue = "";

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

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->EditAttrs["class"] = "form-control";
		$this->date_recieved->EditCustomAttributes = "";
		$this->date_recieved->EditValue = ew_FormatDateTime($this->date_recieved->CurrentValue, 17);
		$this->date_recieved->PlaceHolder = ew_RemoveHtml($this->date_recieved->FldCaption());

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
		$this->material_name->EditValue = $this->material_name->CurrentValue;
		$this->material_name->PlaceHolder = ew_RemoveHtml($this->material_name->FldCaption());

		// quantity
		$this->quantity->EditAttrs["class"] = "form-control";
		$this->quantity->EditCustomAttributes = "";
		$this->quantity->EditValue = $this->quantity->CurrentValue;
		$this->quantity->PlaceHolder = ew_RemoveHtml($this->quantity->FldCaption());

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

		// recieved_by
		$this->recieved_by->EditAttrs["class"] = "form-control";
		$this->recieved_by->EditCustomAttributes = "";
		$this->recieved_by->EditValue = $this->recieved_by->CurrentValue;
		$this->recieved_by->PlaceHolder = ew_RemoveHtml($this->recieved_by->FldCaption());

		// statuss
		$this->statuss->EditAttrs["class"] = "form-control";
		$this->statuss->EditCustomAttributes = "";

		// recieved_action
		$this->recieved_action->EditCustomAttributes = "";
		$this->recieved_action->EditValue = $this->recieved_action->Options(FALSE);

		// recieved_comment
		$this->recieved_comment->EditAttrs["class"] = "form-control";
		$this->recieved_comment->EditCustomAttributes = "";
		$this->recieved_comment->EditValue = $this->recieved_comment->CurrentValue;
		$this->recieved_comment->PlaceHolder = ew_RemoveHtml($this->recieved_comment->FldCaption());

		// date_approved
		$this->date_approved->EditAttrs["class"] = "form-control";
		$this->date_approved->EditCustomAttributes = "";
		$this->date_approved->EditValue = ew_FormatDateTime($this->date_approved->CurrentValue, 17);
		$this->date_approved->PlaceHolder = ew_RemoveHtml($this->date_approved->FldCaption());

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
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date_recieved->Exportable) $Doc->ExportCaption($this->date_recieved);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->capacity->Exportable) $Doc->ExportCaption($this->capacity);
					if ($this->recieved_by->Exportable) $Doc->ExportCaption($this->recieved_by);
					if ($this->statuss->Exportable) $Doc->ExportCaption($this->statuss);
					if ($this->recieved_action->Exportable) $Doc->ExportCaption($this->recieved_action);
					if ($this->recieved_comment->Exportable) $Doc->ExportCaption($this->recieved_comment);
					if ($this->date_approved->Exportable) $Doc->ExportCaption($this->date_approved);
					if ($this->approver_action->Exportable) $Doc->ExportCaption($this->approver_action);
					if ($this->approver_comment->Exportable) $Doc->ExportCaption($this->approver_comment);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->verified_date->Exportable) $Doc->ExportCaption($this->verified_date);
					if ($this->verified_action->Exportable) $Doc->ExportCaption($this->verified_action);
					if ($this->verified_comment->Exportable) $Doc->ExportCaption($this->verified_comment);
					if ($this->verified_by->Exportable) $Doc->ExportCaption($this->verified_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->date_recieved->Exportable) $Doc->ExportCaption($this->date_recieved);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
					if ($this->type->Exportable) $Doc->ExportCaption($this->type);
					if ($this->capacity->Exportable) $Doc->ExportCaption($this->capacity);
					if ($this->recieved_by->Exportable) $Doc->ExportCaption($this->recieved_by);
					if ($this->statuss->Exportable) $Doc->ExportCaption($this->statuss);
					if ($this->recieved_action->Exportable) $Doc->ExportCaption($this->recieved_action);
					if ($this->recieved_comment->Exportable) $Doc->ExportCaption($this->recieved_comment);
					if ($this->date_approved->Exportable) $Doc->ExportCaption($this->date_approved);
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
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date_recieved->Exportable) $Doc->ExportField($this->date_recieved);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->capacity->Exportable) $Doc->ExportField($this->capacity);
						if ($this->recieved_by->Exportable) $Doc->ExportField($this->recieved_by);
						if ($this->statuss->Exportable) $Doc->ExportField($this->statuss);
						if ($this->recieved_action->Exportable) $Doc->ExportField($this->recieved_action);
						if ($this->recieved_comment->Exportable) $Doc->ExportField($this->recieved_comment);
						if ($this->date_approved->Exportable) $Doc->ExportField($this->date_approved);
						if ($this->approver_action->Exportable) $Doc->ExportField($this->approver_action);
						if ($this->approver_comment->Exportable) $Doc->ExportField($this->approver_comment);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->verified_date->Exportable) $Doc->ExportField($this->verified_date);
						if ($this->verified_action->Exportable) $Doc->ExportField($this->verified_action);
						if ($this->verified_comment->Exportable) $Doc->ExportField($this->verified_comment);
						if ($this->verified_by->Exportable) $Doc->ExportField($this->verified_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->date_recieved->Exportable) $Doc->ExportField($this->date_recieved);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
						if ($this->type->Exportable) $Doc->ExportField($this->type);
						if ($this->capacity->Exportable) $Doc->ExportField($this->capacity);
						if ($this->recieved_by->Exportable) $Doc->ExportField($this->recieved_by);
						if ($this->statuss->Exportable) $Doc->ExportField($this->statuss);
						if ($this->recieved_action->Exportable) $Doc->ExportField($this->recieved_action);
						if ($this->recieved_comment->Exportable) $Doc->ExportField($this->recieved_comment);
						if ($this->date_approved->Exportable) $Doc->ExportField($this->date_approved);
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
		/*if (CurrentUserLevel() == 1) {
			ew_AddFilter($filter, "`statuss` in (0,2)");
		}*/
		if (CurrentUserLevel() == 1) {
			ew_AddFilter($filter, "`statuss` in (0,2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}
		if (CurrentUserLevel() == 1) {
			ew_AddFilter($filter, "`statuss` in (0,2) AND `staff_id` = '".$_SESSION['Staff_ID']."'");
		}
		/*if (CurrentUserLevel() == 2) {
			ew_AddFilter($filter, "`statuss` in (0,2)");
		}*/
		if (CurrentUserLevel() == 3) {
			ew_AddFilter($filter, "`statuss` in (1)");
		}
		if (CurrentUserLevel() == 4) {
			ew_AddFilter($filter, "`statuss` in (1)");
		}
		if (CurrentUserLevel() == 5) {
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

		date_default_timezone_set('Africa/Lagos');
		$now = new DateTime();
		$this->date_recieved->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->date_recieved->EditValue = $this->date_recieved->CurrentValue;

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 1) {

			// Save and forward
			if ($this->recieved_action->CurrentValue == 1) {
				$rsnew["statuss"] = 1;
				$rsnew["recieved_action"] = 1;
				$rsnew["recieved_by"] = $_SESSION['Staff_ID'];
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
		if (CurrentPageID() == "add" && CurrentUserLevel() == 2) {

			// Save and forward
			if ($this->recieved_action->CurrentValue == 1) {
				$rsnew["statuss"] = 1;
				$rsnew["recieved_action"] = 1;
				$rsnew["recieved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->recieved_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["recieved_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 3 ) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["statuss"] = 1;
				$rsnew["initiator_action"] = 1;
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 4 ) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["statuss"] = 1;
				$rsnew["initiator_action"] = 1;
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; Record sent for Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
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
		$this->date_recieved->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->date_recieved->EditValue = $this->date_recieved->CurrentValue;
		$this->date_approved->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->date_approved->EditValue = $this->date_approved->CurrentValue;
		$this->verified_date->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->verified_date->EditValue = $this->verified_date->CurrentValue;
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
		}	

		// Officer Only
				if (CurrentPageID() == "edit" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2)) {

				//if (CurrentPageID() == "edit" && CurrentUserLevel() == 1) {
			// Save and forward

			if ($this->recieved_action->CurrentValue == 1 && ($this->statuss->CurrentValue == 0 || $this->statuss->CurrentValue == 2)) {
				$rsnew["statuss"] = 1;
				$rsnew["recieved_action"] = 1;
				$rsnew["approver_action"] = NULL;
				$rsnew["approver_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Recieved Items sent for Review and Approval &#x2714;"); 					
			}

			// Saved only
			if ($this->recieved_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["statuss"] = 0;			
				$rsnew["recieved_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}

		 // Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["date_approved"] = $now->format('Y-m-d H:i:s');
			$rsnew["approved_by"] = $_SESSION['Staff_ID'];
		}

		// Administartor - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4) && ($this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["date_recieved"] = $rsold["date_recieved"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["material_name"] = $rsold["material_name"];
			$rsnew["quantity"] = $rsold["quantity"];
			$rsnew["type"] = $rsold["type"];
			$rsnew["capacity"] = $rsold["capacity"];

			//$rsnew["items"] = $rsold["items"];
			//$rsnew["priority"] = $rsold["priority"];
			//$rsnew["description"] = $rsold["description"];
			//$rsnew["maintained_by"] = $rsold["maintained_by"];
			//$rsnew["date_maintained"] = $rsold["date_maintained"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["recieved_action"] = $rsold["recieved_action"];
			$rsnew["recieved_comment"] = $rsold["recieved_comment"];

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
			$rsnew["date_recieved"] = $rsold["date_recieved"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["material_name"] = $rsold["material_name"];
			$rsnew["quantity"] = $rsold["quantity"];
			$rsnew["type"] = $rsold["type"];
			$rsnew["capacity"] = $rsold["capacity"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["recieved_action"] = $rsold["recieved_action"];
			$rsnew["recieved_comment"] = $rsold["recieved_comment"];
			$rsnew["approver_action"] = $rsold["approver_action"];
			$rsnew["approver_comment"] = $rsold["approver_comment"];
		}

			// Approved by Administrators
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3 || CurrentUserLevel() == 4) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
				$rsnew["date_approved"] = $now->format('Y-m-d H:i:s');
				$rsnew["approved_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Approved by Administrators
				if ($this->approver_action->CurrentValue == 0) {

					// New
					if ($this->statuss->CurrentValue == 2) {
						$rsnew["statuss"] = 0;					
						$rsnew["approver_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Recieved Items Decliend &#x2714;");
				}

				// Approved by Administrators
				if ($this->approver_action->CurrentValue == 1 ) {

					// New
					if ($this->statuss->CurrentValue == 1) {
						$rsnew["statuss"] = 3;					
						$rsnew["approver_action"] = 1;
					}
					$this->setSuccessMessage("&#x25C9; Recieved Items successfully Reviewed and Approved &#x2714;");
				}

			// Verified by Guard=========================================================================================
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 5 && $this->status->CurrentValue == 3)) {
				$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
				$rsnew["verified_by"] = $_SESSION['Staff_ID'];
			  }

			   	// Verified by Guard
				if ($this->verified_action->CurrentValue == 0 && $this->statuss->CurrentValue == 3 ) {

					// New
					if ($this->statuss->CurrentValue == 3) {
						$rsnew["statuss"] = 2;					
						$rsnew["verified_action"] = 0;
					}
					$this->setSuccessMessage("&#x25C9; Record Saved &#x2714;");
				}

				// Verified by Guard
				if ($this->verified_action->CurrentValue == 2 ) {

					// New
					if ($this->statuss->CurrentValue == 3 && CurrentUserLevel() == 5 ) {
						$rsnew["statuss"] = 4;					
						$rsnew["verified_action"] = 2;

						//$rsnew["verified_date"] = $now->format('Y-m-d H:i:s');
					}

					//$this->setSuccessMessage("&#x25C9; Recieved Items successfully  Verified &#x2714;");
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
		if (CurrentPageID() == "add" )  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_recieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_recieved->EditValue = $this->date_recieved->CurrentValue;
		}
		if (CurrentPageID() == "add" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2 || CurrentUserLevel()   == 3 || CurrentUserLevel() == 4)) {
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->recieved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->recieved_by->EditValue = $this->recieved_by->CurrentValue;
		}
		if (CurrentPageID() == "add")  {
			$this->reference_id->CurrentValue = $_SESSION['INV_ID'];
			$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3 ) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_approved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_approved->EditValue = $this->date_approved->CurrentValue;
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 4 ) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_approved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_approved->EditValue = $this->date_approved->CurrentValue;
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->approved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 5 ) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
		 	$this->verified_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->verified_date->EditValue = $this->verified_date->CurrentValue;
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
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
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->material_name->Visible = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->Visible = TRUE;
					$this->capacity->Visible = TRUE;
					$this->recieved_by->Visible = FALSE;
					$this->date_approved->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 2) {
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->material_name->Visible = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->Visible = TRUE;
					$this->capacity->Visible = TRUE;
					$this->recieved_by->Visible = FALSE;
					$this->date_approved->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 2) {
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;

					//$this->recieved_by->Visible = FALSE;	
				}
				if (CurrentUserLevel() == 3) {
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;

					//$this->recieved_by->Visible = FALSE;	
				}
		   }

		   // Edit Page
			if (CurrentPageID() == "edit") {
				if ((CurrentUserLevel() == 1||CurrentUserLevel() == 2)) {
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->material_name->ReadOnly = TRUE;
					$this->quantity->Visible = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->recieved_by->Visible = FALSE;
					$this->date_approved->Visible = FALSE;
					$this->approver_action->Visible = FALSE;
					$this->approver_comment->Visible = FALSE;
					$this->approved_by->Visible = FALSE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if ((CurrentUserLevel() == 3|| CurrentUserLevel() == 4)) {
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->material_name->ReadOnly = TRUE;
					$this->quantity->ReadOnly = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->recieved_by->ReadOnly = TRUE;
					$this->recieved_action->ReadOnly = TRUE;
					$this->recieved_comment->ReadOnly = TRUE;
					$this->date_approved->ReadOnly = TRUE;
					$this->approver_action->Visible = TRUE;
					$this->approver_comment->Visible = TRUE;
					$this->approved_by->ReadOnly = TRUE;
					$this->verified_date->Visible = FALSE;
					$this->verified_action->Visible = FALSE;
					$this->verified_comment->Visible = FALSE;
					$this->verified_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 5) {
					$this->date_recieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->staff_id->ReadOnly = TRUE;
					$this->material_name->ReadOnly = TRUE;
					$this->quantity->ReadOnly = TRUE;
					$this->type->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->recieved_by->ReadOnly = TRUE;
					$this->recieved_by->ReadOnly = TRUE;
					$this->recieved_action->ReadOnly = TRUE;
					$this->recieved_comment->ReadOnly = TRUE;
					$this->date_approved->ReadOnly = TRUE;
					$this->approver_action->ReadOnly = TRUE;
					$this->approver_comment->ReadOnly = TRUE;
					$this->approved_by->ReadOnly = TRUE;
					$this->verified_date->TRUE = TRUE;
					$this->verified_action->Visible = TRUE;
					$this->verified_comment->Visible = TRUE;

					//$this->verified_by->ReadOnly = TRUE;
				}
			}

		// Highligh rows in color based on the status
		if (CurrentPageID() == "list") {

			//$this->branch_code->Visible = FALSE;
			if ($this->statuss->CurrentValue == 1) {
				$this->id->CellCssStyle = "color: orange; text-align: left;";
				$this->date_recieved->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->material_name->CellCssStyle = "color: orange; text-align: left;";
				$this->recieved_by->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity->CellCssStyle = "color: orange; text-align: left;";
				$this->type->CellCssStyle = "color: orange; text-align: left;";
				$this->reference_id->CellCssStyle = "color: orange; text-align: left;";
				$this->capacity->CellCssStyle = "color: orange; text-align: left;";
				$this->statuss->CellCssStyle = "color: orange; text-align: left;";
				$this->date_approved->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_action->CellCssStyle = "color: orange; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: orange; text-align: left;";
				$this->approved_by->CellCssStyle = "color: orange; text-align: left;";
				$this->verified_by->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->statuss->CurrentValue == 2) {
				$this->id->CellCssStyle = "color: red; text-align: left;";
				$this->date_recieved->CellCssStyle = "color: red; text-align: left;";
				$this->staff_id->CellCssStyle = "color: red; text-align: left;";
				$this->material_name->CellCssStyle = "color: red; text-align: left;";
				$this->recieved_by->CellCssStyle = "color: red; text-align: left;";
				$this->quantity->CellCssStyle = "color: red; text-align: left;";
				$this->type->CellCssStyle = "color: red; text-align: left;";
				$this->reference_id->CellCssStyle = "color: red; text-align: left;";
				$this->capacity->CellCssStyle = "color: red; text-align: left;";
				$this->statuss->CellCssStyle = "color: red; text-align: left;";
				$this->date_approved->CellCssStyle = "color: red; text-align: left;";
				$this->approver_action->CellCssStyle = "color: red; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: red; text-align: left;";
				$this->approved_by->CellCssStyle = "color: red; text-align: left;";
				$this->verified_by->CellCssStyle = "color: red; text-align: left;";
			}
			if ($this->statuss->CurrentValue == 3) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->date_recieved->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->material_name->CellCssStyle = "color: blue; text-align: left;";
				$this->recieved_by->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity->CellCssStyle = "color: blue; text-align: left;";
				$this->type->CellCssStyle = "color: blue; text-align: left;";
				$this->reference_id->CellCssStyle = "color: blue; text-align: left;";
				$this->capacity->CellCssStyle = "color: blue; text-align: left;";
				$this->statuss->CellCssStyle = "color: blue; text-align: left;";
				$this->date_approved->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_action->CellCssStyle = "color: blue; text-align: left;";
				$this->approver_comment->CellCssStyle = "color: blue; text-align: left;";
				$this->approved_by->CellCssStyle = "color: blue; text-align: left;";
				$this->verified_by->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->statuss->CurrentValue == 4) {
				$this->id->CellCssStyle = "color: green; text-align: left;";
				$this->date_recieved->CellCssStyle = "color: green; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->material_name->CellCssStyle = "color: green; text-align: left;";
				$this->recieved_by->CellCssStyle = "color: green; text-align: left;";
				$this->quantity->CellCssStyle = "color: green; text-align: left;";
				$this->type->CellCssStyle = "color: green; text-align: left;";
				$this->reference_id->CellCssStyle = "color: green; text-align: left;";
				$this->capacity->CellCssStyle = "color: green; text-align: left;";
				$this->statuss->CellCssStyle = "color: green; text-align: left;";
				$this->date_approved->CellCssStyle = "color: green; text-align: left;";
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
