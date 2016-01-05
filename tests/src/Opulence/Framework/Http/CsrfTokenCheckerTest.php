<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Framework\Http;

use Opulence\Cryptography\Utilities\Strings;
use Opulence\Http\Headers;
use Opulence\Http\Requests\Request;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;

/**
 * Tests the CSRF token checker
 */
class CsrfTokenCheckerTest extends \PHPUnit_Framework_TestCase
{
    /** @var CsrfTokenChecker The token checker to use in tests */
    private $checker = null;
    /** @var Strings|\PHPUnit_Framework_MockObject_MockObject The string utility */
    private $strings = null;
    /** @var Request|\PHPUnit_Framework_MockObject_MockObject The request mock */
    private $request = null;
    /** @var ISession|\PHPUnit_Framework_MockObject_MockObject The session mock */
    private $session = null;

    /**
     * Sets up the tests
     */
    public function setUp()
    {
        $this->strings = $this->getMock(Strings::class);
        $this->checker = new CsrfTokenChecker($this->strings);
        $this->request = $this->getMock(Request::class, [], [], "", false);
        $this->session = $this->getMock(ISession::class);
    }

    /**
     * Tests checking an invalid token from the input
     */
    public function testCheckingInvalidTokenFromInput()
    {
        $this->session->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getInput")->willReturn("bar");
        $this->strings->expects($this->any())->method("isEqual")->with("foo", "bar")->willReturn(false);
        $this->assertFalse($this->checker->tokenIsValid($this->request, $this->session));
    }

    /**
     * Tests checking an invalid token from the X-CSRF header
     */
    public function testCheckingInvalidTokenFromXCSRFHeader()
    {
        $this->session->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getInput")->willReturn(null);
        $mockHeaders = $this->getMock(Headers::class);
        $mockHeaders->expects($this->any())->method("get")->willReturn("bar");
        $this->request->expects($this->any())->method("getHeaders")->willReturn($mockHeaders);
        $this->strings->expects($this->any())->method("isEqual")->with("foo", "bar")->willReturn(false);
        $this->assertFalse($this->checker->tokenIsValid($this->request, $this->session));
    }

    /**
     * Tests checking an invalid token from the X-XSRF header
     */
    public function testCheckingInvalidTokenFromXXsrfHeader()
    {
        $this->session->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getInput")->willReturn(null);
        $mockHeaders = $this->getMock(Headers::class);
        $mockHeaders->expects($this->at(0))->method("get")->willReturn(null);
        $mockHeaders->expects($this->at(1))->method("get")->willReturn("bar");
        $this->request->expects($this->any())->method("getHeaders")->willReturn($mockHeaders);
        $this->strings->expects($this->any())->method("isEqual")->with("foo", "bar")->willReturn(false);
        $this->assertFalse($this->checker->tokenIsValid($this->request, $this->session));
    }

    /**
     * Tests checking a valid token from the input
     */
    public function testCheckingValidTokenFromInput()
    {
        $this->session->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getInput")->willReturn("foo");
        $this->strings->expects($this->any())->method("isEqual")->willReturn(true);
        $this->strings->expects($this->any())->method("isEqual")->with("foo", "foo")->willReturn(true);
        $this->assertTrue($this->checker->tokenIsValid($this->request, $this->session));
    }

    /**
     * Tests checking a valid token from the X-CSRF header
     */
    public function testCheckingValidTokenFromXCsrfHeader()
    {
        $this->session->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getInput")->willReturn(null);
        $mockHeaders = $this->getMock(Headers::class);
        $mockHeaders->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getHeaders")->willReturn($mockHeaders);
        $this->strings->expects($this->any())->method("isEqual")->with("foo", "foo")->willReturn(true);
        $this->assertTrue($this->checker->tokenIsValid($this->request, $this->session));
    }

    /**
     * Tests checking a valid token from the X-XSRF header
     */
    public function testCheckingValidTokenFromXXsrfHeader()
    {
        $this->session->expects($this->any())->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getInput")->willReturn(null);
        $mockHeaders = $this->getMock(Headers::class);
        $mockHeaders->expects($this->at(0))->method("get")->willReturn(null);
        $mockHeaders->expects($this->at(1))->method("get")->willReturn("foo");
        $this->request->expects($this->any())->method("getHeaders")->willReturn($mockHeaders);
        $this->strings->expects($this->any())->method("isEqual")->with("foo", "foo")->willReturn(true);
        $this->assertTrue($this->checker->tokenIsValid($this->request, $this->session));
    }

    /**
     * Tests that the CSRF token is set in the session when it is not already there
     */
    public function testCsrfTokenIsSetInSessionWhenItIsNotAlreadyThere()
    {
        $this->strings->expects($this->once())->method("generateRandomString")->willReturn("foo");
        $this->session->expects($this->once())->method("set")->with(CsrfTokenChecker::TOKEN_INPUT_NAME, "foo");
        $this->request->expects($this->once())->method("getInput")->willReturn("foo");
        $this->checker->tokenIsValid($this->request, $this->session);
    }

    /**
     * Tests that the token is marked as valid for read HTTP GET method
     */
    public function testTokenIsValidForReadHttpGetPMethod()
    {
        $this->request->expects($this->any())->method("getMethod")->willReturn(RequestMethods::GET);
        $this->checker->tokenIsValid($this->request, $this->session);
    }

    /**
     * Tests that the token is marked as valid for read HTTP HEAD method
     */
    public function testTokenIsValidForReadHttpHeadPMethod()
    {
        $this->request->expects($this->any())->method("getMethod")->willReturn(RequestMethods::HEAD);
        $this->checker->tokenIsValid($this->request, $this->session);
    }

    /**
     * Tests that the token is marked as valid for read HTTP OPTIONS method
     */
    public function testTokenIsValidForReadHttpOptionsMethod()
    {
        $this->request->expects($this->any())->method("getMethod")->willReturn(RequestMethods::OPTIONS);
        $this->checker->tokenIsValid($this->request, $this->session);
    }
}