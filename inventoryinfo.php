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
		$this->date_recieved = new cField('inventory', 'inventory', 'x_date_recieved', 'date_recieved', '`date_recieved`', ew_CastDateFieldForLike('`date_recieved`', 0, "DB"), 135, 0, FALSE, '`date_recieved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_recieved->Sortable = TRUE; // Allow sort
		$this->date_recieved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_recieved'] = &$this->date_recieved;

		// reference_id
		$this->reference_id = new cField('inventory', 'inventory', 'x_reference_id', 'reference_id', '`reference_id`', '`reference_id`', 200, -1, FALSE, '`reference_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference_id->Sortable = TRUE; // Allow sort
		$this->fields['reference_id'] = &$this->reference_id;

		// staff_id
		$this->staff_id = new cField('inventory', 'inventory', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
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
		$this->recieved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['recieved_by'] = &$this->recieved_by;
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
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// date_recieved
		$this->date_recieved->ViewValue = $this->date_recieved->CurrentValue;
		$this->date_recieved->ViewValue = ew_FormatDateTime($this->date_recieved->ViewValue, 0);
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
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$this->recieved_by->ViewValue = $this->recieved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->recieved_by->ViewValue = $this->recieved_by->CurrentValue;
			}
		} else {
			$this->recieved_by->ViewValue = NULL;
		}
		$this->recieved_by->ViewCustomAttributes = "";

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
		$this->date_recieved->EditValue = ew_FormatDateTime($this->date_recieved->CurrentValue, 8);
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

			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();

			// last Updated User/Date Value
		 	 $rsnew["last_updated_date"] = ew_CurrentDateTime();
		 	 $rsnew["last_updated_by"] = $_SESSION['Staff_ID'];
		 	 $rsnew["verified_datetime"] = ew_CurrentDateTime();
		 	 $rsnew["verified_by"] = $_SESSION['Staff_ID'];
		 	 $rsnew["report_by"] = $_SESSION['Staff_ID'];

		 	 //$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
		// Officer Only

	if ((CurrentPageID() == "edit" && CurrentUserLevel() == 1) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) || ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID'])) {
			$rsnew["datetime_initiated"] = $now->format('Y-m-d H:i:s');
			$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
			$rsnew["datetime_approved"] = $now->format('Y-m-d H:i:s');
			$rsnew["report_by"] = $_SESSION['Staff_ID'];
		}	

		// Officer Only
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 1 && $this->status->CurrentValue == 3) {

			// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && $this->status->CurrentValue == 3) {
				$rsnew["status"] = 3;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_action"] = NULL;
				$rsnew["resolved_comment"] = NULL;
				$rsnew["approval_action"] = NULL;
				$rsnew["approval_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issue sent for Investigation And Resolution &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
			}
		}

			// Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

				// Save and forward
			if ($this->initiator_action->CurrentValue == 1) {
				$rsnew["status"] = 3;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_action"] = NULL;
				$rsnew["resolved_comment"] = NULL;
				$rsnew["approval_action"] = NULL;
				$rsnew["approval_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issue sent for Review &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
			}			
		}

			// Manager
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 3) && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {

				// Save and forward
			if ($this->initiator_action->CurrentValue == 1 && ($this->status->CurrentValue == 0 || $this->status->CurrentValue == 1 || $this->status->CurrentValue == 3 )) {
				$rsnew["status"] = 4;
				$rsnew["initiator_action"] = 1;
				$rsnew["resolved_action"] = NULL;
				$rsnew["resolved_comment"] = NULL;
				$rsnew["approval_action"] = NULL;
				$rsnew["approval_comment"] = NULL;
				$this->setSuccessMessage("&#x25C9; Issue was Raised and Assigned to the Responsible Personnel to Resolved &#x2714;"); 					
			}

			// Saved only
			if ($this->initiator_action->CurrentValue == 0 && $this->status->CurrentValue == 0) {
				$rsnew["status"] = 0;			
				$rsnew["initiator_action"] = 0; 
				$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
			}			
		}

		   // Supervisor
		   if ((CurrentPageID() == "edit" && CurrentUserLevel() == 2) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']) {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
			$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
		}

		// Supervisor - Don't change field values captured by Officer
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 2  && $this->status->CurrentValue == 4) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];
			$rsnew["incident_venue"] = $rsold["incident_venue"];
			$rsnew["incident_sub_location"] = $rsold["incident_sub_location"];
			$rsnew["sub_sub_category"] = $rsold["sub_sub_category"];

			//$rsnew["closed_by"] = $rsold["closed_by"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];

			//$rsnew["resolved_action"] = $rsold["resolved_action"];
			//$rsnew["resolved_comment"] = $rsold["resolved_comment"];

			$rsnew["approval_action"] = $rsold["approval_action"];
			$rsnew["approval_comment"] = $rsold["approval_comment"];
		}

			// Supervisor - Don't change field values captured by Officer

	  /*	if (CurrentUserLevel() == 2 && $this->status->CurrentValue == 6 && $this->staff_id->CurrentValue == $_SESSION['Staff_ID']) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];
			$rsnew["incident_venue"] = $rsold["incident_venue"];
			$rsnew["incident_sub_location"] = $rsold["incident_sub_location"];
			$rsnew["sub_sub_category"] = $rsold["sub_sub_category"];
			$rsnew["assign_task"] = $rsold["assign_task"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			$rsnew["resolved_action"] = $rsold["resolved_action"];
			$rsnew["resolved_comment"] = $rsold["resolved_comment"];
			$rsnew["approval_action"] = $rsold["approval_action"];
			$rsnew["approval_comment"] = $rsold["approval_comment"];
		}*/

				// Supervisor - Don't change field values captured by Officer
		if (CurrentUserLevel() == 2 && $this->status->CurrentValue == 3) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];
			$rsnew["incident_venue"] = $rsold["incident_venue"];
			$rsnew["incident_sub_location"] = $rsold["incident_sub_location"];
			$rsnew["sub_sub_category"] = $rsold["sub_sub_category"];
			$rsnew["assign_task"] = $rsold["assign_task"];
			$rsnew["reason"] = $rsold["reason"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];

			//$rsnew["resolved_action"] = $rsold["resolved_action"];
		    //$rsnew["resolved_comment"] = $rsold["resolved_comment"];

			$rsnew["approval_action"] = $rsold["approval_action"];
			$rsnew["approval_comment"] = $rsold["approval_comment"];
		}

		// Manager - Don't change field values captured by Officer
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3 && $this->status->CurrentValue == 3) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["staffid"] = $rsold["staffid"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];
			$rsnew["incident_description"] = $rsold["incident_description"];
			$rsnew["incident_venue"] = $rsold["incident_venue"];
			$rsnew["incident_sub_location"] = $rsold["incident_sub_location"];
			$rsnew["sub_sub_category"] = $rsold["sub_sub_category"];
			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			$rsnew["reason"] = $rsold["reason"];

		//	$rsnew["closed_by"] = $rsold["closed_by"];
			//$rsnew["status"] = $rsold["status"];

			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			$rsnew["resolved_action"] = $rsold["resolved_action"];
			$rsnew["resolved_comment"] = $rsold["resolved_comment"];
		}

		// Manager - Don't change field values captured by Officer
			if (CurrentPageID() == "edit" && CurrentUserLevel() == 3 && $this->status->CurrentValue == 6) {
			$rsnew["id"] = $rsold["id"];
			$rsnew["datetime_initiated"] = $rsold["datetime_initiated"];
			$rsnew["staff_id"] = $rsold["staff_id"];
			$rsnew["branch"] = $rsold["branch"];
			$rsnew["department"] = $rsold["department"];
			$rsnew["departments"] = $rsold["departments"];
			$rsnew["start_date"] = $rsold["start_date"];
			$rsnew["end_date"] = $rsold["end_date"];
			$rsnew["duration"] = $rsold["duration"];
			$rsnew["amount_paid"] = $rsold["amount_paid"];
			$rsnew["report_by"] = $rsold["report_by"];
			$rsnew["incident_type"] = $rsold["incident_type"];
			$rsnew["incident_id"] = $rsold["incident_id"];
			$rsnew["incident_location"] = $rsold["incident_location"];
			$rsnew["no_of_people_involved"] = $rsold["no_of_people_involved"];
			$rsnew["category"] = $rsold["category"];
			$rsnew["sub_category"] = $rsold["sub_category"];
			$rsnew["incident-category"] = $rsold["incident-category"];
			$rsnew["incident_venue"] = $rsold["incident_venue"];
			$rsnew["incident_sub_location"] = $rsold["incident_sub_location"];
			$rsnew["sub_sub_category"] = $rsold["sub_sub_category"];
			$rsnew["assign_task"] = $rsold["assign_task"];
			$rsnew["reason"] = $rsold["reason"];

			//$rsnew["status"] = $rsold["status"];
			$rsnew["initiator_action"] = $rsold["initiator_action"];
			$rsnew["initiator_comment"] = $rsold["initiator_comment"];
			$rsnew["resolved_action"] = $rsold["resolved_action"];
			$rsnew["resolved_comment"] = $rsold["resolved_comment"];
			$rsnew["approval_action"] = $rsold["approval_action"];
			$rsnew["approval_comment"] = $rsold["approval_comment"];
		}

		// Supervisor
		if ((CurrentPageID() == "edit" && (CurrentUserLevel() == 2 || CurrentUserLevel() == 1) && $this->staff_id->CurrentValue != $_SESSION['Staff_ID']|| $this->staff_id->CurrentValue = $_SESSION['Staff_ID'])) {
				$rsnew["datetime_resolved"] = $now->format('Y-m-d H:i:s');
				$rsnew["resolved_by"] = $_SESSION['Staff_ID'];
				if ($this->resolved_action->CurrentValue == 0 && $this->status->CurrentValue == 4) {
					$rsnew["status"] = 4;			
					$rsnew["resolved_action"] = 0;

					//$rsnew["resolved_comment"] = NULL;
				 	//$rsnew["approval_action"] = NULL;
				//	$rsnew["approval_comment"] = NULL;

					$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
				}	

				// Issue Resolved by Supervisor/officer
				if ($this->resolved_action->CurrentValue == 1 && $this->status->CurrentValue == 4) {

					// New
					if ($this->status->CurrentValue == 4) {
						$rsnew["status"] = 6;					
						$rsnew["resolved_action"] = 1;

						//$rsnew["resolved_comment"] = NULL;
				 	   // $rsnew["approval_action"] = NULL;
						//$rsnew["approval_comment"] = NULL;

					}
					$this->setSuccessMessage("&#x25C9; Issue was successfully Resolved and sent for Verification &#x2714;");
				}

					// Issue Not Resolved by Supervisor/officer
				if ($this->resolved_action->CurrentValue == 2 && $this->status->CurrentValue == 4) {

					// New
					if ($this->status->CurrentValue == 4 && $this->resolved_action->CurrentValue == 2) {
						$rsnew["status"] = 7;					
						$rsnew["resolved_action"] = 2;

						//$rsnew["resolved_comment"] = NULL;
				 	   // $rsnew["approval_action"] = NULL;
						//$rsnew["approval_comment"] = NULL;

					}
					$this->setSuccessMessage("&#x25C9; Issue was Not Resolved and sent for Re-Asigned &#x2714;");
				}

				//issue saved only by Verifire===================================

				/*	if ($this->verified_action->CurrentValue == 7 && $this->status->CurrentValue == 6) {
					$rsnew["status"] = 6;			
					$rsnew["verified_action"] = 7;

					//$rsnew["resolved_comment"] = NULL;
				 	//$rsnew["approval_action"] = NULL;
				//	$rsnew["approval_comment"] = NULL;

					$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
				}	

					// Issue verified by Supervisor/Officer
				if ($this->verified_action->CurrentValue == 8 && $this->status->CurrentValue == 6) {

					// New
					if ($this->status->CurrentValue == 6) {
						$rsnew["status"] = 5;					
						$rsnew["verified_action"] = 8;

						//$rsnew["resolved_comment"] = NULL;
				 	   // $rsnew["approval_action"] = NULL;
						//$rsnew["approval_comment"] = NULL;

					}
					$this->setSuccessMessage("&#x25C9; Issue has been verified and Closed &#x2714;");
				}*/	
			}

		   	// Manager===========================================================================
		if (CurrentPageID() == "edit" && CurrentUserLevel() == 3) {
		   $rsnew["datetime_approved"] = $now->format('Y-m-d H:i:s');
			$rsnew["approved_by"] = $_SESSION['Staff_ID'];
		}

			// Checked only By Manager
			   if ($this->approval_action->CurrentValue == 1 && $this->status->CurrentValue == 3) {

				// New
				if ($this->status->CurrentValue == 3) {
					$rsnew["status"] = 1;					
					$rsnew["approval_action"] = 1;
				}
				$this->setSuccessMessage("&#x25C9; Issue was Return for Rework &#x2714;");
			}	

			// Approved by Manager
			if ($this->approval_action->CurrentValue == 2 && $this->status->CurrentValue == 3) {

				// New
				if ($this->status->CurrentValue == 3) {
					$rsnew["status"] = 4;					
					$rsnew["approval_action"] = 2;

					//$rsnew["approval_comment"] = NULL;
				}
				$this->setSuccessMessage("&#x25C9; Issue was successfully Assigned to the Reponsible Personnel &#x2714;");
			}

			// Approved by Manager
				if ($this->approval_action->CurrentValue == 3 && $this->status->CurrentValue == 3) {

					// New
					if ($this->status->CurrentValue == 3) {
						$rsnew["status"] = 5;					
						$rsnew["approval_action"] = 3;

						//$rsnew["approval_comment"] = NULL;
					}
					$this->setSuccessMessage("&#x25C9; Issue was successfully Resolved and Closed &#x2714;");
				}

				// Approved Checks by Manager======================
				if ($this->verified_action->CurrentValue == 7 && $this->status->CurrentValue == 6) {
					$rsnew["status"] = 6;			
					$rsnew["verified_action"] = 7;

					//$rsnew["resolved_comment"] = NULL;
				 	//$rsnew["approval_action"] = NULL;
				//	$rsnew["approval_comment"] = NULL;

					$this->setSuccessMessage("&#x25C9; Issue has been saved &#x2714;");
				}

					// Approval Checks by Manager
				if ($this->verified_action->CurrentValue == 8 && $this->status->CurrentValue == 6) {

					// New
					if ($this->status->CurrentValue == 6) {
						$rsnew["status"] = 5;					
						$rsnew["verified_action"] = 8;

					    //$rsnew["verified_action"] = NULL;
					}
					$this->setSuccessMessage("&#x25C9; Issue verified successfully and Closed &#x2714;");
				}

				// Checked only By Manager
			   /*if ($this->verified_action->CurrentValue == 1 && $this->status->CurrentValue == 6) {

				// New
				if ($this->status->CurrentValue == 6) {
					$rsnew["status"] = 3;					
					$rsnew["verified_action"] = 1;
				}
				$this->setSuccessMessage("&#x25C9; Ticket was Return for Re-Assigned &#x2714;");
			}*/

			// Task Re-Asigned only By Manager
				   if ($this->verified_action->CurrentValue == 1 && $this->status->CurrentValue == 7) {

					// New
					if ($this->status->CurrentValue == 7) {
						$rsnew["status"] = 4;					
						$rsnew["verified_action"] = 1;
					}
					$this->setSuccessMessage("&#x25C9; Issue Was Successfully Re-Assigned &#x2714;");
				}	

						// Approval Checks by Manager

			/*	if ($this->verified_action->CurrentValue == 9 && $this->status->CurrentValue == 6) {

					// New
					if ($this->status->CurrentValue == 6) {
						$rsnew["status"] = 5;					
						$rsnew["verified_action"] = 9;

					    //$rsnew["verified_action"] = NULL;
					}
					$this->setSuccessMessage("&#x25C9; Issue Resolved Very Good and Closed &#x2714;");
				}*/

						// Approval Checks by Manager

			/*	if ($this->verified_action->CurrentValue == 10 && $this->status->CurrentValue == 6) {

					// New
					if ($this->status->CurrentValue == 6) {
						$rsnew["status"] = 5;					
						$rsnew["verified_action"] = 10;

					    //$rsnew["verified_action"] = NULL;
					}
					$this->setSuccessMessage("&#x25C9; Issue Resolved Excellent and Closed &#x2714;");
				}*/

						// Approval Checks by Manager

			/*	if ($this->verified_action->CurrentValue == 11 && $this->status->CurrentValue == 6) {

					// New
					if ($this->status->CurrentValue == 6) {
						$rsnew["status"] = 5;					
						$rsnew["verified_action"] = 11;

					    //$rsnew["verified_action"] = NULL;
					}
					$this->setSuccessMessage("&#x25C9; Issue Assessed Satisfactory and Closed &#x2714;");
				}*/	
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

					//$this->recieved_by->Visible = FALSE;	
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
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
