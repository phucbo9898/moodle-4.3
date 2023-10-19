<?php
/**
 * @package local_user
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class user_edit extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $DB, $USER;
        $maxbytes = 5242880; // convert to bytes
        $mform = $this->_form; // Don't forget the underscore!
        $idUser = substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["REQUEST_URI"],"/") + 1);
        $sql = "select * from {local_user} lu where lu.id = :iduser";
        $parrams = ['iduser' => $idUser];
        $getUser = $DB->get_record_sql($sql, $parrams);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INTEGER);
        $mform->setDefault('id', $idUser);

        // Set name
        $mform->addElement('text', 'username', 'UserName', ['placeholder' => 'Please enter a name']); // Add elements to your form.
        $mform->setType('username', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('username', $getUser->username);
        $mform->addRule('username', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('username', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set first name
        $mform->addElement('text', 'firstname', 'First Name', ['placeholder' => 'Please enter a name']); // Add elements to your form.
        $mform->setType('firstname', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('firstname', $getUser->firstname);
        $mform->addRule('firstname', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('firstname', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set last name
        $mform->addElement('text', 'lastname', 'Last Name', ['placeholder' => 'Please enter a name']); // Add elements to your form.
        $mform->setType('lastname', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('lastname', $getUser->lastname);
        $mform->addRule('lastname', get_string('err_required', 'local_message'), 'required', '', 'client');
        $mform->addRule('lastname', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set nickname
        $mform->addElement('text', 'nickname', 'Nickname', ['placeholder' => 'Please enter a nickname']);
        $mform->setType('nickname', PARAM_NOTAGS);
        $mform->setDefault('nickname', $getUser->nickname);
        $mform->addRule('nickname', get_string('err_maxlength', 'local_message', 40), 'maxlength', 40, 'client');

        // Set password
        $mform->addElement('password', 'password', 'Password', ['placeholder' => 'Please enter a password']);
        $mform->setType('password', PARAM_NOTAGS);
        $mform->setDefault('password', 'Default123');
        $mform->addRule('password', 'Please enter a password', 'required', '', 'client');
        $mform->addRule('password', 'Invalid data', 'regex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/', 'client');

        // Set gender
        $choices = array();
        $choices[1] = "Male";
        $choices[2] = "Female";
        $choices[3] = "Other";
        $mform->addElement('select', 'gender', 'Gender', $choices);
        $mform->setDefault('gender', $getUser->gender);
        $mform->addRule('gender', '', 'required', '', 'client');
        $mform->addRule('gender', 'Please choice a gender', 'nonzero', '', 'client');

        // Set avatar
        $htmlImage = "
            <div id='fitem_id_gender' class='form-group row fitem'>
                <div class='col-md-3 col-form-label d-flex pb-0 pr-md-0'>
                    <label id='id_gender_label' class='d-inline word-break ' for='id_gender''>
                        Current image
                    </label>
                </div>
                <div class='col-md-9 form-inline align-items-start felement'>
                    <img src='../{$getUser->avatar}' width='100' height='100' style='border-radius: 50%; object-fit: cover;'/>
                </div>
            </div>
            ";
        $mform->addElement('html', $htmlImage);

        $mform->addElement('advcheckbox', 'deletepicture', '', 'Delete avatar');
        $mform->setDefault('deletepicture', 0);

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