<?php namespace BadPirate\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mockery as m;

class SmartthingsTest extends \PHPUnit_Framework_TestCase
{
    use QueryBuilderTrait;

    protected $provider;

    protected function setUp()
    {
        $this->provider = new \BadPirate\OAuth2\Client\Provider\Smartthings([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    protected function getJsonFile($file, $encode = false)
    {
        $json = file_get_contents(dirname(dirname(dirname(__FILE__))).'/'.$file);
        $data = json_decode($json, true);

        if ($encode && json_last_error() == JSON_ERROR_NONE) {
            return $data;
        }

        return $json;
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testScopes()
    {
        $scopeSeparator = ' ';
        $options = ['scope' => [uniqid(), uniqid()]];
        $query = ['scope' => implode($scopeSeparator, $options['scope'])];
        $url = $this->provider->getAuthorizationUrl($options);
        $encodedScope = $this->buildQueryString($query);
        $this->assertContains($encodedScope, $url);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('graph.api.smartthings.com', $uri['host']);
        $this->assertEquals('/oauth/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('graph.api.smartthings.com', $uri['host']);
        $this->assertEquals('/oauth/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $accessToken = $this->getJsonFile('access_token_response.json');
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn($accessToken);
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNotNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
    }

    /**
     * @expectedException BadPirate\OAuth2\Client\Provider\Exception\ResourceOwnerException
     **/
    public function testUserData()
    {
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $user = $this->provider->getResourceOwner($token);
    }

    /**
     * @expectedException BadPirate\OAuth2\Client\Provider\Exception\ResourceOwnerException
     **/
    public function testCreateResourceOwner()
    {
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        $class = new \ReflectionClass('BadPirate\OAuth2\Client\Provider\Smartthings');
        $method = $class->getMethod('createResourceOwner');
        $method->setAccessible(true);
        $user = $method->invokeArgs($this->provider, array([], $token));
    }

    public function testSetRedirectLimit()
    {
        $redirectLimit = rand(3,5);
        $this->provider->setRedirectLimit($redirectLimit);
        $this->assertEquals($redirectLimit, $this->provider->getRedirectLimit());
    }

    /**
     * @expectedException InvalidArgumentException
     **/
    public function testSetRedirectLimitThrowsExceptionWhenNonNumericProvided()
    {
        $redirectLimit = uniqid();
        $this->provider->setRedirectLimit($redirectLimit);
    }
}
