<?php
// This file is part of Moodle - http://moodle.org/.
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package datalynxview
 * @copyright 2014 Ivan Šakić
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once $CFG->libdir . '/formslib.php';

class datalynxview_entries_form extends moodleform {

    protected function definition() {
        $view = $this->_customdata['view'];
        $mform = &$this->_form;
        $mform->addElement('hidden', 'new', optional_param('new', 0, PARAM_INT));
        $mform->setType('new', PARAM_INT);

        $this->add_action_buttons();

        $view->definition_to_form($mform);

        $this->add_action_buttons();
    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (empty($errors)) {
            $view = $this->_customdata['view'];
            $patterns = $view->get__patterns('field');
            $fields = $view->get_view_fields();
            $entryids = explode(',', $this->_customdata['update']);

            foreach ($entryids as $entryid) {
                foreach ($fields as $fid => $field) {
                    $errors = array_merge($errors,
                            $field->renderer()->validate($entryid, $patterns[$fid], (object) $data));
                }
            }
        }

        return $errors;
    }

    /**
     * Returns an HTML version of the form
     *
     * @return string HTML version of the form
     */
    public function html() {
        return $this->_form->toHtml();
    }
}
