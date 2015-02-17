#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want

@homepage
Feature: EspaceMembers homepage
    In order to verify authorization form
    As an admin, teacher and student
    I need to be sure that authentication works

    Scenario: Viewing the homepage at website root
        Given I am on homepage
        Then  I should be on "/login"

    Scenario: Authorization checking as admin
        Given I am logged in as "teachermale1@test.com" with password "teacher"
        Then  I should be on "/admin/dashboard"

    Scenario: Authorization checking error with wrong password
        Given I am logged in as "teachermale1@test.com" with password "teach"
        Then  I should be on "/login"
        And   I should see "Invalid username or password"

    Scenario: Authorization checking error with wrong username
        Given I am logged in as "teachermale@test.c" with password "teacher"
        Then  I should be on "/login"
        And   I should see "Invalid username or password"
