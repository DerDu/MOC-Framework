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
 * Driver
 * 14.01.2013 20:35
 */
namespace MOC\Module\Database;
require_once('Configuration.php');
/**
 *
 */
abstract class Driver extends Configuration {
	/** @var null|\resource $Resource */
	private $Resource = null;
	/** @var array $Statement */
	private $Statement = array();
	/** @var int $StatementCount */
	private $StatementCount = 0;
	/** @var array $Parameter */
	private $Parameter = array();

	private $Debug = false;

	/**
	 * @param string $Host
	 * @param string $User
	 * @param string $Password
	 * @param null|string $Database
	 *
	 * @return bool
	 */
	abstract public function Open( $Host, $User, $Password, $Database = null );

	/**
	 * @return bool
	 */
	abstract public function Close();

	/**
	 * @param string $SqlTemplate
	 *
	 * @return Driver
	 */
	public function Statement( $SqlTemplate ) {
		$this->SetStatement( $SqlTemplate );
		return $this;
	}

	/**
	 * @param mixed $Value
	 * @param null|string $Key
	 * @param int $Type
	 *
	 * @return Driver
	 */
	public function Parameter( $Value, $Key = null, $Type = Driver::PARAM_TYPE_NONE ) {
		$this->SetParameter( $Value, $Key, $Type );
		return $this;
	}

	/**
	 * @param int $FETCH_AS
	 *
	 * @return array
	 */
	abstract public function Execute( $FETCH_AS = Driver::RESULT_AS_ARRAY_ASSOC );


	abstract public function TransactionStart();

	abstract public function TransactionCommit();

	abstract public function TransactionRollback();

	/**
	 * @param \resource $Result
	 *
	 * @return array
	 */
	abstract protected function FetchAsArray( $Result );

	/**
	 * @param \resource $Result
	 *
	 * @return array
	 */
	abstract protected function FetchAsArrayAssoc( $Result );

	/**
	 * Final Driver Methods
	 */

	/**
	 * @param string $SqlTemplate
	 *
	 * @return Driver
	 */
	final protected function SetStatement( $SqlTemplate ) {
		array_push( $this->Statement, $SqlTemplate );
		$this->StatementCount++;
		return $this;
	}

	/**
	 * @param mixed $Value
	 * @param null|string $Key
	 * @param int $Type
	 *
	 * @return Driver
	 */
	final protected function SetParameter( $Value, $Key = null, $Type = Driver::PARAM_TYPE_NONE ) {
		if( !isset( $this->Parameter[$this->StatementCount] ) ) {
			$this->Parameter[$this->StatementCount] = array();
		}
		array_push( $this->Parameter[$this->StatementCount], array( $Value, $Key, $Type ) );
		return $this;
	}

