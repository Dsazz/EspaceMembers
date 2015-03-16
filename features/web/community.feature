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
        Given I am logged in as "student@test.com" with password "student"
        Then  I should be on "/profile/"

    Scenario: Index action return the correct number teachers
        Given I am on "/community"
        Then  I should see the correct number teachers in the ".box" element
