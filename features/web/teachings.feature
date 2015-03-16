#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want
@teachings
Feature: Teachings
    In order to check the teaching page
    As a student
    I need to be sure that page has no errors

    Background:
        Given I am logged in as "student@test.com" with password "student"
        Then  I should be on "/profile/"
