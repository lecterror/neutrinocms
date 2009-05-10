<?php

class RememberMeTestController extends Controller
{
	var $name = 'RememberMeTest';
	var $uses = null;
	var $components = array('RememberMe');

}

class RememberMeTest extends CakeTestCase
{
	var $name = 'RememberMe';

	function skip()
	{
		$this->skipIf(headers_sent(), 'Cannot run test - headers already sent.');
	}

	function setUp()
	{
		$this->Controller =& new RememberMeTestController();

		restore_error_handler();
		@$this->Controller->_initComponents();
		set_error_handler('simpleTestErrorHandler');

		$this->Controller->RememberMe->startup($this->Controller);
		ClassRegistry::addObject('view', new View($this->Controller));
	}

	function testCookieWriteAndRead()
	{
		$this->RememberMe->cookieName = 'Gobshite';
		$this->Controller->RememberMe->remember('Jack', 'Hackett');

		$cookie = $this->Controller->RememberMe->Cookie->read($this->Controller->RememberMe->cookieName);

		$this->assertEqual
			(
				array
				(
					'username' => 'Jack',
					'password' => 'Hackett',
				),
				$cookie
			);
	}
}

?>