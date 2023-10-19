<?php
/**
 * @package local_message
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $PAGE, $OUTPUT, $DB;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/message/classes/form/edit.php');

$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage messages edit');

// Display form.
$mform = new message_edit();

echo $OUTPUT->header();

if ($mform->is_cancelled()) {
    // Go back to manage.php page
    redirect($CFG->wwwroot . '/local/message/manage.php');
} else if ($fromform = $mform->get_data()) {
    // Insert data to database table.
    $updateData = new stdClass();
    $updateData->id = $fromform->id;
    $updateData->messagetext = $fromform->messagetext;
    $updateData->messagetype = $fromform->messagetype;
    $DB->update_record_raw('local_message', $updateData);
    redirect($CFG->wwwroot . '/local/message/manage.php', "Update message success with title $fromform->messagetext", '', \core\output\notification::NOTIFY_SUCCESS);
}
$mform->display();
echo $OUTPUT->footer();
