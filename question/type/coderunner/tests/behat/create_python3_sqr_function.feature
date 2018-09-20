@qtype @qtype_coderunner @javascript
Feature: Create a CodeRunner question (the sqr function example)
  In order to test my students' programming ability
  As a teacher
  I need to create a new CodeRunner question

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
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" node in "Course administration"

  Scenario: As a teacher, I create a Python3 sqr(n) -> n**2 function CodeRunner question
    When I disable UI plugins
    And I add a "CodeRunner" question filling the form with:
      | id_coderunnertype | python3                 |
      | name              | sqr acceptance question |
      | id_answer         | def sqr(n): return n * n    |
      | id_validateonsave | 1                       |
      | id_answerboxlines | 3                       |
      | id_questiontext   | Write a sqr function    |
      | id_testcode_0     | print(sqr(-7))          |
      | id_expected_0     | 49                      |
      | id_testcode_1     | print(sqr(11))          |
      | id_expected_1     | 120                     |
    Then I should see "Failed 1 test(s)"
    And I should see "Click on the << button to replace the expected output of this testcase with actual output."
    
    When I press "<<"
    And I press "id_submitbutton"
    Then I should not see "Save changes"
    And I should not see "Write a sqr function"
    And I should see "sqr acceptance question"

    When I click on "Edit" "link" in the "sqr acceptance question" "table_row"
    And I set the field "id_customise" to "1"
    And I set the field "id_iscombinatortemplate" to "1"

    # Set up a standard combinator template
    And I set the field "id_template" to:
      """
      {{ STUDENT_ANSWER }}
      SEPARATOR = '#<ab@17943918#@>#'
      {% for TEST in TESTCASES %}
      {{TEST.testcode}}
      {% if not loop.last %}
      print(SEPARATOR)
      {% endif %}
      {% endfor %}
      """
    And I press "id_submitbutton"
    And I should not see "Write a sqr function"
    And I should see "sqr acceptance question"
