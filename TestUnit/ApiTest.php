<?php
namespace MOC\TestUnit;

use MOC\Api;

class ApiTest extends \PHPUnit_Framework_TestCase {
	public function testInstanceCreation() {
		$this->assertInstanceOf( '\MOC\Adapter\Core', Api::Core() );
		$this->assertInstanceOf( '\MOC\Adapter\Extension', Api::Extension() );
		$this->assertInstanceOf( '\MOC\Adapter\Module', Api::Module() );
		$this->assertInstanceOf( '\MOC\Adapter\Plugin', Api::Plugin() );
	}
}
