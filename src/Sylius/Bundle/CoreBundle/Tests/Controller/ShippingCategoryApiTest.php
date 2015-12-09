<?php

namespace Sylius\Bundle\CoreBundle\Tests\Controller;

use Lakion\ApiTestCase\JsonApiTestCase;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class ShippingCategoryApiTest extends JsonApiTestCase
{
    public function testCreateShippingCategoryAccessDeniedResponse()
    {
        $this->client->request('POST', '/api/shipping-categories/');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'authentication/access_denied', 401);
    }

    public function testCreateShippingCategoryValidationFailResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');

        $this->client->request('POST', '/api/shipping-categories/', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'CONTENT_TYPE' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/create_validation_fail', 400);
    }

    public function testCreateShippingCategoryResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');

        $this->client->request('POST', '/api/shipping-categories/', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'CONTENT_TYPE' => 'application/json',
        ], '{"name": "Regular", "description": "Regular weight items", "code": "reg"}');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/new_shipping_category', 201);
    }

    public function testGetShippingCategoriesListAccessDeniedResponse()
    {
        $this->client->request('GET', '/api/shipping-categories/');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'authentication/access_denied', 401);
    }

    public function testGetShippingCategoriesListResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');
        $this->createShippingCategory();

        $this->client->request('GET', '/api/shipping-categories/', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/get_shipping_categories', 200);
    }

    public function testGetShippingCategoryAccessDeniedResponse()
    {
        $this->client->request('GET', '/api/shipping-categories/1');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'authentication/access_denied', 401);
    }

    public function testGetShippingCategoryWhichDoesNotExistResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');

        $this->client->request('GET', '/api/shipping-categories/-1', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'error/not_found', 404);
    }

    public function testGetShippingCategoryResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');
        $id = $this->createShippingCategory();

        $this->client->request('GET', '/api/shipping-categories/'.$id, [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/shipping_category', 200);
    }

    public function testFullUpdateShippingCategoryAccessDeniedResponse()
    {
        $this->client->request('PUT', '/api/shipping-categories/1');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'authentication/access_denied', 401);
    }

    public function testFullUpdateShippingCategoryWhichDoesNotExistResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');

        $this->client->request('PUT', '/api/shipping-categories/-1', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'error/not_found', 404);
    }

    public function testFullUpdateShippingCategoryValidationFailResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');
        $id = $this->createShippingCategory();

        $this->client->request('PUT', '/api/shipping-categories/'.$id, [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'CONTENT_TYPE' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/validation_fail', 400);
    }

    public function testFullUpdateShippingCategoryResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');
        $id = $this->createShippingCategory();

        $this->client->request('PUT', '/api/shipping-categories/'.$id, [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'CONTENT_TYPE' => 'application/json',
        ], '{"name": "Light", "description": "Light weight items"}');

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, 204);

        $this->client->request('GET', '/api/shipping-categories/'.$id, [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/updated_shipping_category', 200);
    }

    public function testPartialUpdateShippingCategoryWhichDoesNotExistResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');

        $this->client->request('PATCH', '/api/shipping-categories/-1', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'error/not_found', 404);
    }

    public function testPartialUpdateShippingCategoryResponse()
    {
        $this->loadFixturesFromFile('api_administrator.yml');
        $id = $this->createShippingCategory();

        $this->client->request('PATCH', '/api/shipping-categories/'.$id, [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'CONTENT_TYPE' => 'application/json',
        ], '{"name": "Light", "description": "Light weight items"}');

        $response = $this->client->getResponse();
        $this->assertResponseCode($response, 204);

        $this->client->request('GET', '/api/shipping-categories/'.$id, [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'ACCEPT' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'shipping_category/updated_shipping_category', 200);
    }

    /**
     * @return integer
     */
    private function createShippingCategory()
    {
        $this->client->request('POST', '/api/shipping-categories/', [], [], [
            'HTTP_Authorization' => 'Bearer SampleTokenNjZkNjY2MDEwMTAzMDkxMGE0OTlhYzU3NzYyMTE0ZGQ3ODcyMDAwM2EwMDZjNDI5NDlhMDdlMQ',
            'CONTENT_TYPE' => 'application/json',
        ], '{"name": "Regular", "description": "Regular weight items", "code": "reg"}');

        $response = $this->client->getResponse();
        return json_decode($response->getContent())->id;
    }
}
