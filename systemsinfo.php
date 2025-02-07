<?php

// Global variable for table object
$systems = NULL;

//
// Table class for systems
//
class csystems extends cTable {
	var $id;
	var $asset_tag;
	var $start_sate;
	var $end_date;
	var $cost_for_repair;
	var $service_provider;
	var $address;
	var $type_of_repair;
	var $note;
	var $status;
	var $asset_category;
	var $asset_sub_category;
	var $serial_number;
	var $programe_area;
	var $division;
	var $branch;
	var $department;
	var $staff_id;
	var $created_by;
	var $created_date;
	var $device_number;
	var $tablet_imie_number;
	var $model;
	var $flag;
	var $area;
	var $updated_date;
	var $updated_by;
	var $received_date;
	var $received_by;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'systems';
		$this->TableName = 'systems';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`systems`";
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
		$this->id = new cField('systems', 'systems', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// asset_tag
		$this->asset_tag = new cField('systems', 'systems', 'x_asset_tag', 'asset_tag', '`asset_tag`', '`asset_tag`', 200, -1, FALSE, '`asset_tag`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->asset_tag->Sortable = TRUE; // Allow sort
		$this->fields['asset_tag'] = &$this->asset_tag;

		// start_sate
		$this->start_sate = new cField('systems', 'systems', 'x_start_sate', 'start_sate', '`start_sate`', ew_CastDateFieldForLike('`start_sate`', 0, "DB"), 135, 0, FALSE, '`start_sate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->start_sate->Sortable = TRUE; // Allow sort
		$this->start_sate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['start_sate'] = &$this->start_sate;

		// end_date
		$this->end_date = new cField('systems', 'systems', 'x_end_date', 'end_date', '`end_date`', ew_CastDateFieldForLike('`end_date`', 0, "DB"), 135, 0, FALSE, '`end_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->end_date->Sortable = TRUE; // Allow sort
		$this->end_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['end_date'] = &$this->end_date;

		// cost_for_repair
		$this->cost_for_repair = new cField('systems', 'systems', 'x_cost_for_repair', 'cost_for_repair', '`cost_for_repair`', '`cost_for_repair`', 3, -1, FALSE, '`cost_for_repair`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->cost_for_repair->Sortable = TRUE; // Allow sort
		$this->cost_for_repair->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['cost_for_repair'] = &$this->cost_for_repair;

		// service_provider
		$this->service_provider = new cField('systems', 'systems', 'x_service_provider', 'service_provider', '`service_provider`', '`service_provider`', 200, -1, FALSE, '`service_provider`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->service_provider->Sortable = TRUE; // Allow sort
		$this->fields['service_provider'] = &$this->service_provider;

		// address
		$this->address = new cField('systems', 'systems', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// type_of_repair
		$this->type_of_repair = new cField('systems', 'systems', 'x_type_of_repair', 'type_of_repair', '`type_of_repair`', '`type_of_repair`', 200, -1, FALSE, '`type_of_repair`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->type_of_repair->Sortable = TRUE; // Allow sort
		$this->fields['type_of_repair'] = &$this->type_of_repair;

		// note
		$this->note = new cField('systems', 'systems', 'x_note', 'note', '`note`', '`note`', 200, -1, FALSE, '`note`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->note->Sortable = TRUE; // Allow sort
		$this->fields['note'] = &$this->note;

		// status
		$this->status = new cField('systems', 'systems', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// asset_category
		$this->asset_category = new cField('systems', 'systems', 'x_asset_category', 'asset_category', '`asset_category`', '`asset_category`', 200, -1, FALSE, '`asset_category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->asset_category->Sortable = TRUE; // Allow sort
		$this->fields['asset_category'] = &$this->asset_category;

		// asset_sub_category
		$this->asset_sub_category = new cField('systems', 'systems', 'x_asset_sub_category', 'asset_sub_category', '`asset_sub_category`', '`asset_sub_category`', 200, -1, FALSE, '`asset_sub_category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->asset_sub_category->Sortable = TRUE; // Allow sort
		$this->fields['asset_sub_category'] = &$this->asset_sub_category;

		// serial_number
		$this->serial_number = new cField('systems', 'systems', 'x_serial_number', 'serial_number', '`serial_number`', '`serial_number`', 200, -1, FALSE, '`serial_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->serial_number->Sortable = TRUE; // Allow sort
		$this->fields['serial_number'] = &$this->serial_number;

		// programe_area
		$this->programe_area = new cField('systems', 'systems', 'x_programe_area', 'programe_area', '`programe_area`', '`programe_area`', 200, -1, FALSE, '`programe_area`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->programe_area->Sortable = TRUE; // Allow sort
		$this->fields['programe_area'] = &$this->programe_area;

		// division
		$this->division = new cField('systems', 'systems', 'x_division', 'division', '`division`', '`division`', 200, -1, FALSE, '`division`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->division->Sortable = TRUE; // Allow sort
		$this->fields['division'] = &$this->division;

		// branch
		$this->branch = new cField('systems', 'systems', 'x_branch', 'branch', '`branch`', '`branch`', 200, -1, FALSE, '`branch`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->branch->Sortable = TRUE; // Allow sort
		$this->fields['branch'] = &$this->branch;

		// department
		$this->department = new cField('systems', 'systems', 'x_department', 'department', '`department`', '`department`', 200, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->fields['department'] = &$this->department;

		// staff_id
		$this->staff_id = new cField('systems', 'systems', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 200, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->fields['staff_id'] = &$this->staff_id;

		// created_by
		$this->created_by = new cField('systems', 'systems', 'x_created_by', 'created_by', '`created_by`', '`created_by`', 200, -1, FALSE, '`created_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->created_by->Sortable = TRUE; // Allow sort
		$this->fields['created_by'] = &$this->created_by;

		// created_date
		$this->created_date = new cField('systems', 'systems', 'x_created_date', 'created_date', '`created_date`', ew_CastDateFieldForLike('`created_date`', 0, "DB"), 135, 0, FALSE, '`created_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->created_date->Sortable = TRUE; // Allow sort
		$this->created_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['created_date'] = &$this->created_date;

		// device_number
		$this->device_number = new cField('systems', 'systems', 'x_device_number', 'device_number', '`device_number`', '`device_number`', 200, -1, FALSE, '`device_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->device_number->Sortable = TRUE; // Allow sort
		$this->fields['device_number'] = &$this->device_number;

		// tablet_imie_number
		$this->tablet_imie_number = new cField('systems', 'systems', 'x_tablet_imie_number', 'tablet_imie_number', '`tablet_imie_number`', '`tablet_imie_number`', 200, -1, FALSE, '`tablet_imie_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tablet_imie_number->Sortable = TRUE; // Allow sort
		$this->fields['tablet_imie_number'] = &$this->tablet_imie_number;

		// model
		$this->model = new cField('systems', 'systems', 'x_model', 'model', '`model`', '`model`', 200, -1, FALSE, '`model`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->model->Sortable = TRUE; // Allow sort
		$this->fields['model'] = &$this->model;

		// flag
		$this->flag = new cField('systems', 'systems', 'x_flag', 'flag', '`flag`', '`flag`', 16, -1, FALSE, '`flag`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->flag->Sortable = TRUE; // Allow sort
		$this->flag->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['flag'] = &$this->flag;

		// area
		$this->area = new cField('systems', 'systems', 'x_area', 'area', '`area`', '`area`', 200, -1, FALSE, '`area`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->area->Sortable = TRUE; // Allow sort
		$this->fields['area'] = &$this->area;

		// updated_date
		$this->updated_date = new cField('systems', 'systems', 'x_updated_date', 'updated_date', '`updated_date`', ew_CastDateFieldForLike('`updated_date`', 0, "DB"), 135, 0, FALSE, '`updated_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->updated_date->Sortable = TRUE; // Allow sort
		$this->updated_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['updated_date'] = &$this->updated_date;

		// updated_by
		$this->updated_by = new cField('systems', 'systems', 'x_updated_by', 'updated_by', '`updated_by`', '`updated_by`', 3, -1, FALSE, '`updated_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->updated_by->Sortable = TRUE; // Allow sort
		$this->updated_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['updated_by'] = &$this->updated_by;

		// received_date
		$this->received_date = new cField('systems', 'systems', 'x_received_date', 'received_date', '`received_date`', ew_CastDateFieldForLike('`received_date`', 0, "DB"), 135, 0, FALSE, '`received_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->received_date->Sortable = TRUE; // Allow sort
		$this->received_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['received_date'] = &$this->received_date;

		// received_by
		$this->received_by = new cField('systems', 'systems', 'x_received_by', 'received_by', '`received_by`', '`received_by`', 3, -1, FALSE, '`received_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->received_by->Sortable = TRUE; // Allow sort
		$this->received_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['received_by'] = &$this->received_by;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`systems`";
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
			return "systemslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "systemsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "systemsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "systemsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "systemslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("systemsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("systemsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "systemsadd.php?" . $this->UrlParm($parm);
		else
			$url = "systemsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("systemsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("systemsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("systemsdelete.php", $this->UrlParm());
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
		$this->asset_tag->setDbValue($rs->fields('asset_tag'));
		$this->start_sate->setDbValue($rs->fields('start_sate'));
		$this->end_date->setDbValue($rs->fields('end_date'));
		$this->cost_for_repair->setDbValue($rs->fields('cost_for_repair'));
		$this->service_provider->setDbValue($rs->fields('service_provider'));
		$this->address->setDbValue($rs->fields('address'));
		$this->type_of_repair->setDbValue($rs->fields('type_of_repair'));
		$this->note->setDbValue($rs->fields('note'));
		$this->status->setDbValue($rs->fields('status'));
		$this->asset_category->setDbValue($rs->fields('asset_category'));
		$this->asset_sub_category->setDbValue($rs->fields('asset_sub_category'));
		$this->serial_number->setDbValue($rs->fields('serial_number'));
		$this->programe_area->setDbValue($rs->fields('programe_area'));
		$this->division->setDbValue($rs->fields('division'));
		$this->branch->setDbValue($rs->fields('branch'));
		$this->department->setDbValue($rs->fields('department'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->created_by->setDbValue($rs->fields('created_by'));
		$this->created_date->setDbValue($rs->fields('created_date'));
		$this->device_number->setDbValue($rs->fields('device_number'));
		$this->tablet_imie_number->setDbValue($rs->fields('tablet_imie_number'));
		$this->model->setDbValue($rs->fields('model'));
		$this->flag->setDbValue($rs->fields('flag'));
		$this->area->setDbValue($rs->fields('area'));
		$this->updated_date->setDbValue($rs->fields('updated_date'));
		$this->updated_by->setDbValue($rs->fields('updated_by'));
		$this->received_date->setDbValue($rs->fields('received_date'));
		$this->received_by->setDbValue($rs->fields('received_by'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// asset_tag
		// start_sate
		// end_date
		// cost_for_repair
		// service_provider
		// address
		// type_of_repair
		// note
		// status
		// asset_category
		// asset_sub_category
		// serial_number
		// programe_area
		// division
		// branch
		// department
		// staff_id
		// created_by
		// created_date
		// device_number
		// tablet_imie_number
		// model
		// flag
		// area
		// updated_date
		// updated_by
		// received_date
		// received_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// asset_tag
		$this->asset_tag->ViewValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->ViewCustomAttributes = "";

		// start_sate
		$this->start_sate->ViewValue = $this->start_sate->CurrentValue;
		$this->start_sate->ViewValue = ew_FormatDateTime($this->start_sate->ViewValue, 0);
		$this->start_sate->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 0);
		$this->end_date->ViewCustomAttributes = "";

		// cost_for_repair
		$this->cost_for_repair->ViewValue = $this->cost_for_repair->CurrentValue;
		$this->cost_for_repair->ViewCustomAttributes = "";

		// service_provider
		$this->service_provider->ViewValue = $this->service_provider->CurrentValue;
		$this->service_provider->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// type_of_repair
		$this->type_of_repair->ViewValue = $this->type_of_repair->CurrentValue;
		$this->type_of_repair->ViewCustomAttributes = "";

		// note
		$this->note->ViewValue = $this->note->CurrentValue;
		$this->note->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// asset_category
		$this->asset_category->ViewValue = $this->asset_category->CurrentValue;
		$this->asset_category->ViewCustomAttributes = "";

		// asset_sub_category
		$this->asset_sub_category->ViewValue = $this->asset_sub_category->CurrentValue;
		$this->asset_sub_category->ViewCustomAttributes = "";

		// serial_number
		$this->serial_number->ViewValue = $this->serial_number->CurrentValue;
		$this->serial_number->ViewCustomAttributes = "";

		// programe_area
		$this->programe_area->ViewValue = $this->programe_area->CurrentValue;
		$this->programe_area->ViewCustomAttributes = "";

		// division
		$this->division->ViewValue = $this->division->CurrentValue;
		$this->division->ViewCustomAttributes = "";

		// branch
		$this->branch->ViewValue = $this->branch->CurrentValue;
		$this->branch->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
		$this->department->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		$this->staff_id->ViewCustomAttributes = "";

		// created_by
		$this->created_by->ViewValue = $this->created_by->CurrentValue;
		$this->created_by->ViewCustomAttributes = "";

		// created_date
		$this->created_date->ViewValue = $this->created_date->CurrentValue;
		$this->created_date->ViewValue = ew_FormatDateTime($this->created_date->ViewValue, 0);
		$this->created_date->ViewCustomAttributes = "";

		// device_number
		$this->device_number->ViewValue = $this->device_number->CurrentValue;
		$this->device_number->ViewCustomAttributes = "";

		// tablet_imie_number
		$this->tablet_imie_number->ViewValue = $this->tablet_imie_number->CurrentValue;
		$this->tablet_imie_number->ViewCustomAttributes = "";

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// flag
		$this->flag->ViewValue = $this->flag->CurrentValue;
		$this->flag->ViewCustomAttributes = "";

		// area
		$this->area->ViewValue = $this->area->CurrentValue;
		$this->area->ViewCustomAttributes = "";

		// updated_date
		$this->updated_date->ViewValue = $this->updated_date->CurrentValue;
		$this->updated_date->ViewValue = ew_FormatDateTime($this->updated_date->ViewValue, 0);
		$this->updated_date->ViewCustomAttributes = "";

		// updated_by
		$this->updated_by->ViewValue = $this->updated_by->CurrentValue;
		$this->updated_by->ViewCustomAttributes = "";

		// received_date
		$this->received_date->ViewValue = $this->received_date->CurrentValue;
		$this->received_date->ViewValue = ew_FormatDateTime($this->received_date->ViewValue, 0);
		$this->received_date->ViewCustomAttributes = "";

		// received_by
		$this->received_by->ViewValue = $this->received_by->CurrentValue;
		$this->received_by->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// asset_tag
		$this->asset_tag->LinkCustomAttributes = "";
		$this->asset_tag->HrefValue = "";
		$this->asset_tag->TooltipValue = "";

		// start_sate
		$this->start_sate->LinkCustomAttributes = "";
		$this->start_sate->HrefValue = "";
		$this->start_sate->TooltipValue = "";

		// end_date
		$this->end_date->LinkCustomAttributes = "";
		$this->end_date->HrefValue = "";
		$this->end_date->TooltipValue = "";

		// cost_for_repair
		$this->cost_for_repair->LinkCustomAttributes = "";
		$this->cost_for_repair->HrefValue = "";
		$this->cost_for_repair->TooltipValue = "";

		// service_provider
		$this->service_provider->LinkCustomAttributes = "";
		$this->service_provider->HrefValue = "";
		$this->service_provider->TooltipValue = "";

		// address
		$this->address->LinkCustomAttributes = "";
		$this->address->HrefValue = "";
		$this->address->TooltipValue = "";

		// type_of_repair
		$this->type_of_repair->LinkCustomAttributes = "";
		$this->type_of_repair->HrefValue = "";
		$this->type_of_repair->TooltipValue = "";

		// note
		$this->note->LinkCustomAttributes = "";
		$this->note->HrefValue = "";
		$this->note->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// asset_category
		$this->asset_category->LinkCustomAttributes = "";
		$this->asset_category->HrefValue = "";
		$this->asset_category->TooltipValue = "";

		// asset_sub_category
		$this->asset_sub_category->LinkCustomAttributes = "";
		$this->asset_sub_category->HrefValue = "";
		$this->asset_sub_category->TooltipValue = "";

		// serial_number
		$this->serial_number->LinkCustomAttributes = "";
		$this->serial_number->HrefValue = "";
		$this->serial_number->TooltipValue = "";

		// programe_area
		$this->programe_area->LinkCustomAttributes = "";
		$this->programe_area->HrefValue = "";
		$this->programe_area->TooltipValue = "";

		// division
		$this->division->LinkCustomAttributes = "";
		$this->division->HrefValue = "";
		$this->division->TooltipValue = "";

		// branch
		$this->branch->LinkCustomAttributes = "";
		$this->branch->HrefValue = "";
		$this->branch->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// created_by
		$this->created_by->LinkCustomAttributes = "";
		$this->created_by->HrefValue = "";
		$this->created_by->TooltipValue = "";

		// created_date
		$this->created_date->LinkCustomAttributes = "";
		$this->created_date->HrefValue = "";
		$this->created_date->TooltipValue = "";

		// device_number
		$this->device_number->LinkCustomAttributes = "";
		$this->device_number->HrefValue = "";
		$this->device_number->TooltipValue = "";

		// tablet_imie_number
		$this->tablet_imie_number->LinkCustomAttributes = "";
		$this->tablet_imie_number->HrefValue = "";
		$this->tablet_imie_number->TooltipValue = "";

		// model
		$this->model->LinkCustomAttributes = "";
		$this->model->HrefValue = "";
		$this->model->TooltipValue = "";

		// flag
		$this->flag->LinkCustomAttributes = "";
		$this->flag->HrefValue = "";
		$this->flag->TooltipValue = "";

		// area
		$this->area->LinkCustomAttributes = "";
		$this->area->HrefValue = "";
		$this->area->TooltipValue = "";

		// updated_date
		$this->updated_date->LinkCustomAttributes = "";
		$this->updated_date->HrefValue = "";
		$this->updated_date->TooltipValue = "";

		// updated_by
		$this->updated_by->LinkCustomAttributes = "";
		$this->updated_by->HrefValue = "";
		$this->updated_by->TooltipValue = "";

		// received_date
		$this->received_date->LinkCustomAttributes = "";
		$this->received_date->HrefValue = "";
		$this->received_date->TooltipValue = "";

		// received_by
		$this->received_by->LinkCustomAttributes = "";
		$this->received_by->HrefValue = "";
		$this->received_by->TooltipValue = "";

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

		// asset_tag
		$this->asset_tag->EditAttrs["class"] = "form-control";
		$this->asset_tag->EditCustomAttributes = "";
		$this->asset_tag->EditValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->PlaceHolder = ew_RemoveHtml($this->asset_tag->FldCaption());

		// start_sate
		$this->start_sate->EditAttrs["class"] = "form-control";
		$this->start_sate->EditCustomAttributes = "";
		$this->start_sate->EditValue = ew_FormatDateTime($this->start_sate->CurrentValue, 8);
		$this->start_sate->PlaceHolder = ew_RemoveHtml($this->start_sate->FldCaption());

		// end_date
		$this->end_date->EditAttrs["class"] = "form-control";
		$this->end_date->EditCustomAttributes = "";
		$this->end_date->EditValue = ew_FormatDateTime($this->end_date->CurrentValue, 8);
		$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

		// cost_for_repair
		$this->cost_for_repair->EditAttrs["class"] = "form-control";
		$this->cost_for_repair->EditCustomAttributes = "";
		$this->cost_for_repair->EditValue = $this->cost_for_repair->CurrentValue;
		$this->cost_for_repair->PlaceHolder = ew_RemoveHtml($this->cost_for_repair->FldCaption());

		// service_provider
		$this->service_provider->EditAttrs["class"] = "form-control";
		$this->service_provider->EditCustomAttributes = "";
		$this->service_provider->EditValue = $this->service_provider->CurrentValue;
		$this->service_provider->PlaceHolder = ew_RemoveHtml($this->service_provider->FldCaption());

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// type_of_repair
		$this->type_of_repair->EditAttrs["class"] = "form-control";
		$this->type_of_repair->EditCustomAttributes = "";
		$this->type_of_repair->EditValue = $this->type_of_repair->CurrentValue;
		$this->type_of_repair->PlaceHolder = ew_RemoveHtml($this->type_of_repair->FldCaption());

		// note
		$this->note->EditAttrs["class"] = "form-control";
		$this->note->EditCustomAttributes = "";
		$this->note->EditValue = $this->note->CurrentValue;
		$this->note->PlaceHolder = ew_RemoveHtml($this->note->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// asset_category
		$this->asset_category->EditAttrs["class"] = "form-control";
		$this->asset_category->EditCustomAttributes = "";
		$this->asset_category->EditValue = $this->asset_category->CurrentValue;
		$this->asset_category->PlaceHolder = ew_RemoveHtml($this->asset_category->FldCaption());

		// asset_sub_category
		$this->asset_sub_category->EditAttrs["class"] = "form-control";
		$this->asset_sub_category->EditCustomAttributes = "";
		$this->asset_sub_category->EditValue = $this->asset_sub_category->CurrentValue;
		$this->asset_sub_category->PlaceHolder = ew_RemoveHtml($this->asset_sub_category->FldCaption());

		// serial_number
		$this->serial_number->EditAttrs["class"] = "form-control";
		$this->serial_number->EditCustomAttributes = "";
		$this->serial_number->EditValue = $this->serial_number->CurrentValue;
		$this->serial_number->PlaceHolder = ew_RemoveHtml($this->serial_number->FldCaption());

		// programe_area
		$this->programe_area->EditAttrs["class"] = "form-control";
		$this->programe_area->EditCustomAttributes = "";
		$this->programe_area->EditValue = $this->programe_area->CurrentValue;
		$this->programe_area->PlaceHolder = ew_RemoveHtml($this->programe_area->FldCaption());

		// division
		$this->division->EditAttrs["class"] = "form-control";
		$this->division->EditCustomAttributes = "";
		$this->division->EditValue = $this->division->CurrentValue;
		$this->division->PlaceHolder = ew_RemoveHtml($this->division->FldCaption());

		// branch
		$this->branch->EditAttrs["class"] = "form-control";
		$this->branch->EditCustomAttributes = "";
		$this->branch->EditValue = $this->branch->CurrentValue;
		$this->branch->PlaceHolder = ew_RemoveHtml($this->branch->FldCaption());

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";
		$this->department->EditValue = $this->department->CurrentValue;
		$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

		// created_by
		$this->created_by->EditAttrs["class"] = "form-control";
		$this->created_by->EditCustomAttributes = "";
		$this->created_by->EditValue = $this->created_by->CurrentValue;
		$this->created_by->PlaceHolder = ew_RemoveHtml($this->created_by->FldCaption());

		// created_date
		$this->created_date->EditAttrs["class"] = "form-control";
		$this->created_date->EditCustomAttributes = "";
		$this->created_date->EditValue = ew_FormatDateTime($this->created_date->CurrentValue, 8);
		$this->created_date->PlaceHolder = ew_RemoveHtml($this->created_date->FldCaption());

		// device_number
		$this->device_number->EditAttrs["class"] = "form-control";
		$this->device_number->EditCustomAttributes = "";
		$this->device_number->EditValue = $this->device_number->CurrentValue;
		$this->device_number->PlaceHolder = ew_RemoveHtml($this->device_number->FldCaption());

		// tablet_imie_number
		$this->tablet_imie_number->EditAttrs["class"] = "form-control";
		$this->tablet_imie_number->EditCustomAttributes = "";
		$this->tablet_imie_number->EditValue = $this->tablet_imie_number->CurrentValue;
		$this->tablet_imie_number->PlaceHolder = ew_RemoveHtml($this->tablet_imie_number->FldCaption());

		// model
		$this->model->EditAttrs["class"] = "form-control";
		$this->model->EditCustomAttributes = "";
		$this->model->EditValue = $this->model->CurrentValue;
		$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

		// flag
		$this->flag->EditAttrs["class"] = "form-control";
		$this->flag->EditCustomAttributes = "";
		$this->flag->EditValue = $this->flag->CurrentValue;
		$this->flag->PlaceHolder = ew_RemoveHtml($this->flag->FldCaption());

		// area
		$this->area->EditAttrs["class"] = "form-control";
		$this->area->EditCustomAttributes = "";
		$this->area->EditValue = $this->area->CurrentValue;
		$this->area->PlaceHolder = ew_RemoveHtml($this->area->FldCaption());

		// updated_date
		$this->updated_date->EditAttrs["class"] = "form-control";
		$this->updated_date->EditCustomAttributes = "";
		$this->updated_date->EditValue = ew_FormatDateTime($this->updated_date->CurrentValue, 8);
		$this->updated_date->PlaceHolder = ew_RemoveHtml($this->updated_date->FldCaption());

		// updated_by
		$this->updated_by->EditAttrs["class"] = "form-control";
		$this->updated_by->EditCustomAttributes = "";
		$this->updated_by->EditValue = $this->updated_by->CurrentValue;
		$this->updated_by->PlaceHolder = ew_RemoveHtml($this->updated_by->FldCaption());

		// received_date
		$this->received_date->EditAttrs["class"] = "form-control";
		$this->received_date->EditCustomAttributes = "";
		$this->received_date->EditValue = ew_FormatDateTime($this->received_date->CurrentValue, 8);
		$this->received_date->PlaceHolder = ew_RemoveHtml($this->received_date->FldCaption());

		// received_by
		$this->received_by->EditAttrs["class"] = "form-control";
		$this->received_by->EditCustomAttributes = "";
		$this->received_by->EditValue = $this->received_by->CurrentValue;
		$this->received_by->PlaceHolder = ew_RemoveHtml($this->received_by->FldCaption());

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
					if ($this->asset_tag->Exportable) $Doc->ExportCaption($this->asset_tag);
					if ($this->start_sate->Exportable) $Doc->ExportCaption($this->start_sate);
					if ($this->end_date->Exportable) $Doc->ExportCaption($this->end_date);
					if ($this->cost_for_repair->Exportable) $Doc->ExportCaption($this->cost_for_repair);
					if ($this->service_provider->Exportable) $Doc->ExportCaption($this->service_provider);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->type_of_repair->Exportable) $Doc->ExportCaption($this->type_of_repair);
					if ($this->note->Exportable) $Doc->ExportCaption($this->note);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->asset_category->Exportable) $Doc->ExportCaption($this->asset_category);
					if ($this->asset_sub_category->Exportable) $Doc->ExportCaption($this->asset_sub_category);
					if ($this->serial_number->Exportable) $Doc->ExportCaption($this->serial_number);
					if ($this->programe_area->Exportable) $Doc->ExportCaption($this->programe_area);
					if ($this->division->Exportable) $Doc->ExportCaption($this->division);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->created_by->Exportable) $Doc->ExportCaption($this->created_by);
					if ($this->created_date->Exportable) $Doc->ExportCaption($this->created_date);
					if ($this->device_number->Exportable) $Doc->ExportCaption($this->device_number);
					if ($this->tablet_imie_number->Exportable) $Doc->ExportCaption($this->tablet_imie_number);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->flag->Exportable) $Doc->ExportCaption($this->flag);
					if ($this->area->Exportable) $Doc->ExportCaption($this->area);
					if ($this->updated_date->Exportable) $Doc->ExportCaption($this->updated_date);
					if ($this->updated_by->Exportable) $Doc->ExportCaption($this->updated_by);
					if ($this->received_date->Exportable) $Doc->ExportCaption($this->received_date);
					if ($this->received_by->Exportable) $Doc->ExportCaption($this->received_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->asset_tag->Exportable) $Doc->ExportCaption($this->asset_tag);
					if ($this->start_sate->Exportable) $Doc->ExportCaption($this->start_sate);
					if ($this->end_date->Exportable) $Doc->ExportCaption($this->end_date);
					if ($this->cost_for_repair->Exportable) $Doc->ExportCaption($this->cost_for_repair);
					if ($this->service_provider->Exportable) $Doc->ExportCaption($this->service_provider);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->type_of_repair->Exportable) $Doc->ExportCaption($this->type_of_repair);
					if ($this->note->Exportable) $Doc->ExportCaption($this->note);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->asset_category->Exportable) $Doc->ExportCaption($this->asset_category);
					if ($this->asset_sub_category->Exportable) $Doc->ExportCaption($this->asset_sub_category);
					if ($this->serial_number->Exportable) $Doc->ExportCaption($this->serial_number);
					if ($this->programe_area->Exportable) $Doc->ExportCaption($this->programe_area);
					if ($this->division->Exportable) $Doc->ExportCaption($this->division);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->created_by->Exportable) $Doc->ExportCaption($this->created_by);
					if ($this->created_date->Exportable) $Doc->ExportCaption($this->created_date);
					if ($this->device_number->Exportable) $Doc->ExportCaption($this->device_number);
					if ($this->tablet_imie_number->Exportable) $Doc->ExportCaption($this->tablet_imie_number);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->flag->Exportable) $Doc->ExportCaption($this->flag);
					if ($this->area->Exportable) $Doc->ExportCaption($this->area);
					if ($this->updated_date->Exportable) $Doc->ExportCaption($this->updated_date);
					if ($this->updated_by->Exportable) $Doc->ExportCaption($this->updated_by);
					if ($this->received_date->Exportable) $Doc->ExportCaption($this->received_date);
					if ($this->received_by->Exportable) $Doc->ExportCaption($this->received_by);
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
						if ($this->asset_tag->Exportable) $Doc->ExportField($this->asset_tag);
						if ($this->start_sate->Exportable) $Doc->ExportField($this->start_sate);
						if ($this->end_date->Exportable) $Doc->ExportField($this->end_date);
						if ($this->cost_for_repair->Exportable) $Doc->ExportField($this->cost_for_repair);
						if ($this->service_provider->Exportable) $Doc->ExportField($this->service_provider);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->type_of_repair->Exportable) $Doc->ExportField($this->type_of_repair);
						if ($this->note->Exportable) $Doc->ExportField($this->note);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->asset_category->Exportable) $Doc->ExportField($this->asset_category);
						if ($this->asset_sub_category->Exportable) $Doc->ExportField($this->asset_sub_category);
						if ($this->serial_number->Exportable) $Doc->ExportField($this->serial_number);
						if ($this->programe_area->Exportable) $Doc->ExportField($this->programe_area);
						if ($this->division->Exportable) $Doc->ExportField($this->division);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->created_by->Exportable) $Doc->ExportField($this->created_by);
						if ($this->created_date->Exportable) $Doc->ExportField($this->created_date);
						if ($this->device_number->Exportable) $Doc->ExportField($this->device_number);
						if ($this->tablet_imie_number->Exportable) $Doc->ExportField($this->tablet_imie_number);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->flag->Exportable) $Doc->ExportField($this->flag);
						if ($this->area->Exportable) $Doc->ExportField($this->area);
						if ($this->updated_date->Exportable) $Doc->ExportField($this->updated_date);
						if ($this->updated_by->Exportable) $Doc->ExportField($this->updated_by);
						if ($this->received_date->Exportable) $Doc->ExportField($this->received_date);
						if ($this->received_by->Exportable) $Doc->ExportField($this->received_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->asset_tag->Exportable) $Doc->ExportField($this->asset_tag);
						if ($this->start_sate->Exportable) $Doc->ExportField($this->start_sate);
						if ($this->end_date->Exportable) $Doc->ExportField($this->end_date);
						if ($this->cost_for_repair->Exportable) $Doc->ExportField($this->cost_for_repair);
						if ($this->service_provider->Exportable) $Doc->ExportField($this->service_provider);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->type_of_repair->Exportable) $Doc->ExportField($this->type_of_repair);
						if ($this->note->Exportable) $Doc->ExportField($this->note);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->asset_category->Exportable) $Doc->ExportField($this->asset_category);
						if ($this->asset_sub_category->Exportable) $Doc->ExportField($this->asset_sub_category);
						if ($this->serial_number->Exportable) $Doc->ExportField($this->serial_number);
						if ($this->programe_area->Exportable) $Doc->ExportField($this->programe_area);
						if ($this->division->Exportable) $Doc->ExportField($this->division);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->created_by->Exportable) $Doc->ExportField($this->created_by);
						if ($this->created_date->Exportable) $Doc->ExportField($this->created_date);
						if ($this->device_number->Exportable) $Doc->ExportField($this->device_number);
						if ($this->tablet_imie_number->Exportable) $Doc->ExportField($this->tablet_imie_number);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->flag->Exportable) $Doc->ExportField($this->flag);
						if ($this->area->Exportable) $Doc->ExportField($this->area);
						if ($this->updated_date->Exportable) $Doc->ExportField($this->updated_date);
						if ($this->updated_by->Exportable) $Doc->ExportField($this->updated_by);
						if ($this->received_date->Exportable) $Doc->ExportField($this->received_date);
						if ($this->received_by->Exportable) $Doc->ExportField($this->received_by);
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

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
