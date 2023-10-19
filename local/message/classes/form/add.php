<?php
/**
 * @package local_message
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class message_add extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!
        $mform->addElement('text', 'messagetext', get_string('message_text', 'local_message'), ['placeholder' => get_string('enter_message', 'local_message')]); // Add elements to your form.
        $mform->setType('messagetext', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('messagetext', '');        // Default value.
        $mform->addRule('messagetext', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('messagetext', get_string('err_maxlength', 'local_message', 255), 'maxlength', 255, 'client');

        $choices = array();
        $choices[1] = \core\output\notification::NOTIFY_INFO;
        $choices[2] = \core\output\notification::NOTIFY_ERROR;
        $choices[3] = \core\output\notification::NOTIFY_SUCCESS;
        $choices[4] = \core\output\notification::NOTIFY_WARNING;
        $mform->addElement('select', 'messagetype', get_string('message_type', 'local_message'), $choices);
        $mform->setDefault('messagetype', '1');

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}