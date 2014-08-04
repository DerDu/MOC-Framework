<?php
namespace MOC\TestUnit\Adapter;

use MOC\Api;

class ModuleTest extends \PHPUnit_Framework_TestCase {
	public function testInstanceCreation() {
		$this->assertInstanceOf( '\MOC\Module\Browser', Api::Module()->Browser() );
		$this->assertInstanceOf( '\MOC\Module\Database', Api::Module()->Database() );
		$this->assertInstanceOf( '\MOC\Module\Drive', Api::Module()->Drive() );
		$this->assertInstanceOf( '\MOC\Module\Encoding', Api::Module()->Encoding() );
		$this->assertInstanceOf( '\MOC\Module\Html', Api::Module()->Html() );
		$this->assertInstanceOf( '\MOC\Module\Network', Api::Module()->Network() );
		$this->assertInstanceOf( '\MOC\Module\Office', Api::Module()->Office() );
		$this->assertInstanceOf( '\MOC\Module\Packer', Api::Module()->Packer() );
		$this->assertInstanceOf( '\MOC\Module\Template', Api::Module()->Template() );
	}
}
