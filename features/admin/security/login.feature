Feature: Login
  In order to manage my store
  As an Admin
  I need to login to the administration panel

  Background:
    Given there is admin user "rick@example.com" identified by "i9Qp#ZJtZr&U9B"
    And there is user "john@example.com" identified by "Z/XU.wmxH9Mk8Z"

  Scenario: Trying to login with non existing credentials
    Given I want to login to the administration panel
    When I try to login with e-mail "mark@example.com" and password "Boeic6d8*PWCe&"
    Then I should see message about bad credentials
    And I should not be able to access the administration panel

  Scenario: Trying to login with invalid password
    Given I want to login to the administration panel
    When I try to login with e-mail "rick@example.com" and password "oKjX74[h{GxwQV"
    Then I should see message about bad credentials
    And I should not be able to access the administration panel

  Scenario: Trying to login as regular user
    Given I want to login to the administration panel
    When I try to login with e-mail "john@example.com" and password "Z/XU.wmxH9Mk8Z"
    Then I should see message about bad credentials
    And I should not be able to access the administration panel

  Scenario: Logging in as admin
    Given I want to login to the administration panel
    When I try to login with e-mail "rick@example.com" and password "i9Qp#ZJtZr&U9B"
    Then I should login successfully
    And I should be able to access the administration panel

  Scenario: Logging in as admin with "Remember me" feature
    Given I want to login to the administration panel
    When I fill in credentials as "rick@example.com" and password "i9Qp#ZJtZr&U9B"
    And I ask to be remembered
    Then I should login successfully and my session should be persistent
    And I should be able to access the administration panel
