<?php

/**
* 
*  Member Account Controller
* 
*  Full Tutorial on SSbits.com
* 
*  @package member-accounts
* 
*/
class MemberAccount_Controller extends Page_Controller
{
	private static $allowed_actions = array(
		'register',
		'AccountRegistrationForm'
	);

	/*
	*	return the registration form
	*/
	public function AccountRegistrationForm()
	{
		return new MemberAccountRegistrationForm($this,'AccountRegistrationForm');
	}

	/*
	* check for success message
	*/
	public function Success()
	{
		return $this->request->getVar("success");
	}
}