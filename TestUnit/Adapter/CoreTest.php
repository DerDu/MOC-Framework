<?php
namespace MOC\TestUnit\Adapter;

use MOC\Api;

class CoreTest extends \PHPUnit_Framework_TestCase {
	public function testInstanceCreation() {
		$this->assertInstanceOf( '\MOC\Core\Cache', Api::Core()->Cache() );
		$this->assertInstanceOf( '\MOC\Core\Drive', Api::Core()->Drive() );
		$this->assertInstanceOf( '\MOC\Core\Encoding', Api::Core()->Encoding() );
		$this->assertInstanceOf( '\MOC\Core\Error', Api::Core()->Error() );
		$this->assertInstanceOf( '\MOC\Core\Journal', Api::Core()->Journal() );
		$this->assertInstanceOf( '\MOC\Core\Proxy', Api::Core()->Proxy() );
		$this->assertInstanceOf( '\MOC\Core\Session', Api::Core()->Session() );
		$this->assertInstanceOf( '\MOC\Core\Template', Api::Core()->Template() );
		$this->assertInstanceOf( '\MOC\Core\Version', Api::Core()->Version() );
		$this->assertInstanceOf( '\MOC\Core\Xml', Api::Core()->Xml() );
	}
}
