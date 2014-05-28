@addressing
Feature: Zones
    As a store owner
    I want to be able to manage zones
    In order to apply taxes and allow shipping to geographical areas

    Background:
        Given there is default currency configured
        And I am logged in as administrator
        And there are following zones:
            | name                      | type     | members                                       | scope      |
            | Baltic states             | country  | Lithuania, Latvia, Estonia                    | content    |
            | USA GMT-8                 | province | Washington, Oregon, Nevada, Idaho, California | shipping   |
            | Baltic states + USA GMT-8 | zone     | Baltic states, USA GMT-8                      |            |
            | Germany                   | country  | Germany                                       | price      |

    Scenario: Seeing index of all zones
        When I go to the zone index page
        Then I should see 4 zones in the list

    Scenario: Seeing empty index of zones
        Given there are no zones
        When I go to the zone index page
        Then I should see "There are no zones configured"


    Scenario: Accessing the editing form from list
        Given I am on the zone index page
        When I click "edit" near "USA GMT-8"
        Then I should be editing zone "USA GMT-8"

    Scenario: Updating the zone
        Given I am editing zone "USA GMT-8"
        When I fill in "Name" with "USA GMT-9"
        And I press "Save changes"
        Then I should be on the page of zone "USA GMT-9"
        And I should see "Zone has been successfully updated."

    @javascript
    Scenario: Adding zone member to the existing zone
        Given I am editing zone "Baltic states"
        When I click "Add member"
        And I select "Estonia" from "Country"
        And I press "Save changes"
        Then I should be on the page of zone "Baltic states"
        And I should see "Zone has been successfully updated."
        And "Estonia" should appear on the page

    Scenario: Deleting zone from list
        Given I am on the zone index page
        When I click "delete" near "USA GMT-8"
        Then I should still be on the zone index page
        And I should see "Zone has been successfully deleted."
        But I should not see zone with name "Germany" in that list

