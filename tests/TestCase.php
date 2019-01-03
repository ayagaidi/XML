<?php

namespace ACFBentveld\XML\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends PHPUnitTestCase
{
    use MatchesSnapshots;
}