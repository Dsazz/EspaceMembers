#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want
@teachings
Feature: Teachings
    In order to check the Teaching controller
    As a student
    I need to be sure that all actions is fine

    Background:
        Given I am logged in as "student@test.com" with password "student"
        Then  I should be on "/profile/"

    Scenario: Index action responds with 200
        Given I am on "/"
        Then  the response status code should be 200
