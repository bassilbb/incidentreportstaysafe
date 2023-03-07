<?php

// Global variable for table object
$history = NULL;

//
// Table class for history
//
class chistory extends cTable {
	var $id;
	var $datetime_initiated;
	var $incident_id;
	var $staffid;
	var $staff_id;
	var $department;
	var $branch;
	var $responsible_departments;
	var $category;
	var $sub_category;
	var $sub_sub_category;
	var $selection_sub_category;
	var $start_date;
	var $end_date;
	var $duration;
	var $amount_paid;
	var $no_of_people_involved;
	var $incident_type;
	var $incident_category;
	var $incident_location;
	var $incident_sub_location;
	var $incident_venue;
	var $incident_description;
	var $_upload;
	var $status;
	var $initiator_action;
	var $initiator_comment;
	var $report_by;
	var $datetime_resolved;
	var $assign_task;
	var $approval_action;
	var $approval_comment;
	var $resolved_action;
	var $resolved_comment;
	var $resolved_by;
	var $datetime_approved;
	var $approved_by;
	var $last_updated_date;
	var $last_updated_by;
	var $verified_datetime;
	var $job_assessment;
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
		$this->TableVar = 'history';
		$this->TableName = 'history';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "report_form";
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
		$this->id = new cField('history', 'history', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// datetime_initiated
		$this->datetime_initiated = new cField('history', 'history', 'x_datetime_initiated', 'datetime_initiated', '`datetime_initiated`', ew_CastDateFieldForLike('`datetime_initiated`', 11, "DB"), 135, 11, FALSE, '`datetime_initiated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime_initiated->Sortable = TRUE; // Allow sort
		$this->datetime_initiated->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datetime_initiated'] = &$this->datetime_initiated;

		// incident_id
		$this->incident_id = new cField('history', 'history', 'x_incident_id', 'incident_id', '`incident_id`', '`incident_id`', 200, -1, FALSE, '`incident_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->incident_id->Sortable = TRUE; // Allow sort
		$this->fields['incident_id'] = &$this->incident_id;

		// staffid
		$this->staffid = new cField('history', 'history', 'x_staffid', 'staffid', '`staffid`', '`staffid`', 200, -1, FALSE, '`staffid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staffid->Sortable = TRUE; // Allow sort
		$this->fields['staffid'] = &$this->staffid;

		// staff_id
		$this->staff_id = new cField('history', 'history', 'x_staff_id', 'staff_id', '`staff_id`', '`staff_id`', 3, -1, FALSE, '`staff_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->staff_id->Sortable = TRUE; // Allow sort
		$this->staff_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['staff_id'] = &$this->staff_id;

		// department
		$this->department = new cField('history', 'history', 'x_department', 'department', '`department`', '`department`', 3, -1, FALSE, '`department`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->department->Sortable = TRUE; // Allow sort
		$this->department->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->department->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->department->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['department'] = &$this->department;

		// branch
		$this->branch = new cField('history', 'history', 'x_branch', 'branch', '`branch`', '`branch`', 3, -1, FALSE, '`branch`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->branch->Sortable = TRUE; // Allow sort
		$this->branch->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->branch->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->branch->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['branch'] = &$this->branch;

		// responsible_departments
		$this->responsible_departments = new cField('history', 'history', 'x_responsible_departments', 'responsible_departments', '`responsible_departments`', '`responsible_departments`', 3, -1, FALSE, '`responsible_departments`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->responsible_departments->Sortable = TRUE; // Allow sort
		$this->responsible_departments->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->responsible_departments->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->responsible_departments->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['responsible_departments'] = &$this->responsible_departments;

		// category
		$this->category = new cField('history', 'history', 'x_category', 'category', '`category`', '`category`', 3, -1, FALSE, '`category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->category->Sortable = TRUE; // Allow sort
		$this->category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['category'] = &$this->category;

		// sub_category
		$this->sub_category = new cField('history', 'history', 'x_sub_category', 'sub_category', '`sub_category`', '`sub_category`', 3, -1, FALSE, '`sub_category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->sub_category->Sortable = TRUE; // Allow sort
		$this->sub_category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->sub_category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->sub_category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sub_category'] = &$this->sub_category;

		// sub_sub_category
		$this->sub_sub_category = new cField('history', 'history', 'x_sub_sub_category', 'sub_sub_category', '`sub_sub_category`', '`sub_sub_category`', 200, -1, FALSE, '`sub_sub_category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->sub_sub_category->Sortable = TRUE; // Allow sort
		$this->sub_sub_category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['sub_sub_category'] = &$this->sub_sub_category;

		// selection_sub_category
		$this->selection_sub_category = new cField('history', 'history', 'x_selection_sub_category', 'selection_sub_category', '`selection_sub_category`', '`selection_sub_category`', 200, -1, FALSE, '`selection_sub_category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->selection_sub_category->Sortable = TRUE; // Allow sort
		$this->fields['selection_sub_category'] = &$this->selection_sub_category;

		// start_date
		$this->start_date = new cField('history', 'history', 'x_start_date', 'start_date', '`start_date`', ew_CastDateFieldForLike('`start_date`', 7, "DB"), 133, 7, FALSE, '`start_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->start_date->Sortable = TRUE; // Allow sort
		$this->start_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['start_date'] = &$this->start_date;

		// end_date
		$this->end_date = new cField('history', 'history', 'x_end_date', 'end_date', '`end_date`', ew_CastDateFieldForLike('`end_date`', 7, "DB"), 133, 7, FALSE, '`end_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->end_date->Sortable = TRUE; // Allow sort
		$this->end_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['end_date'] = &$this->end_date;

		// duration
		$this->duration = new cField('history', 'history', 'x_duration', 'duration', '`duration`', '`duration`', 3, -1, FALSE, '`duration`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->duration->Sortable = TRUE; // Allow sort
		$this->duration->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['duration'] = &$this->duration;

		// amount_paid
		$this->amount_paid = new cField('history', 'history', 'x_amount_paid', 'amount_paid', '`amount_paid`', '`amount_paid`', 131, -1, FALSE, '`amount_paid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->amount_paid->Sortable = TRUE; // Allow sort
		$this->amount_paid->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['amount_paid'] = &$this->amount_paid;

		// no_of_people_involved
		$this->no_of_people_involved = new cField('history', 'history', 'x_no_of_people_involved', 'no_of_people_involved', '`no_of_people_involved`', '`no_of_people_involved`', 3, -1, FALSE, '`no_of_people_involved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_of_people_involved->Sortable = TRUE; // Allow sort
		$this->fields['no_of_people_involved'] = &$this->no_of_people_involved;

		// incident_type
		$this->incident_type = new cField('history', 'history', 'x_incident_type', 'incident_type', '`incident_type`', '`incident_type`', 3, -1, FALSE, '`incident_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_type->Sortable = TRUE; // Allow sort
		$this->incident_type->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_type->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident_type'] = &$this->incident_type;

		// incident-category
		$this->incident_category = new cField('history', 'history', 'x_incident_category', 'incident-category', '`incident-category`', '`incident-category`', 3, -1, FALSE, '`incident-category`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_category->Sortable = TRUE; // Allow sort
		$this->incident_category->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_category->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_category->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident-category'] = &$this->incident_category;

		// incident_location
		$this->incident_location = new cField('history', 'history', 'x_incident_location', 'incident_location', '`incident_location`', '`incident_location`', 3, -1, FALSE, '`incident_location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_location->Sortable = TRUE; // Allow sort
		$this->incident_location->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_location->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_location->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident_location'] = &$this->incident_location;

		// incident_sub_location
		$this->incident_sub_location = new cField('history', 'history', 'x_incident_sub_location', 'incident_sub_location', '`incident_sub_location`', '`incident_sub_location`', 3, -1, FALSE, '`incident_sub_location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_sub_location->Sortable = TRUE; // Allow sort
		$this->incident_sub_location->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_sub_location->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_sub_location->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident_sub_location'] = &$this->incident_sub_location;

		// incident_venue
		$this->incident_venue = new cField('history', 'history', 'x_incident_venue', 'incident_venue', '`incident_venue`', '`incident_venue`', 3, -1, FALSE, '`incident_venue`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->incident_venue->Sortable = TRUE; // Allow sort
		$this->incident_venue->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->incident_venue->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->incident_venue->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['incident_venue'] = &$this->incident_venue;

		// incident_description
		$this->incident_description = new cField('history', 'history', 'x_incident_description', 'incident_description', '`incident_description`', '`incident_description`', 201, -1, FALSE, '`incident_description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->incident_description->Sortable = TRUE; // Allow sort
		$this->fields['incident_description'] = &$this->incident_description;

		// upload
		$this->_upload = new cField('history', 'history', 'x__upload', 'upload', '`upload`', '`upload`', 200, -1, TRUE, '`upload`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->_upload->Sortable = TRUE; // Allow sort
		$this->_upload->UploadMultiple = TRUE;
		$this->_upload->Upload->UploadMultiple = TRUE;
		$this->_upload->UploadMaxFileCount = 4;
		$this->fields['upload'] = &$this->_upload;

		// status
		$this->status = new cField('history', 'history', 'x_status', 'status', '`status`', '`status`', 3, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;

		// initiator_action
		$this->initiator_action = new cField('history', 'history', 'x_initiator_action', 'initiator_action', '`initiator_action`', '`initiator_action`', 3, -1, FALSE, '`initiator_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->initiator_action->Sortable = TRUE; // Allow sort
		$this->initiator_action->OptionCount = 2;
		$this->initiator_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['initiator_action'] = &$this->initiator_action;

		// initiator_comment
		$this->initiator_comment = new cField('history', 'history', 'x_initiator_comment', 'initiator_comment', '`initiator_comment`', '`initiator_comment`', 201, -1, FALSE, '`initiator_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->initiator_comment->Sortable = TRUE; // Allow sort
		$this->fields['initiator_comment'] = &$this->initiator_comment;

		// report_by
		$this->report_by = new cField('history', 'history', 'x_report_by', 'report_by', '`report_by`', '`report_by`', 3, -1, FALSE, '`report_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->report_by->Sortable = TRUE; // Allow sort
		$this->report_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['report_by'] = &$this->report_by;

		// datetime_resolved
		$this->datetime_resolved = new cField('history', 'history', 'x_datetime_resolved', 'datetime_resolved', '`datetime_resolved`', ew_CastDateFieldForLike('`datetime_resolved`', 11, "DB"), 135, 11, FALSE, '`datetime_resolved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime_resolved->Sortable = TRUE; // Allow sort
		$this->datetime_resolved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datetime_resolved'] = &$this->datetime_resolved;

		// assign_task
		$this->assign_task = new cField('history', 'history', 'x_assign_task', 'assign_task', '`assign_task`', '`assign_task`', 3, -1, FALSE, '`assign_task`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->assign_task->Sortable = TRUE; // Allow sort
		$this->assign_task->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->assign_task->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->assign_task->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['assign_task'] = &$this->assign_task;

		// approval_action
		$this->approval_action = new cField('history', 'history', 'x_approval_action', 'approval_action', '`approval_action`', '`approval_action`', 3, -1, FALSE, '`approval_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->approval_action->Sortable = TRUE; // Allow sort
		$this->approval_action->OptionCount = 3;
		$this->approval_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approval_action'] = &$this->approval_action;

		// approval_comment
		$this->approval_comment = new cField('history', 'history', 'x_approval_comment', 'approval_comment', '`approval_comment`', '`approval_comment`', 201, -1, FALSE, '`approval_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->approval_comment->Sortable = TRUE; // Allow sort
		$this->fields['approval_comment'] = &$this->approval_comment;

		// resolved_action
		$this->resolved_action = new cField('history', 'history', 'x_resolved_action', 'resolved_action', '`resolved_action`', '`resolved_action`', 3, -1, FALSE, '`resolved_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->resolved_action->Sortable = TRUE; // Allow sort
		$this->resolved_action->OptionCount = 2;
		$this->resolved_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['resolved_action'] = &$this->resolved_action;

		// resolved_comment
		$this->resolved_comment = new cField('history', 'history', 'x_resolved_comment', 'resolved_comment', '`resolved_comment`', '`resolved_comment`', 201, -1, FALSE, '`resolved_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->resolved_comment->Sortable = TRUE; // Allow sort
		$this->fields['resolved_comment'] = &$this->resolved_comment;

		// resolved_by
		$this->resolved_by = new cField('history', 'history', 'x_resolved_by', 'resolved_by', '`resolved_by`', '`resolved_by`', 3, -1, FALSE, '`resolved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->resolved_by->Sortable = TRUE; // Allow sort
		$this->resolved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['resolved_by'] = &$this->resolved_by;

		// datetime_approved
		$this->datetime_approved = new cField('history', 'history', 'x_datetime_approved', 'datetime_approved', '`datetime_approved`', ew_CastDateFieldForLike('`datetime_approved`', 11, "DB"), 135, 11, FALSE, '`datetime_approved`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->datetime_approved->Sortable = TRUE; // Allow sort
		$this->datetime_approved->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['datetime_approved'] = &$this->datetime_approved;

		// approved_by
		$this->approved_by = new cField('history', 'history', 'x_approved_by', 'approved_by', '`approved_by`', '`approved_by`', 3, -1, FALSE, '`approved_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->approved_by->Sortable = TRUE; // Allow sort
		$this->approved_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['approved_by'] = &$this->approved_by;

		// last_updated_date
		$this->last_updated_date = new cField('history', 'history', 'x_last_updated_date', 'last_updated_date', '`last_updated_date`', ew_CastDateFieldForLike('`last_updated_date`', 17, "DB"), 135, 17, FALSE, '`last_updated_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_updated_date->Sortable = TRUE; // Allow sort
		$this->last_updated_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['last_updated_date'] = &$this->last_updated_date;

		// last_updated_by
		$this->last_updated_by = new cField('history', 'history', 'x_last_updated_by', 'last_updated_by', '`last_updated_by`', '`last_updated_by`', 3, -1, FALSE, '`last_updated_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_updated_by->Sortable = TRUE; // Allow sort
		$this->last_updated_by->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['last_updated_by'] = &$this->last_updated_by;

		// verified_datetime
		$this->verified_datetime = new cField('history', 'history', 'x_verified_datetime', 'verified_datetime', '`verified_datetime`', ew_CastDateFieldForLike('`verified_datetime`', 17, "DB"), 135, 17, FALSE, '`verified_datetime`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->verified_datetime->Sortable = TRUE; // Allow sort
		$this->verified_datetime->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['verified_datetime'] = &$this->verified_datetime;

		// job_assessment
		$this->job_assessment = new cField('history', 'history', 'x_job_assessment', 'job_assessment', '`job_assessment`', '`job_assessment`', 200, -1, FALSE, '`job_assessment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->job_assessment->Sortable = TRUE; // Allow sort
		$this->job_assessment->OptionCount = 5;
		$this->fields['job_assessment'] = &$this->job_assessment;

		// verified_action
		$this->verified_action = new cField('history', 'history', 'x_verified_action', 'verified_action', '`verified_action`', '`verified_action`', 3, -1, FALSE, '`verified_action`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->verified_action->Sortable = TRUE; // Allow sort
		$this->verified_action->OptionCount = 2;
		$this->verified_action->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['verified_action'] = &$this->verified_action;

		// verified_comment
		$this->verified_comment = new cField('history', 'history', 'x_verified_comment', 'verified_comment', '`verified_comment`', '`verified_comment`', 200, -1, FALSE, '`verified_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->verified_comment->Sortable = TRUE; // Allow sort
		$this->fields['verified_comment'] = &$this->verified_comment;

		// verified_by
		$this->verified_by = new cField('history', 'history', 'x_verified_by', 'verified_by', '`verified_by`', '`verified_by`', 3, -1, FALSE, '`verified_by`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`history`";
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
			return "historylist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "historyview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "historyedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "historyadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "historylist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("historyview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("historyview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "historyadd.php?" . $this->UrlParm($parm);
		else
			$url = "historyadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("historyedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("historyadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("historydelete.php", $this->UrlParm());
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
		$this->datetime_initiated->setDbValue($rs->fields('datetime_initiated'));
		$this->incident_id->setDbValue($rs->fields('incident_id'));
		$this->staffid->setDbValue($rs->fields('staffid'));
		$this->staff_id->setDbValue($rs->fields('staff_id'));
		$this->department->setDbValue($rs->fields('department'));
		$this->branch->setDbValue($rs->fields('branch'));
		$this->responsible_departments->setDbValue($rs->fields('responsible_departments'));
		$this->category->setDbValue($rs->fields('category'));
		$this->sub_category->setDbValue($rs->fields('sub_category'));
		$this->sub_sub_category->setDbValue($rs->fields('sub_sub_category'));
		$this->selection_sub_category->setDbValue($rs->fields('selection_sub_category'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->end_date->setDbValue($rs->fields('end_date'));
		$this->duration->setDbValue($rs->fields('duration'));
		$this->amount_paid->setDbValue($rs->fields('amount_paid'));
		$this->no_of_people_involved->setDbValue($rs->fields('no_of_people_involved'));
		$this->incident_type->setDbValue($rs->fields('incident_type'));
		$this->incident_category->setDbValue($rs->fields('incident-category'));
		$this->incident_location->setDbValue($rs->fields('incident_location'));
		$this->incident_sub_location->setDbValue($rs->fields('incident_sub_location'));
		$this->incident_venue->setDbValue($rs->fields('incident_venue'));
		$this->incident_description->setDbValue($rs->fields('incident_description'));
		$this->_upload->Upload->DbValue = $rs->fields('upload');
		$this->status->setDbValue($rs->fields('status'));
		$this->initiator_action->setDbValue($rs->fields('initiator_action'));
		$this->initiator_comment->setDbValue($rs->fields('initiator_comment'));
		$this->report_by->setDbValue($rs->fields('report_by'));
		$this->datetime_resolved->setDbValue($rs->fields('datetime_resolved'));
		$this->assign_task->setDbValue($rs->fields('assign_task'));
		$this->approval_action->setDbValue($rs->fields('approval_action'));
		$this->approval_comment->setDbValue($rs->fields('approval_comment'));
		$this->resolved_action->setDbValue($rs->fields('resolved_action'));
		$this->resolved_comment->setDbValue($rs->fields('resolved_comment'));
		$this->resolved_by->setDbValue($rs->fields('resolved_by'));
		$this->datetime_approved->setDbValue($rs->fields('datetime_approved'));
		$this->approved_by->setDbValue($rs->fields('approved_by'));
		$this->last_updated_date->setDbValue($rs->fields('last_updated_date'));
		$this->last_updated_by->setDbValue($rs->fields('last_updated_by'));
		$this->verified_datetime->setDbValue($rs->fields('verified_datetime'));
		$this->job_assessment->setDbValue($rs->fields('job_assessment'));
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
		// datetime_initiated
		// incident_id
		// staffid
		// staff_id
		// department
		// branch
		// responsible_departments
		// category
		// sub_category
		// sub_sub_category
		// selection_sub_category
		// start_date
		// end_date
		// duration
		// amount_paid
		// no_of_people_involved
		// incident_type
		// incident-category
		// incident_location
		// incident_sub_location
		// incident_venue
		// incident_description
		// upload
		// status
		// initiator_action
		// initiator_comment
		// report_by
		// datetime_resolved
		// assign_task
		// approval_action
		// approval_comment
		// resolved_action
		// resolved_comment
		// resolved_by
		// datetime_approved
		// approved_by
		// last_updated_date
		// last_updated_by
		// verified_datetime
		// job_assessment
		// verified_action
		// verified_comment
		// verified_by
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// datetime_initiated
		$this->datetime_initiated->ViewValue = $this->datetime_initiated->CurrentValue;
		$this->datetime_initiated->ViewValue = ew_FormatDateTime($this->datetime_initiated->ViewValue, 11);
		$this->datetime_initiated->ViewCustomAttributes = "";

		// incident_id
		$this->incident_id->ViewValue = $this->incident_id->CurrentValue;
		$this->incident_id->ViewCustomAttributes = "";

		// staffid
		$this->staffid->ViewValue = $this->staffid->CurrentValue;
		if (strval($this->staffid->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staffid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `staffno` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staffid->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->staffid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->staffid->ViewValue = $this->staffid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->staffid->ViewValue = $this->staffid->CurrentValue;
			}
		} else {
			$this->staffid->ViewValue = NULL;
		}
		$this->staffid->ViewCustomAttributes = "";

		// staff_id
		$this->staff_id->ViewValue = $this->staff_id->CurrentValue;
		if (strval($this->staff_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->staff_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->staff_id->LookupFilters = array("dx1" => '`firstname`', "dx2" => '`lastname`');
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

		// department
		if (strval($this->department->CurrentValue) <> "") {
			$sFilterWrk = "`department_id`" . ew_SearchString("=", $this->department->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `department_id`, `department_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `depertment`";
		$sWhereWrk = "";
		$this->department->LookupFilters = array();
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

		// branch
		if (strval($this->branch->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->branch->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `branch`";
		$sWhereWrk = "";
		$this->branch->LookupFilters = array();
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

		// responsible_departments
		if (strval($this->responsible_departments->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->responsible_departments->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departments`";
		$sWhereWrk = "";
		$this->responsible_departments->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->responsible_departments, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->responsible_departments->ViewValue = $this->responsible_departments->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->responsible_departments->ViewValue = $this->responsible_departments->CurrentValue;
			}
		} else {
			$this->responsible_departments->ViewValue = NULL;
		}
		$this->responsible_departments->ViewCustomAttributes = "";

		// category
		if (strval($this->category->CurrentValue) <> "") {
			$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `category_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
		$sWhereWrk = "";
		$this->category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category->ViewValue = $this->category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category->ViewValue = $this->category->CurrentValue;
			}
		} else {
			$this->category->ViewValue = NULL;
		}
		$this->category->ViewCustomAttributes = "";

		// sub_category
		if (strval($this->sub_category->CurrentValue) <> "") {
			$sFilterWrk = "`sub-category_id`" . ew_SearchString("=", $this->sub_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `sub-category_id`, `sub-category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub-category`";
		$sWhereWrk = "";
		$this->sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sub_category->ViewValue = $this->sub_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sub_category->ViewValue = $this->sub_category->CurrentValue;
			}
		} else {
			$this->sub_category->ViewValue = NULL;
		}
		$this->sub_category->ViewCustomAttributes = "";

		// sub_sub_category
		if (strval($this->sub_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->sub_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`code`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_sub_category`";
		$sWhereWrk = "";
		$this->sub_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sub_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->sub_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->sub_sub_category->ViewValue .= $this->sub_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->sub_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->sub_sub_category->ViewValue = $this->sub_sub_category->CurrentValue;
			}
		} else {
			$this->sub_sub_category->ViewValue = NULL;
		}
		$this->sub_sub_category->ViewCustomAttributes = "";

		// selection_sub_category
		if (strval($this->selection_sub_category->CurrentValue) <> "") {
			$arwrk = explode(",", $this->selection_sub_category->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `selection_sub_category`";
		$sWhereWrk = "";
		$this->selection_sub_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->selection_sub_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->selection_sub_category->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->selection_sub_category->ViewValue .= $this->selection_sub_category->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->selection_sub_category->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->selection_sub_category->ViewValue = $this->selection_sub_category->CurrentValue;
			}
		} else {
			$this->selection_sub_category->ViewValue = NULL;
		}
		$this->selection_sub_category->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
		$this->start_date->ViewCustomAttributes = "";

		// end_date
		$this->end_date->ViewValue = $this->end_date->CurrentValue;
		$this->end_date->ViewValue = ew_FormatDateTime($this->end_date->ViewValue, 7);
		$this->end_date->ViewCustomAttributes = "";

		// duration
		$this->duration->ViewValue = $this->duration->CurrentValue;
		$this->duration->ViewCustomAttributes = "";

		// amount_paid
		$this->amount_paid->ViewValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->ViewValue = ew_FormatCurrency($this->amount_paid->ViewValue, 2, -2, -2, -2);
		$this->amount_paid->ViewCustomAttributes = "";

		// no_of_people_involved
		$this->no_of_people_involved->ViewValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->ViewCustomAttributes = "";

		// incident_type
		if (strval($this->incident_type->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `type_of_incident`";
		$sWhereWrk = "";
		$this->incident_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_type->ViewValue = $this->incident_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_type->ViewValue = $this->incident_type->CurrentValue;
			}
		} else {
			$this->incident_type->ViewValue = NULL;
		}
		$this->incident_type->ViewCustomAttributes = "";

		// incident-category
		if (strval($this->incident_category->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->incident_category->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident-category`";
		$sWhereWrk = "";
		$this->incident_category->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_category, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_category->ViewValue = $this->incident_category->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_category->ViewValue = $this->incident_category->CurrentValue;
			}
		} else {
			$this->incident_category->ViewValue = NULL;
		}
		$this->incident_category->ViewCustomAttributes = "";

		// incident_location
		if (strval($this->incident_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_id`" . ew_SearchString("=", $this->incident_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_id`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_location`";
		$sWhereWrk = "";
		$this->incident_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `code_id` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_location->ViewValue = $this->incident_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_location->ViewValue = $this->incident_location->CurrentValue;
			}
		} else {
			$this->incident_location->ViewValue = NULL;
		}
		$this->incident_location->ViewCustomAttributes = "";

		// incident_sub_location
		if (strval($this->incident_sub_location->CurrentValue) <> "") {
			$sFilterWrk = "`code_sub`" . ew_SearchString("=", $this->incident_sub_location->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code_sub`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_sub_location`";
		$sWhereWrk = "";
		$this->incident_sub_location->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_sub_location, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_sub_location->ViewValue = $this->incident_sub_location->CurrentValue;
			}
		} else {
			$this->incident_sub_location->ViewValue = NULL;
		}
		$this->incident_sub_location->ViewCustomAttributes = "";

		// incident_venue
		if (strval($this->incident_venue->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->incident_venue->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `incident_venue`";
		$sWhereWrk = "";
		$this->incident_venue->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->incident_venue, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->incident_venue->ViewValue = $this->incident_venue->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->incident_venue->ViewValue = $this->incident_venue->CurrentValue;
			}
		} else {
			$this->incident_venue->ViewValue = NULL;
		}
		$this->incident_venue->ViewCustomAttributes = "";

		// incident_description
		$this->incident_description->ViewValue = $this->incident_description->CurrentValue;
		$this->incident_description->ViewCustomAttributes = "";

		// upload
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->ViewValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->ViewValue = "";
		}
		$this->_upload->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$sFilterWrk = "`code`" . ew_SearchString("=", $this->status->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `code`, `description` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
		$sWhereWrk = "";
		$this->status->LookupFilters = array("dx1" => '`description`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `description` ASC";
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

		// report_by
		$this->report_by->ViewValue = $this->report_by->CurrentValue;
		if (strval($this->report_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->report_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->report_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->report_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->report_by->ViewValue = $this->report_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->report_by->ViewValue = $this->report_by->CurrentValue;
			}
		} else {
			$this->report_by->ViewValue = NULL;
		}
		$this->report_by->ViewCustomAttributes = "";

		// datetime_resolved
		$this->datetime_resolved->ViewValue = $this->datetime_resolved->CurrentValue;
		$this->datetime_resolved->ViewValue = ew_FormatDateTime($this->datetime_resolved->ViewValue, 11);
		$this->datetime_resolved->ViewCustomAttributes = "";

		// assign_task
		if (strval($this->assign_task->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->assign_task->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->assign_task->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->assign_task, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->assign_task->ViewValue = $this->assign_task->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->assign_task->ViewValue = $this->assign_task->CurrentValue;
			}
		} else {
			$this->assign_task->ViewValue = NULL;
		}
		$this->assign_task->ViewCustomAttributes = "";

		// approval_action
		if (strval($this->approval_action->CurrentValue) <> "") {
			$this->approval_action->ViewValue = $this->approval_action->OptionCaption($this->approval_action->CurrentValue);
		} else {
			$this->approval_action->ViewValue = NULL;
		}
		$this->approval_action->ViewCustomAttributes = "";

		// approval_comment
		$this->approval_comment->ViewValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->ViewCustomAttributes = "";

		// resolved_action
		if (strval($this->resolved_action->CurrentValue) <> "") {
			$this->resolved_action->ViewValue = $this->resolved_action->OptionCaption($this->resolved_action->CurrentValue);
		} else {
			$this->resolved_action->ViewValue = NULL;
		}
		$this->resolved_action->ViewCustomAttributes = "";

		// resolved_comment
		$this->resolved_comment->ViewValue = $this->resolved_comment->CurrentValue;
		$this->resolved_comment->ViewCustomAttributes = "";

		// resolved_by
		$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
		if (strval($this->resolved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->resolved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->resolved_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->resolved_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->resolved_by->ViewValue = $this->resolved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->resolved_by->ViewValue = $this->resolved_by->CurrentValue;
			}
		} else {
			$this->resolved_by->ViewValue = NULL;
		}
		$this->resolved_by->ViewCustomAttributes = "";

		// datetime_approved
		$this->datetime_approved->ViewValue = $this->datetime_approved->CurrentValue;
		$this->datetime_approved->ViewValue = ew_FormatDateTime($this->datetime_approved->ViewValue, 11);
		$this->datetime_approved->ViewCustomAttributes = "";

		// approved_by
		$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
		if (strval($this->approved_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approved_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
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
				$this->approved_by->ViewValue = $this->approved_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approved_by->ViewValue = $this->approved_by->CurrentValue;
			}
		} else {
			$this->approved_by->ViewValue = NULL;
		}
		$this->approved_by->ViewCustomAttributes = "";

		// last_updated_date
		$this->last_updated_date->ViewValue = $this->last_updated_date->CurrentValue;
		$this->last_updated_date->ViewValue = ew_FormatDateTime($this->last_updated_date->ViewValue, 17);
		$this->last_updated_date->ViewCustomAttributes = "";

		// last_updated_by
		$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
		if (strval($this->last_updated_by->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->last_updated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `firstname` AS `DispFld`, `lastname` AS `Disp2Fld`, `staffno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->last_updated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->last_updated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->last_updated_by->ViewValue = $this->last_updated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->last_updated_by->ViewValue = $this->last_updated_by->CurrentValue;
			}
		} else {
			$this->last_updated_by->ViewValue = NULL;
		}
		$this->last_updated_by->ViewCustomAttributes = "";

		// verified_datetime
		$this->verified_datetime->ViewValue = $this->verified_datetime->CurrentValue;
		$this->verified_datetime->ViewValue = ew_FormatDateTime($this->verified_datetime->ViewValue, 17);
		$this->verified_datetime->ViewCustomAttributes = "";

		// job_assessment
		if (strval($this->job_assessment->CurrentValue) <> "") {
			$this->job_assessment->ViewValue = $this->job_assessment->OptionCaption($this->job_assessment->CurrentValue);
		} else {
			$this->job_assessment->ViewValue = NULL;
		}
		$this->job_assessment->ViewCustomAttributes = "";

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

		// datetime_initiated
		$this->datetime_initiated->LinkCustomAttributes = "";
		$this->datetime_initiated->HrefValue = "";
		$this->datetime_initiated->TooltipValue = "";

		// incident_id
		$this->incident_id->LinkCustomAttributes = "";
		$this->incident_id->HrefValue = "";
		$this->incident_id->TooltipValue = "";

		// staffid
		$this->staffid->LinkCustomAttributes = "";
		$this->staffid->HrefValue = "";
		$this->staffid->TooltipValue = "";

		// staff_id
		$this->staff_id->LinkCustomAttributes = "";
		$this->staff_id->HrefValue = "";
		$this->staff_id->TooltipValue = "";

		// department
		$this->department->LinkCustomAttributes = "";
		$this->department->HrefValue = "";
		$this->department->TooltipValue = "";

		// branch
		$this->branch->LinkCustomAttributes = "";
		$this->branch->HrefValue = "";
		$this->branch->TooltipValue = "";

		// responsible_departments
		$this->responsible_departments->LinkCustomAttributes = "";
		$this->responsible_departments->HrefValue = "";
		$this->responsible_departments->TooltipValue = "";

		// category
		$this->category->LinkCustomAttributes = "";
		$this->category->HrefValue = "";
		$this->category->TooltipValue = "";

		// sub_category
		$this->sub_category->LinkCustomAttributes = "";
		$this->sub_category->HrefValue = "";
		$this->sub_category->TooltipValue = "";

		// sub_sub_category
		$this->sub_sub_category->LinkCustomAttributes = "";
		$this->sub_sub_category->HrefValue = "";
		$this->sub_sub_category->TooltipValue = "";

		// selection_sub_category
		$this->selection_sub_category->LinkCustomAttributes = "";
		$this->selection_sub_category->HrefValue = "";
		$this->selection_sub_category->TooltipValue = "";

		// start_date
		$this->start_date->LinkCustomAttributes = "";
		$this->start_date->HrefValue = "";
		$this->start_date->TooltipValue = "";

		// end_date
		$this->end_date->LinkCustomAttributes = "";
		$this->end_date->HrefValue = "";
		$this->end_date->TooltipValue = "";

		// duration
		$this->duration->LinkCustomAttributes = "";
		$this->duration->HrefValue = "";
		$this->duration->TooltipValue = "";

		// amount_paid
		$this->amount_paid->LinkCustomAttributes = "";
		$this->amount_paid->HrefValue = "";
		$this->amount_paid->TooltipValue = "";

		// no_of_people_involved
		$this->no_of_people_involved->LinkCustomAttributes = "";
		$this->no_of_people_involved->HrefValue = "";
		$this->no_of_people_involved->TooltipValue = "";

		// incident_type
		$this->incident_type->LinkCustomAttributes = "";
		$this->incident_type->HrefValue = "";
		$this->incident_type->TooltipValue = "";

		// incident-category
		$this->incident_category->LinkCustomAttributes = "";
		$this->incident_category->HrefValue = "";
		$this->incident_category->TooltipValue = "";

		// incident_location
		$this->incident_location->LinkCustomAttributes = "";
		$this->incident_location->HrefValue = "";
		$this->incident_location->TooltipValue = "";

		// incident_sub_location
		$this->incident_sub_location->LinkCustomAttributes = "";
		$this->incident_sub_location->HrefValue = "";
		$this->incident_sub_location->TooltipValue = "";

		// incident_venue
		$this->incident_venue->LinkCustomAttributes = "";
		$this->incident_venue->HrefValue = "";
		$this->incident_venue->TooltipValue = "";

		// incident_description
		$this->incident_description->LinkCustomAttributes = "";
		$this->incident_description->HrefValue = "";
		$this->incident_description->TooltipValue = "";

		// upload
		$this->_upload->LinkCustomAttributes = "";
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->HrefValue = "%u"; // Add prefix/suffix
			$this->_upload->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->_upload->HrefValue = ew_FullUrl($this->_upload->HrefValue, "href");
		} else {
			$this->_upload->HrefValue = "";
		}
		$this->_upload->HrefValue2 = $this->_upload->UploadPath . $this->_upload->Upload->DbValue;
		$this->_upload->TooltipValue = "";
		if ($this->_upload->UseColorbox) {
			if (ew_Empty($this->_upload->TooltipValue))
				$this->_upload->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->_upload->LinkAttrs["data-rel"] = "history_x__upload";
			ew_AppendClass($this->_upload->LinkAttrs["class"], "ewLightbox");
		}

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// initiator_action
		$this->initiator_action->LinkCustomAttributes = "";
		$this->initiator_action->HrefValue = "";
		$this->initiator_action->TooltipValue = "";

		// initiator_comment
		$this->initiator_comment->LinkCustomAttributes = "";
		$this->initiator_comment->HrefValue = "";
		$this->initiator_comment->TooltipValue = "";

		// report_by
		$this->report_by->LinkCustomAttributes = "";
		$this->report_by->HrefValue = "";
		$this->report_by->TooltipValue = "";

		// datetime_resolved
		$this->datetime_resolved->LinkCustomAttributes = "";
		$this->datetime_resolved->HrefValue = "";
		$this->datetime_resolved->TooltipValue = "";

		// assign_task
		$this->assign_task->LinkCustomAttributes = "";
		$this->assign_task->HrefValue = "";
		$this->assign_task->TooltipValue = "";

		// approval_action
		$this->approval_action->LinkCustomAttributes = "";
		$this->approval_action->HrefValue = "";
		$this->approval_action->TooltipValue = "";

		// approval_comment
		$this->approval_comment->LinkCustomAttributes = "";
		$this->approval_comment->HrefValue = "";
		$this->approval_comment->TooltipValue = "";

		// resolved_action
		$this->resolved_action->LinkCustomAttributes = "";
		$this->resolved_action->HrefValue = "";
		$this->resolved_action->TooltipValue = "";

		// resolved_comment
		$this->resolved_comment->LinkCustomAttributes = "";
		$this->resolved_comment->HrefValue = "";
		$this->resolved_comment->TooltipValue = "";

		// resolved_by
		$this->resolved_by->LinkCustomAttributes = "";
		$this->resolved_by->HrefValue = "";
		$this->resolved_by->TooltipValue = "";

		// datetime_approved
		$this->datetime_approved->LinkCustomAttributes = "";
		$this->datetime_approved->HrefValue = "";
		$this->datetime_approved->TooltipValue = "";

		// approved_by
		$this->approved_by->LinkCustomAttributes = "";
		$this->approved_by->HrefValue = "";
		$this->approved_by->TooltipValue = "";

		// last_updated_date
		$this->last_updated_date->LinkCustomAttributes = "";
		$this->last_updated_date->HrefValue = "";
		$this->last_updated_date->TooltipValue = "";

		// last_updated_by
		$this->last_updated_by->LinkCustomAttributes = "";
		$this->last_updated_by->HrefValue = "";
		$this->last_updated_by->TooltipValue = "";

		// verified_datetime
		$this->verified_datetime->LinkCustomAttributes = "";
		$this->verified_datetime->HrefValue = "";
		$this->verified_datetime->TooltipValue = "";

		// job_assessment
		$this->job_assessment->LinkCustomAttributes = "";
		$this->job_assessment->HrefValue = "";
		$this->job_assessment->TooltipValue = "";

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

		// datetime_initiated
		$this->datetime_initiated->EditAttrs["class"] = "form-control";
		$this->datetime_initiated->EditCustomAttributes = "";
		$this->datetime_initiated->EditValue = ew_FormatDateTime($this->datetime_initiated->CurrentValue, 11);
		$this->datetime_initiated->PlaceHolder = ew_RemoveHtml($this->datetime_initiated->FldCaption());

		// incident_id
		$this->incident_id->EditAttrs["class"] = "form-control";
		$this->incident_id->EditCustomAttributes = "";
		$this->incident_id->EditValue = $this->incident_id->CurrentValue;
		$this->incident_id->PlaceHolder = ew_RemoveHtml($this->incident_id->FldCaption());

		// staffid
		$this->staffid->EditAttrs["class"] = "form-control";
		$this->staffid->EditCustomAttributes = "";
		$this->staffid->EditValue = $this->staffid->CurrentValue;
		$this->staffid->PlaceHolder = ew_RemoveHtml($this->staffid->FldCaption());

		// staff_id
		$this->staff_id->EditAttrs["class"] = "form-control";
		$this->staff_id->EditCustomAttributes = "";
		$this->staff_id->EditValue = $this->staff_id->CurrentValue;
		$this->staff_id->PlaceHolder = ew_RemoveHtml($this->staff_id->FldCaption());

		// department
		$this->department->EditCustomAttributes = "";

		// branch
		$this->branch->EditCustomAttributes = "";

		// responsible_departments
		$this->responsible_departments->EditAttrs["class"] = "form-control";
		$this->responsible_departments->EditCustomAttributes = "";

		// category
		$this->category->EditAttrs["class"] = "form-control";
		$this->category->EditCustomAttributes = "";

		// sub_category
		$this->sub_category->EditCustomAttributes = "";

		// sub_sub_category
		$this->sub_sub_category->EditCustomAttributes = "";

		// selection_sub_category
		$this->selection_sub_category->EditCustomAttributes = "";

		// start_date
		$this->start_date->EditAttrs["class"] = "form-control";
		$this->start_date->EditCustomAttributes = "";
		$this->start_date->EditValue = ew_FormatDateTime($this->start_date->CurrentValue, 7);
		$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

		// end_date
		$this->end_date->EditAttrs["class"] = "form-control";
		$this->end_date->EditCustomAttributes = "";
		$this->end_date->EditValue = ew_FormatDateTime($this->end_date->CurrentValue, 7);
		$this->end_date->PlaceHolder = ew_RemoveHtml($this->end_date->FldCaption());

		// duration
		$this->duration->EditAttrs["class"] = "form-control";
		$this->duration->EditCustomAttributes = "";
		$this->duration->EditValue = $this->duration->CurrentValue;
		$this->duration->PlaceHolder = ew_RemoveHtml($this->duration->FldCaption());

		// amount_paid
		$this->amount_paid->EditAttrs["class"] = "form-control";
		$this->amount_paid->EditCustomAttributes = "";
		$this->amount_paid->EditValue = $this->amount_paid->CurrentValue;
		$this->amount_paid->PlaceHolder = ew_RemoveHtml($this->amount_paid->FldCaption());
		if (strval($this->amount_paid->EditValue) <> "" && is_numeric($this->amount_paid->EditValue)) $this->amount_paid->EditValue = ew_FormatNumber($this->amount_paid->EditValue, -2, -2, -2, -2);

		// no_of_people_involved
		$this->no_of_people_involved->EditAttrs["class"] = "form-control";
		$this->no_of_people_involved->EditCustomAttributes = "";
		$this->no_of_people_involved->EditValue = $this->no_of_people_involved->CurrentValue;
		$this->no_of_people_involved->PlaceHolder = ew_RemoveHtml($this->no_of_people_involved->FldCaption());

		// incident_type
		$this->incident_type->EditCustomAttributes = "";

		// incident-category
		$this->incident_category->EditCustomAttributes = "";

		// incident_location
		$this->incident_location->EditCustomAttributes = "";

		// incident_sub_location
		$this->incident_sub_location->EditAttrs["class"] = "form-control";
		$this->incident_sub_location->EditCustomAttributes = "";

		// incident_venue
		$this->incident_venue->EditAttrs["class"] = "form-control";
		$this->incident_venue->EditCustomAttributes = "";

		// incident_description
		$this->incident_description->EditAttrs["class"] = "form-control";
		$this->incident_description->EditCustomAttributes = "";
		$this->incident_description->EditValue = $this->incident_description->CurrentValue;
		$this->incident_description->PlaceHolder = ew_RemoveHtml($this->incident_description->FldCaption());

		// upload
		$this->_upload->EditAttrs["class"] = "form-control";
		$this->_upload->EditCustomAttributes = "";
		$this->_upload->UploadPath = "picture/";
		if (!ew_Empty($this->_upload->Upload->DbValue)) {
			$this->_upload->ImageAlt = $this->_upload->FldAlt();
			$this->_upload->EditValue = $this->_upload->Upload->DbValue;
		} else {
			$this->_upload->EditValue = "";
		}
		if (!ew_Empty($this->_upload->CurrentValue))
				$this->_upload->Upload->FileName = $this->_upload->CurrentValue;

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";

		// initiator_action
		$this->initiator_action->EditCustomAttributes = "";
		$this->initiator_action->EditValue = $this->initiator_action->Options(FALSE);

		// initiator_comment
		$this->initiator_comment->EditAttrs["class"] = "form-control";
		$this->initiator_comment->EditCustomAttributes = "";
		$this->initiator_comment->EditValue = $this->initiator_comment->CurrentValue;
		$this->initiator_comment->PlaceHolder = ew_RemoveHtml($this->initiator_comment->FldCaption());

		// report_by
		$this->report_by->EditAttrs["class"] = "form-control";
		$this->report_by->EditCustomAttributes = "";
		$this->report_by->EditValue = $this->report_by->CurrentValue;
		$this->report_by->PlaceHolder = ew_RemoveHtml($this->report_by->FldCaption());

		// datetime_resolved
		$this->datetime_resolved->EditAttrs["class"] = "form-control";
		$this->datetime_resolved->EditCustomAttributes = "";
		$this->datetime_resolved->EditValue = ew_FormatDateTime($this->datetime_resolved->CurrentValue, 11);
		$this->datetime_resolved->PlaceHolder = ew_RemoveHtml($this->datetime_resolved->FldCaption());

		// assign_task
		$this->assign_task->EditAttrs["class"] = "form-control";
		$this->assign_task->EditCustomAttributes = "";

		// approval_action
		$this->approval_action->EditCustomAttributes = "";
		$this->approval_action->EditValue = $this->approval_action->Options(FALSE);

		// approval_comment
		$this->approval_comment->EditAttrs["class"] = "form-control";
		$this->approval_comment->EditCustomAttributes = "";
		$this->approval_comment->EditValue = $this->approval_comment->CurrentValue;
		$this->approval_comment->PlaceHolder = ew_RemoveHtml($this->approval_comment->FldCaption());

		// resolved_action
		$this->resolved_action->EditCustomAttributes = "";
		$this->resolved_action->EditValue = $this->resolved_action->Options(FALSE);

		// resolved_comment
		$this->resolved_comment->EditAttrs["class"] = "form-control";
		$this->resolved_comment->EditCustomAttributes = "";
		$this->resolved_comment->EditValue = $this->resolved_comment->CurrentValue;
		$this->resolved_comment->PlaceHolder = ew_RemoveHtml($this->resolved_comment->FldCaption());

		// resolved_by
		$this->resolved_by->EditAttrs["class"] = "form-control";
		$this->resolved_by->EditCustomAttributes = "";
		$this->resolved_by->EditValue = $this->resolved_by->CurrentValue;
		$this->resolved_by->PlaceHolder = ew_RemoveHtml($this->resolved_by->FldCaption());

		// datetime_approved
		$this->datetime_approved->EditAttrs["class"] = "form-control";
		$this->datetime_approved->EditCustomAttributes = "";
		$this->datetime_approved->EditValue = ew_FormatDateTime($this->datetime_approved->CurrentValue, 11);
		$this->datetime_approved->PlaceHolder = ew_RemoveHtml($this->datetime_approved->FldCaption());

		// approved_by
		$this->approved_by->EditAttrs["class"] = "form-control";
		$this->approved_by->EditCustomAttributes = "";
		$this->approved_by->EditValue = $this->approved_by->CurrentValue;
		$this->approved_by->PlaceHolder = ew_RemoveHtml($this->approved_by->FldCaption());

		// last_updated_date
		$this->last_updated_date->EditAttrs["class"] = "form-control";
		$this->last_updated_date->EditCustomAttributes = "";
		$this->last_updated_date->EditValue = ew_FormatDateTime($this->last_updated_date->CurrentValue, 17);
		$this->last_updated_date->PlaceHolder = ew_RemoveHtml($this->last_updated_date->FldCaption());

		// last_updated_by
		$this->last_updated_by->EditAttrs["class"] = "form-control";
		$this->last_updated_by->EditCustomAttributes = "";
		$this->last_updated_by->EditValue = $this->last_updated_by->CurrentValue;
		$this->last_updated_by->PlaceHolder = ew_RemoveHtml($this->last_updated_by->FldCaption());

		// verified_datetime
		$this->verified_datetime->EditAttrs["class"] = "form-control";
		$this->verified_datetime->EditCustomAttributes = "";
		$this->verified_datetime->EditValue = ew_FormatDateTime($this->verified_datetime->CurrentValue, 17);
		$this->verified_datetime->PlaceHolder = ew_RemoveHtml($this->verified_datetime->FldCaption());

		// job_assessment
		$this->job_assessment->EditCustomAttributes = "";
		$this->job_assessment->EditValue = $this->job_assessment->Options(FALSE);

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
					if ($this->datetime_initiated->Exportable) $Doc->ExportCaption($this->datetime_initiated);
					if ($this->incident_id->Exportable) $Doc->ExportCaption($this->incident_id);
					if ($this->staffid->Exportable) $Doc->ExportCaption($this->staffid);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->responsible_departments->Exportable) $Doc->ExportCaption($this->responsible_departments);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->sub_category->Exportable) $Doc->ExportCaption($this->sub_category);
					if ($this->sub_sub_category->Exportable) $Doc->ExportCaption($this->sub_sub_category);
					if ($this->selection_sub_category->Exportable) $Doc->ExportCaption($this->selection_sub_category);
					if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
					if ($this->end_date->Exportable) $Doc->ExportCaption($this->end_date);
					if ($this->duration->Exportable) $Doc->ExportCaption($this->duration);
					if ($this->amount_paid->Exportable) $Doc->ExportCaption($this->amount_paid);
					if ($this->no_of_people_involved->Exportable) $Doc->ExportCaption($this->no_of_people_involved);
					if ($this->incident_type->Exportable) $Doc->ExportCaption($this->incident_type);
					if ($this->incident_category->Exportable) $Doc->ExportCaption($this->incident_category);
					if ($this->incident_location->Exportable) $Doc->ExportCaption($this->incident_location);
					if ($this->incident_sub_location->Exportable) $Doc->ExportCaption($this->incident_sub_location);
					if ($this->incident_venue->Exportable) $Doc->ExportCaption($this->incident_venue);
					if ($this->incident_description->Exportable) $Doc->ExportCaption($this->incident_description);
					if ($this->_upload->Exportable) $Doc->ExportCaption($this->_upload);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->initiator_comment->Exportable) $Doc->ExportCaption($this->initiator_comment);
					if ($this->report_by->Exportable) $Doc->ExportCaption($this->report_by);
					if ($this->datetime_resolved->Exportable) $Doc->ExportCaption($this->datetime_resolved);
					if ($this->assign_task->Exportable) $Doc->ExportCaption($this->assign_task);
					if ($this->approval_action->Exportable) $Doc->ExportCaption($this->approval_action);
					if ($this->approval_comment->Exportable) $Doc->ExportCaption($this->approval_comment);
					if ($this->resolved_action->Exportable) $Doc->ExportCaption($this->resolved_action);
					if ($this->resolved_comment->Exportable) $Doc->ExportCaption($this->resolved_comment);
					if ($this->resolved_by->Exportable) $Doc->ExportCaption($this->resolved_by);
					if ($this->datetime_approved->Exportable) $Doc->ExportCaption($this->datetime_approved);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->last_updated_date->Exportable) $Doc->ExportCaption($this->last_updated_date);
					if ($this->last_updated_by->Exportable) $Doc->ExportCaption($this->last_updated_by);
					if ($this->verified_datetime->Exportable) $Doc->ExportCaption($this->verified_datetime);
					if ($this->job_assessment->Exportable) $Doc->ExportCaption($this->job_assessment);
					if ($this->verified_action->Exportable) $Doc->ExportCaption($this->verified_action);
					if ($this->verified_comment->Exportable) $Doc->ExportCaption($this->verified_comment);
					if ($this->verified_by->Exportable) $Doc->ExportCaption($this->verified_by);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->datetime_initiated->Exportable) $Doc->ExportCaption($this->datetime_initiated);
					if ($this->incident_id->Exportable) $Doc->ExportCaption($this->incident_id);
					if ($this->staffid->Exportable) $Doc->ExportCaption($this->staffid);
					if ($this->staff_id->Exportable) $Doc->ExportCaption($this->staff_id);
					if ($this->department->Exportable) $Doc->ExportCaption($this->department);
					if ($this->branch->Exportable) $Doc->ExportCaption($this->branch);
					if ($this->responsible_departments->Exportable) $Doc->ExportCaption($this->responsible_departments);
					if ($this->category->Exportable) $Doc->ExportCaption($this->category);
					if ($this->sub_category->Exportable) $Doc->ExportCaption($this->sub_category);
					if ($this->sub_sub_category->Exportable) $Doc->ExportCaption($this->sub_sub_category);
					if ($this->selection_sub_category->Exportable) $Doc->ExportCaption($this->selection_sub_category);
					if ($this->start_date->Exportable) $Doc->ExportCaption($this->start_date);
					if ($this->end_date->Exportable) $Doc->ExportCaption($this->end_date);
					if ($this->duration->Exportable) $Doc->ExportCaption($this->duration);
					if ($this->amount_paid->Exportable) $Doc->ExportCaption($this->amount_paid);
					if ($this->no_of_people_involved->Exportable) $Doc->ExportCaption($this->no_of_people_involved);
					if ($this->incident_type->Exportable) $Doc->ExportCaption($this->incident_type);
					if ($this->incident_category->Exportable) $Doc->ExportCaption($this->incident_category);
					if ($this->incident_location->Exportable) $Doc->ExportCaption($this->incident_location);
					if ($this->incident_sub_location->Exportable) $Doc->ExportCaption($this->incident_sub_location);
					if ($this->incident_venue->Exportable) $Doc->ExportCaption($this->incident_venue);
					if ($this->incident_description->Exportable) $Doc->ExportCaption($this->incident_description);
					if ($this->_upload->Exportable) $Doc->ExportCaption($this->_upload);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->initiator_action->Exportable) $Doc->ExportCaption($this->initiator_action);
					if ($this->report_by->Exportable) $Doc->ExportCaption($this->report_by);
					if ($this->datetime_resolved->Exportable) $Doc->ExportCaption($this->datetime_resolved);
					if ($this->assign_task->Exportable) $Doc->ExportCaption($this->assign_task);
					if ($this->approval_action->Exportable) $Doc->ExportCaption($this->approval_action);
					if ($this->resolved_action->Exportable) $Doc->ExportCaption($this->resolved_action);
					if ($this->resolved_by->Exportable) $Doc->ExportCaption($this->resolved_by);
					if ($this->datetime_approved->Exportable) $Doc->ExportCaption($this->datetime_approved);
					if ($this->approved_by->Exportable) $Doc->ExportCaption($this->approved_by);
					if ($this->last_updated_date->Exportable) $Doc->ExportCaption($this->last_updated_date);
					if ($this->last_updated_by->Exportable) $Doc->ExportCaption($this->last_updated_by);
					if ($this->verified_datetime->Exportable) $Doc->ExportCaption($this->verified_datetime);
					if ($this->job_assessment->Exportable) $Doc->ExportCaption($this->job_assessment);
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
						if ($this->datetime_initiated->Exportable) $Doc->ExportField($this->datetime_initiated);
						if ($this->incident_id->Exportable) $Doc->ExportField($this->incident_id);
						if ($this->staffid->Exportable) $Doc->ExportField($this->staffid);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->responsible_departments->Exportable) $Doc->ExportField($this->responsible_departments);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->sub_category->Exportable) $Doc->ExportField($this->sub_category);
						if ($this->sub_sub_category->Exportable) $Doc->ExportField($this->sub_sub_category);
						if ($this->selection_sub_category->Exportable) $Doc->ExportField($this->selection_sub_category);
						if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
						if ($this->end_date->Exportable) $Doc->ExportField($this->end_date);
						if ($this->duration->Exportable) $Doc->ExportField($this->duration);
						if ($this->amount_paid->Exportable) $Doc->ExportField($this->amount_paid);
						if ($this->no_of_people_involved->Exportable) $Doc->ExportField($this->no_of_people_involved);
						if ($this->incident_type->Exportable) $Doc->ExportField($this->incident_type);
						if ($this->incident_category->Exportable) $Doc->ExportField($this->incident_category);
						if ($this->incident_location->Exportable) $Doc->ExportField($this->incident_location);
						if ($this->incident_sub_location->Exportable) $Doc->ExportField($this->incident_sub_location);
						if ($this->incident_venue->Exportable) $Doc->ExportField($this->incident_venue);
						if ($this->incident_description->Exportable) $Doc->ExportField($this->incident_description);
						if ($this->_upload->Exportable) $Doc->ExportField($this->_upload);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->initiator_comment->Exportable) $Doc->ExportField($this->initiator_comment);
						if ($this->report_by->Exportable) $Doc->ExportField($this->report_by);
						if ($this->datetime_resolved->Exportable) $Doc->ExportField($this->datetime_resolved);
						if ($this->assign_task->Exportable) $Doc->ExportField($this->assign_task);
						if ($this->approval_action->Exportable) $Doc->ExportField($this->approval_action);
						if ($this->approval_comment->Exportable) $Doc->ExportField($this->approval_comment);
						if ($this->resolved_action->Exportable) $Doc->ExportField($this->resolved_action);
						if ($this->resolved_comment->Exportable) $Doc->ExportField($this->resolved_comment);
						if ($this->resolved_by->Exportable) $Doc->ExportField($this->resolved_by);
						if ($this->datetime_approved->Exportable) $Doc->ExportField($this->datetime_approved);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->last_updated_date->Exportable) $Doc->ExportField($this->last_updated_date);
						if ($this->last_updated_by->Exportable) $Doc->ExportField($this->last_updated_by);
						if ($this->verified_datetime->Exportable) $Doc->ExportField($this->verified_datetime);
						if ($this->job_assessment->Exportable) $Doc->ExportField($this->job_assessment);
						if ($this->verified_action->Exportable) $Doc->ExportField($this->verified_action);
						if ($this->verified_comment->Exportable) $Doc->ExportField($this->verified_comment);
						if ($this->verified_by->Exportable) $Doc->ExportField($this->verified_by);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->datetime_initiated->Exportable) $Doc->ExportField($this->datetime_initiated);
						if ($this->incident_id->Exportable) $Doc->ExportField($this->incident_id);
						if ($this->staffid->Exportable) $Doc->ExportField($this->staffid);
						if ($this->staff_id->Exportable) $Doc->ExportField($this->staff_id);
						if ($this->department->Exportable) $Doc->ExportField($this->department);
						if ($this->branch->Exportable) $Doc->ExportField($this->branch);
						if ($this->responsible_departments->Exportable) $Doc->ExportField($this->responsible_departments);
						if ($this->category->Exportable) $Doc->ExportField($this->category);
						if ($this->sub_category->Exportable) $Doc->ExportField($this->sub_category);
						if ($this->sub_sub_category->Exportable) $Doc->ExportField($this->sub_sub_category);
						if ($this->selection_sub_category->Exportable) $Doc->ExportField($this->selection_sub_category);
						if ($this->start_date->Exportable) $Doc->ExportField($this->start_date);
						if ($this->end_date->Exportable) $Doc->ExportField($this->end_date);
						if ($this->duration->Exportable) $Doc->ExportField($this->duration);
						if ($this->amount_paid->Exportable) $Doc->ExportField($this->amount_paid);
						if ($this->no_of_people_involved->Exportable) $Doc->ExportField($this->no_of_people_involved);
						if ($this->incident_type->Exportable) $Doc->ExportField($this->incident_type);
						if ($this->incident_category->Exportable) $Doc->ExportField($this->incident_category);
						if ($this->incident_location->Exportable) $Doc->ExportField($this->incident_location);
						if ($this->incident_sub_location->Exportable) $Doc->ExportField($this->incident_sub_location);
						if ($this->incident_venue->Exportable) $Doc->ExportField($this->incident_venue);
						if ($this->incident_description->Exportable) $Doc->ExportField($this->incident_description);
						if ($this->_upload->Exportable) $Doc->ExportField($this->_upload);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->initiator_action->Exportable) $Doc->ExportField($this->initiator_action);
						if ($this->report_by->Exportable) $Doc->ExportField($this->report_by);
						if ($this->datetime_resolved->Exportable) $Doc->ExportField($this->datetime_resolved);
						if ($this->assign_task->Exportable) $Doc->ExportField($this->assign_task);
						if ($this->approval_action->Exportable) $Doc->ExportField($this->approval_action);
						if ($this->resolved_action->Exportable) $Doc->ExportField($this->resolved_action);
						if ($this->resolved_by->Exportable) $Doc->ExportField($this->resolved_by);
						if ($this->datetime_approved->Exportable) $Doc->ExportField($this->datetime_approved);
						if ($this->approved_by->Exportable) $Doc->ExportField($this->approved_by);
						if ($this->last_updated_date->Exportable) $Doc->ExportField($this->last_updated_date);
						if ($this->last_updated_by->Exportable) $Doc->ExportField($this->last_updated_by);
						if ($this->verified_datetime->Exportable) $Doc->ExportField($this->verified_datetime);
						if ($this->job_assessment->Exportable) $Doc->ExportField($this->job_assessment);
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
				$this->id->CellCssStyle = "color: orange; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: orange; text-align: left;";
				$this->staff_id->CellCssStyle = "color: orange; text-align: left;";
				$this->staffid->CellCssStyle = "color: orange; text-align: left;";
				$this->report_by->CellCssStyle = "color: orange; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: orange; text-align: left;";
				$this->department->CellCssStyle = "color: orange; text-align: left;";
				$this->branch->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_id->CellCssStyle = "color: orange; text-align: left;";
				$this->category->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_type->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_location->CellCssStyle = "color: orange; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_category->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_description->CellCssStyle = "color: orange; text-align: left;";
				$this->sub_category->CellCssStyle = "color: orange; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: red; text-align: left;";
				$this->status->CellCssStyle = "color: orange; text-align: left;";
				$this->start_date->CellCssStyle = "color: orange; text-align: left;";
				$this->end_date->CellCssStyle = "color: orange; text-align: left;";
				$this->duration->CellCssStyle = "color: orange; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: orange; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: orange; text-align: left;";
				$this->assign_task->CellCssStyle = "color: orange; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: orange; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: orange; text-align: left;";
			}
			if ($this->status->CurrentValue == 2) {
				$this->id->CellCssStyle = "color: red; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: red; text-align: left;";
				$this->staff_id->CellCssStyle = "color: red; text-align: left;";
				$this->staffid->CellCssStyle = "color: red; text-align: left;";
				$this->report_by->CellCssStyle = "color: red; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: red; text-align: left;";
				$this->department->CellCssStyle = "color: red; text-align: left;";
				$this->branch->CellCssStyle = "color: red; text-align: left;";
				$this->incident_id->CellCssStyle = "color: red; text-align: left;";
				$this->incident_type->CellCssStyle = "color: red; text-align: left;";
				$this->incident_location->CellCssStyle = "color: red; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: red; text-align: left;";
				$this->category->CellCssStyle = "color: red; text-align: left;";
				$this->incident_category->CellCssStyle = "color: red; text-align: left;";
				$this->incident_description->CellCssStyle = "color: red; text-align: left;";
				$this->sub_category->CellCssStyle = "color: red; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: red; text-align: left;";
				$this->status->CellCssStyle = "color: red; text-align: left;";
				$this->start_date->CellCssStyle = "color: red; text-align: left;";
				$this->end_date->CellCssStyle = "color: red; text-align: left;";
				$this->duration->CellCssStyle = "color: red; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: red; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: red; text-align: left;";
				$this->assign_task->CellCssStyle = "color: red; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: red; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: red; text-align: left;";
			}
			if ($this->status->CurrentValue == 3) {
				$this->id->CellCssStyle = "color: blue; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: blue; text-align: left;";
				$this->staff_id->CellCssStyle = "color: blue; text-align: left;";
				$this->staffid->CellCssStyle = "color: blue; text-align: left;";
				$this->report_by->CellCssStyle = "color: blue; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: blue; text-align: left;";
				$this->department->CellCssStyle = "color: blue; text-align: left;";
				$this->branch->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_id->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_type->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_location->CellCssStyle = "color: blue; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: blue; text-align: left;";
				$this->category->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_category->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_description->CellCssStyle = "color: blue; text-align: left;";
				$this->sub_category->CellCssStyle = "color: blue; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: blue; text-align: left;";
				$this->status->CellCssStyle = "color: blue; text-align: left;";
				$this->start_date->CellCssStyle = "color: blue; text-align: left;";
				$this->end_date->CellCssStyle = "color: blue; text-align: left;";
				$this->duration->CellCssStyle = "color: blue; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: blue; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: blue; text-align: left;";
				$this->assign_task->CellCssStyle = "color: blue; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: blue; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: blue; text-align: left;";
			}
			if ($this->status->CurrentValue == 0) {
				$this->id->CellCssStyle = "color: teal; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: teal; text-align: left;";
				$this->staff_id->CellCssStyle = "color: teal; text-align: left;";
				$this->staffid->CellCssStyle = "color: teal; text-align: left;";
				$this->report_by->CellCssStyle = "color: teal; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: teal; text-align: left;";
				$this->department->CellCssStyle = "color: teal; text-align: left;";
				$this->branch->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_id->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_type->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_location->CellCssStyle = "color: teal; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: teal; text-align: left;";
				$this->category->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_category->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_description->CellCssStyle = "color: teal; text-align: left;";
				$this->sub_category->CellCssStyle = "color: teal; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: teal; text-align: left;";
				$this->status->CellCssStyle = "color: teal; text-align: left;";
				$this->start_date->CellCssStyle = "color: teal; text-align: left;";
				$this->end_date->CellCssStyle = "color: teal; text-align: left;";
				$this->duration->CellCssStyle = "color: teal; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: teal; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: teal; text-align: left;";
				$this->assign_task->CellCssStyle = "color: teal; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: teal; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: teal; text-align: left;";
			}
			if ($this->status->CurrentValue == 4) {
				$this->id->CellCssStyle = "color: purple; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: purple; text-align: left;";
				$this->staff_id->CellCssStyle = "color: purple; text-align: left;";
				$this->staffid->CellCssStyle = "color: purple; text-align: left;";
				$this->report_by->CellCssStyle = "color: purple; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: purple; text-align: left;";
				$this->department->CellCssStyle = "color: purple; text-align: left;";
				$this->branch->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_id->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_type->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_location->CellCssStyle = "color: purple; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: purple; text-align: left;";
				$this->category->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_description->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_category->CellCssStyle = "color: purple; text-align: left;";
				$this->sub_category->CellCssStyle = "color: purple; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: purple; text-align: left;";
				$this->status->CellCssStyle = "color: purple; text-align: left;";
				$this->start_date->CellCssStyle = "color: purple; text-align: left;";
				$this->end_date->CellCssStyle = "color: purple; text-align: left;";
				$this->duration->CellCssStyle = "color: purple; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: purple; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: purple; text-align: left;";
				$this->assign_task->CellCssStyle = "color: purple; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: purple; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: purple; text-align: left;";
			}
				if ($this->status->CurrentValue == 6) {
				$this->id->CellCssStyle = "color: coral; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: coral; text-align: left;";
				$this->staff_id->CellCssStyle = "color: coral; text-align: left;";
				$this->staffid->CellCssStyle = "color: coral; text-align: left;";
				$this->report_by->CellCssStyle = "color: coral; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: coral; text-align: left;";
				$this->department->CellCssStyle = "color: coral; text-align: left;";
				$this->branch->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_id->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_type->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_location->CellCssStyle = "color: coral; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: coral; text-align: left;";
				$this->category->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_description->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_category->CellCssStyle = "color: coral; text-align: left;";
				$this->sub_category->CellCssStyle = "color: coral; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: coral; text-align: left;";
				$this->status->CellCssStyle = "color: coral; text-align: left;";
				$this->start_date->CellCssStyle = "color: coral; text-align: left;";
				$this->end_date->CellCssStyle = "color: coral; text-align: left;";
				$this->duration->CellCssStyle = "color: coral; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: coral; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: coral; text-align: left;";
				$this->assign_task->CellCssStyle = "color: coral; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: coral; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: coral; text-align: left;";
			}
			if ($this->status->CurrentValue == 5) {
				$this->id->CellCssStyle = "color: green; text-align: left;";
				$this->datetime_initiated->CellCssStyle = "color: green; text-align: left;";
				$this->staff_id->CellCssStyle = "color: green; text-align: left;";
				$this->staffid->CellCssStyle = "color: green; text-align: left;";
				$this->report_by->CellCssStyle = "color: green; text-align: left;";
				$this->responsible_departments->CellCssStyle = "color: green; text-align: left;";
				$this->department->CellCssStyle = "color: green; text-align: left;";
				$this->branch->CellCssStyle = "color: green; text-align: left;";
				$this->incident_id->CellCssStyle = "color: green; text-align: left;";
				$this->incident_type->CellCssStyle = "color: green; text-align: left;";
				$this->incident_location->CellCssStyle = "color: green; text-align: left;";
				$this->no_of_people_involved->CellCssStyle = "color: green; text-align: left;";
				$this->category->CellCssStyle = "color: green; text-align: left;";
				$this->incident_category->CellCssStyle = "color: green; text-align: left;";
				$this->incident_description->CellCssStyle = "color: green; text-align: left;";
				$this->sub_category->CellCssStyle = "color: green; text-align: left;";
				$this->sub_sub_category->CellCssStyle = "color: green; text-align: left;";
				$this->status->CellCssStyle = "color: green; text-align: left;";
				$this->start_date->CellCssStyle = "color: green; text-align: left;";
				$this->end_date->CellCssStyle = "color: green; text-align: left;";
				$this->duration->CellCssStyle = "color: green; text-align: left;";
				$this->amount_paid->CellCssStyle = "color: green; text-align: left;";
				$this->incident_sub_location->CellCssStyle = "color: green; text-align: left;";
				$this->assign_task->CellCssStyle = "color: green; text-align: left;";
				$this->last_updated_date->CellCssStyle = "color: green; text-align: left;";
				$this->last_updated_by->CellCssStyle = "color: green; text-align: left;";
				$this->job_assessment->CellCssStyle = "color: green; text-align: left;";
			}
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
