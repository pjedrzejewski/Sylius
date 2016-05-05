@viewing_products
Feature: Viewing product's attributes
    In order to see product's specification
    As a visitor
    I want to be able to see product's attributes

    Background:
        Given the store operates on a channel named "Web Channel"
        And the store has a base currency "EUR"

    @ui
    Scenario: Viewing a detailed page with product's attributes
        Given the store has a product "T-shirt banana"
        And this product has text attribute "T-shirt brand" with value "Banana skin"
        When I open this product page
        Then I should see the product attribute "T-shirt brand"
