<?php

/**
 * Test: Nette\Security\Permission Ensures that removing non-existent default deny rule does nothing.
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
$acl->removeDeny();
Assert::true( $acl->isAllowed() );
