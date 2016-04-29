@viewing_products
Feature: Viewing a product details
    In order to see products detailed information
    As a visitor
    I want to be able to browsing products

    Background:
        Given the store operates on a channel named "Web Channel"

    @ui
    Scenario: Viewing a detailed page with product's name
        Given the store has a product "T-shirt banana"
        When I open this product page
        Then I should see the product name "T-shirt banana"
