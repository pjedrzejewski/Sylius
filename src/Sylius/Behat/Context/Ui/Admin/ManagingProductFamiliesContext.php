<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Admin\Crud\IndexPageInterface;
use Sylius\Behat\Page\Admin\ProductFamily\CreatePageInterface;
use Sylius\Behat\Page\Admin\ProductFamily\UpdatePageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Component\Product\Model\ProductFamilyInterface;
use Webmozart\Assert\Assert;

final class ManagingProductFamiliesContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var CreatePageInterface */
    private $createPage;

    /** @var UpdatePageInterface */
    private $updatePage;

    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    public function __construct(
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage,
        CurrentPageResolverInterface $currentPageResolver
    ) {
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new product family
     */
    public function iWantToCreateANewProductFamily()
    {
        $this->createPage->open();
    }

    /**
     * @Given I want to modify the :productFamily product family
     */
    public function iWantToModifyAProductFamily(ProductFamilyInterface $productFamily)
    {
        $this->updatePage->open(['id' => $productFamily->getId()]);
    }

    /**
     * @When I browse product familys
     */
    public function iBrowseProductFamilies()
    {
        $this->indexPage->open();
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt()
    {
        $this->createPage->create();
    }

    /**
     * @When I save my changes
     * @When I try to save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updatePage->saveChanges();
    }

    /**
     * @When I name it :name in :language
     */
    public function iNameItInLanguage($name, $language)
    {
        $this->createPage->nameItIn($name, $language);
    }

    /**
     * @When I rename it to :name in :language
     * @When I remove its name from :language translation
     */
    public function iRenameItToInLanguage($name = null, $language)
    {
        $this->updatePage->nameItIn($name ?? '', $language);
    }

    /**
     * @When I do not name it
     */
    public function iDoNotNameIt()
    {
        // Intentionally left blank to fulfill context expectation
    }

    /**
     * @When I specify its code as :code
     * @When I do not specify its code
     */
    public function iSpecifyItsCodeAs($code = null)
    {
        $this->createPage->specifyCode($code ?? '');
    }

    /**
     * @When I add the :value family value identified by :code
     */
    public function iAddTheFamilyValueWithCodeAndValue($value, $code)
    {
        /** @var CreatePageInterface|UpdatePageInterface $currentPage */
        $currentPage = $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);

        $currentPage->addFamilyValue($code, $value);
    }

    /**
     * @When I check (also) the :productFamilyName product family
     */
    public function iCheckTheProductFamily(string $productFamilyName): void
    {
        $this->indexPage->checkResourceOnPage(['name' => $productFamilyName]);
    }

    /**
     * @When I delete them
     */
    public function iDeleteThem(): void
    {
        $this->indexPage->bulkDelete();
    }

    /**
     * @Then I should see the product family :productFamilyName in the list
     * @Then the product family :productFamilyName should appear in the registry
     * @Then the product family :productFamilyName should be in the registry
     */
    public function theProductFamilyShouldAppearInTheRegistry(string $productFamilyName): void
    {
        $this->iBrowseProductFamilies();

        Assert::true($this->indexPage->isSingleResourceOnPage(['name' => $productFamilyName]));
    }

    /**
     * @Then I should be notified that product family with this code already exists
     */
    public function iShouldBeNotifiedThatProductFamilyWithThisCodeAlreadyExists()
    {
        Assert::same($this->createPage->getValidationMessage('code'), 'The family with given code already exists.');
    }

    /**
     * @Then there should still be only one product family with :element :value
     */
    public function thereShouldStillBeOnlyOneProductFamilyWith($element, $value)
    {
        $this->iBrowseProductFamilies();

        Assert::true($this->indexPage->isSingleResourceOnPage([$element => $value]));
    }

    /**
     * @Then I should be notified that :element is required
     */
    public function iShouldBeNotifiedThatElementIsRequired($element)
    {
        Assert::same($this->createPage->getValidationMessage($element), sprintf('Please enter family %s.', $element));
    }

    /**
     * @Then the product family with :element :value should not be added
     */
    public function theProductFamilyWithElementValueShouldNotBeAdded($element, $value)
    {
        $this->iBrowseProductFamilies();

        Assert::false($this->indexPage->isSingleResourceOnPage([$element => $value]));
    }

    /**
     * @Then /^(this product family) should still be named "([^"]+)"$/
     * @Then /^(this product family) name should be "([^"]+)"$/
     */
    public function thisProductFamilyNameShouldStillBe(ProductFamilyInterface $productFamily, $productFamilyName)
    {
        $this->iBrowseProductFamilies();

        Assert::true($this->indexPage->isSingleResourceOnPage([
            'code' => $productFamily->getCode(),
            'name' => $productFamilyName,
        ]));
    }

    /**
     * @Then the code field should be disabled
     */
    public function theCodeFieldShouldBeDisabled()
    {
        Assert::true($this->updatePage->isCodeDisabled());
    }

    /**
     * @When I do not add an family value
     */
    public function iDoNotAddAnFamilyValue()
    {
        // Intentionally left blank to fulfill context expectation
    }

    /**
     * @Then I should be notified that at least two family values are required
     */
    public function iShouldBeNotifiedThatAtLeastTwoFamilyValuesAreRequired()
    {
        Assert::true($this->createPage->checkValidationMessageForFamilyValues('Please add at least 2 family values.'));
    }

    /**
     * @Then I should see a single product family in the list
     * @Then I should see :amount product familys in the list
     */
    public function iShouldSeeProductFamiliesInTheList(int $amount = 1): void
    {
        Assert::same($this->indexPage->countItems(), $amount);
    }

    /**
     * @Then /^(this product family) should have the "([^"]*)" family value$/
     */
    public function thisProductFamilyShouldHaveTheFamilyValue(ProductFamilyInterface $productFamily, $familyValue)
    {
        $this->iWantToModifyAProductFamily($productFamily);

        Assert::true($this->updatePage->isThereFamilyValue($familyValue));
    }

    /**
     * @Then the first product family in the list should have :field :value
     */
    public function theFirstProductFamilyInTheListShouldHave($field, $value)
    {
        Assert::same($this->indexPage->getColumnFields($field)[0], $value);
    }

    /**
     * @Then the last product family in the list should have :field :value
     */
    public function theLastProductFamilyInTheListShouldHave($field, $value)
    {
        $values = $this->indexPage->getColumnFields($field);

        Assert::same(end($values), $value);
    }
}
