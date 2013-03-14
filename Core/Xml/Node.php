<?php
/**
 * LICENSE (BSD)
 *
 * Copyright (c) 2013, Gerd Christian Kunze
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
 * Node
 * 06.01.2013 15:09
 */
namespace MOC\Core\Xml;
use \MOC\Api;
/**
 *
 */
class Node implements \MOC\Generic\Device\Core {
	/**
	 * Get Dependencies
	 *
	 * @static
	 * @return \MOC\Core\Depending
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceDepending() {
		return Api::Core()->Depending();
	}

	/**
	 * Get Singleton/Instance
	 *
	 * @static
	 * @return \MOC\Core\Xml\Node
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceInstance() {
		return new \MOC\Core\Xml\Node();
	}

	/**
	 * Get Changelog
	 *
	 * @static
	 * @return \MOC\Core\Changelog
	 * @noinspection PhpAbstractStaticMethodInspection
	 */
	public static function InterfaceChangelog() {
		return Api::Core()->Changelog()->Create( __CLASS__ );
	}

	const TYPE_STRUCTURE = 1;
	const TYPE_CONTENT = 2;
	const TYPE_CDATA = 3;
	const TYPE_COMMENT = 4;

	private $Type = self::TYPE_CONTENT;
	/** @var null|int $Position */
	private $Position = null;
	private $Parent = null;
	private $ChildList = array();

	private $Name = null;
	private $Content = null;
	private $AttributeList = array();

	/**
	 * @param $NameOrToken
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function Setup( $NameOrToken ) {
		if( is_object( $NameOrToken ) && $NameOrToken instanceof Token ) {
			$this->SetName( $NameOrToken->GetName() );
			$this->SetAttributeList( $NameOrToken->GetAttributeList() );
			$this->SetPosition( $NameOrToken->GetPosition() );
			unset( $NameOrToken );
		} else {
			$this->SetName( $NameOrToken );
			$this->SetType( self::TYPE_STRUCTURE );
		}
		return $this;
	}

	/**
	 * @param $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function SetType( $Value ) {
		$this->Type = $Value;
		return $this;
	}

	/**
	 * @return int
	 */
	public function GetType() {
		return $this->Type;
	}

	/**
	 * @param $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function SetPosition( $Value ) {
		$this->Position = $Value;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function GetPosition() {
		return $this->Position;
	}

	/**
	 * @param \MOC\Core\Xml\Node $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function SetParent( \MOC\Core\Xml\Node $Value ) {
		$this->Parent = $Value;
		return $this;
	}

	/**
	 * @return null
	 */
	public function GetParent() {
		return $this->Parent;
	}

	/**
	 * @param \MOC\Core\Xml\Node $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function AddChild( \MOC\Core\Xml\Node $Value ) {
		$Value->SetParent( $this );
		array_push( $this->ChildList, $Value );
		$this->Content = null;
		$this->SetType( self::TYPE_STRUCTURE );
		return $this;
	}

	/**
	 * @param      $Name
	 * @param null $AttributeList
	 * @param null $Index
	 * @param bool $Recursive
	 *
	 * @return bool|\MOC\Core\Xml\Node|object
	 */
	public function GetChild( $Name, $AttributeList = null, $Index = null, $Recursive = true ) {
		/** @var \MOC\Core\Xml\Node $Node */
		foreach( $this->ChildList as $Node ) {
			if( $Node->GetName() == $Name ) {
				if( $AttributeList === null && $Index === null ) {
					return $Node;
				} else if( $Index === null ) {
					if( $Node->GetAttributeList() == $AttributeList ) {
						return $Node;
					}
				} else if( $AttributeList === null ) {
					if( $Index === 0 ) {
						return $Node;
					} else {
						$Index--;
					}
				} else {
					if( $Node->GetAttributeList() == $AttributeList && $Index === 0 ) {
						return $Node;
					} else if( $Node->GetAttributeList() == $AttributeList ) {
						$Index--;
					}
				}
			}
			if( true === $Recursive && !empty( $Node->ChildList ) ) {
				if( false !== ( $Result = $Node->GetChild( $Name, $AttributeList, $Index, $Recursive ) ) ) {
					if( !is_object( $Result ) ) {
						$Index = $Result;
					} else {
						return $Result;
					}
				}
			}
		}
		if( $Index !== null ) {
			return $Index;
		} else {
			return false;
		}
	}

