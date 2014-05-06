@stores
Feature: Store management
    In order to manage different websites through single UI
    As a store owner
    I want to configure multiple stores

    Background:
        Given I am logged in as administrator
          And the following zones are defined:
            | name | type    | members                         |
            | USA  | country | USA                             |
            | EU   | country | Germany, United Kingdom, France |
          And there are following currencies configured:
            | code | exchange rate | enabled |
            | USD  | 0.76496       | yes     |
            | GBP  | 1.16998       | yes     |
            | EUR  | 1.00000       | yes     |
          And there are following locales configured:
            | code  | activated |
            | en_US | yes       |
            | en_GB | yes       |
            | fr_FR | yes       |
            | de_DE | yes       |
          And the following payment methods exist:
            | name             | gateway |
            | Credit Card (US) | stripe  |
            | Credit Card (EU) | adyen   |
            | PayPal           | paypal  |
          And the following shipping methods exist:
            | zone | name  |
            | USA  | FedEx |
            | EU   | DHL   |
          And there are following channles configured:
            | code   | name       | currencies | locales             |
            | WEB-US | mystore.us | EUR, GBP   | en_US               |
            | WEB-EU | mystore.eu | USD        | en_GB, fr_FR, de_DE |
          And channel "WEB-US" has following configuration:
            | shipping | payment                  |
            | FedEx    | Credit Card (US), PayPal |
          And channel "WEB-EU" has following configuration:
            | shipping | payment                  |
            | DHL      | Credit Card (EU), PayPal |
          And there are following stores configured:
            | code   | name       | url               |
            | WEB-US | mystore.us | store.mystore.com |
            | WEB-EU | mystore.eu | store.mystore.eu  |

    Scenario: Browsing all configured stores
        Given I am on the dashboard page
         When I follow "Stores"
         Then I should be on the store index page
          And I should see 2 stores in the list

    Scenario: Store codes are visible in the grid
        Given I am on the dashboard page
         When I follow "Stores"
         Then I should be on the store index page
          And I should see store with code "WEB-US" in the list

    Scenario: Seeing empty index of stores
        Given there are no stores
         When I am on the store index page
         Then I should see "There are no stores to display."

    Scenario: Accessing the store creation form
        Given I am on the dashboard page
         When I follow "Stores"
          And I follow "Create store"
         Then I should be on the store creation page

    Scenario: Creating new store
        Given I am on the store creation page
          And I fill in "Code" with "MOBILE-US"
          And I fill in "Name" with "US Android application"
          And I select "WEB-US" from "Channel"
         When I press "Create"
         Then I should be on the store index page
          And I should see "Store has been successfully created."

    Scenario: Accessing the store edit form
        Given I am on the store index page
         When I click "edit" near "WEB-US"
         Then I should be editing store with code "WEB-US"

    Scenario: Updating the store
        Given I am editing store with code "WEB-US"
          And I fill in "Url" with "shopping.nike.com"
          And I press "Save changes"
         Then I should be on the store index page
          And I should see store with name "mystore.com" in the list

    @javascript
    Scenario: Deleting a store
        Given I am on the store index page
         When I press "delete" near "WEB-EU"
          And I confirm the deletion action
         Then I should still be on the store index page
          And I should see "Store has been successfully deleted."
          And I should not see store with name "mystore.eu" in the list
