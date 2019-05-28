<?php

/**
 * This file is part of the Dibi, smart database abstraction layer (https://dibiphp.com)
 * Copyright (c) 2005 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Dibi;

/**
 * Driver interface.
 */
interface Driver
{
	/**
	 * Disconnects from a database.
	 * @throws Exception
	 */
	function disconnect(): void;

	/**
	 * Internal: Executes the SQL query.
	 * @throws DriverException
	 */
	function query(string $sql): ?ResultDriver;

	/**
	 * Gets the number of affected rows by the last INSERT, UPDATE or DELETE query.
	 */
	function getAffectedRows(): ?int;

	/**
	 * Retrieves the ID generated for an AUTO_INCREMENT column by the previous INSERT query.
	 */
	function getInsertId(?string $sequence): ?int;

	/**
	 * Begins a transaction (if supported).
	 * @throws DriverException
	 */
	function begin(string $savepoint = null): void;

	/**
	 * Commits statements in a transaction.
	 * @throws DriverException
	 */
	function commit(string $savepoint = null): void;

	/**
	 * Rollback changes in a transaction.
	 * @throws DriverException
	 */
	function rollback(string $savepoint = null): void;

	/**
	 * Returns the connection resource.
	 * @return mixed
	 */
	function getResource();

	/**
	 * Returns the connection reflector.
	 */
	function getReflector(): Reflector;

	/**
	 * Encodes data for use in a SQL statement.
	 */
	function escapeText(string $value): string;

	function escapeBinary(string $value): string;

	function escapeIdentifier(string $value): string;

	function escapeBool(bool $value): string;

	/**
	 * @param  \DateTimeInterface|string|int  $value
	 */
	function escapeDate($value): string;

	/**
	 * @param  \DateTimeInterface|string|int  $value
	 */
	function escapeDateTime($value): string;

	/**
	 * Encodes string for use in a LIKE statement.
	 */
	function escapeLike(string $value, int $pos): string;

	/**
	 * Injects LIMIT/OFFSET to the SQL query.
	 */
	function applyLimit(string &$sql, ?int $limit, ?int $offset): void;
}
