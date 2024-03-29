<?php

/**
 * Test: Nette\Security\Permission Ensures that by default denies access to everything by all.
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
Assert::false( $acl->isAllowed(NULL, NULL, 'somePrivilege') );

$acl->addRole('guest');
Assert::false( $acl->isAllowed('guest') );
Assert::false( $acl->isAllowed('guest', NULL, 'somePrivilege') );
