<?php
namespace MOC\TestUnit\Adapter;

use MOC\Api;

class ExtensionTest extends \PHPUnit_Framework_TestCase {
	public function testInstanceCreation() {
//		$this->assertInstanceOf( '\MOC\Extension\AppGati\Instance', Api::Extension()->AppGati() );
		$this->assertInstanceOf( '\MOC\Extension\BarcodeGenerator\Instance', Api::Extension()->BarcodeGenerator() );
		$this->assertInstanceOf( '\MOC\Extension\Excel\Instance', Api::Extension()->Excel() );
		$this->assertInstanceOf( '\MOC\Extension\HuffmanCompressor\Instance', Api::Extension()->HuffmanCompressor() );
		$this->assertInstanceOf( '\MOC\Extension\Mail\Instance', Api::Extension()->Mail() );
/**
 *  Not Testable
 *  $this->assertInstanceOf( '\MOC\Extension\Pdf\Instance\FPdf', Api::Extension()->Pdf() );
 */
		$this->assertInstanceOf( '\MOC\Extension\Pdf\Instance\DomPdf', Api::Extension()->PdfGenerator() );
		$this->assertInstanceOf( '\MOC\Extension\QrCode\Instance', Api::Extension()->QrCode() );
		$this->assertInstanceOf( '\MOC\Extension\Uuid\Instance', Api::Extension()->UuidGenerator() );
		$this->assertInstanceOf( '\MOC\Extension\Word\Instance', Api::Extension()->Word() );
		$this->assertInstanceOf( '\MOC\Extension\Xml\Instance', Api::Extension()->Xml() );
		$this->assertInstanceOf( '\MOC\Extension\YUICompressor\Instance', Api::Extension()->YUICompressor() );
		$this->assertInstanceOf( '\MOC\Extension\Zip\Instance', Api::Extension()->Zip() );
	}
}
