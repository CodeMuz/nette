<?php

/**
 * Test: Nette\Security\Permission Ensures that for a particular Role, a deny rule on a specific privilege is honored before an allow
 * rule on the entire ACL.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Security
 * @subpackage UnitTests
 */

/*use Nette\Security\Permission;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$acl = new Permission;
$acl->addRole('guest');
$acl->addRole('staff', 'guest');
$acl->deny();
$acl->allow('staff');
$acl->deny('staff', NULL, array('privilege1', 'privilege2'));
Assert::false( $acl->isAllowed('staff', NULL, 'privilege1') );
