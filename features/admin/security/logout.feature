Feature: Logout
  In order to leave administration panel safely
  As an Admin
  I need to be able to logout

  Background:
    Given there is admin user "rick@example.com" identified by "i9Qp#ZJtZr&U9B"

  Scenario: Logging out
    Given I am logged in as "rick@example.com"
    And I am browsing administration panel
    When I try to logout
    Then I should be successfully logged out
    And I should see the admin login screen
