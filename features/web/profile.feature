#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want
@profile
Feature: User profile
    In order to check the user profile page
    As a student
    I need to be sure that page has no errors

    Background:
        Given I am logged in as "student@test.com" with password "student"
        Then  I should be on "/profile/"

    Scenario: Edit action return the correct number teachers
        When  I follow "Edit profile"
        And   I fill in the following:
            | espacemembers_user_profile_form_first_name | Oliver |
            | espacemembers_user_profile_form_last_name  | Queen  |
        And   I press "Submit"
        Then  I should be on "/profile/"
        And   I should see "The profile has been updated"
