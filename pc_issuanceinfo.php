<?php

// Global variable for table object
$pc_issuance = NULL;

//
// Table class for pc_issuance
//
class cpc_issuance extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id;
	var $issued_date;
	var $reference_id;
	var $material_name;
	var $asset_tag;
	var $make;
	var $ram;
	var $hard_disk;
	var $color;
	var $capacity;
	var $quantity_in;
	var $quantity_out;
	var $total_quantity;
	var $department;
	var $designation;
	var $assign_to;
	var $date_assign;
	var $assign_action;
	var $assign_comment;
	var $assign_by;
	var $statuse;
	var $date_retrieved;
	var $retriever_action;
	var $retriever_comment;
	var $retrieved_by;
	var $staff_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pc_issuance';
		$this->TableName = 'pc_issuance';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`pc_issuance`";
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
		$this->id = new cField('pc_issuance', 'pc_issuance', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// issued_date
		$this->issued_date = new cField('pc_issuance', 'pc_issuance', 'x_issued_date', 'issued_date', '`issued_date`', ew_CastDateFieldForLike('`issued_date`', 17, "DB"), 135, 17, FALSE, '`issued_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->issued_date->Sortable = TRUE; // Allow sort
		$this->fields['issued_date'] = &$this->issued_date;

		// reference_id
		$this->reference_id = new cField('pc_issuance', 'pc_issuance', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// material_name
		$this->material_name = new cField('pc_issuance', 'pc_issuance', 'x_material_name', 'material_name', '`material_name`', '`material_name`', 3, -1, FALSE, '`material_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->material_name->Sortable = TRUE; // Allow sort
		$this->material_name->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->material_name->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['material_name'] = &$this->material_name;

		// asset_tag
		$this->asset_tag = new cField('pc_issuance', 'pc_issuance', 'x_asset_tag', 'asset_tag', '`asset_tag`', '`asset_tag`', 200, -1, FALSE, '`asset_tag`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->asset_tag->Sortable = TRUE; // Allow sort
		$this->fields['asset_tag'] = &$this->asset_tag;

		// make
		$this->make = new cField('pc_issuance', 'pc_issuance', 'x_make', 'make', '`make`', '`make`', 200, -1, FALSE, '`make`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->make->Sortable = TRUE; // Allow sort
		$this->fields['make'] = &$this->make;

		// ram
		$this->ram = new cField('pc_issuance', 'pc_issuance', 'x_ram', 'ram', '`ram`', '`ram`', 200, -1, FALSE, '`ram`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ram->Sortable = TRUE; // Allow sort
		$this->fields['ram'] = &$this->ram;

		// hard_disk
		$this->hard_disk = new cField('pc_issuance', 'pc_issuance', 'x_hard_disk', 'hard_disk', '`hard_disk`', '`hard_disk`', 200, -1, FALSE, '`hard_disk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hard_disk->Sortable = TRUE; // Allow sort
		$this->fields['hard_disk'] = &$this->hard_disk;

		// color
		$this->color = new cField('pc_issuance', 'pc_issuance', 'x_color', 'color', '`color`', '`color`', 200, -1, FALSE, '`color`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->color->Sortable = TRUE; // Allow sort
		$this->fields['color'] = &$this->color;

		// capacity
		$this->capacity = new cField('pc_issuance', 'pc_issuance', 'x_capacity', 'capacity', '`capacity`', '`capacity`', 200, -1, FALSE, '`capacity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->capacity->Sortable = TRUE; // Allow sort
		$this->fields['capacity'] = &$this->capacity;

		// quantity_in
		$this->quantity_in = new cField('pc_issuance', 'pc_issuance', 'x_quantity_in', 'quantity_in', '`quantity_in`', '`quantity_in`', 200, -1, FALSE, '`quantity_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_in->Sortable = TRUE; // Allow sort
		$this->fields['quantity_in'] = &$this->quantity_in;

		// quantity_out
		$this->quantity_out = new cField('pc_issuance', 'pc_issuance', 'x_quantity_out', 'quantity_out', '`quantity_out`', '`quantity_out`', 200, -1, FALSE, '`quantity_out`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->quantity_out->Sortable = TRUE; // Allow sort
		$this->fields['quantity_out'] = &$this->quantity_out;

		// total_quantity
		$this->total_quantity = new cField('pc_issuance', 'pc_issuance', 'x_total_quantity', 'total_quantity', '`total_quantity`', '`total_quantity`', 200, -1, FALSE, '`total_quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_quantity->Sortable = TRUE; // Allow sort
		$this->fields['total_quantity'] = &$this->total_quantity;

		// department
		$this->department = new cField('pc_issuance', 'pc_issuance', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->department->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->department->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['department'] = &$this->department;

		// designation
		$this->designation = new cField('pc_issuance', 'pc_issuance', 'x_designation', 'designation', '`designation`', '`designation`', 3, -1, FALSE, '`designation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->designation->Sortable = TRUE; // Allow sort
		$this->designation->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->designation->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['designation'] = &$this->designation;

		// assign_to
		$this->assign_to = new cField('pc_issuance', 'pc_issuance', 'x_assign_to', 'assign_to', '`assign_to`', '`assign_to`', 3, -1, FALSE, '`assign_to`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->assign_to->Sortable = TRUE; // Allow sort
		$this->assign_to->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->assign_to->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->assign_to->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assign_to'] = &$this->assign_to;

		// date_assign
		$this->date_assign = new cField('pc_issuance', 'pc_issuance', 'x_date_assign', 'date_assign', '`date_assign`', ew_CastDateFieldForLike('`date_assign`', 17, "DB"), 135, 17, FALSE, '`date_assign`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_assign->Sortable = TRUE; // Allow sort
		$this->date_assign->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['date_assign'] = &$this->date_assign;

		// assign_action
		$this->assign_action = new cField('pc_issuance', 'pc_issuance', 'x_assign_action', 'assign_action', '`assign_action`', '`assign_action`', 3, -1, FALSE, '`assign_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->assign_action->Sortable = TRUE; // Allow sort
		$this->assign_action->OptionCount = 2;
		$this->assign_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assign_action'] = &$this->assign_action;

		// assign_comment
		$this->assign_comment = new cField('pc_issuance', 'pc_issuance', 'x_assign_comment', 'assign_comment', '`assign_comment`', '`assign_comment`', 201, -1, FALSE, '`assign_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->assign_comment->Sortable = TRUE; // Allow sort
		$this->fields['assign_comment'] = &$this->assign_comment;

		// assign_by
		$this->assign_by = new cField('pc_issuance', 'pc_issuance', 'x_assign_by', 'assign_by', '`assign_by`', '`assign_by`', 3, -1, FALSE, '`assign_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->assign_by->Sortable = TRUE; // Allow sort
		$this->assign_by->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->assign_by->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['assign_by'] = &$this->assign_by;

		// statuse
		$this->statuse = new cField('pc_issuance', 'pc_issuance', 'x_statuse', 'statuse', '`statuse`', '`statuse`', 200, -1, FALSE, '`statuse`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->statuse->Sortable = TRUE; // Allow sort
		$this->statuse->FldSelectMultiple = TRUE; // Multiple select
		$this->fields['statuse'] = &$this->statuse;

		// date_retrieved
		$this->date_retrieved = new cField('pc_issuance', 'pc_issuance', 'x_date_retrieved', 'date_retrieved', '`date_retrieved`', ew_CastDateFieldForLike('`date_retrieved`', 17, "DB"), 135, 17, FALSE, '`date_retrieved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_retrieved->Sortable = TRUE; // Allow sort
		$this->date_retrieved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['date_retrieved'] = &$this->date_retrieved;

		// retriever_action
		$this->retriever_action = new cField('pc_issuance', 'pc_issuance', 'x_retriever_action', 'retriever_action', '`retriever_action`', '`retriever_action`', 3, -1, FALSE, '`retriever_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->retriever_action->Sortable = TRUE; // Allow sort
		$this->retriever_action->OptionCount = 2;
		$this->fields['retriever_action'] = &$this->retriever_action;

		// retriever_comment
		$this->retriever_comment = new cField('pc_issuance', 'pc_issuance', 'x_retriever_comment', 'retriever_comment', '`retriever_comment`', '`retriever_comment`', 201, -1, FALSE, '`retriever_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->retriever_comment->Sortable = TRUE; // Allow sort
		$this->fields['retriever_comment'] = &$this->retriever_comment;

		// retrieved_by
		$this->retrieved_by = new cField('pc_issuance', 'pc_issuance', 'x_retrieved_by', 'retrieved_by', '`retrieved_by`', '`retrieved_by`', 3, -1, FALSE, '`retrieved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->retrieved_by->Sortable = TRUE; // Allow sort
		$this->retrieved_by->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->retrieved_by->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->retrieved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['retrieved_by'] = &$this->retrieved_by;

		// staff_id
		$this->staff_id = new cField('pc_issuance', 'pc_issuance', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staff_id'] = &$this->staff_id;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pc_issuance`";
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
			return "pc_issuancelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "pc_issuanceview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "pc_issuanceedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "pc_issuanceadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "pc_issuancelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pc_issuanceview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pc_issuanceview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "pc_issuanceadd.php?" . $this->UrlParm($parm);
		else
			$url = "pc_issuanceadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("pc_issuanceedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("pc_issuanceadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pc_issuancedelete.php", $this->UrlParm());
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
		$this->issued_date->setDbValue($rs->fields('issued_date'));
		$this->reference_id->setDbValue($rs->fields('reference_id'));
		$this->material_name->setDbValue($rs->fields('material_name'));
		$this->asset_tag->setDbValue($rs->fields('asset_tag'));
		$this->make->setDbValue($rs->fields('make'));
		$this->ram->setDbValue($rs->fields('ram'));
		$this->hard_disk->setDbValue($rs->fields('hard_disk'));
		$this->color->setDbValue($rs->fields('color'));
		$this->capacity->setDbValue($rs->fields('capacity'));
		$this->quantity_in->setDbValue($rs->fields('quantity_in'));
		$this->quantity_out->setDbValue($rs->fields('quantity_out'));
		$this->total_quantity->setDbValue($rs->fields('total_quantity'));
		$this->department->setDbValue($rs->fields('department'));
		$this->designation->setDbValue($rs->fields('designation'));
		$this->assign_to->setDbValue($rs->fields('assign_to'));
		$this->date_assign->setDbValue($rs->fields('date_assign'));
		$this->assign_action->setDbValue($rs->fields('assign_action'));
		$this->assign_comment->setDbValue($rs->fields('assign_comment'));
		$this->assign_by->setDbValue($rs->fields('assign_by'));
		$this->statuse->setDbValue($rs->fields('statuse'));
		$this->date_retrieved->setDbValue($rs->fields('date_retrieved'));
		$this->retriever_action->setDbValue($rs->fields('retriever_action'));
		$this->retriever_comment->setDbValue($rs->fields('retriever_comment'));
		$this->retrieved_by->setDbValue($rs->fields('retrieved_by'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// issued_date
		// reference_id
		// material_name
		// asset_tag
		// make
		// ram
		// hard_disk
		// color
		// capacity
		// quantity_in
		// quantity_out
		// total_quantity
		// department
		// designation
		// assign_to
		// date_assign
		// assign_action
		// assign_comment
		// assign_by
		// statuse
		// date_retrieved
		// retriever_action
		// retriever_comment
		// retrieved_by
		// staff_id
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// issued_date
		$this->issued_date->ViewValue = $this->issued_date->CurrentValue;
		$this->issued_date->ViewValue = ew_FormatDateTime($this->issued_date->ViewValue, 17);
		$this->issued_date->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// material_name
		if (strval($this->material_name->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->material_name->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `material_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_inventory`";
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

		// asset_tag
		$this->asset_tag->ViewValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->ViewCustomAttributes = "";

		// make
		$this->make->ViewValue = $this->make->CurrentValue;
		$this->make->ViewCustomAttributes = "";

		// ram
		$this->ram->ViewValue = $this->ram->CurrentValue;
		$this->ram->ViewCustomAttributes = "";

		// hard_disk
		$this->hard_disk->ViewValue = $this->hard_disk->CurrentValue;
		$this->hard_disk->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// capacity
		$this->capacity->ViewValue = $this->capacity->CurrentValue;
		$this->capacity->ViewCustomAttributes = "";

		// quantity_in
		$this->quantity_in->ViewValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->ViewCustomAttributes = "";

		// quantity_out
		$this->quantity_out->ViewValue = $this->quantity_out->CurrentValue;
		$this->quantity_out->ViewCustomAttributes = "";

		// total_quantity
		$this->total_quantity->ViewValue = $this->total_quantity->CurrentValue;
		$this->total_quantity->ViewCustomAttributes = "";

		// department
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

		// designation
		if (strval($this->designation->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->designation->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `designation`";
		$sWhereWrk = "";
		$this->designation->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->designation, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
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

		// assign_to
		if (strval($this->assign_to->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_to->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_to->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_to, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->assign_to->ViewValue = $this->assign_to->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_to->ViewValue = $this->assign_to->CurrentValue;
			}
		} else {
			$this->assign_to->ViewValue = NULL;
		}
		$this->assign_to->ViewCustomAttributes = "";

		// date_assign
		$this->date_assign->ViewValue = $this->date_assign->CurrentValue;
		$this->date_assign->ViewValue = ew_FormatDateTime($this->date_assign->ViewValue, 17);
		$this->date_assign->ViewCustomAttributes = "";

		// assign_action
		if (strval($this->assign_action->CurrentValue) <> "") {
			$this->assign_action->ViewValue = $this->assign_action->OptionCaption($this->assign_action->CurrentValue);
		} else {
			$this->assign_action->ViewValue = NULL;
		}
		$this->assign_action->ViewCustomAttributes = "";

		// assign_comment
		$this->assign_comment->ViewValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->ViewCustomAttributes = "";

		// assign_by
		if (strval($this->assign_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->assign_by->ViewValue = $this->assign_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_by->ViewValue = $this->assign_by->CurrentValue;
			}
		} else {
			$this->assign_by->ViewValue = NULL;
		}
		$this->assign_by->ViewCustomAttributes = "";

		// statuse
		if (strval($this->statuse->CurrentValue) <> "") {
			$arwrk = explode(",", $this->statuse->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `system_status`";
		$sWhereWrk = "";
		$this->statuse->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->statuse, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->statuse->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->statuse->ViewValue .= $this->statuse->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->statuse->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->statuse->ViewValue = $this->statuse->CurrentValue;
			}
		} else {
			$this->statuse->ViewValue = NULL;
		}
		$this->statuse->ViewCustomAttributes = "";

		// date_retrieved
		$this->date_retrieved->ViewValue = $this->date_retrieved->CurrentValue;
		$this->date_retrieved->ViewValue = ew_FormatDateTime($this->date_retrieved->ViewValue, 17);
		$this->date_retrieved->ViewCustomAttributes = "";

		// retriever_action
		if (strval($this->retriever_action->CurrentValue) <> "") {
			$this->retriever_action->ViewValue = $this->retriever_action->OptionCaption($this->retriever_action->CurrentValue);
		} else {
			$this->retriever_action->ViewValue = NULL;
		}
		$this->retriever_action->ViewCustomAttributes = "";

		// retriever_comment
		$this->retriever_comment->ViewValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->ViewCustomAttributes = "";

		// retrieved_by
		if (strval($this->retrieved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->retrieved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->retrieved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->retrieved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->retrieved_by->ViewValue = $this->retrieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->retrieved_by->ViewValue = $this->retrieved_by->CurrentValue;
			}
		} else {
			$this->retrieved_by->ViewValue = NULL;
		}
		$this->retrieved_by->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
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

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// issued_date
		$this->issued_date->LinkCustomAttributes = "";
		$this->issued_date->HrefValue = "";
		$this->issued_date->TooltipValue = "";

		// reference_id
		$this->reference_id->LinkCustomAttributes = "";
		$this->reference_id->HrefValue = "";
		$this->reference_id->TooltipValue = "";

		// material_name
		$this->material_name->LinkCustomAttributes = "";
		$this->material_name->HrefValue = "";
		$this->material_name->TooltipValue = "";

		// asset_tag
		$this->asset_tag->LinkCustomAttributes = "";
		$this->asset_tag->HrefValue = "";
		$this->asset_tag->TooltipValue = "";

		// make
		$this->make->LinkCustomAttributes = "";
		$this->make->HrefValue = "";
		$this->make->TooltipValue = "";

		// ram
		$this->ram->LinkCustomAttributes = "";
		$this->ram->HrefValue = "";
		$this->ram->TooltipValue = "";

		// hard_disk
		$this->hard_disk->LinkCustomAttributes = "";
		$this->hard_disk->HrefValue = "";
		$this->hard_disk->TooltipValue = "";

		// color
		$this->color->LinkCustomAttributes = "";
		$this->color->HrefValue = "";
		$this->color->TooltipValue = "";

		// capacity
		$this->capacity->LinkCustomAttributes = "";
		$this->capacity->HrefValue = "";
		$this->capacity->TooltipValue = "";

		// quantity_in
		$this->quantity_in->LinkCustomAttributes = "";
		$this->quantity_in->HrefValue = "";
		$this->quantity_in->TooltipValue = "";

		// quantity_out
		$this->quantity_out->LinkCustomAttributes = "";
		$this->quantity_out->HrefValue = "";
		$this->quantity_out->TooltipValue = "";

		// total_quantity
		$this->total_quantity->LinkCustomAttributes = "";
		$this->total_quantity->HrefValue = "";
		$this->total_quantity->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// designation
		$this->designation->LinkCustomAttributes = "";
		$this->designation->HrefValue = "";
		$this->designation->TooltipValue = "";

		// assign_to
		$this->assign_to->LinkCustomAttributes = "";
		$this->assign_to->HrefValue = "";
		$this->assign_to->TooltipValue = "";

		// date_assign
		$this->date_assign->LinkCustomAttributes = "";
		$this->date_assign->HrefValue = "";
		$this->date_assign->TooltipValue = "";

		// assign_action
		$this->assign_action->LinkCustomAttributes = "";
		$this->assign_action->HrefValue = "";
		$this->assign_action->TooltipValue = "";

		// assign_comment
		$this->assign_comment->LinkCustomAttributes = "";
		$this->assign_comment->HrefValue = "";
		$this->assign_comment->TooltipValue = "";

		// assign_by
		$this->assign_by->LinkCustomAttributes = "";
		$this->assign_by->HrefValue = "";
		$this->assign_by->TooltipValue = "";

		// statuse
		$this->statuse->LinkCustomAttributes = "";
		$this->statuse->HrefValue = "";
		$this->statuse->TooltipValue = "";

		// date_retrieved
		$this->date_retrieved->LinkCustomAttributes = "";
		$this->date_retrieved->HrefValue = "";
		$this->date_retrieved->TooltipValue = "";

		// retriever_action
		$this->retriever_action->LinkCustomAttributes = "";
		$this->retriever_action->HrefValue = "";
		$this->retriever_action->TooltipValue = "";

		// retriever_comment
		$this->retriever_comment->LinkCustomAttributes = "";
		$this->retriever_comment->HrefValue = "";
		$this->retriever_comment->TooltipValue = "";

		// retrieved_by
		$this->retrieved_by->LinkCustomAttributes = "";
		$this->retrieved_by->HrefValue = "";
		$this->retrieved_by->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

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

		// issued_date
		$this->issued_date->EditAttrs["class"] = "form-control";
		$this->issued_date->EditCustomAttributes = "";
		$this->issued_date->EditValue = ew_FormatDateTime($this->issued_date->CurrentValue, 17);
		$this->issued_date->PlaceHolder = ew_RemoveHtml($this->issued_date->FldCaption());

		// reference_id
		$this->reference_id->EditAttrs["class"] = "form-control";
		$this->reference_id->EditCustomAttributes = "";
		$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

		// material_name
		$this->material_name->EditAttrs["class"] = "form-control";
		$this->material_name->EditCustomAttributes = "";

		// asset_tag
		$this->asset_tag->EditAttrs["class"] = "form-control";
		$this->asset_tag->EditCustomAttributes = "";
		$this->asset_tag->EditValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->PlaceHolder = ew_RemoveHtml($this->asset_tag->FldCaption());

		// make
		$this->make->EditAttrs["class"] = "form-control";
		$this->make->EditCustomAttributes = "";
		$this->make->EditValue = $this->make->CurrentValue;
		$this->make->PlaceHolder = ew_RemoveHtml($this->make->FldCaption());

		// ram
		$this->ram->EditAttrs["class"] = "form-control";
		$this->ram->EditCustomAttributes = "";
		$this->ram->EditValue = $this->ram->CurrentValue;
		$this->ram->PlaceHolder = ew_RemoveHtml($this->ram->FldCaption());

		// hard_disk
		$this->hard_disk->EditAttrs["class"] = "form-control";
		$this->hard_disk->EditCustomAttributes = "";
		$this->hard_disk->EditValue = $this->hard_disk->CurrentValue;
		$this->hard_disk->PlaceHolder = ew_RemoveHtml($this->hard_disk->FldCaption());

		// color
		$this->color->EditAttrs["class"] = "form-control";
		$this->color->EditCustomAttributes = "";
		$this->color->EditValue = $this->color->CurrentValue;
		$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

		// capacity
		$this->capacity->EditAttrs["class"] = "form-control";
		$this->capacity->EditCustomAttributes = "";
		$this->capacity->EditValue = $this->capacity->CurrentValue;
		$this->capacity->PlaceHolder = ew_RemoveHtml($this->capacity->FldCaption());

		// quantity_in
		$this->quantity_in->EditAttrs["class"] = "form-control";
		$this->quantity_in->EditCustomAttributes = "";
		$this->quantity_in->EditValue = $this->quantity_in->CurrentValue;
		$this->quantity_in->PlaceHolder = ew_RemoveHtml($this->quantity_in->FldCaption());

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

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";

		// designation
		$this->designation->EditAttrs["class"] = "form-control";
		$this->designation->EditCustomAttributes = "";

		// assign_to
		$this->assign_to->EditAttrs["class"] = "form-control";
		$this->assign_to->EditCustomAttributes = "";

		// date_assign
		$this->date_assign->EditAttrs["class"] = "form-control";
		$this->date_assign->EditCustomAttributes = "";
		$this->date_assign->EditValue = ew_FormatDateTime($this->date_assign->CurrentValue, 17);
		$this->date_assign->PlaceHolder = ew_RemoveHtml($this->date_assign->FldCaption());

		// assign_action
		$this->assign_action->EditCustomAttributes = "";
		$this->assign_action->EditValue = $this->assign_action->Options(FALSE);

		// assign_comment
		$this->assign_comment->EditAttrs["class"] = "form-control";
		$this->assign_comment->EditCustomAttributes = "";
		$this->assign_comment->EditValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->PlaceHolder = ew_RemoveHtml($this->assign_comment->FldCaption());

		// assign_by
		$this->assign_by->EditAttrs["class"] = "form-control";
		$this->assign_by->EditCustomAttributes = "";

		// statuse
		$this->statuse->EditAttrs["class"] = "form-control";
		$this->statuse->EditCustomAttributes = "";

		// date_retrieved
		$this->date_retrieved->EditAttrs["class"] = "form-control";
		$this->date_retrieved->EditCustomAttributes = "";
		$this->date_retrieved->EditValue = ew_FormatDateTime($this->date_retrieved->CurrentValue, 17);
		$this->date_retrieved->PlaceHolder = ew_RemoveHtml($this->date_retrieved->FldCaption());

		// retriever_action
		$this->retriever_action->EditCustomAttributes = "";
		$this->retriever_action->EditValue = $this->retriever_action->Options(FALSE);

		// retriever_comment
		$this->retriever_comment->EditAttrs["class"] = "form-control";
		$this->retriever_comment->EditCustomAttributes = "";
		$this->retriever_comment->EditValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->PlaceHolder = ew_RemoveHtml($this->retriever_comment->FldCaption());

		// retrieved_by
		$this->retrieved_by->EditAttrs["class"] = "form-control";
		$this->retrieved_by->EditCustomAttributes = "";

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

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
					if ($this->issued_date->Exportable) $Doc->ExportCaption($this->issued_date);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->asset_tag->Exportable) $Doc->ExportCaption($this->asset_tag);
					if ($this->make->Exportable) $Doc->ExportCaption($this->make);
					if ($this->ram->Exportable) $Doc->ExportCaption($this->ram);
					if ($this->hard_disk->Exportable) $Doc->ExportCaption($this->hard_disk);
					if ($this->color->Exportable) $Doc->ExportCaption($this->color);
					if ($this->capacity->Exportable) $Doc->ExportCaption($this->capacity);
					if ($this->quantity_in->Exportable) $Doc->ExportCaption($this->quantity_in);
					if ($this->quantity_out->Exportable) $Doc->ExportCaption($this->quantity_out);
					if ($this->total_quantity->Exportable) $Doc->ExportCaption($this->total_quantity);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->designation->Exportable) $Doc->ExportCaption($this->designation);
					if ($this->assign_to->Exportable) $Doc->ExportCaption($this->assign_to);
					if ($this->date_assign->Exportable) $Doc->ExportCaption($this->date_assign);
					if ($this->assign_action->Exportable) $Doc->ExportCaption($this->assign_action);
					if ($this->assign_comment->Exportable) $Doc->ExportCaption($this->assign_comment);
					if ($this->assign_by->Exportable) $Doc->ExportCaption($this->assign_by);
					if ($this->statuse->Exportable) $Doc->ExportCaption($this->statuse);
					if ($this->date_retrieved->Exportable) $Doc->ExportCaption($this->date_retrieved);
					if ($this->retriever_action->Exportable) $Doc->ExportCaption($this->retriever_action);
					if ($this->retriever_comment->Exportable) $Doc->ExportCaption($this->retriever_comment);
					if ($this->retrieved_by->Exportable) $Doc->ExportCaption($this->retrieved_by);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->issued_date->Exportable) $Doc->ExportCaption($this->issued_date);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->material_name->Exportable) $Doc->ExportCaption($this->material_name);
					if ($this->asset_tag->Exportable) $Doc->ExportCaption($this->asset_tag);
					if ($this->make->Exportable) $Doc->ExportCaption($this->make);
					if ($this->ram->Exportable) $Doc->ExportCaption($this->ram);
					if ($this->hard_disk->Exportable) $Doc->ExportCaption($this->hard_disk);
					if ($this->color->Exportable) $Doc->ExportCaption($this->color);
					if ($this->capacity->Exportable) $Doc->ExportCaption($this->capacity);
					if ($this->quantity_in->Exportable) $Doc->ExportCaption($this->quantity_in);
					if ($this->quantity_out->Exportable) $Doc->ExportCaption($this->quantity_out);
					if ($this->total_quantity->Exportable) $Doc->ExportCaption($this->total_quantity);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->designation->Exportable) $Doc->ExportCaption($this->designation);
					if ($this->assign_to->Exportable) $Doc->ExportCaption($this->assign_to);
					if ($this->date_assign->Exportable) $Doc->ExportCaption($this->date_assign);
					if ($this->assign_action->Exportable) $Doc->ExportCaption($this->assign_action);
					if ($this->assign_by->Exportable) $Doc->ExportCaption($this->assign_by);
					if ($this->statuse->Exportable) $Doc->ExportCaption($this->statuse);
					if ($this->date_retrieved->Exportable) $Doc->ExportCaption($this->date_retrieved);
					if ($this->retriever_action->Exportable) $Doc->ExportCaption($this->retriever_action);
					if ($this->retrieved_by->Exportable) $Doc->ExportCaption($this->retrieved_by);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
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
						if ($this->issued_date->Exportable) $Doc->ExportField($this->issued_date);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->asset_tag->Exportable) $Doc->ExportField($this->asset_tag);
						if ($this->make->Exportable) $Doc->ExportField($this->make);
						if ($this->ram->Exportable) $Doc->ExportField($this->ram);
						if ($this->hard_disk->Exportable) $Doc->ExportField($this->hard_disk);
						if ($this->color->Exportable) $Doc->ExportField($this->color);
						if ($this->capacity->Exportable) $Doc->ExportField($this->capacity);
						if ($this->quantity_in->Exportable) $Doc->ExportField($this->quantity_in);
						if ($this->quantity_out->Exportable) $Doc->ExportField($this->quantity_out);
						if ($this->total_quantity->Exportable) $Doc->ExportField($this->total_quantity);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->designation->Exportable) $Doc->ExportField($this->designation);
						if ($this->assign_to->Exportable) $Doc->ExportField($this->assign_to);
						if ($this->date_assign->Exportable) $Doc->ExportField($this->date_assign);
						if ($this->assign_action->Exportable) $Doc->ExportField($this->assign_action);
						if ($this->assign_comment->Exportable) $Doc->ExportField($this->assign_comment);
						if ($this->assign_by->Exportable) $Doc->ExportField($this->assign_by);
						if ($this->statuse->Exportable) $Doc->ExportField($this->statuse);
						if ($this->date_retrieved->Exportable) $Doc->ExportField($this->date_retrieved);
						if ($this->retriever_action->Exportable) $Doc->ExportField($this->retriever_action);
						if ($this->retriever_comment->Exportable) $Doc->ExportField($this->retriever_comment);
						if ($this->retrieved_by->Exportable) $Doc->ExportField($this->retrieved_by);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->issued_date->Exportable) $Doc->ExportField($this->issued_date);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->material_name->Exportable) $Doc->ExportField($this->material_name);
						if ($this->asset_tag->Exportable) $Doc->ExportField($this->asset_tag);
						if ($this->make->Exportable) $Doc->ExportField($this->make);
						if ($this->ram->Exportable) $Doc->ExportField($this->ram);
						if ($this->hard_disk->Exportable) $Doc->ExportField($this->hard_disk);
						if ($this->color->Exportable) $Doc->ExportField($this->color);
						if ($this->capacity->Exportable) $Doc->ExportField($this->capacity);
						if ($this->quantity_in->Exportable) $Doc->ExportField($this->quantity_in);
						if ($this->quantity_out->Exportable) $Doc->ExportField($this->quantity_out);
						if ($this->total_quantity->Exportable) $Doc->ExportField($this->total_quantity);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->designation->Exportable) $Doc->ExportField($this->designation);
						if ($this->assign_to->Exportable) $Doc->ExportField($this->assign_to);
						if ($this->date_assign->Exportable) $Doc->ExportField($this->date_assign);
						if ($this->assign_action->Exportable) $Doc->ExportField($this->assign_action);
						if ($this->assign_by->Exportable) $Doc->ExportField($this->assign_by);
						if ($this->statuse->Exportable) $Doc->ExportField($this->statuse);
						if ($this->date_retrieved->Exportable) $Doc->ExportField($this->date_retrieved);
						if ($this->retriever_action->Exportable) $Doc->ExportField($this->retriever_action);
						if ($this->retrieved_by->Exportable) $Doc->ExportField($this->retrieved_by);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
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
		$table = 'pc_issuance';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'pc_issuance';

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
		$table = 'pc_issuance';

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
		$table = 'pc_issuance';

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
		// Row_Inserted event
		//ew_Execute("UPDATE system_inventory SET quantity = quantity - {$qty} WHERE id = {$id}");

	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		date_default_timezone_set('Africa/Lagos');
		$now = new DateTime();
		$this->issued_date->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->issued_date->EditValue = $this->issued_date->CurrentValue;

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 1) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["staff_id"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; System Assigned to the Approperate Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Officer Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 2) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["staff_id"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; System Assigned to the Approperate Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 3 ) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["staff_id"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; System Assigned to Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 4 ) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["staff_id"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; System Assigned to Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}

			// Manager Only
		if (CurrentPageID() == "add" && CurrentUserLevel() == 6 ) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["staff_id"] = $_SESSION['Staff_ID'];
				$this->setSuccessMessage("&#x25C9; System Assigned to Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}			
		}
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		ew_Execute("UPDATE `system_inventory` SET `quantity`= (`quantity` - " . $this->quantity_out->CurrentValue . ") WHERE `id`= ".$this->material_name->CurrentValue."");
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		date_default_timezone_set('Africa/Lagos');
		$now = new DateTime();
		$this->date_issued->CurrentValue = $now->Format('Y-m-d H:i:s');
		$this->date_issued->EditValue = $this->date_issued->CurrentValue;
		$rsnew["retrieved_by"] = $_SESSION['Staff_ID'];
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 5) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 6) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
		}	

		// Officer Only
		if (CurrentPageID() == "edit" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2 || CurrentUserLevel() == 3 || CurrentUserLevel() == 4 || CurrentUserLevel() == 6)) {

				//if (CurrentPageID() == "edit" && CurrentUserLevel() == 1) {
			// Save and forward

			if ($this->assign_action->CurrentValue == 1 && $this->statuse->CurrentValue == 0) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["retriever_action"] = NULL;
				$rsnew["retriever_comment"] = NULL;

				//$this->setSuccessMessage("&#x25C9; System Issued to Assign Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}
		 if (CurrentPageID() == "edit" && CurrentUserLevel() == 3) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1 && $this->statuse->CurrentValue == 0) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["retriever_action"] = NULL;
				$rsnew["retriever_comment"] = NULL;

				//$this->setSuccessMessage("&#x25C9; System Assign to Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0 ) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 6) {

			// Save and forward
			if ($this->assign_action->CurrentValue == 1 && $this->statuse->CurrentValue == 0) {
				$rsnew["statuse"] = 1;
				$rsnew["assign_action"] = 1;
				$rsnew["retriever_action"] = NULL;
				$rsnew["retriever_comment"] = NULL;

				//$this->setSuccessMessage("&#x25C9; System Assigned to Personnel &#x2714;"); 					
			}

			// Saved only
			if ($this->assign_action->CurrentValue == 0 ) {
				$rsnew["statuse"] = 0;			
				$rsnew["assign_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Record has been saved &#x2714;");
			}
		}

		// Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && ($this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["date_retrieved"] = $now->format('Y-m-d H:i:s');
			$rsnew["retrieved_by"] = $_SESSION['Staff_ID'];
		}

		// Administartor - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && ($this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["issue_date"] = $rsold["date_recieved"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["asset_tag"] = $rsold["asset_tag"];
			$rsnew["make"] = $rsold["make"];
			$rsnew["color"] = $rsold["color"];
			$rsnew["assign_to"] = $rsold["assign_to"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["designation"] = $rsold["designation"];
			$rsnew["ram"] = $rsold["ram"];
			$rsnew["hard_disk"] = $rsold["hard_disk"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["assign_action"] = $rsold["recieved_action"];
			$rsnew["assign_comment"] = $rsold["assign_comment"];

			//$rsnew["reviewed_action"] = $rsold["reviewed_action"];
			//$rsnew["reviewed_comment"] = $rsold["reviewed_comment"];

		}

		// ICT PERSONNEL
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 6) && ($this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["date_retrieved"] = $now->format('Y-m-d H:i:s');
			$rsnew["retrieved_by"] = $_SESSION['Staff_ID'];
		}

		// ICT PERSONNEL - Don't change field values captured by tenant
		if ((CurrentPageID() == "edit" && CurrentUserLevel() == 6) && ($this->staff_id->CurrentValue == $_SESSION['Staff_ID'] || $this->staff_id->CurrentValue != $_SESSION['Staff_ID'])) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["issue_date"] = $rsold["date_recieved"];
			$rsnew["reference_id"] = $rsold["reference_id"];
			$rsnew["asset_tag"] = $rsold["asset_tag"];
			$rsnew["make"] = $rsold["make"];
			$rsnew["color"] = $rsold["color"];
			$rsnew["assign_to"] = $rsold["assign_to"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["designation"] = $rsold["designation"];
			$rsnew["ram"] = $rsold["ram"];
			$rsnew["hard_disk"] = $rsold["hard_disk"];

			//$rsnew["assign_action"] = $rsold["assign_action"];
			//$rsnew["assign_comment"] = $rsold["assign_comment"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["assign_action"] = $rsold["assign_action"];
			$rsnew["assign_comment"] = $rsold["assign_comment"];

			//$rsnew["reviewed_action"] = $rsold["reviewed_action"];
			//$rsnew["reviewed_comment"] = $rsold["reviewed_comment"];

		}

			// Confirmed by Administrators
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
				$rsnew["date_retrieved"] = $now->format('Y-m-d H:i:s');
				$rsnew["retrieved_by"] = $_SESSION['Staff_ID'];
			  }

			  // Confirmed by Administrators
				if ($this->reviewed_action->CurrentValue == 0 && $this->statuse->CurrentValue == 1 ) {

					// New
					if ($this->statuse->CurrentValue == 1 && CurrentUserLevel() == 3) {
						$rsnew["statuse"] = 1;					
						$rsnew["reviewed_action"] = 0;
					}

					//$this->setSuccessMessage("&#x25C9; Save Only &#x2714;");
				}

				// Confirmed by Administrators
				if ($this->retriever_action->CurrentValue == 3 && CurrentUserLevel() == 3) {

					// New
					if ($this->statuse->CurrentValue == 1) {
						$rsnew["statuse"] = 2;					
						$rsnew["retriever_action"] = 3;
					}
					$this->setSuccessMessage("&#x25C9; System was Successfully Retrieved &#x2714;");
				}

			// Confirmed by Administrators====================================================================================================
			if ((CurrentPageID() == "edit" && CurrentUserLevel() == 6) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
				$rsnew["date_retrieved"] = $now->format('Y-m-d H:i:s');
				$rsnew["retrieved_by"] = $_SESSION['Staff_ID'];
			  }

			  // Confirmed by Administrators
				if ($this->reviewed_action->CurrentValue == 0 && $this->statuse->CurrentValue == 1 ) {

					// New
					if ($this->statuse->CurrentValue == 1 && CurrentUserLevel() == 6) {
						$rsnew["statuse"] = 1;					
						$rsnew["reviewed_action"] = 0;
					}

					//$this->setSuccessMessage("&#x25C9; Save Only &#x2714;");
				}

				// Confirmed by Administrators
				if ($this->retriever_action->CurrentValue == 3) {

					// New
					if ($this->statuse->CurrentValue == 1 && CurrentUserLevel() == 6) {
						$rsnew["statuse"] = 2;					
						$rsnew["retriever_action"] = 3;
					}
					$this->setSuccessMessage("&#x25C9; System was Successfully Retrieved &#x2714;");
				}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		  if (CurrentPageID() == "edit" && (CurrentUserLevel() == 6 && $rsnew["statuse"] == 1 )) {
		   ew_Execute("UPDATE `system_inventory` SET `quantity`= (`quantity` - " . $this->quantity_out->CurrentValue . ") WHERE `id`= ".$this->material_name->CurrentValue."");

		   	  // ew_Execute("UPDATE `system_inventory` SET `quantity`= $this->total_quantity->CurrentValue  WHERE `id`= ".$this->items_name->CurrentValue."");
		}
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
			$this->issued_date->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->issued_date->EditValue = $this->issued_date->CurrentValue;
			$this->date_assign->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_assign->EditValue = $this->date_assign->CurrentValue;
		}
		if (CurrentPageID() == "add" && (CurrentUserLevel() == 1 || CurrentUserLevel() == 2 || CurrentUserLevel()   == 3 || CurrentUserLevel() == 4 || CurrentUserLevel() == 6)) {
			$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			$this->staff_id->EditValue = $this->staff_id->CurrentValue;
			$this->assign_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->assign_by->EditValue = $this->assign_by->CurrentValue;
		}
		if (CurrentPageID() == "add")  {
			$this->reference_id->CurrentValue = $_SESSION['SYS_ID'];
			$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		}

			//if (CurrentPageID() == "edit" && CurrentUserLevel() == 3 ) {
		if (CurrentPageID() == "add" && (CurrentUserLevel() == 2 || CurrentUserLevel() == 3 || CurrentUserLevel()   == 4 || CurrentUserLevel() == 5 || CurrentUserLevel() == 6)) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_retrieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_retrieved->EditValue = $this->date_retrieved->CurrentValue;

			//$this->staff_id->CurrentValue = $_SESSION['Staff_ID'];
			//$this->staff_id->EditValue = $this->staff_id->CurrentValue;

			$this->retrieved_by->CurrentValue = $_SESSION['Staff_ID'];
			$this->retrieved_by->EditValue = $this->retrieved_by->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3  ) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_retrieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_retrieved->EditValue = $this->date_retrieved->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 2 && $this->statuse->CurrentValue == 1) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_retrieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_retrieved->EditValue = $this->date_retrieved->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 1 && $this->statuse->CurrentValue == 1) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_retrieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_retrieved->EditValue = $this->date_retrieved->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3 && $this->statuse->CurrentValue == 1) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_retrieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_retrieved->EditValue = $this->date_retrieved->CurrentValue;
		}
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 6 && $this->statuse->CurrentValue == 1) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_retrieved->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_retrieved->EditValue = $this->date_retrieved->CurrentValue;
		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

			if (CurrentPageID() == "add") {
				if (CurrentUserLevel() == 1) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->Visible = TRUE;
					$this->total_quantity->ReadOnly = TRUE;
					$this->date_retrieved->Visible = FALSE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;
					$this->retrieved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 2) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->Visible = TRUE;
					$this->total_quantity->ReadOnly = TRUE;				
					$this->date_retrieved->Visible = FALSE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;
					$this->retrieved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 3) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->Visible = TRUE;
					$this->total_quantity->ReadOnly = TRUE;					
					$this->date_retrieved->Visible = FALSE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;
					$this->retrieved_by->Visible = FALSE;	
				}
				if (CurrentUserLevel() == 6) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->Visible = TRUE;
					$this->total_quantity->ReadOnly = TRUE;					
					$this->date_retrieved->Visible = FALSE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;
					$this->retrieved_by->Visible = FALSE;	
				}
		   }

		   // Edit Page
			if (CurrentPageID() == "edit") {
				if ((CurrentUserLevel() == 1||CurrentUserLevel() == 2 && $this->statuse->CurrentValue == 0)) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->asset_tag->ReadOnly = TRUE;
					$this->make->ReadOnly = TRUE;
					$this->color->ReadOnly = TRUE;
					$this->ram->ReadOnly = TRUE;
					$this->hard_disk->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->designation->ReadOnly = TRUE;
					$this->assign_to->ReadOnly = TRUE;
					$this->date_assign->ReadOnly = TRUE;
					$this->assign_action->Visible = TRUE;
					$this->assign_comment->Visible = TRUE;
					$this->assign_by->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->Visible = TRUE;
					$this->total_quantity->ReadOnly = TRUE;	

					//$this->staff_id->ReadOnly = TRUE;
					$this->date_retrieved->Visible = FALSE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;

					//$this->retrieved_by->Visible = FALSE;
				}
				if ((CurrentUserLevel() == 1||CurrentUserLevel() == 2 && $this->statuse->CurrentValue == 1)) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->asset_tag->ReadOnly = TRUE;
					$this->make->ReadOnly = TRUE;
					$this->color->ReadOnly = TRUE;
					$this->ram->ReadOnly = TRUE;
					$this->hard_disk->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->designation->ReadOnly = TRUE;
					$this->assign_to->ReadOnly = TRUE;
					$this->date_assign->ReadOnly = TRUE;
					$this->assign_action->ReadOnly = TRUE;
					$this->assign_comment->ReadOnly = TRUE;
					$this->assign_by->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;	
					$this->date_retrieved->ReadOnly = FALSE;
					$this->retriever_action->Visible = TRUE;
					$this->retriever_comment->Visible = TRUE;

					//$this->retrieved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 3 && $this->statuse->CurrentValue == 0 || $this->statuse->CurrentValue == 1) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->asset_tag->ReadOnly = TRUE;
					$this->make->ReadOnly = TRUE;
					$this->color->ReadOnly = TRUE;
					$this->ram->ReadOnly = TRUE;
					$this->hard_disk->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->designation->ReadOnly = TRUE;
					$this->assign_to->ReadOnly = TRUE;
					$this->date_assign->ReadOnly = TRUE;
					$this->assign_action->Visible = TRUE;
					$this->assign_comment->Visible = TRUE;
					$this->assign_by->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;	
					$this->date_retrieved->ReadOnly = FALSE;
					$this->retriever_action->Visible = TRUE;
					$this->retriever_comment->Visible = TRUE;

					//$this->retrieved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 5 && $this->statuse->CurrentValue == 0) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->asset_tag->ReadOnly = TRUE;
					$this->make->ReadOnly = TRUE;
					$this->color->ReadOnly = TRUE;
					$this->ram->ReadOnly = TRUE;
					$this->hard_disk->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->designation->ReadOnly = TRUE;
					$this->assign_to->ReadOnly = TRUE;
					$this->date_assign->ReadOnly = TRUE;
					$this->assign_action->Visible = TRUE;
					$this->assign_comment->Visible = TRUE;
					$this->assign_by->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;	

					//$this->staff_id->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = FALSE;
					$this->retriever_action->Visible = TRUE;
					$this->retriever_comment->Visible = TRUE;

					//$this->retrieved_by->Visible = FALSE;
				}
				if (CurrentUserLevel() == 6 && $this->statuse->CurrentValue == 0) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->asset_tag->ReadOnly = TRUE;
					$this->make->ReadOnly = TRUE;
					$this->color->ReadOnly = TRUE;
					$this->ram->ReadOnly = TRUE;
					$this->hard_disk->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->designation->ReadOnly = TRUE;
					$this->assign_to->ReadOnly = TRUE;
					$this->date_assign->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;	

					//$this->assign_action->Visible = TRUE;
					//$this->assign_comment->Visible = TRUE;

					$this->assign_by->ReadOnly = TRUE;

					//$this->staff_id->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;
				}
					if (CurrentUserLevel() == 6 && $this->statuse->CurrentValue == 1) {
					$this->issued_date->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->reference_id->ReadOnly = TRUE;
					$this->asset_tag->ReadOnly = TRUE;
					$this->make->ReadOnly = TRUE;
					$this->color->ReadOnly = TRUE;
					$this->ram->ReadOnly = TRUE;
					$this->hard_disk->ReadOnly = TRUE;
					$this->department->ReadOnly = TRUE;
					$this->designation->ReadOnly = TRUE;
					$this->assign_to->ReadOnly = TRUE;
					$this->date_assign->ReadOnly = TRUE;
					$this->assign_action->Visible = TRUE;
					$this->assign_comment->Visible = TRUE;
					$this->assign_by->ReadOnly = TRUE;
					$this->capacity->ReadOnly = TRUE;
					$this->quantity_in->ReadOnly = TRUE;
					$this->quantity_out->ReadOnly = TRUE;
					$this->total_quantity->ReadOnly = TRUE;	

					//$this->staff_id->ReadOnly = TRUE;
					$this->date_retrieved->ReadOnly = TRUE;
					$this->retriever_action->Visible = FALSE;
					$this->retriever_comment->Visible = FALSE;
					$this->date_retrieved->ReadOnly = FALSE;
					$this->retriever_action->Visible = TRUE;
					$this->retriever_comment->Visible = TRUE;
				}
			}

			// Highligh rows in color based on the status
		if (CurrentPageID() == "list") {

			//$this->branch_code->Visible = FALSE;
			if ($this->statuse->CurrentValue == 12) {
				$this->id->CellCssStyle = "color: orange; text-align: left;";
				$this->issued_date->CellCssStyle = "color: orange; text-align: left;";
				$this->date_retrieved->CellCssStyle = "color: orange; text-align: left;";
				$this->reference_id->CellCssStyle = "color: orange; text-align: left;";
				$this->asset_tag->CellCssStyle = "color: orange; text-align: left;";
				$this->make->CellCssStyle = "color: orange; text-align: left;";
				$this->color->CellCssStyle = "color: orange; text-align: left;";
				$this->department->CellCssStyle = "color: orange; text-align: left;";
				$this->designation->CellCssStyle = "color: orange; text-align: left;";
				$this->assign_to->CellCssStyle = "color: orange; text-align: left;";
				$this->statuse->CellCssStyle = "color: orange; text-align: left;";
				$this->date_assign->CellCssStyle = "color: orange; text-align: left;";
				$this->ram->CellCssStyle = "color: orange; text-align: left;";
				$this->hard_disk->CellCssStyle = "color: orange; text-align: left;";
				$this->assign_by->CellCssStyle = "color: orange; text-align: left;";
				$this->retrieved_by->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: orange; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: orange; text-align: left;";
				$this->total_quantity->CellCssStyle = "color: orange; text-align: left;";
				$this->capacity->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->statuse->CurrentValue == 0) {
				$this->id->CellCssStyle = "color: red; text-align: left;";
				$this->date_retrieved->CellCssStyle = "color: red; text-align: left;";
				$this->issued_date->CellCssStyle = "color: red; text-align: left;";
				$this->reference_id->CellCssStyle = "color: red; text-align: left;";
				$this->asset_tag->CellCssStyle = "color: red; text-align: left;";
				$this->make->CellCssStyle = "color: red; text-align: left;";
				$this->color->CellCssStyle = "color: red; text-align: left;";
				$this->department->CellCssStyle = "color: red; text-align: left;";
				$this->designation->CellCssStyle = "color: red; text-align: left;";
				$this->assign_to->CellCssStyle = "color: red; text-align: left;";
				$this->statuse->CellCssStyle = "color: red; text-align: left;";
				$this->date_assign->CellCssStyle = "color: red; text-align: left;";
				$this->ram->CellCssStyle = "color: red; text-align: left;";
				$this->hard_disk->CellCssStyle = "color: red; text-align: left;";
				$this->assign_by->CellCssStyle = "color: red; text-align: left;";
				$this->retrieved_by->CellCssStyle = "color: red; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: red; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: red; text-align: left;";
				$this->total_quantity->CellCssStyle = "color: red; text-align: left;";
				$this->capacity->CellCssStyle = "color: red; text-align: left;";
			}
			if ($this->statuse->CurrentValue == 2) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->date_retrieved->CellCssStyle = "color: blue; text-align: left;";
				$this->issued_date->CellCssStyle = "color: blue; text-align: left;";
				$this->reference_id->CellCssStyle = "color: blue; text-align: left;";
				$this->asset_tag->CellCssStyle = "color: blue; text-align: left;";
				$this->make->CellCssStyle = "color: blue; text-align: left;";
				$this->color->CellCssStyle = "color: blue; text-align: left;";
				$this->department->CellCssStyle = "color: blue; text-align: left;";
				$this->designation->CellCssStyle = "color: blue; text-align: left;";
				$this->assign_to->CellCssStyle = "color: blue; text-align: left;";
				$this->statuse->CellCssStyle = "color: blue; text-align: left;";
				$this->date_assign->CellCssStyle = "color: blue; text-align: left;";
				$this->ram->CellCssStyle = "color: blue; text-align: left;";
				$this->hard_disk->CellCssStyle = "color: blue; text-align: left;";
				$this->assign_by->CellCssStyle = "color: blue; text-align: left;";
				$this->retrieved_by->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: blue; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: blue; text-align: left;";
				$this->total_quantity->CellCssStyle = "color: blue; text-align: left;";
				$this->capacity->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->statuse->CurrentValue == 1) {
				$this->id->CellCssStyle = "color: green; text-align: left;";
				$this->date_retrieved->CellCssStyle = "color: green; text-align: left;";
				$this->issued_date->CellCssStyle = "color: green; text-align: left;";
				$this->reference_id->CellCssStyle = "color: green; text-align: left;";
				$this->asset_tag->CellCssStyle = "color: green; text-align: left;";
				$this->make->CellCssStyle = "color: green; text-align: left;";
				$this->color->CellCssStyle = "color: green; text-align: left;";
				$this->department->CellCssStyle = "color: green; text-align: left;";
				$this->designation->CellCssStyle = "color: green; text-align: left;";
				$this->assign_to->CellCssStyle = "color: green; text-align: left;";
				$this->statuse->CellCssStyle = "color: green; text-align: left;";
				$this->date_assign->CellCssStyle = "color: green; text-align: left;";
				$this->ram->CellCssStyle = "color: green; text-align: left;";
				$this->hard_disk->CellCssStyle = "color: green; text-align: left;";
				$this->assign_by->CellCssStyle = "color: green; text-align: left;";
				$this->retrieved_by->CellCssStyle = "color: green; text-align: left;";
				$this->quantity_in->CellCssStyle = "color: green; text-align: left;";
				$this->quantity_out->CellCssStyle = "color: green; text-align: left;";
				$this->total_quantity->CellCssStyle = "color: green; text-align: left;";
				$this->capacity->CellCssStyle = "color: green; text-align: left;";
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
