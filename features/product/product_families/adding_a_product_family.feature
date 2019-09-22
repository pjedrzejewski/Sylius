@managing_product_families
Feature: Adding a new product family
    In order to easily create and manage similar products
    As an Administrator
    I want to be able to add a new product family with predefined options

    Background:
        Given the store is available in "English (United States)"
        And the store has a product option "Bottle size" with a code "bottle_size"
        And this product option has the "0.7" option value with code "bottle_size_medium"
        And this product option has also the "0.5" option value with code "bottle_size_small"
        And I am logged in as an administrator

    @ui
    Scenario: Adding a new product option with two predefined options
        Given I want to create a new product family
        When I name it "Bottle" in "English (United States)"
        And I specify its code as "bottle_product"
        And I add the "Bottle size" option to it
        Then I should be notified that it has been successfully created
        And the product family "Bottle" should appear in the registry
