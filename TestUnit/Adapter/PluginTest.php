<?php
namespace MOC\TestUnit\Adapter;

use MOC\Api;

class PluginTest extends \PHPUnit_Framework_TestCase {
	public function testInstanceCreation() {
		$this->assertInstanceOf( '\MOC\Plugin\Gateway', Api::Plugin()->Get() );

		$this->assertInstanceOf( '\MOC\Plugin\Shared\Documentation', Api::Plugin()->Get()->Documentation() );
		$this->assertInstanceOf( '\MOC\Plugin\Shared', Api::Plugin()->Load( Api::Plugin()->Get()->Documentation() ) );

		$this->assertInstanceOf( '\MOC\Plugin\Shared\MusicPlayer', Api::Plugin()->Get()->MusicPlayer() );
		$this->assertInstanceOf( '\MOC\Plugin\Shared', Api::Plugin()->Load( Api::Plugin()->Get()->MusicPlayer()->PlayerSource('.mp3') ) );

		$this->assertInstanceOf( '\MOC\Plugin\Shared\VideoPlayer', Api::Plugin()->Get()->VideoPlayer() );
		$this->assertInstanceOf( '\MOC\Plugin\Shared', Api::Plugin()->Load( Api::Plugin()->Get()->VideoPlayer()->VideoSource('.flv') ) );
	}
}
