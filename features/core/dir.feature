#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want
Feature: EspaceMembers drop tables
    In order to verify relationships of tables
    I need to be sure that all tables droped fine

    Scenario: Drop listed tables
        Given I drop tables:
            | name       |
            | Group      |
            | User       |
            | Category   |
            | Chronology |
            | Event      |
            | Media      |
            | Teaching   |
            | Tag        |
            | Voie       |
