#Feature: FEATURENAME
    #In order to ...    // Why this feature is useful
    #As ...    // It can be 'an admin' and 'a developer' or other
    #I need to ...      // The feature we want
@teachings
Feature: Teachings
    In order to check the teaching page and filters
    As a student
    I need to be sure that page has no errors

    Background:
        Given all tables is empty
        And the following groups exist:
            | name    |
            | Members |
            | Headers |
        And the following users exist:
            | first name | last name | gender | groups  | roles      | is teacher | email             | password |
            | Oliver     | Queen     | MALE   |         |            |            | student@test.com  | student  |
            | Stiven     | King      | MALE   | Headers | ROLE_ADMIN | yes        | teacher1@test.com |          |
            | Asher      | Bell      | MALE   | Members | ROLE_ADMIN | yes        | teacher2@test.com |          |
            | Dennis     | Anthony   | FEMALE | Members | ROLE_ADMIN | yes        | teacher3@test.com |          |
            | Mary       | Ferguson  | FEMALE | Members | ROLE_ADMIN | yes        | teacher4@test.com |          |
        And the following categories exist:
            | name     |
            | News     |
            | Top      |
            | Archived |
        And the following directions exist:
            | name        |
            | Mathematic  |
            | Philosophic |
        And the following tags exist:
            | name    |
            | Best    |
            | Awesome |
        And the following events exist:
            | title             | year | usernames                            | tags     | category | is show |
            | Best News 2014    | 2014 | teacher1@test.com, teacher2@test.com | Best     | News     |         |
            | Awesome Top 2013  | 2013 | teacher3@test.com, teacher4@test.com | Awesome  | Top      |         |
            | Best News 2013    | 2013 | teacher1@test.com, teacher2@test.com | Best     | News     |         |
            | Archived Top 2013 | 2013 |                                      | Best     | Archived | yes     |
        And the following teachings exist:
            | title                      | usernames                            | tags    | directions  | serial | event            |
            | Best Mathematic 2014 1     | teacher1@test.com                    | Best    | Mathematic  | 1      | Best News 2014   |
            | Best Mathematic 2014 2     | teacher2@test.com                    | Best    | Mathematic  | 2      | Best News 2014   |
            | Awesome Philosophic 2013 1 | teacher3@test.com, teacher4@test.com | Awesome | Philosophic | 1      | Awesome Top 2013 |
            | Best Mathematic 2013 1     | teacher1@test.com                    | Best    | Mathematic  | 1      | Best News 2013   |
            | Best Philosophic 2013 1    | teacher2@test.com                    | Best    | Philosophic | 1      | Best News 2013   |
        And I am logged in as "student@test.com" with password "student"
        Then  I should be on "/profile/"
        And   I follow "Enseignements"

    @javascript
    Scenario: Index action return the correct number events
        Then  I should see 2 ".left-column" element
        And   I should see "Best News 2014"
        And   I should see "Best News 2013"
        And   I should see "Awesome Top 2013"
        And   I should not see "Archived Top 2013"


    @javascript
    Scenario: Filtering events for 2013
        When  I click on the element "#ui-accordion-accordion-header-0"
        And   I follow "2013"
        Then  I should see 1 ".left-column" element
        And   I should see "Best News 2013"
        And   I should see "Awesome Top 2013"
        And   I should not see "Archived Top 2013"
        And   I should not see "Best News 2014"
        And   I should see 4 ".detail-box" element
        And   I should see "Awesome Philosophic 2013 1"
        And   I should see "Best Philosophic 2013 1"
        And   I should see "Best Mathematic 2013 1"
        And   I should not see "Best Mathematic 2014 1"
        And   I should not see "Best Mathematic 2014 2"

    @javascript
    Scenario: Filtering events by category "News"
        When  I click on the element "#ui-accordion-accordion-header-1"
        And   I follow "News"
        Then  I should see 2 ".left-column" element
        And   I should see "Best News 2013"
        And   I should see "Best News 2014"
        And   I should not see "Archived Top 2013"
        And   I should not see "Awesome Top 2013"
        And   I should see 4 ".detail-box" element
        And   I should see "Best Mathematic 2014 1"
        And   I should see "Best Mathematic 2014 2"
        And   I should see "Best Mathematic 2013 1"
        And   I should see "Best Philosophic 2013 1"
        And   I should not see "Awesome Philosophic 2013 1"

    @javascript
    Scenario: Filtering events by direction "Mathematic"
        When  I click on the element "#ui-accordion-accordion-header-2"
        And   I follow "Mathematic"
        Then  I should see 2 ".left-column" element
        And   I should see "Best News 2013"
        And   I should see "Best News 2014"
        And   I should not see "Archived Top 2013"
        And   I should not see "Awesome Top 2013"
        And   I should see 3 ".detail-box" element
        And   I should see "Best Mathematic 2014 1"
        And   I should see "Best Mathematic 2014 2"
        And   I should see "Best Mathematic 2013 1"
        And   I should not see "Best Philosophic 2013 1"
        And   I should not see "Awesome Philosophic 2013 1"

    @javascript
    Scenario: Filtering events by teacher "King Stiven"
        When  I click on the element "#ui-accordion-accordion-header-3"
        And   I follow "King Stiven"
        Then  I should see 2 ".left-column" element
        And   I should see "King Stiven"
        And   I should see "Best News 2013"
        And   I should see "Best News 2014"
        And   I should not see "Archived Top 2013"
        And   I should not see "Awesome Top 2013"
        And   I should see 2 ".detail-box" element
        And   I should see "Best Mathematic 2014 1"
        And   I should see "Best Mathematic 2013 1"
        And   I should not see "Best Mathematic 2014 2"
        And   I should not see "Best Philosophic 2013 1"
        And   I should not see "Awesome Philosophic 2013 1"

    @javascript
    Scenario: Filtering events by tag "Best"
        When   I click on the element "#ui-accordion-accordion-header-4"
        And   I follow "Best"
        Then  I should see 2 ".left-column" element
        And   I should see "Best News 2013"
        And   I should see "Best News 2014"
        And   I should not see "Archived Top 2013"
        And   I should not see "Awesome Top 2013"
        And   I should see 4 ".detail-box" element
        And   I should see "Best Mathematic 2014 1"
        And   I should see "Best Mathematic 2014 2"
        And   I should see "Best Mathematic 2013 1"
        And   I should see "Best Philosophic 2013 1"
        And   I should not see "Awesome Philosophic 2013 1"

    @data
    Scenario: Adding one bookmark
        When  I click on the element "a.follow.fav[data-id='1']"
        And   I follow "bookmarks"
        Then  I should see 1 ".left-column" element
        And   I should see 1 ".detail-box" element
        And   I should see "Best News 2014"
        And   I should see "King Stiven"
        And   I should see "Best Mathematic 2014 1"
        #And   the "a.follow.fav[data-id='1']" element should has class "active"
