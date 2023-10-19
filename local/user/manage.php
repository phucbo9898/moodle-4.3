<?php
/**
 * @package local_user
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

global $PAGE, $OUTPUT, $DB, $USER;
$PAGE->set_url(new moodle_url('/local/user/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage User');
$urlCreateUser = new moodle_url('/local/user/create.php');
$urlEditUser = new moodle_url('/local/user/edit.php');

$lstUser = $DB->get_records('local_user');
foreach (array_values($lstUser) as $user) {
    switch ($user->gender) {
        case '1':
            $user->gender = 'Male';
            break;
        case '2':
            $user->gender = 'Female';
            break;
        default:
            $user->gender = 'Other';
    }
    $user->checkSrc = $user->avatar == '' ? 'hidden' : '';
}
$templatecontext = (object)[
    'lstUser' => array_values($lstUser),
    'urlCreateUser' => $urlCreateUser,
    'urlEditUser' => $urlEditUser,
    'isLogin' => isloggedin() ? TRUE : FALSE
];
if ($_GET['id'] != null && $_GET['isDelete'] != null) {
    var_dump(11111);die();
    if ($_GET['isDelete'] == 'true') {
        $id = $_GET['id'];
        $DB->delete_records('local_user', ['id' => $_GET['id']]);
        redirect($CFG->wwwroot . '/local/user/manage.php', "Delete user success with id is $id", '', \core\output\notification::NOTIFY_SUCCESS);
    }
}
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_user/manageuser', $templatecontext);
echo $OUTPUT->footer();