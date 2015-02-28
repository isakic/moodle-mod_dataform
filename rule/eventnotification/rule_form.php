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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
 
/**
 * @package datalynx_rule
 * @subpackage eventnotification
 * @copyright 2012 Itamar Tzadok
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->dirroot/mod/datalynx/rule/rule_form.php");
HTML_QuickForm::registerElementType('checkboxgroup', "$CFG->dirroot/mod/datalynx/checkboxgroup/checkboxgroup.php", 'HTML_QuickForm_checkboxgroup');

class datalynx_rule_eventnotification_form extends datalynx_rule_form {
    function rule_definition() {
        $br = html_writer::empty_tag('br');
        $mform = &$this->_form;

        //-------------------------------------------------------------------------------
        $mform->addElement('header', 'settingshdr', get_string('settings'));

        // sender
        $options = array(
            datalynx_rule_eventnotification::FROM_AUTHOR => get_string('author', 'datalynx'),
            datalynx_rule_eventnotification::FROM_CURRENT_USER => get_string('user')
        );
        $mform->addElement('select', 'param2', get_string('from'), $options);

        // recipient
        $grp = array();
        $grp[] = &$mform->createElement('checkbox', 'author', null, get_string('author', 'datalynx'), null);
        $grp[] = &$mform->createElement('static', '', '', $br);

        $grp[] = &$mform->createElement('checkboxgroup', 'roles', get_string('roles'), $this->_df->get_datalynx_permission_names(true), $br);
        $grp[] = &$mform->createElement('static', '', '', $br);

        $grp[] = &$mform->createElement('checkboxgroup', 'teams', get_string('teams', 'datalynx'), $this->get_datalynx_team_fields(), $br);

        $mform->addGroup($grp, 'recipientgrp', get_string('to'), $br, false);

        $mform->addElement('header', 'settingshdr', get_string('linksettings', 'datalynx'));
        $mform->addElement('static', '', get_string('targetviewforroles', 'datalynx'));

        foreach ($this->_df->get_datalynx_permission_names(true) as $permissionid => $permissionname) {
            $views = $this->get_views_visible_to_datalynx_permission($permissionid);
            $mform->addElement('select', "param4[$permissionid]", $permissionname, $views);
            $defaultview = $this->_df->get_default_view_id();
            if ($defaultview && in_array($defaultview, array_keys($views))) {
                $mform->setDefault("param4[$permissionid]", $defaultview);
            }
        }
    }

    private function get_views_visible_to_datalynx_permission($permissionid) {
        global $DB;
        if ($permissionid == datalynx::PERMISSION_ADMIN) {
            $sql = "SELECT id, name FROM {datalynx_views} WHERE dataid = :dataid";
            return $DB->get_records_sql_menu($sql, ['dataid' => $this->_df->id()]);
        } else {
            $sql = "SELECT id, name FROM {datalynx_views} WHERE dataid = :dataid AND visible & :permissionid <> 0";
            return $DB->get_records_sql_menu($sql, ['dataid' => $this->_df->id(), 'permissionid' => $permissionid]);
        }
    }

    protected function get_datalynx_team_fields() {
        global $DB;
        $sql = "SELECT id, name
                  FROM {datalynx_fields}
                 WHERE dataid = :dataid
                   AND " . $DB->sql_like('type', ':type');
        $params = ['dataid' => $this->_df->id(), 'type' => 'teammemberselect'];
        return $DB->get_records_sql_menu($sql, $params);
    }

    protected function menu_roles_used_in_context() {
        $roles = array();
        foreach (get_roles_used_in_context($this->_df->context) as $roleid => $role) {
            $roles[$roleid] = $role->coursealias ? $role->coursealias : ($role->name ? $role->name : $role->shortname);
        }
        return $roles;
    }

    function set_data($data) {
        $recipients = unserialize($data->param3);
        if (isset($recipients['author'])) {
            $data->author = $recipients['author'];
        }
        if (isset($recipients['roles'])) {
            $data->roles = $recipients['roles'];
        }
        if (isset($recipients['teams'])) {
            $data->teams = $recipients['teams'];
        }
        $data->param4 = unserialize($data->param4);
        parent::set_data($data);
    }

    function get_data($slashed = true) {
        if ($data = parent::get_data($slashed)) {
            // set recipient
            $recipients = array();
            if (isset($data->author)) {
                $recipients['author'] = 1;
            }

            if (isset($data->roles)) {
                $recipients['roles'] = $data->roles;
            }

            if (isset($data->teams)) {
                $recipients['teams'] = $data->teams;
            }
            $data->param3 = serialize($recipients);
            $data->param4 = serialize($data->param4);
        }
        return $data;
    }
}
