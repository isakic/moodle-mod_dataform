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
 * @package datalynxfield
 * @subpackage radiobutton
 * @copyright 2011 Itamar Tzadok
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->dirroot/mod/datalynx/field/select/field_form.php");

class datalynxfield_radiobutton_form extends datalynxfield_select_form {

    /**
     */
    function field_definition() {
        parent::field_definition();

        $mform = &$this->_form;

        // options separator
        $mform->addElement('select', 'param3', get_string('fieldoptionsseparator', 'datalynx'),
                array_map('current', $this->_field->separators));
    }
}