	/**
	 * @return bool|string
	 */
	final protected function GetQuery() {
		if( $this->StatementCount > 0 ) {
			$Statement = array_pop( $this->Statement );
			$this->DebugMessage( 'Statement: '.$Statement );
			if( isset( $this->Parameter[$this->StatementCount] ) ) {
				$ParameterList = array_pop( $this->Parameter );
			} else {
				$ParameterList = array();
			}
			foreach( (array)$ParameterList as $Parameter ) {
				if( !$Parameter[1] ) {
					$Parameter[1] = Driver::PARAM_KEY_UNDEFINED;
				}
				switch( $Parameter[2] ) {
					case Driver::PARAM_TYPE_STRING: {
						/**
						 * mixed => 'mixed'
						 */
						// Escape Quote-Char
						$Parameter[0] = str_replace( $this->OptionQuote(), $this->OptionEscapeQuoteWith(), $Parameter[0] );
						// Quote Parameter
						$Parameter[0] = $this->OptionQuote().$Parameter[0].$this->OptionQuote();
						break;
					}
					case Driver::PARAM_TYPE_STRING_LIST: {
						/**
						 * array('string', '..') => string('string', '..')
						 */
						foreach( (array)$Parameter[0] as $Index => $String ) {
							// Escape Quote-Char
							$Parameter[0][$Index] = str_replace( $this->OptionQuote(), $this->OptionEscapeQuoteWith(), $Parameter[0][$Index] );
							// Quote Parameter
							$Parameter[0][$Index] = $this->OptionQuote().$Parameter[0][$Index].$this->OptionQuote();
						}
						$Parameter[0] = implode( ', ', $Parameter[0] );
						break;
					}
					case Driver::PARAM_TYPE_INTEGER: {
						/**
						 * mixed => integer
						 */
						$Parameter[0] = (integer)$Parameter[0];
						break;
					}
					case Driver::PARAM_TYPE_DATETIME: {
						/**
						 * string => datetime
						 */
						if( is_integer( $Parameter[0] ) ) {
							$Parameter[0] = date( $this->OptionDateTimeFormat(), $Parameter[0] );
						} else {
							$Parameter[0] = date( $this->OptionDateTimeFormat(), strtotime( $Parameter[0] ) );
						}
						// Quote Parameter
						$Parameter[0] = $this->OptionQuote().$Parameter[0].$this->OptionQuote();
						break;
					}
					default: {
					/**
					 * null => NULL
					 * mixed => 'mixed'
 					 */
						if( null != $Parameter[0] ) {
							// Escape Quote-Char
							$Parameter[0] = str_replace( $this->OptionQuote(), $this->OptionEscapeQuoteWith(), $Parameter[0] );
							// Quote Parameter
							$Parameter[0] = $this->OptionQuote().$Parameter[0].$this->OptionQuote();
						} else {
							$Parameter[0] = 'NULL';
						}
						break;
					}
				}
				$this->DebugMessage( 'Parameter-Key: '.$Parameter[1].' Parameter-Value: '.$Parameter[0].' Parameter-Type: '.$Parameter[2] );
				$Statement = preg_replace( '!'.$this->RegExpEncode( $Parameter[1] ).'!s', $Parameter[0], $Statement, 1 );
			}
			$this->StatementCount--;
			$this->DebugMessage( 'Query: '.$Statement );
			return $Statement;
		} else {
			return false;
		}
	}

	/**
	 * @param null|\resource $Resource
	 */
	final protected function SetResource( $Resource ) {
		$this->Resource = $Resource;
	}

	/**
	 * @return null|\resource
	 */
	final protected function GetResource() {
		return $this->Resource;
	}

	/**
	 * @param string $String
	 *
	 * @return string
	 */
	final private function RegExpEncode( $String ) {
		return str_replace(
			array( '?'  ),
			array( '\?' ),
			$String
		);
	}

	/**
	 * Debug
	 */

	/**
	 * @param int $Type
	 */
	final public function EnableDebug( $Type = Driver::DEBUG_HTML ) {
		$this->Debug = $Type;
	}
	final protected function DebugError( $Content ) {
		if( !$this->Debug ) {
			return false;
		}
		switch( $this->Debug ) {
			default: {
			$this->DebugErrorAsHtml( $Content );
			break;
			}
		}
		return true;
	}
	final protected function DebugMessage( $Content ) {
		if( !$this->Debug ) {
			return false;
		}
		switch( $this->Debug ) {
			default: {
				$this->DebugAsHtml( $Content );
				break;
			}
		}
		return true;
	}
	final private function DebugAsHtml( $Content ) {
		if( is_array( $Content ) ) {
			print '<div style="font-family: arial; font-size: 12px; background-color: #F3F3F3; color: #999999; border: 1px dotted #CCCCCC; margin: 1px; padding: 5px;">Sample:<pre>'.htmlspecialchars(print_r($Content,true)).'</pre></div>';
		} else {
			print '<div style="font-family: arial; font-size: 12px; background-color: #F3F3F3; color: #999999; border: 1px dotted #CCCCCC; margin: 1px; padding: 5px;">'.nl2br($Content).'</div>';
		}
	}
	final private function DebugErrorAsHtml( $Content ) {
		if( is_array( $Content ) ) {
			print '<div style="font-family: arial; font-size: 12px; background-color: #FFF3F3; color: #FF9999; border: 1px dotted #FFCCCC; margin: 1px; padding: 5px;">Sample:<pre>'.htmlspecialchars(print_r($Content,true)).'</pre></div>';
		} else {
			print '<div style="font-family: arial; font-size: 12px; background-color: #FFF3F3; color: #FF9999; border: 1px dotted #FFCCCC; margin: 1px; padding: 5px;">'.nl2br($Content).'</div>';
		}
	}
}
