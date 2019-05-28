<?php

/**
 * This file is part of the Dibi, smart database abstraction layer (https://dibiphp.com)
 * Copyright (c) 2005 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Dibi;

/**
 * Dibi connection.
 */
interface IConnection
{
	/**
	 * Connects to a database.
	 */
	function connect(): void;

	/**
	 * Disconnects from a database.
	 */
	function disconnect(): void;

	/**
	 * Returns true when connection was established.
	 */
	function isConnected(): bool;

	/**
	 * Returns the driver and connects to a database in lazy mode.
	 */
	function getDriver(): Driver;

	/**
	 * Generates (translates) and executes SQL query.
	 * @throws Exception
	 */
	function query(...$args): Result;

	/**
	 * Gets the number of affected rows by the last INSERT, UPDATE or DELETE query.
	 * @throws Exception
	 */
	function getAffectedRows(): int;

	/**
	 * Retrieves the ID generated for an AUTO_INCREMENT column by the previous INSERT query.
	 * @throws Exception
	 */
	function getInsertId(string $sequence = null): int;

	/**
	 * Begins a transaction (if supported).
	 */
	function begin(string $savepoint = null): void;

	/**
	 * Commits statements in a transaction.
	 */
	function commit(string $savepoint = null): void;

	/**
	 * Rollback changes in a transaction.
	 */
	function rollback(string $savepoint = null): void;
}
