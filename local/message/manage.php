<?php
/**
 * @package local_message
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG, $PAGE, $OUTPUT, $DB;

require_once(__DIR__ . '/../../config.php');
$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage messages');

$keyword = $_GET['keyword'] ?? '';
$typeMsg = $_GET['typeMsg'] ?? '';
$getTypeMsg = $DB->get_records('local_message', [], '', 'messagetype');
$sql = "SELECT * FROM {local_message} lm ";

// Write sql to search by keyword and type msg
if (!empty($_GET)) {
    if (!empty($keyword) && empty($typeMsg)) {
        $sql .= "where messagetext like '%$keyword%'";
    } else if (empty($keyword) && !empty($typeMsg)) {
        $sql .= "where messagetype = $typeMsg";
    } else if (!empty($keyword) && !empty($typeMsg)) {
        $sql .= "where messagetext like '%$keyword%' and messagetype = $typeMsg";
    }
}

$getRecord = $DB->get_records_sql($sql, null);
$urlAdd = new moodle_url('/local/message/add.php');
$urlEdit = new moodle_url('/local/message/edit.php');
$urlCurrent = new moodle_url('/local/message/manage.php');
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/message/amd/src/search-input.js'), true);
foreach (array_values($getRecord) as $key => $message) {
    switch ($message->messagetype) {
        case '1':
            $message->messagetype = \core\output\notification::NOTIFY_INFO;
            $message->classcolor = 'btn btn-info';
            break;
        case '2':
            $message->messagetype = \core\output\notification::NOTIFY_ERROR;
            $message->classcolor = 'btn btn-danger';
            break;
        case '3':
            $message->messagetype = \core\output\notification::NOTIFY_SUCCESS;
            $message->classcolor = 'btn btn-success';
            break;
        default:
            $message->messagetype = \core\output\notification::NOTIFY_WARNING;
            $message->classcolor = 'btn btn-warning';
    }
}
foreach (array_values($getTypeMsg) as $type) {
    switch ($type->messagetype) {
        case '1':
            $type->typename = \core\output\notification::NOTIFY_INFO;
            break;
        case '2':
            $type->typename = \core\output\notification::NOTIFY_ERROR;
            break;
        case '3':
            $type->typename = \core\output\notification::NOTIFY_SUCCESS;
            break;
        default:
            $type->typename = \core\output\notification::NOTIFY_WARNING;
    }
    if ($type->messagetype == $typeMsg) {
        $type->isSelected = 'selected';
    } else {
        $type->isSelected = '';
    }
}
if (!empty($_GET)) {
    if (in_array('id', $_GET) && in_array('isDelete', $_GET) && $_GET['id'] != null && $_GET['isDelete'] != null) {
        if ($_GET['isDelete'] == 'true') {
            $id = $_GET['id'];
            $DB->delete_records('local_message_read', ['messageid' => $_GET['id']]);
            $DB->delete_records('local_message', ['id' => $_GET['id']]);
            redirect($CFG->wwwroot . '/local/message/manage.php', "Delete message success with id is $id", '', \core\output\notification::NOTIFY_SUCCESS);
        }
    }
}
echo $OUTPUT->header();
$templatecontext = (object)[
    'lstMessage' => array_values($getRecord),
    'getTypeMsg' => array_values($getTypeMsg),
    'urlAdd' => $urlAdd,
    'urlEdit' => $urlEdit,
    'urlCurrent' =>$urlCurrent,
    'keyword' => $keyword,
    'typeMsg' => $typeMsg,
    'isLogin' => isloggedin() ? true : false
];
echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

echo $OUTPUT->footer();

