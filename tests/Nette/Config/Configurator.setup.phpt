<?php

/**
 * Test: Nette\Config\Configurator: services setup.
 *
 * @author     David Grudl
 * @package    Nette\Config
 */

use Nette\Config\Configurator;



require __DIR__ . '/../bootstrap.php';



class Factory
{
	static function createLorem($arg)
	{
		Tester\Helpers::note(__METHOD__ . ' ' . $arg);
		return new Lorem;
	}
}


class Lorem
{
	function test($arg)
	{
		Tester\Helpers::note(__METHOD__ . ' ' . $arg);
	}
}


class Ipsum
{
	public static $staticTest;

	public $test;

	static function test($arg)
	{
		Tester\Helpers::note(__METHOD__ . ' ' . $arg);
	}
}


function test($arg)
{
	Tester\Helpers::note(__METHOD__ . ' ' . $arg);
}


$configurator = new Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$container = $configurator->addConfig('files/config.setup.neon', Configurator::NONE)
	->createContainer();

Assert::same(array(
), Tester\Helpers::fetchNotes());

Assert::true( $container->lorem instanceof Lorem );

Assert::same(array(
	'Factory::createLorem 1',
	'Lorem::test 2',
	'Lorem::test 3',
	'Lorem::test 4',
	'Ipsum::test 5',
	'Ipsum::test 6',
	'test 7',
), Tester\Helpers::fetchNotes());

Assert::same( 8, $container->lorem->test );
Assert::same( 9, Ipsum::$staticTest );
Assert::equal( new Lorem, $container->ipsum->test );
