@block @block_quickmailsms @usage @javascript @block_quickmailsms_student_usage_feature
Feature: Control student usage of QuickmailSMS
  In order to prevent or allow student usage of QuickmailSMS block
  As a system administrator
  I need be able to set a site config setting to control student usage or leave
  the decision to the instructor

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@asd.com |
      | student1 | Student | 1 | student1@asd.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |
#Then I wait "3" seconds
    And I log in as "admin"
    And I follow "Course 1"
    And I turn editing mode on
    When I add the "QuickmailSMS" block
    Then I should see "Compose New Email" in the "QuickmailSMS" "block"

  Scenario: Leaving "Allow student to use QuickmailSMS" at default values
    Given I click on "Configuration" "link" in the "QuickmailSMS" "block"
    Then I should see "Allow students to use QuickmailSMS"
    And the field "Allow students to use QuickmailSMS" matches value "No"

  Scenario: Disabling "Allow student to use QuickmailSMS" at the site level
    Given I set the following administration settings values:
      | Allow students to use QuickmailSMS | Never |
    And I log out
    When I log in as "teacher1"
    And I follow "Course 1"
    And I click on "Configuration" "link" in the "QuickmailSMS" "block"
    Then I should not see "Allow students to use QuickmailSMS"

  Scenario: Allow access at course level, then disable it at site level
    Given I click on "Configuration" "link" in the "QuickmailSMS" "block"
    Then I set the following fields to these values:
        | Allow students to use QuickmailSMS | Yes |
    And I press "Save changes"
    And I log out
    When I log in as "student1"
    And I follow "Course 1"
    Then I should see "Compose New Email" in the "QuickmailSMS" "block"
    And I click on "Compose New Email" "link" in the "QuickmailSMS" "block"
    And I should see "Subject"
    And I should see "Message"
    Then I log out
    When I log in as "admin"
    And I set the following administration settings values:
      | Allow students to use QuickmailSMS | Never |
    And I log out
    When I log in as "student1"
    And I follow "Course 1"
    Then I should not see "QuickmailSMS"

