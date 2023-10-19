<?php
/**
 * @package local_message
 * @author Kristian
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_message_before_footer()
{
    global $DB, $USER;
    $lstMessage = $DB->get_records('local_message');
    $sql = "SELECT lm.id, lm.messagetext, lm.messagetype FROM {local_message} lm
            left outer join {local_message_read} lmr on lm.id = lmr.messageid
            where lmr.userid is null or lmr.userid != :userid";
    $sql = "select lm.id, lm.messagetext, lm.messagetype from {local_message} lm
             where lm.id not in (select lmr.messageid from {local_message_read} lmr where lmr.userid = :userid)";
    $parrams = [
        'userid' => $USER->id,
    ];

    $lstMessage = $DB->get_records_sql($sql, $parrams);

    $messageType = '';
    foreach ($lstMessage as $message) {
        switch ($message->messagetype) {
            case '1':
                $messageType = \core\output\notification::NOTIFY_INFO;
                break;
            case '2':
                $messageType = \core\output\notification::NOTIFY_ERROR;
                break;
            case '3':
                $messageType = \core\output\notification::NOTIFY_SUCCESS;
                break;
            default:
                $messageType = \core\output\notification::NOTIFY_WARNING;
        }
        if (isloggedin()) {
            \core\notification::add($message->messagetext, $messageType);
        }

        $sqlCheckExists = "select * from {local_message_read} lmr where lmr.messageid = :messageid and lmr.userid = :userid";
        $parramInfor = [
            'messageid' => $message->id,
            'userid' => $USER->id
        ];
        $checkMsg = $DB->record_exists_sql($sqlCheckExists, $parramInfor);
        var_dump($checkMsg);
        if (!$checkMsg) {
            if (isloggedin()) {
                $readRecord = new stdClass();
                $readRecord->messageid = $message->id;
                $readRecord->userid = $USER->id;
                $readRecord->timeread = time();
                $DB->insert_record('local_message_read', $readRecord);
            }
        }
    }
}