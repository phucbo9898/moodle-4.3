<?php
/**
 * @package local_message
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG, $PAGE, $OUTPUT, $DB;

require_once(__DIR__ . '/../../config.php');
$keyword = $_GET['keyword'] ?? '';
$PAGE->set_url(new moodle_url('/local/message/manage.php?keyword=' . $keyword));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage messages');
$sql = "SELECT * FROM {local_message} lm
        WHERE lm.messagetext LIKE '%$keyword%'";
$parramSearch = [
    'keyword' => $keyword
];
//$getRecord = $DB->get_records('local_message');
$getRecord = $DB->get_records_sql($sql, $parramSearch);
var_dump($getRecord);die();
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
if ($_GET['id'] != null && $_GET['isDelete'] != null) {
    if ($_GET['isDelete'] == 'true') {
        $id = $_GET['id'];
        $DB->delete_records('local_message_read', ['messageid' => $_GET['id']]);
        $DB->delete_records('local_message', ['id' => $_GET['id']]);
        redirect($CFG->wwwroot . '/local/message/manage.php', "Delete message success with id is $id", '', \core\output\notification::NOTIFY_SUCCESS);
    }
}
echo $OUTPUT->header();
$templatecontext = (object)[
    'lstMessage' => array_values($getRecord),
    'urlAdd' => $urlAdd,
    'urlEdit' => $urlEdit,
    'urlCurrent' =>$urlCurrent,
    'isLogin' => isloggedin() ? true : false
];
echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

echo $OUTPUT->footer();

