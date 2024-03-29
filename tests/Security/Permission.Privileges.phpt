<?php

/**
 * Test: Nette\Security\Permission Ensures that multiple privileges work properly.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Security
 * @subpackage UnitTests
 */

/*use Nette\Security\Permission;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$acl = new Permission;
$acl->allow(NULL, NULL, array('p1', 'p2', 'p3'));
Assert::true( $acl->isAllowed(NULL, NULL, 'p1') );
Assert::true( $acl->isAllowed(NULL, NULL, 'p2') );
Assert::true( $acl->isAllowed(NULL, NULL, 'p3') );
Assert::false( $acl->isAllowed(NULL, NULL, 'p4') );
$acl->deny(NULL, NULL, 'p1');
Assert::false( $acl->isAllowed(NULL, NULL, 'p1') );
$acl->deny(NULL, NULL, array('p2', 'p3'));
Assert::false( $acl->isAllowed(NULL, NULL, 'p2') );
Assert::false( $acl->isAllowed(NULL, NULL, 'p3') );
