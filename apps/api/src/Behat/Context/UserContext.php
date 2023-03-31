<?php declare(strict_types=1);

namespace DumpIt\Api\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Given;
use Behatch\Context\RestContext;

class UserContext implements Context
{
    private RestContext $restContext;

    private $token;

    private $jwtToken;

    public function __construct(RestContext $restContext, string $token)
    {
        $this->restContext = $restContext;
        $this->token = $token;
    }

    /**
     * @Given I am logged in as :username
     */
    public function iAmLoggedAs(string $username)
    {
        $body = json_encode(['username' => $username, 'token' => $this->token]);

        $this->restContext->iAddHeaderEqualTo('Content-Type', 'application/json');
        
        $response = $this->restContext->iSendARequestTo(
            'POST',
            '/api/auth',
            new PyStringNode([$body], 0),
        );

        $jwt = json_decode($response->getContent(), true)['token'];

        $this->jwtToken = $jwt;
      
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer ' . $this->jwtToken);
    }

    public function addHeaders()
    {
        $this->restContext->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer ' . $this->jwtToken);
    }
}
