<?php

/**
 * Test: Nette\Security\Permission Ensures that removing the default allow rule results in default deny rule being assigned.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Security
 * @subpackage UnitTests
 */

/*use Nette\Security\Permission;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$acl = new Permission;
$acl->allow();
Assert::true( $acl->isAllowed() );
$acl->removeAllow();
Assert::false( $acl->isAllowed() );
