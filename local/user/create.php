<?php
/**
 * @package local_user
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/user/classes/form/add.php');
global $PAGE, $OUTPUT, $DB;
$PAGE->set_url(new moodle_url('/local/user/create.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Create new User');
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/user/amd/src/setTimeOut.js'), true);
$mform = new user_add();

echo $OUTPUT->header();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/user/manage.php');
} else if ($fromData = $mform->get_data()) {
    $sql = "SELECT lu.id FROM {local_user} lu
            where lu.username = :username";
    $parrams = [
        'username' => $fromData->username,
    ];
    $checkUnique = $DB->get_records_sql($sql, $parrams);
    if (!empty($checkUnique)) {
        \core\notification::add('Already exists username is ' . $fromData->username . '. Please check again', \core\output\notification::NOTIFY_ERROR);
    }
    $fileName = $mform->get_new_filename('avatar');
    $createUser = new stdClass();
    $createUser->username = $fromData->username;
    $createUser->firstname = $fromData->firstname;
    $createUser->lastname = $fromData->lastname;
    $createUser->nickname = $fromData->nickname;
    $createUser->gender = $fromData->gender;
    $createUser->password = hash_internal_user_password($fromData->password);
    if ($fileName) {
        $fullPath = 'upload/' . time() . '_' . $fileName;
        $uploadSuccess = $mform->save_file('avatar', $fullPath, true);
        $createUser->avatar = $fullPath;
    }
    $createUser->created_at = time();
    $createUser->updated_at = time();

    $DB->insert_record('local_user', $createUser);
    redirect($CFG->wwwroot . '/local/user/manage.php', 'Create user success', '', \core\output\notification::NOTIFY_SUCCESS);
}

$mform->display();

echo $OUTPUT->footer();