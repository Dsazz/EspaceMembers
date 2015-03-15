#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want

@homepage
Feature: EspaceMembers enseignements
    In order to verify authorization form
    As an admin, teacher and student
    I need to be sure that authentication works

    Scenario: Viewing the google guide PDF
        #Given I request Google guide for PDF
        #Then  I should see response headers with content type PDF
