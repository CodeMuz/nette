<?php

/**
 * Test: Nette\Security\Permission Ensures that ACL-wide rules (all Resources and privileges) work properly for a particular Role.
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
$acl->allow('guest');
Assert::true( $acl->isAllowed('guest') );
$acl->deny('guest');
Assert::false( $acl->isAllowed('guest') );
