<?php

namespace HumanityChallenge\Http\Services\Auth;

use Http\Services\Auth\Authentication;
use Http\Services\Request\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticationTest
 */
class AuthenticationTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testReturnsExceptionIfRequestIsEmpty()
    {
        $this->expectException(\Exception::class);
        new Authentication(new Request);
    }

    /**
     * @throws \Exception
     */
    public function testReconstructsDigestCorrectly()
    {
        $request = new Request;

        $request->requestUri = '/users/5';
        $request->requestMethod = 'PATCH';
        $request->httpAuthentication = 'email:nonce:digest';

        $auth = new Authentication($request);
        $this->assertEquals('YjQ0ZWVkMD', $auth->reconstructDigest('dummysecret'));
    }
}