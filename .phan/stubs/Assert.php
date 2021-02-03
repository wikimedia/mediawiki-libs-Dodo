<?php
namespace PHPUnit\Framework;

abstract class Assert {
	/**
	 * Asserts that two variables are equal.
	 *
	 * @throws ExpectationFailedException
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 */
	public static function assertEquals( $expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false ): void {
	}

	/**
	 * Asserts that a condition is true.
	 *
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 * @throws ExpectationFailedException
	 *
	 * @psalm-assert true $condition
	 */
	public static function assertTrue( $condition, string $message = '' ): void {
	}

	/**
	 * Mark the test as skipped.
	 *
	 * @throws SkippedTestError
	 * @throws SyntheticSkippedError
	 *
	 * @psalm-return never-return
	 */
	public static function markTestSkipped( string $message = '' ): void {
 }
}
