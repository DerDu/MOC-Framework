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
 * Pdo
 * 13.03.2014 13:49
 */
namespace MOC\Module\Database\Driver;
use MOC\Api;
use MOC\Module\Database\Driver;
use MOC\Module\Database\Result;

/**
 * Class which provides a common PDO interface
 */
class Pdo extends Driver {

	/**
	 * Opens a PDO database connection
	 *
	 * @param string $Host
	 * @param string $User
	 * @param string $Password
	 * @param null|string $Database
	 *
	 * @return bool
	 */
	public function Open( $Host, $User, $Password, $Database = null ){
		Api::Core()->Error()->Reporting()->Debug(true)->Display(true)->Apply();
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		if( false == ( $Resource = new \PDO("dblib:host=".$Host.";dbname=".$Database, $User, $Password) ) ) {
			if( strlen( $Error = $Resource->errorInfo() ) ) { $this->DebugError( $Resource->errorInfo().' '.$Error ); }
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
		if( false === ( $Result = $this->GetResource()->query( $Query = $this->GetQuery() ) ) ) {
			if( strlen( $Error = $this->GetResource()->errorInfo() ) ) {
				$this->DebugError( $this->GetResource()->errorInfo().' '.$Error."\n\n".$Query );
				Api::Core()->Error()->Type()->Error()->Trigger( $this->GetResource()->errorInfo().' '.$Error."\n\n".$Query, __FILE__, __LINE__, true );
			}
			return false;
		}
		switch( $FETCH_AS ) {
			case self::RESULT_AS_OBJECT: {
				return $this->FetchAsObject( $Result );
			}
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
	 * @param \PDOStatement $Result
	 *
	 * @return array
	 */
	protected function FetchAsArray( $Result ) {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$Return = array();
		$RowCount = $Result->rowCount();
		while( false !== ( $Row = $Result->fetch( \PDO::FETCH_NUM ) ) ) {
			array_push( $Return, $Row );
		}
		$this->DebugError( 'Affected Rows: '.$RowCount );
		$this->DebugMessage( array_slice( $Return, 0, ( $RowCount > 1 ? 1 : $RowCount ), true ) );
		return $Return;
	}

	/**
	 * Fetches a query result as an associative array
	 *
	 * @param \PDOStatement $Result
	 *
	 * @return array
	 */
	protected function FetchAsArrayAssoc( $Result ) {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$Return = array();
		$RowCount = $Result->rowCount();
		while( false !== ( $Row = $Result->fetch( \PDO::FETCH_ASSOC ) ) ) {
			array_push( $Return, $Row );
		}
		if( $RowCount < 0 ) { $RowCount = count( $Return ); }
		$this->DebugError( 'Affected Rows: '.$RowCount );
		$this->DebugMessage( array_slice( $Return, 0, ( $RowCount > 1 ? 1 : $RowCount ), true ) );
		return $Return;
	}

	/**
	 * Fetches a query result as an associative array
	 *
	 * @param \PDOStatement $Result
	 *
	 * @return array
	 */
	protected function FetchAsObject( $Result ) {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$Return = $this->FetchAsArrayAssoc( $Result );
		return new Result( $Return );
	}

	/**
	 * Closes a database connection
	 */
	public function Close(){
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
	}

	/**
	 * Starts a Transaction
	 */
	public function TransactionStart() {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$this->GetResource()->beginTransaction();
	}

	/**
	 * Ends a Transaction with Commit
	 */
	public function TransactionCommit() {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$this->GetResource()->commit();
	}

	/**
	 * Ends a Transaction with Rollback
	 */
	public function TransactionRollback() {
		$this->DebugMessage( get_class( $this ).'::'.__FUNCTION__ );
		$this->GetResource()->rollBack();
	}
}
