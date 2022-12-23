<?php
// This file is part of Moodle - http://moodle.org/
namespace drawioflow\event;
defined('MOODLE_INTERNAL') || die();

class drawio_event extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'c'; // c(reate), r(ead), u(pdate), d(elete)
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = '...';
    }

    public static function get_name() {
        return get_string('eventdrawioevent', 'drawioflow');
    }

    public function get_description() {
        return "The user with id {$this->userid} created ... ... ... with id {$this->objectid}.";
    }

    public function get_legacy_logdata() {
        // Override if you are migrating an add_to_log() call.
        return array($this->courseid, 'drawioflow', 'LOGACTION',
            '...........',
            $this->objectid, $this->contextinstanceid);
    }

    public static function get_legacy_eventname() {
        // Override ONLY if you are migrating events_trigger() call.
        return 'MYPLUGIN_OLD_EVENT_NAME';
    }

    protected function get_legacy_eventdata() {
        // Override if you migrating events_trigger() call.
        $data = new \stdClass();
        $data->id = $this->objectid;
        $data->userid = $this->relateduserid;
        return $data;
    }
}