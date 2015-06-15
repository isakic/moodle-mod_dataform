@mod @mod_datalynx @mod_peter @mink:selenium2
Feature: Check if the ##entries## tag is there.

  Background:
    Given the following "courses" exist:
      | fullname  | shortname   | category  | groupmode   |
      | Course 1  | C1          | 0         | 1           |
    And the following "users" exist:
      | username  | firstname   | lastname  | email                   |
      | teacher1  | Teacher     | 1         | teacher1@mailinator.com |
    And the following "course enrolments" exist:
      | user      | course  | role            |
      | teacher1  | C1      | editingteacher  |
    And the following "activities" exist:
      | activity | course | idnumber | name                   |
      | datalynx | C1     | 12345    | Datalynx Test Instance |
    And "Datalynx Test Instance" has following fields:
      | type        | name    | param1 |
      | text        | Text    |        |
    And "Datalynx Test Instance" has following views:
      | type    | name        | status  | redirect    | filter      |
      | grid    | DefaultView | default | DefaultView |             |
      
      
  Scenario: Check for Tag
    Given I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Datalynx Test Instance"
    And I follow "Manage"
    And I should see "DefaultView"
    Then I click "Edit" button of "DefaultView" item
    And I follow "View template"
    And I click inside "esection_editor_general_tag_menu"
    And I should see "##entries"