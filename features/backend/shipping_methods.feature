@shipping
Feature: Shipping methods
    In order to apply proper shipping to my merchandise
    As a store owner
    I want to be able to configure shipping methods

    Background:
        Given there is default currency configured
        And I am logged in as administrator
        And the following zones are defined:
            | name         | type    | members                 |
            | UK + Germany | country | United Kingdom, Germany |
            | USA          | country | USA                     |
        And there are following shipping categories:
            | name    |
            | Regular |
            | Heavy   |
        And the following shipping methods exist:
            | category | zone         | name         |
            | Regular  | USA          | FedEx        |
            | Heavy    | UK + Germany | DHL          |
            |          | UK + Germany | DHL Express  |
            |          | USA          | TurboPackage |
        And shipping method "DHL Express" has following rules defined:
            | type       | configuration |
            | Item total | Amount: 10000 |
        And shipping method "TurboPackage" has following rules defined:
            | type   | configuration     |
            | Weight | Min: 10, Max: 500 |

    Scenario: Seeing index of all shipping methods
        When I go to the shipping method index page
        Then I should see 4 shipping methods in the list

    Scenario: Seeing empty index of shipping methods
        Given there are no shipping methods
        When I go to the shipping method index page
        Then I should see "There are no shipping methods configured"

    Scenario: Accessing the shipping method creation form
        Given I am on the shipping method index page
        When I follow "Create shipping method"
        Then I should be on the shipping method creation page

    Scenario: Submitting invalid form without name
        Given I am on the shipping method creation page
        When I press "Save"
        Then I should still be on the shipping method creation page
        And I should see "Please enter shipping method name."

    @javascript
    Scenario: Creating new shipping method for specific zone
        Given I am on the shipping method creation page
        When I fill in "Name" with "FedEx World Shipping"
        And I select "USA" from "Zone"
        And I select "Flat rate per item" from "Calculator"
        And I fill in "Amount" with "10"
        And I press "Save"
        Then I should be on the page of shipping method "FedEx World Shipping"
        And I should see "Shipping method has been successfully created."
        And I should see "USA"

    @javascript
    Scenario: Creating new shipping method with flat rate per item
        Given I am on the shipping method creation page
        When I fill in "Name" with "FedEx World Shipping"
        And I select "USA" from "Zone"
        And I select "Flat rate per item" from "Calculator"
        And I fill in "Amount" with "10"
        And I press "Save"
        Then I should be on the page of shipping method "FedEx World Shipping"
        And I should see "Shipping method has been successfully created."

    @javascript
    Scenario: Creating new shipping method with flat rate per shipment
        Given I am on the shipping method creation page
        When I fill in "Name" with "FedEx World Shipping"
        And I select "Flat rate per shipment" from "Calculator"
        And I fill in "Amount" with "10"
        And I press "Save"
        Then I should be on the page of shipping method "FedEx World Shipping"
        And I should see "Shipping method has been successfully created."

    @javascript
    Scenario: Creating new shipping method with flexible rate
        Given I am on the shipping method creation page
        When I fill in "Name" with "FedEx World Shipping"
        And I select "Flexible rate" from "Calculator"
        And I fill in "First item cost" with "100"
        And I fill in "Additional item cost" with "10"
        And I fill in "Limit additional items" with "5"
        And I press "Save"
        Then I should be on the page of shipping method "FedEx World Shipping"
        And I should see "Shipping method has been successfully created."

    Scenario: Accessing the editing form from the list
        Given I am on the shipping method index page
        When I click "edit" near "FedEx"
        Then I should be editing shipping method "FedEx"

    Scenario: Updating the shipping method
        Given I am editing shipping method "FedEx"
        When I fill in "Name" with "General Shipping"
        And I press "Save changes"
        Then I should be on the page of shipping method "General Shipping"

    Scenario: Deleting shipping method
        Given I am on the shipping method index page
        When I click "delete" near "FedEx"
        Then I should be on the shipping method index page
        And I should see "Shipping method has been successfully deleted."
        And I should not see shipping method with name "FedEx" in that list
