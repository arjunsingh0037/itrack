@qtype @qtype_coderunner @javascript @setuiplugintest 
Feature: Check that a selected UI plugin is saved
  To check that a selected UI Plugin is saved
  As a teacher
  I should be able to select a UI plugin and save the form

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email            |
      | teacher1 | Teacher   | 1        | teacher1@asd.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "questions" exist:
      | questioncategory | qtype      | name            | template |
      | Test questions   | coderunner | Square function | sqr      |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" node in "Course administration"
    And I enable UI plugins

  Scenario: Selecting the Graph UI plugin results in a canvas being displayed
    When I click on "Edit" "link" in the "Square function" "table_row"
    And I set the following fields to these values:
      | id_customise | 1     |
      | id_uiplugin  | graph |
    Then I should see a canvas

  Scenario: UI plugin state is saved when question is saved
    When I click on "Edit" "link" in the "Square function" "table_row"
    And I click on "a[aria-controls='id_answerhdr']" "css_element"
    And I set the following fields to these values:
      | id_customise | 1     |
      | id_uiplugin  | graph |
    And I press "id_submitbutton"
    And I click on "Edit" "link" in the "Square function" "table_row"
    Then I should see a canvas
  
Scenario: UI plugin state is saved for student
When I click on "Edit" "link" in the "Square function" "table_row"
    And I set the following fields to these values:
      | id_customise | 1     |
      | id_uiplugin  | graph |
    And I press "id_submitbutton"
    When I click on "Preview" "link" in the "Square function" "table_row"
    And I switch to "questionpreview" window
    Then I should see a canvas
