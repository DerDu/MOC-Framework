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
 * Odbc
 * 14.01.2013 20:33
 */
namespace MOC\Module\Database\Driver;
use MOC\Module\Database\Driver;

/**
 * Class which provides a common ODBC interface
 */
class Odbc extends Driver {

	/**
	 * Opens a ODBC database connection
	 *
	 * @param string $DSN
	 * @param string $User
	 * @param string $Password
	 * @param null|string $Database
	 *
	 * @return bool
	 */
	public function Open( $DSN, $User, $Password, $Database = null ){
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		if( false == ( $Resource = odbc_connect( $DSN, $User, $Password ) ) ) {
			if( strlen( $Error = odbc_errormsg() ) ) { $this->DebugError( odbc_error().' '.$Error ); }
			return false;
		} else {
			$this->SetResource( $Resource );
			return true;
		}
	}

	/**
	 * Executes a SQL query
	 *
	 * @param int $FETCH_AS
	 *
	 * @return array|bool
	 */
	public function Execute( $FETCH_AS = self::RESULT_AS_ARRAY_ASSOC ) {
		if( !$this->GetResource() ) {
			return false;
		}
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		if( false === ( $Result = odbc_exec( $this->GetResource(), $this->GetQuery() ) ) ) {
			if( strlen( $Error = odbc_errormsg() ) ) { $this->DebugError( odbc_error().' '.$Error."\n\n".$this->GetQuery() ); }
			return false;
		}
		switch( $FETCH_AS ) {
			case self::RESULT_AS_RESOURCE: {
				return $Result;
			}
			case self::RESULT_AS_ARRAY_ASSOC: {
				return $this->FetchAsArrayAssoc( $Result );
			}
			default: {
				return $this->FetchAsArray( $Result );
			}
		}
	}

	/**
	 * Fetches a query result as an array
	 *
	 * @param resource $Result
	 *
	 * @return array
	 */
	protected function FetchAsArray( $Result ) {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$Return = array();
		$RowCount = odbc_num_rows( $Result );
		while( false !== ( $Row = odbc_fetch_array( $Result ) ) ) {
			//if( strlen( $Error = odbc_errormsg() ) ) { $this->DebugError( odbc_error().' '.$Error ); }
			array_push( $Return, array_values( $Row ) );
		}
		$this->DebugMessage( 'Affected Rows: '.( $RowCount == -1 ? $RowCount = odbc_num_rows( $Result ) : $RowCount ) );
		$this->DebugMessage( array_slice( $Return, 0, ( $RowCount > 1 ? 1 : $RowCount ), true ) );
		odbc_free_result( $Result );
		return $Return;
	}

	/**
	 * Fetches a query result as an associative array
	 *
	 * @param resource $Result
	 *
	 * @return array
	 */
	protected function FetchAsArrayAssoc( $Result ) {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$Return = array();
		$RowCount = odbc_num_rows( $Result );
		while( false !== ( $Row = odbc_fetch_array( $Result ) ) ) {
			//if( strlen( $Error = odbc_errormsg() ) ) { $this->DebugError( odbc_error().' '.$Error ); }
			array_push( $Return, $Row );
		}
		$this->DebugError( 'Affected Rows: '.( $RowCount == -1 ? $RowCount = odbc_num_rows( $Result ) : $RowCount ) );
		//$this->DebugMessage( array_slice( $Return, 0, ( $RowCount > 1 ? 1 : $RowCount ), true ) );
		odbc_free_result( $Result );
		return $Return;
	}

	/**
	 * Closes a database connection
	 */
	public function Close(){
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		odbc_close( $this->GetResource() );
		$this->SetResource(null);
	}

	/**
	 * Starts a Transaction
	 */
	public function TransactionStart() {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		odbc_autocommit( $this->GetResource(), false );
	}

	/**
	 * Ends a Transaction with Commit
	 */
	public function TransactionCommit() {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		odbc_commit( $this->GetResource() );
		odbc_autocommit( $this->GetResource(), true );
	}

	/**
	 * Ends a Transaction with Rollback
	 */
	public function TransactionRollback() {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		odbc_rollback( $this->GetResource() );
		odbc_autocommit( $this->GetResource(), true );
	}
}
