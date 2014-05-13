<?php
/**
 * LICENSE (BSD)
 *
 * Copyright (c) 2014, Gerd Christian Kunze
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 *  * Neither the name of Gerd Christian Kunze nor the names of the
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
 * IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * Envelope
 * 13.01.2014 08:36
 */
namespace MOC\Module\Network\Feed\Generator;
use MOC\Api;
use MOC\Module\Drive\File;

/**
 *
 */
class Envelope extends Constants {

	/** @var int $Type */
	private $Type = self::TYPE_RSS;

	/** @var string $Title */
	private $Title = '';
	/** @var string $Link */
	private $Link = '';
	/** @var string $Description */
	private $Description = '';

	/** @var File $Image */
	private $Image = null;
	/** @var Item[] $ItemList */
	private $ItemList = array();

	/**
	 * @param $Value
	 *
	 * @return Envelope
	 */
	public function SetTitle( $Value ) {
		$this->Title = $Value;
		return $this;
	}

	/**
	 * @param $Value
	 *
	 * @return Envelope
	 */
	public function SetLink( $Value ) {
		$this->Link = $Value;
		return $this;
	}

	/**
	 * @param $Value
	 *
	 * @return Envelope
	 */
	public function SetDescription( $Value ) {
		$this->Description = $Value;
		return $this;
	}

	/**
	 * @param File $File
	 *
	 * @return Envelope
	 */
	public function SetImage( File $File ) {
		$this->Image = $File->GetUrl();
		return $this;
	}

	/**
	 * @return Item
	 */
	public function SetItem() {
		$Item = new Item( $this->Type );
		array_push( $this->ItemList, $Item );
		return $Item;
	}

	public function CreateFeed() {
		switch( $this->Type ) {
			case self::TYPE_RSS:
			default: {
				$Envelope = Api::Core()->Xml()->Create( 'rss' )->SetAttribute( 'version', '2.0' );
				$Envelope->AddChild(
					$Feed = Api::Core()->Xml()->Create( 'channel' )->AddChild(
						Api::Core()->Xml()->Create( 'title' )->SetContent( $this->Title )
					)->AddChild(
						Api::Core()->Xml()->Create( 'link' )->SetContent( $this->Link )
					)->AddChild(
						Api::Core()->Xml()->Create( 'description' )->SetContent( $this->Description )
					)
				);
				/** @var Item $Item */
				foreach( $this->ItemList as $Item ) {
					$Feed->AddChild( $Item->GetItem() );
				}
			}
		}
		return $Envelope;
	}

	public function CreateTag( $FeedName = 'Feed' ) {
		$Directory = Api::Module()->Drive()->Directory()->Open( __DIR__.'/../../../../Data/Feed' );
		$Feed = Api::Module()->Drive()->File()->Open( $Directory->GetLocation().'/'.$FeedName.'.xml' );
		$Feed->Write( $this->CreateFeed()->Code() );
		return '<link rel="alternate" type="application/rss+xml" href="'.$Feed->GetUrl().'" title="'.$this->Title.'"/>';
	}

}
