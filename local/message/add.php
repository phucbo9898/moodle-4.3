<?php
/**
 * @package local_message
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $PAGE, $OUTPUT, $DB, $CFG;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/message/classes/form/add.php');

$PAGE->set_url(new moodle_url('/local/message/add.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage messages add');

// Display form.
$mform = new message_add();

echo $OUTPUT->header();

if ($mform->is_cancelled()) {
    // Go back to manage.php page
    redirect($CFG->wwwroot . '/local/message/manage.php', '', \core\output\notification::NOTIFY_INFO);
} else if ($fromform = $mform->get_data()) {
    // Insert data to database table.
    $insertData = new stdClass();
    $insertData->messagetext = $fromform->messagetext;
    $insertData->messagetype = $fromform->messagetype;

//    $insertDataRead = new stdClass();
//    $insertDataRead->
    $DB->insert_record('local_message', $insertData);
    redirect($CFG->wwwroot . '/local/message/manage.php', "Insert message success with title $fromform->messagetext", '', \core\output\notification::NOTIFY_SUCCESS);
}
$mform->display();
echo $OUTPUT->footer();