	/**
	 * @return array
	 */
	public function GetChildList() {
		return $this->ChildList;
	}

	/**
	 * @param $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function SetName( $Value ) {
		$this->Name = $Value;
		return $this;
	}

	/**
	 * @return null
	 */
	public function GetName() {
		return $this->Name;
	}

	/**
	 * @param null $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function SetContent( $Value = null ) {
		if( preg_match( '![<>&]!is', $Value ) ) {
			$this->SetType( self::TYPE_CDATA );
		} else {
			$this->SetType( self::TYPE_CONTENT );
		}
		if( strlen( $Value ) == 0 ) {
			$this->Content = null;
		} else {
			$this->Content = $Value;
		}
		$this->ChildList = array();
		return $this;
	}

	/**
	 * @return null
	 */
	public function GetContent() {
		return $this->Content;
	}

	/**
	 * @param      $Name
	 * @param null $Value
	 *
	 * @return \MOC\Core\Xml\Node
	 */
	public function SetAttribute( $Name, $Value = null ) {
		if( $Value === null ) {
			unset( $this->AttributeList[$Name] );
		} else {
			$this->AttributeList[$Name] = $Value;
		}
		return $this;
	}

	/**
	 * @param $Name
	 *
	 * @return null
	 */
	public function GetAttribute( $Name ) {
		if( isset( $this->AttributeList[$Name] ) ) {
			return $this->AttributeList[$Name];
		} else {
			return null;
		}
	}

	/**
	 * @param $Array
	 */
	public function SetAttributeList( $Array ) {
		$this->AttributeList = $Array;
	}

	/**
	 * @return array
	 */
	public function GetAttributeList() {
		return $this->AttributeList;
	}

	/**
	 * @return string
	 */
	public function GetAttributeString() {
		$AttributeList = $this->AttributeList;
		array_walk( $AttributeList, create_function( '&$Value,$Key', '$Value = $Key.\'="\'.$Value.\'"\';' ) );
		return implode(' ',$AttributeList);
	}

	/**
	 * @return string
	 */
	public function Code() {
		$FuncArgs = func_get_args();
		if( empty( $FuncArgs) ) {
			$FuncArgs[0] = false;
			$FuncArgs[1] = 0;
		}
		// BUILD STRUCTURE STRING
		$Result = ''
			.( !$FuncArgs[0]?'<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n":"\n" )
			.str_repeat( "\t", $FuncArgs[1] );
		if( $this->GetType() == self::TYPE_COMMENT ) {
			$Result .= '<!-- '.$this->GetContent().' //-->';
		} else {
			$Result .= '<'.trim($this->GetName().' '.$this->GetAttributeString());
		}
		if( $this->GetContent() === null && empty( $this->ChildList ) ) {
			$Result .= ' />';
		}
		else if( $this->GetType() == self::TYPE_CONTENT ) {
			$Result .= '>'.$this->GetContent().'</'.$this->GetName().'>';
		}
		else if( $this->GetType() == self::TYPE_CDATA ) {
			$Result .= '><![CDATA['.$this->GetContent().']]></'.$this->GetName().'>';
		}
		else if( $this->GetType() == self::TYPE_STRUCTURE ) {
			$Result .= '>';
			/** @var \MOC\Core\Xml\Node $Node */
			foreach( $this->ChildList as $Node ) {
				$Result .= $Node->Code(true, $FuncArgs[1] + 1 );
			}
			$Result .= "\n".str_repeat( "\t", $FuncArgs[1] ).'</'.$this->GetName().'>';
		}
		// RETURN STRUCTURE
		return $Result;
	}

	public function __destruct() {
		/** @var \MOC\Core\Xml\Node $Node */
		unset( $this->Parent );
		foreach( (array)$this->ChildList as $Node ) {
			$Node->__destruct();
		}
		unset( $this );
	}
}
