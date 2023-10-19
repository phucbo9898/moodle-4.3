<?php
/**
 * @package local_user
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class user_add extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
        $maxbytes = 5242880; // convert to bytes
        $mform = $this->_form; // Don't forget the underscore!

        // Set name
        $mform->addElement('text', 'username', 'UserName', ['placeholder' => 'Please enter a name']); // Add elements to your form.
        $mform->setType('username', PARAM_NOTAGS);                   // Set type of element.
        $mform->addRule('username', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('username', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set first name
        $mform->addElement('text', 'firstname', 'First Name', ['placeholder' => 'Please enter a name']); // Add elements to your form.
        $mform->setType('firstname', PARAM_NOTAGS);                   // Set type of element.
        $mform->addRule('firstname', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('firstname', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set last name
        $mform->addElement('text', 'lastname', 'Last Name', ['placeholder' => 'Please enter a name']); // Add elements to your form.
        $mform->setType('lastname', PARAM_NOTAGS);                   // Set type of element.
        $mform->addRule('lastname', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('lastname', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set nickname
        $mform->addElement('text', 'nickname', 'Nickname', ['placeholder' => 'Please enter a nickname']);
        $mform->setType('nickname', PARAM_NOTAGS);
        $mform->addRule('nickname', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set password
        $mform->addElement('text', 'password', 'Password', ['placeholder' => 'Please enter a password']);
        $mform->setType('password', PARAM_NOTAGS);
        $mform->addRule('password', 'Please enter a password', 'required', '', 'client');
        $mform->addRule('password', 'Invalid data', 'regex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/', 'client');

        // Set gender
        $choices = array();
        $choices[0] = "Please choice gender";
        $choices[1] = "Male";
        $choices[2] = "Female";
        $choices[3] = "Other";
        $mform->addElement('select', 'gender', 'Gender', $choices);
        $mform->setDefault('gender', '0');
        $mform->addRule('gender', '', 'required', '', 'client');
        $mform->addRule('gender', 'Please choice a gender', 'nonzero', '', 'client');

        // Set avater
        $mform->addElement('filepicker', 'avatar', 'Avatar', null, [
            'maxbytes' => $maxbytes,
            'accepted_types' => 'image/*',
        ]);

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}