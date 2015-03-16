#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want
@community
Feature: Community
    In order to check the community page
    As a student
    I need to be sure that page has no errors

    Background:
        Given all tables is empty
        And the following groups exist:
            | name    |
            | Members |
        And the following users exist:
            | first name | last name | gender | groups  | roles      | is teacher | email            | password |
            | Oliver     | Queen     | MALE   |         |            |            | student@test.com | student  |
            | Stiven     | King      | MALE   | Members | ROLE_ADMIN | yes        |                  |          |
            | Asher      | Bell      | MALE   | Members | ROLE_ADMIN | yes        |                  |          |
            | Dennis     | Anthony   | FEMALE | Members | ROLE_ADMIN | yes        |                  |          |
            | Mary       | Ferguson  | FEMALE | Members | ROLE_ADMIN | yes        |                  |          |
        And I am logged in as "student@test.com" with password "student"
        Then  I should be on "/profile/"

    @javascript
    Scenario: Index action return the correct number teachers
        Given I am on "/community"
        Then  I should see 4 ".box" element
