<?php

// Global variable for table object
$users = NULL;

//
// Table class for users
//
class cusers extends cTable {
	var $id;
	var $staffno;
	var $date_created;
	var $firstname;
	var $lastname;
	var $_email;
	var $designation;
	var $branch;
	var $department;
	var $gender;
	var $mobile;
	var $username;
	var $password;
	var $accesslevel;
	var $status;
	var $profile;
	var $staff_id;
	var $flag;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'users';
		$this->TableName = 'users';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`users`";
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
		$this->id = new cField('users', 'users', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// staffno
		$this->staffno = new cField('users', 'users', 'x_staffno', 'staffno', '`staffno`', '`staffno`', 200, -1, FALSE, '`staffno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffno->Sortable = TRUE; // Allow sort
		$this->fields['staffno'] = &$this->staffno;

		// date_created
		$this->date_created = new cField('users', 'users', 'x_date_created', 'date_created', '`date_created`', ew_CastDateFieldForLike('`date_created`', 11, "DB"), 135, 11, FALSE, '`date_created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_created->Sortable = TRUE; // Allow sort
		$this->date_created->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['date_created'] = &$this->date_created;

		// firstname
		$this->firstname = new cField('users', 'users', 'x_firstname', 'firstname', '`firstname`', '`firstname`', 200, -1, FALSE, '`firstname`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->firstname->Sortable = TRUE; // Allow sort
		$this->fields['firstname'] = &$this->firstname;

		// lastname
		$this->lastname = new cField('users', 'users', 'x_lastname', 'lastname', '`lastname`', '`lastname`', 200, -1, FALSE, '`lastname`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lastname->Sortable = TRUE; // Allow sort
		$this->fields['lastname'] = &$this->lastname;

		// email
		$this->_email = new cField('users', 'users', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// designation
		$this->designation = new cField('users', 'users', 'x_designation', 'designation', '`designation`', '`designation`', 3, -1, FALSE, '`designation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->designation->Sortable = TRUE; // Allow sort
		$this->designation->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->designation->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->designation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['designation'] = &$this->designation;

		// branch
		$this->branch = new cField('users', 'users', 'x_branch', 'branch', '`branch`', '`branch`', 3, -1, FALSE, '`branch`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->branch->Sortable = TRUE; // Allow sort
		$this->branch->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->branch->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->branch->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['branch'] = &$this->branch;

		// department
		$this->department = new cField('users', 'users', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->department->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->department->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['department'] = &$this->department;

		// gender
		$this->gender = new cField('users', 'users', 'x_gender', 'gender', '`gender`', '`gender`', 3, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->gender->Sortable = TRUE; // Allow sort
		$this->gender->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['gender'] = &$this->gender;

		// mobile
		$this->mobile = new cField('users', 'users', 'x_mobile', 'mobile', '`mobile`', '`mobile`', 3, -1, FALSE, '`mobile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mobile->Sortable = TRUE; // Allow sort
		$this->fields['mobile'] = &$this->mobile;

		// username
		$this->username = new cField('users', 'users', 'x_username', 'username', '`username`', '`username`', 200, -1, FALSE, '`username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->username->Sortable = TRUE; // Allow sort
		$this->fields['username'] = &$this->username;

		// password
		$this->password = new cField('users', 'users', 'x_password', 'password', '`password`', '`password`', 200, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->password->Sortable = TRUE; // Allow sort
		$this->fields['password'] = &$this->password;

		// accesslevel
		$this->accesslevel = new cField('users', 'users', 'x_accesslevel', 'accesslevel', '`accesslevel`', '`accesslevel`', 3, -1, FALSE, '`accesslevel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->accesslevel->Sortable = TRUE; // Allow sort
		$this->accesslevel->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->accesslevel->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->accesslevel->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['accesslevel'] = &$this->accesslevel;

		// status
		$this->status = new cField('users', 'users', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->OptionCount = 2;
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// profile
		$this->profile = new cField('users', 'users', 'x_profile', 'profile', '`profile`', '`profile`', 201, -1, FALSE, '`profile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->profile->Sortable = TRUE; // Allow sort
		$this->fields['profile'] = &$this->profile;

		// staff_id
		$this->staff_id = new cField('users', 'users', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staff_id'] = &$this->staff_id;

		// flag
		$this->flag = new cField('users', 'users', 'x_flag', 'flag', '`flag`', '`flag`', 3, -1, FALSE, '`flag`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->flag->Sortable = TRUE; // Allow sort
		$this->flag->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['flag'] = &$this->flag;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`users`";
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'password')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'password') {
				if ($value == $this->fields[$name]->OldValue) // No need to update hashed password if not changed
					continue;
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			return "userslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "usersview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "usersedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "usersadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "userslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("usersview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("usersview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "usersadd.php?" . $this->UrlParm($parm);
		else
			$url = "usersadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("usersedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("usersadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("usersdelete.php", $this->UrlParm());
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
		$this->staffno->setDbValue($rs->fields('staffno'));
		$this->date_created->setDbValue($rs->fields('date_created'));
		$this->firstname->setDbValue($rs->fields('firstname'));
		$this->lastname->setDbValue($rs->fields('lastname'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->designation->setDbValue($rs->fields('designation'));
		$this->branch->setDbValue($rs->fields('branch'));
		$this->department->setDbValue($rs->fields('department'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->mobile->setDbValue($rs->fields('mobile'));
		$this->username->setDbValue($rs->fields('username'));
		$this->password->setDbValue($rs->fields('password'));
		$this->accesslevel->setDbValue($rs->fields('accesslevel'));
		$this->status->setDbValue($rs->fields('status'));
		$this->profile->setDbValue($rs->fields('profile'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->flag->setDbValue($rs->fields('flag'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// staffno
		// date_created
		// firstname
		// lastname
		// email
		// designation
		// branch
		// department
		// gender
		// mobile
		// username
		// password
		// accesslevel
		// status
		// profile
		// staff_id
		// flag
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// staffno
		$this->staffno->ViewValue = $this->staffno->CurrentValue;
		$this->staffno->ViewCustomAttributes = "";

		// date_created
		$this->date_created->ViewValue = $this->date_created->CurrentValue;
		$this->date_created->ViewValue = ew_FormatDateTime($this->date_created->ViewValue, 11);
		$this->date_created->ViewCustomAttributes = "";

		// firstname
		$this->firstname->ViewValue = $this->firstname->CurrentValue;
		$this->firstname->ViewCustomAttributes = "";

		// lastname
		$this->lastname->ViewValue = $this->lastname->CurrentValue;
		$this->lastname->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// designation
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

		// branch
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array("dx1" => '`branch_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->branch, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `branch_id` ASC";
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array("dx1" => '`department_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->department, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `department_id` ASC";
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

		// gender
		if (strval($this->gender->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->gender->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gender`";
		$sWhereWrk = "";
		$this->gender->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->gender, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->gender->ViewValue = $this->gender->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->gender->ViewValue = $this->gender->CurrentValue;
			}
		} else {
			$this->gender->ViewValue = NULL;
		}
		$this->gender->ViewCustomAttributes = "";

		// mobile
		$this->mobile->ViewValue = $this->mobile->CurrentValue;
		$this->mobile->ViewCustomAttributes = "";

		// username
		$this->username->ViewValue = $this->username->CurrentValue;
		$this->username->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// accesslevel
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->accesslevel->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->accesslevel->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->accesslevel->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->accesslevel, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->accesslevel->ViewValue = $this->accesslevel->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->accesslevel->ViewValue = $this->accesslevel->CurrentValue;
			}
		} else {
			$this->accesslevel->ViewValue = NULL;
		}
		} else {
			$this->accesslevel->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->accesslevel->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

		// profile
		$this->profile->ViewValue = $this->profile->CurrentValue;
		$this->profile->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		$this->staff_id->ViewCustomAttributes = "";

		// flag
		$this->flag->ViewValue = $this->flag->CurrentValue;
		$this->flag->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// staffno
		$this->staffno->LinkCustomAttributes = "";
		$this->staffno->HrefValue = "";
		$this->staffno->TooltipValue = "";

		// date_created
		$this->date_created->LinkCustomAttributes = "";
		$this->date_created->HrefValue = "";
		$this->date_created->TooltipValue = "";

		// firstname
		$this->firstname->LinkCustomAttributes = "";
		$this->firstname->HrefValue = "";
		$this->firstname->TooltipValue = "";

		// lastname
		$this->lastname->LinkCustomAttributes = "";
		$this->lastname->HrefValue = "";
		$this->lastname->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// designation
		$this->designation->LinkCustomAttributes = "";
		$this->designation->HrefValue = "";
		$this->designation->TooltipValue = "";

		// branch
		$this->branch->LinkCustomAttributes = "";
		$this->branch->HrefValue = "";
		$this->branch->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// gender
		$this->gender->LinkCustomAttributes = "";
		$this->gender->HrefValue = "";
		$this->gender->TooltipValue = "";

		// mobile
		$this->mobile->LinkCustomAttributes = "";
		$this->mobile->HrefValue = "";
		$this->mobile->TooltipValue = "";

		// username
		$this->username->LinkCustomAttributes = "";
		$this->username->HrefValue = "";
		$this->username->TooltipValue = "";

		// password
		$this->password->LinkCustomAttributes = "";
		$this->password->HrefValue = "";
		$this->password->TooltipValue = "";

		// accesslevel
		$this->accesslevel->LinkCustomAttributes = "";
		$this->accesslevel->HrefValue = "";
		$this->accesslevel->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// profile
		$this->profile->LinkCustomAttributes = "";
		$this->profile->HrefValue = "";
		$this->profile->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// flag
		$this->flag->LinkCustomAttributes = "";
		$this->flag->HrefValue = "";
		$this->flag->TooltipValue = "";

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

		// staffno
		$this->staffno->EditAttrs["class"] = "form-control";
		$this->staffno->EditCustomAttributes = "";
		$this->staffno->EditValue = $this->staffno->CurrentValue;
		$this->staffno->PlaceHolder = ew_RemoveHtml($this->staffno->FldCaption());

		// date_created
		$this->date_created->EditAttrs["class"] = "form-control";
		$this->date_created->EditCustomAttributes = "";
		$this->date_created->EditValue = ew_FormatDateTime($this->date_created->CurrentValue, 11);
		$this->date_created->PlaceHolder = ew_RemoveHtml($this->date_created->FldCaption());

		// firstname
		$this->firstname->EditAttrs["class"] = "form-control";
		$this->firstname->EditCustomAttributes = "";
		$this->firstname->EditValue = $this->firstname->CurrentValue;
		$this->firstname->PlaceHolder = ew_RemoveHtml($this->firstname->FldCaption());

		// lastname
		$this->lastname->EditAttrs["class"] = "form-control";
		$this->lastname->EditCustomAttributes = "";
		$this->lastname->EditValue = $this->lastname->CurrentValue;
		$this->lastname->PlaceHolder = ew_RemoveHtml($this->lastname->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// designation
		$this->designation->EditAttrs["class"] = "form-control";
		$this->designation->EditCustomAttributes = "";

		// branch
		$this->branch->EditAttrs["class"] = "form-control";
		$this->branch->EditCustomAttributes = "";

		// department
		$this->department->EditAttrs["class"] = "form-control";
		$this->department->EditCustomAttributes = "";

		// gender
		$this->gender->EditCustomAttributes = "";

		// mobile
		$this->mobile->EditAttrs["class"] = "form-control";
		$this->mobile->EditCustomAttributes = "";
		$this->mobile->EditValue = $this->mobile->CurrentValue;
		$this->mobile->PlaceHolder = ew_RemoveHtml($this->mobile->FldCaption());

		// username
		$this->username->EditAttrs["class"] = "form-control";
		$this->username->EditCustomAttributes = "";
		$this->username->EditValue = $this->username->CurrentValue;
		$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

		// password
		$this->password->EditAttrs["class"] = "form-control";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = $this->password->CurrentValue;
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

		// accesslevel
		$this->accesslevel->EditAttrs["class"] = "form-control";
		$this->accesslevel->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->accesslevel->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

		// status
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->Options(FALSE);

		// profile
		$this->profile->EditAttrs["class"] = "form-control";
		$this->profile->EditCustomAttributes = "";
		$this->profile->EditValue = $this->profile->CurrentValue;
		$this->profile->PlaceHolder = ew_RemoveHtml($this->profile->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

		// flag
		$this->flag->EditAttrs["class"] = "form-control";
		$this->flag->EditCustomAttributes = "";
		$this->flag->EditValue = $this->flag->CurrentValue;
		$this->flag->PlaceHolder = ew_RemoveHtml($this->flag->FldCaption());

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
					if ($this->staffno->Exportable) $Doc->ExportCaption($this->staffno);
					if ($this->date_created->Exportable) $Doc->ExportCaption($this->date_created);
					if ($this->firstname->Exportable) $Doc->ExportCaption($this->firstname);
					if ($this->lastname->Exportable) $Doc->ExportCaption($this->lastname);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->designation->Exportable) $Doc->ExportCaption($this->designation);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->mobile->Exportable) $Doc->ExportCaption($this->mobile);
					if ($this->username->Exportable) $Doc->ExportCaption($this->username);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->accesslevel->Exportable) $Doc->ExportCaption($this->accesslevel);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->profile->Exportable) $Doc->ExportCaption($this->profile);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->flag->Exportable) $Doc->ExportCaption($this->flag);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->staffno->Exportable) $Doc->ExportCaption($this->staffno);
					if ($this->date_created->Exportable) $Doc->ExportCaption($this->date_created);
					if ($this->firstname->Exportable) $Doc->ExportCaption($this->firstname);
					if ($this->lastname->Exportable) $Doc->ExportCaption($this->lastname);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->designation->Exportable) $Doc->ExportCaption($this->designation);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->mobile->Exportable) $Doc->ExportCaption($this->mobile);
					if ($this->username->Exportable) $Doc->ExportCaption($this->username);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->accesslevel->Exportable) $Doc->ExportCaption($this->accesslevel);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->flag->Exportable) $Doc->ExportCaption($this->flag);
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
						if ($this->staffno->Exportable) $Doc->ExportField($this->staffno);
						if ($this->date_created->Exportable) $Doc->ExportField($this->date_created);
						if ($this->firstname->Exportable) $Doc->ExportField($this->firstname);
						if ($this->lastname->Exportable) $Doc->ExportField($this->lastname);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->designation->Exportable) $Doc->ExportField($this->designation);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->mobile->Exportable) $Doc->ExportField($this->mobile);
						if ($this->username->Exportable) $Doc->ExportField($this->username);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->accesslevel->Exportable) $Doc->ExportField($this->accesslevel);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->profile->Exportable) $Doc->ExportField($this->profile);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->flag->Exportable) $Doc->ExportField($this->flag);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->staffno->Exportable) $Doc->ExportField($this->staffno);
						if ($this->date_created->Exportable) $Doc->ExportField($this->date_created);
						if ($this->firstname->Exportable) $Doc->ExportField($this->firstname);
						if ($this->lastname->Exportable) $Doc->ExportField($this->lastname);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->designation->Exportable) $Doc->ExportField($this->designation);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->mobile->Exportable) $Doc->ExportField($this->mobile);
						if ($this->username->Exportable) $Doc->ExportField($this->username);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->accesslevel->Exportable) $Doc->ExportField($this->accesslevel);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->flag->Exportable) $Doc->ExportField($this->flag);
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

	// Send register email
	function SendRegisterEmail($row) {
		$Email = $this->PrepareRegisterEmail($row);
		$Args = array();
		$Args["rs"] = $row;
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $Args)) // NOTE: use Email_Sending server event of user table
			$bEmailSent = $Email->Send();
		return $bEmailSent;
	}

	// Prepare register email
	function PrepareRegisterEmail($row = NULL, $langid = "") {
		$Email = new cEmail;
		$Email->Load(EW_EMAIL_REGISTER_TEMPLATE, $langid);
		$sReceiverEmail = ($row == NULL) ? $this->_email->CurrentValue : $row['email'];
		if ($sReceiverEmail == "") { // Send to recipient directly
			$sReceiverEmail = EW_RECIPIENT_EMAIL;
			$sBccEmail = "";
		} else { // Bcc recipient
			$sBccEmail = EW_RECIPIENT_EMAIL;
		}
		$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
		$Email->ReplaceRecipient($sReceiverEmail); // Replace Recipient
		if ($sBccEmail <> "") $Email->AddBcc($sBccEmail); // Add Bcc
		$Email->ReplaceContent('<!--FieldCaption_staffno-->', $this->staffno->FldCaption());
		$Email->ReplaceContent('<!--staffno-->', ($row == NULL) ? strval($this->staffno->FormValue) : $row['staffno']);
		$Email->ReplaceContent('<!--FieldCaption_date_created-->', $this->date_created->FldCaption());
		$Email->ReplaceContent('<!--date_created-->', ($row == NULL) ? strval($this->date_created->FormValue) : $row['date_created']);
		$Email->ReplaceContent('<!--FieldCaption_firstname-->', $this->firstname->FldCaption());
		$Email->ReplaceContent('<!--firstname-->', ($row == NULL) ? strval($this->firstname->FormValue) : $row['firstname']);
		$Email->ReplaceContent('<!--FieldCaption_lastname-->', $this->lastname->FldCaption());
		$Email->ReplaceContent('<!--lastname-->', ($row == NULL) ? strval($this->lastname->FormValue) : $row['lastname']);
		$Email->ReplaceContent('<!--FieldCaption_email-->', $this->_email->FldCaption());
		$Email->ReplaceContent('<!--email-->', ($row == NULL) ? strval($this->_email->FormValue) : $row['email']);
		$Email->ReplaceContent('<!--FieldCaption_designation-->', $this->designation->FldCaption());
		$Email->ReplaceContent('<!--designation-->', ($row == NULL) ? strval($this->designation->FormValue) : $row['designation']);
		$Email->ReplaceContent('<!--FieldCaption_branch-->', $this->branch->FldCaption());
		$Email->ReplaceContent('<!--branch-->', ($row == NULL) ? strval($this->branch->FormValue) : $row['branch']);
		$Email->ReplaceContent('<!--FieldCaption_department-->', $this->department->FldCaption());
		$Email->ReplaceContent('<!--department-->', ($row == NULL) ? strval($this->department->FormValue) : $row['department']);
		$Email->ReplaceContent('<!--FieldCaption_gender-->', $this->gender->FldCaption());
		$Email->ReplaceContent('<!--gender-->', ($row == NULL) ? strval($this->gender->FormValue) : $row['gender']);
		$Email->ReplaceContent('<!--FieldCaption_mobile-->', $this->mobile->FldCaption());
		$Email->ReplaceContent('<!--mobile-->', ($row == NULL) ? strval($this->mobile->FormValue) : $row['mobile']);
		$Email->ReplaceContent('<!--FieldCaption_username-->', $this->username->FldCaption());
		$Email->ReplaceContent('<!--username-->', ($row == NULL) ? strval($this->username->FormValue) : $row['username']);
		$Email->ReplaceContent('<!--FieldCaption_password-->', $this->password->FldCaption());
		$Email->ReplaceContent('<!--password-->', ($row == NULL) ? strval($this->password->FormValue) : $row['password']);
		$Email->ReplaceContent('<!--FieldCaption_accesslevel-->', $this->accesslevel->FldCaption());
		$Email->ReplaceContent('<!--accesslevel-->', ($row == NULL) ? strval($this->accesslevel->FormValue) : $row['accesslevel']);
		$Email->ReplaceContent('<!--FieldCaption_status-->', $this->status->FldCaption());
		$Email->ReplaceContent('<!--status-->', ($row == NULL) ? strval($this->status->FormValue) : $row['status']);
		$sLoginID = ($row == NULL) ? $this->username->CurrentValue : $row['username'];
		$sPassword = ($row == NULL) ? $this->password->CurrentValue : $row['password'];
		$sActivateLink = ew_FullUrl("register.php", "activate") . "?action=confirm";
		$sActivateLink .= "&email=" . $sReceiverEmail;
		$sToken = ew_Encrypt($sReceiverEmail) . "," . ew_Encrypt($sLoginID) . "," . ew_Encrypt($sPassword);
		$sActivateLink .= "&token=" . $sToken;
		$Email->ReplaceContent("<!--ActivateLink-->", $sActivateLink);
		$Email->Content = preg_replace('/<!--\s*register_activate_link[\s\S]*?-->/i', '', $Email->Content); // Remove comments
		return $Email;
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
			if (CurrentPageID() == "register")  {
			date_default_timezone_set('Africa/Lagos');
			$now = new DateTime();
			$this->date_created->CurrentValue = $now->Format('Y-m-d H:i:s');
			$this->date_created->EditValue = $this->date_created->CurrentValue;

		//	$this->incident_id->CurrentValue = $_SESSION['Inc_ID'];
		//	$this->incident_id->EditValue = $this->incident_id->CurrentValue;
		//	$this->staffid->CurrentValue = $_SESSION['StfID'];
		//	$this->staffid->EditValue = $this->staffid->CurrentValue;
			//$this->staffno->CurrentValue = $_SESSION['STAffNO'];
			//$this->staffno->EditValue = $this->staffno->CurrentValue;

		}
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);
		//$this->staffno->ReadOnly = TRUE;

		$this->date_created->ReadOnly = TRUE;
		/*if (CurrentPageID() == "add") {
			if (CurrentUserLevel() == 1) {
			}
		}*/
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
