<?php

// Global variable for table object
$pc_issuance = NULL;

//
// Table class for pc_issuance
//
class cpc_issuance extends cTable {
	var $id;
	var $issued_date;
	var $reference_id;
	var $asset_tag;
	var $make;
	var $color;
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
		$this->issued_date = new cField('pc_issuance', 'pc_issuance', 'x_issued_date', 'issued_date', '`issued_date`', ew_CastDateFieldForLike('`issued_date`', 0, "DB"), 135, 0, FALSE, '`issued_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->issued_date->Sortable = TRUE; // Allow sort
		$this->issued_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['issued_date'] = &$this->issued_date;

		// reference_id
		$this->reference_id = new cField('pc_issuance', 'pc_issuance', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// asset_tag
		$this->asset_tag = new cField('pc_issuance', 'pc_issuance', 'x_asset_tag', 'asset_tag', '`asset_tag`', '`asset_tag`', 200, -1, FALSE, '`asset_tag`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->asset_tag->Sortable = TRUE; // Allow sort
		$this->fields['asset_tag'] = &$this->asset_tag;

		// make
		$this->make = new cField('pc_issuance', 'pc_issuance', 'x_make', 'make', '`make`', '`make`', 200, -1, FALSE, '`make`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->make->Sortable = TRUE; // Allow sort
		$this->fields['make'] = &$this->make;

		// color
		$this->color = new cField('pc_issuance', 'pc_issuance', 'x_color', 'color', '`color`', '`color`', 200, -1, FALSE, '`color`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->color->Sortable = TRUE; // Allow sort
		$this->fields['color'] = &$this->color;

		// department
		$this->department = new cField('pc_issuance', 'pc_issuance', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['department'] = &$this->department;

		// designation
		$this->designation = new cField('pc_issuance', 'pc_issuance', 'x_designation', 'designation', '`designation`', '`designation`', 3, -1, FALSE, '`designation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->designation->Sortable = TRUE; // Allow sort
		$this->designation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['designation'] = &$this->designation;

		// assign_to
		$this->assign_to = new cField('pc_issuance', 'pc_issuance', 'x_assign_to', 'assign_to', '`assign_to`', '`assign_to`', 3, -1, FALSE, '`assign_to`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->assign_to->Sortable = TRUE; // Allow sort
		$this->assign_to->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assign_to'] = &$this->assign_to;

		// date_assign
		$this->date_assign = new cField('pc_issuance', 'pc_issuance', 'x_date_assign', 'date_assign', '`date_assign`', ew_CastDateFieldForLike('`date_assign`', 0, "DB"), 135, 0, FALSE, '`date_assign`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_assign->Sortable = TRUE; // Allow sort
		$this->date_assign->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_assign'] = &$this->date_assign;

		// assign_action
		$this->assign_action = new cField('pc_issuance', 'pc_issuance', 'x_assign_action', 'assign_action', '`assign_action`', '`assign_action`', 3, -1, FALSE, '`assign_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->assign_action->Sortable = TRUE; // Allow sort
		$this->assign_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assign_action'] = &$this->assign_action;

		// assign_comment
		$this->assign_comment = new cField('pc_issuance', 'pc_issuance', 'x_assign_comment', 'assign_comment', '`assign_comment`', '`assign_comment`', 201, -1, FALSE, '`assign_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->assign_comment->Sortable = TRUE; // Allow sort
		$this->fields['assign_comment'] = &$this->assign_comment;

		// assign_by
		$this->assign_by = new cField('pc_issuance', 'pc_issuance', 'x_assign_by', 'assign_by', '`assign_by`', '`assign_by`', 3, -1, FALSE, '`assign_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->assign_by->Sortable = TRUE; // Allow sort
		$this->assign_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assign_by'] = &$this->assign_by;

		// statuse
		$this->statuse = new cField('pc_issuance', 'pc_issuance', 'x_statuse', 'statuse', '`statuse`', '`statuse`', 200, -1, FALSE, '`statuse`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->statuse->Sortable = TRUE; // Allow sort
		$this->fields['statuse'] = &$this->statuse;

		// date_retrieved
		$this->date_retrieved = new cField('pc_issuance', 'pc_issuance', 'x_date_retrieved', 'date_retrieved', '`date_retrieved`', ew_CastDateFieldForLike('`date_retrieved`', 0, "DB"), 135, 0, FALSE, '`date_retrieved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_retrieved->Sortable = TRUE; // Allow sort
		$this->date_retrieved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_retrieved'] = &$this->date_retrieved;

		// retriever_action
		$this->retriever_action = new cField('pc_issuance', 'pc_issuance', 'x_retriever_action', 'retriever_action', '`retriever_action`', '`retriever_action`', 3, -1, FALSE, '`retriever_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->retriever_action->Sortable = TRUE; // Allow sort
		$this->retriever_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['retriever_action'] = &$this->retriever_action;

		// retriever_comment
		$this->retriever_comment = new cField('pc_issuance', 'pc_issuance', 'x_retriever_comment', 'retriever_comment', '`retriever_comment`', '`retriever_comment`', 201, -1, FALSE, '`retriever_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->retriever_comment->Sortable = TRUE; // Allow sort
		$this->fields['retriever_comment'] = &$this->retriever_comment;

		// retrieved_by
		$this->retrieved_by = new cField('pc_issuance', 'pc_issuance', 'x_retrieved_by', 'retrieved_by', '`retrieved_by`', '`retrieved_by`', 3, -1, FALSE, '`retrieved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->retrieved_by->Sortable = TRUE; // Allow sort
		$this->retrieved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['retrieved_by'] = &$this->retrieved_by;
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
		$this->asset_tag->setDbValue($rs->fields('asset_tag'));
		$this->make->setDbValue($rs->fields('make'));
		$this->color->setDbValue($rs->fields('color'));
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
		// asset_tag
		// make
		// color
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
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// issued_date
		$this->issued_date->ViewValue = $this->issued_date->CurrentValue;
		$this->issued_date->ViewValue = ew_FormatDateTime($this->issued_date->ViewValue, 0);
		$this->issued_date->ViewCustomAttributes = "";

		// reference_id
		$this->reference_id->ViewValue = $this->reference_id->CurrentValue;
		$this->reference_id->ViewCustomAttributes = "";

		// asset_tag
		$this->asset_tag->ViewValue = $this->asset_tag->CurrentValue;
		$this->asset_tag->ViewCustomAttributes = "";

		// make
		$this->make->ViewValue = $this->make->CurrentValue;
		$this->make->ViewCustomAttributes = "";

		// color
		$this->color->ViewValue = $this->color->CurrentValue;
		$this->color->ViewCustomAttributes = "";

		// department
		$this->department->ViewValue = $this->department->CurrentValue;
		$this->department->ViewCustomAttributes = "";

		// designation
		$this->designation->ViewValue = $this->designation->CurrentValue;
		$this->designation->ViewCustomAttributes = "";

		// assign_to
		$this->assign_to->ViewValue = $this->assign_to->CurrentValue;
		$this->assign_to->ViewCustomAttributes = "";

		// date_assign
		$this->date_assign->ViewValue = $this->date_assign->CurrentValue;
		$this->date_assign->ViewValue = ew_FormatDateTime($this->date_assign->ViewValue, 0);
		$this->date_assign->ViewCustomAttributes = "";

		// assign_action
		$this->assign_action->ViewValue = $this->assign_action->CurrentValue;
		$this->assign_action->ViewCustomAttributes = "";

		// assign_comment
		$this->assign_comment->ViewValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->ViewCustomAttributes = "";

		// assign_by
		$this->assign_by->ViewValue = $this->assign_by->CurrentValue;
		$this->assign_by->ViewCustomAttributes = "";

		// statuse
		$this->statuse->ViewValue = $this->statuse->CurrentValue;
		$this->statuse->ViewCustomAttributes = "";

		// date_retrieved
		$this->date_retrieved->ViewValue = $this->date_retrieved->CurrentValue;
		$this->date_retrieved->ViewValue = ew_FormatDateTime($this->date_retrieved->ViewValue, 0);
		$this->date_retrieved->ViewCustomAttributes = "";

		// retriever_action
		$this->retriever_action->ViewValue = $this->retriever_action->CurrentValue;
		$this->retriever_action->ViewCustomAttributes = "";

		// retriever_comment
		$this->retriever_comment->ViewValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->ViewCustomAttributes = "";

		// retrieved_by
		$this->retrieved_by->ViewValue = $this->retrieved_by->CurrentValue;
		$this->retrieved_by->ViewCustomAttributes = "";

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

		// asset_tag
		$this->asset_tag->LinkCustomAttributes = "";
		$this->asset_tag->HrefValue = "";
		$this->asset_tag->TooltipValue = "";

		// make
		$this->make->LinkCustomAttributes = "";
		$this->make->HrefValue = "";
		$this->make->TooltipValue = "";

		// color
		$this->color->LinkCustomAttributes = "";
		$this->color->HrefValue = "";
		$this->color->TooltipValue = "";

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
		$this->issued_date->EditValue = ew_FormatDateTime($this->issued_date->CurrentValue, 8);
		$this->issued_date->PlaceHolder = ew_RemoveHtml($this->issued_date->FldCaption());

		// reference_id
		$this->reference_id->EditAttrs["class"] = "form-control";
		$this->reference_id->EditCustomAttributes = "";
		$this->reference_id->EditValue = $this->reference_id->CurrentValue;
		$this->reference_id->PlaceHolder = ew_RemoveHtml($this->reference_id->FldCaption());

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

		// color
		$this->color->EditAttrs["class"] = "form-control";
		$this->color->EditCustomAttributes = "";
		$this->color->EditValue = $this->color->CurrentValue;
		$this->color->PlaceHolder = ew_RemoveHtml($this->color->FldCaption());

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";
		$this->department->EditValue = $this->department->CurrentValue;
		$this->department->PlaceHolder = ew_RemoveHtml($this->department->FldCaption());

		// designation
		$this->designation->EditAttrs["class"] = "form-control";
		$this->designation->EditCustomAttributes = "";
		$this->designation->EditValue = $this->designation->CurrentValue;
		$this->designation->PlaceHolder = ew_RemoveHtml($this->designation->FldCaption());

		// assign_to
		$this->assign_to->EditAttrs["class"] = "form-control";
		$this->assign_to->EditCustomAttributes = "";
		$this->assign_to->EditValue = $this->assign_to->CurrentValue;
		$this->assign_to->PlaceHolder = ew_RemoveHtml($this->assign_to->FldCaption());

		// date_assign
		$this->date_assign->EditAttrs["class"] = "form-control";
		$this->date_assign->EditCustomAttributes = "";
		$this->date_assign->EditValue = ew_FormatDateTime($this->date_assign->CurrentValue, 8);
		$this->date_assign->PlaceHolder = ew_RemoveHtml($this->date_assign->FldCaption());

		// assign_action
		$this->assign_action->EditAttrs["class"] = "form-control";
		$this->assign_action->EditCustomAttributes = "";
		$this->assign_action->EditValue = $this->assign_action->CurrentValue;
		$this->assign_action->PlaceHolder = ew_RemoveHtml($this->assign_action->FldCaption());

		// assign_comment
		$this->assign_comment->EditAttrs["class"] = "form-control";
		$this->assign_comment->EditCustomAttributes = "";
		$this->assign_comment->EditValue = $this->assign_comment->CurrentValue;
		$this->assign_comment->PlaceHolder = ew_RemoveHtml($this->assign_comment->FldCaption());

		// assign_by
		$this->assign_by->EditAttrs["class"] = "form-control";
		$this->assign_by->EditCustomAttributes = "";
		$this->assign_by->EditValue = $this->assign_by->CurrentValue;
		$this->assign_by->PlaceHolder = ew_RemoveHtml($this->assign_by->FldCaption());

		// statuse
		$this->statuse->EditAttrs["class"] = "form-control";
		$this->statuse->EditCustomAttributes = "";
		$this->statuse->EditValue = $this->statuse->CurrentValue;
		$this->statuse->PlaceHolder = ew_RemoveHtml($this->statuse->FldCaption());

		// date_retrieved
		$this->date_retrieved->EditAttrs["class"] = "form-control";
		$this->date_retrieved->EditCustomAttributes = "";
		$this->date_retrieved->EditValue = ew_FormatDateTime($this->date_retrieved->CurrentValue, 8);
		$this->date_retrieved->PlaceHolder = ew_RemoveHtml($this->date_retrieved->FldCaption());

		// retriever_action
		$this->retriever_action->EditAttrs["class"] = "form-control";
		$this->retriever_action->EditCustomAttributes = "";
		$this->retriever_action->EditValue = $this->retriever_action->CurrentValue;
		$this->retriever_action->PlaceHolder = ew_RemoveHtml($this->retriever_action->FldCaption());

		// retriever_comment
		$this->retriever_comment->EditAttrs["class"] = "form-control";
		$this->retriever_comment->EditCustomAttributes = "";
		$this->retriever_comment->EditValue = $this->retriever_comment->CurrentValue;
		$this->retriever_comment->PlaceHolder = ew_RemoveHtml($this->retriever_comment->FldCaption());

		// retrieved_by
		$this->retrieved_by->EditAttrs["class"] = "form-control";
		$this->retrieved_by->EditCustomAttributes = "";
		$this->retrieved_by->EditValue = $this->retrieved_by->CurrentValue;
		$this->retrieved_by->PlaceHolder = ew_RemoveHtml($this->retrieved_by->FldCaption());

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
					if ($this->asset_tag->Exportable) $Doc->ExportCaption($this->asset_tag);
					if ($this->make->Exportable) $Doc->ExportCaption($this->make);
					if ($this->color->Exportable) $Doc->ExportCaption($this->color);
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
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->issued_date->Exportable) $Doc->ExportCaption($this->issued_date);
					if ($this->reference_id->Exportable) $Doc->ExportCaption($this->reference_id);
					if ($this->asset_tag->Exportable) $Doc->ExportCaption($this->asset_tag);
					if ($this->make->Exportable) $Doc->ExportCaption($this->make);
					if ($this->color->Exportable) $Doc->ExportCaption($this->color);
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
						if ($this->asset_tag->Exportable) $Doc->ExportField($this->asset_tag);
						if ($this->make->Exportable) $Doc->ExportField($this->make);
						if ($this->color->Exportable) $Doc->ExportField($this->color);
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
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->issued_date->Exportable) $Doc->ExportField($this->issued_date);
						if ($this->reference_id->Exportable) $Doc->ExportField($this->reference_id);
						if ($this->asset_tag->Exportable) $Doc->ExportField($this->asset_tag);
						if ($this->make->Exportable) $Doc->ExportField($this->make);
						if ($this->color->Exportable) $Doc->ExportField($this->color);
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
