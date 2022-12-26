<?php
require 'melog.php';

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * YOURQTYPENAME question definition class.
 *
 * @package    qtype
 * @subpackage YOURQTYPENAME
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Represents a YOURQTYPENAME question.
 *
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_drawioflow_question extends question_with_responses {

    public $responseformat;

    /** @var int Indicates whether an inline response is required ('0') or optional ('1')  */
    public $responserequired;

    public $responsefieldlines;

    /** @var int indicates whether the minimum number of words required */
    public $minwordlimit;

    /** @var int indicates whether the maximum number of words required */
    public $maxwordlimit;

    public $attachments;

    /** @var int maximum file size in bytes */
    public $maxbytes;

    /** @var int The number of attachments required for a response to be complete. */
    public $attachmentsrequired;

    public $graderinfo;
    public $graderinfoformat;
    public $responsetemplate;
    public $responsetemplateformat;

    /** @var array The string array of file types accepted upon file submission. */
    public $filetypeslist;

    public function make_behaviour(question_attempt $qa, $preferredbehaviour) {
        return question_engine::make_behaviour('manualgraded', $qa, $preferredbehaviour);
    }

    /**
     * @param moodle_page the page we are outputting to.
     * @return qtype_essay_format_renderer_base the response-format-specific renderer.
     */
    public function get_format_renderer(moodle_page $page) {
        return $page->get_renderer('qtype_drawioflow', 'format_' . $this->responseformat);
    }

    public function get_expected_data() {
        return array('answer' => PARAM_RAW_TRIMMED);
    }

    public function summarise_response(array $response) {
        if (isset($response['answer'])) {
            return $response['answer'];
        } else {
            return null;
        }
    }

    public function un_summarise_response(string $summary) {
        if (!empty($summary)) {
            return ['answer' => $summary];
        } else {
            return [];
        }
    }

    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response) &&
        ($response['answer'] || $response['answer'] === '0');
    }

    public function get_correct_response() {
        return null;
    }

    /**
     * Return null if is_complete_response() returns true
     * otherwise, return the minmax-limit error message
     *
     * @param array $response
     * @return string
     */
    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_shortanswer');
    }

    public function is_gradable_response(array $response) {
        // Determine if the given response has online text and attachments.
        if (array_key_exists('answer', $response) && ($response['answer'] !== '')) {
            return true;
        } else if (array_key_exists('attachments', $response)
                && $response['attachments'] instanceof question_response_files) {
            return true;
        } else {
            return false;
        }
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        
        return question_utils::arrays_same_at_key_missing_is_blank(
            $prevresponse, $newresponse, 'answer');

    }

    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload) {
        if ($component == 'question' && $filearea == 'response_attachments') {
            // Response attachments visible if the question has them.
            return $this->attachments != 0;

        } else if ($component == 'question' && $filearea == 'response_answer') {
            // Response attachments visible if the question has them.
            return $this->responseformat === 'editorfilepicker';

        } else if ($component == 'qtype_essay' && $filearea == 'graderinfo') {
            return $options->manualcomment && $args[0] == $this->id;

        } else {
            return parent::check_file_access($qa, $options, $component,
                    $filearea, $args, $forcedownload);
        }
    }

    /**
     * Return the question settings that define this question as structured data.
     *
     * @param question_attempt $qa the current attempt for which we are exporting the settings.
     * @param question_display_options $options the question display options which say which aspects of the question
     * should be visible.
     * @return mixed structure representing the question settings. In web services, this will be JSON-encoded.
     */
    public function get_question_definition_for_external_rendering(question_attempt $qa, question_display_options $options) {
        // This is a partial implementation, returning only the most relevant question settings for now,
        // ideally, we should return as much as settings as possible (depending on the state and display options).

        $settings = [
            'responseformat' => $this->responseformat,
            'responserequired' => $this->responserequired,
            'responsefieldlines' => $this->responsefieldlines,
            'attachments' => $this->attachments,
            'attachmentsrequired' => $this->attachmentsrequired,
            'maxbytes' => $this->maxbytes,
            'filetypeslist' => $this->filetypeslist,
            'responsetemplate' => $this->responsetemplate,
            'responsetemplateformat' => $this->responsetemplateformat,
            'minwordlimit' => $this->minwordlimit,
            'maxwordlimit' => $this->maxwordlimit,
        ];

        return $settings;
    }

    /**
     * Check the input word count and return a message to user
     * when the number of words are outside the boundary settings.
     *
     * @param string $responsestring
     * @return string|null
     .*/


    /**
     * If this question uses word counts, then return a display of the current
     * count, and whether it is within limit, for when the question is being reviewed.
     *
     * @param array $response responses, as returned by
     *      {@see question_attempt_step::get_qt_data()}.
     * @return string If relevant to this question, a display of the word count.
     */
    
}