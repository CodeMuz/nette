<?php

/**
 * Test: Nette\Security\Permission Ensures that removing the default deny rule results in default deny rule.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Security
 * @subpackage UnitTests
 */

/*use Nette\Security\Permission;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$acl = new Permission;
Assert::false( $acl->isAllowed() );
$acl->removeDeny();
Assert::false( $acl->isAllowed() );
