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
		'edit',
		'AccountRegistrationForm',
		'AccountEditForm'
	);

	/*
	*	return the registration form
	*/
	public function AccountRegistrationForm()
	{
		return new MemberAccountRegistrationForm($this,'AccountRegistrationForm');
	}

	/*
	*	return the edit account form
	*/
	public function AccountEditForm()
	{
		return new MemberAccountEditForm($this,'AccountEditForm');
	}

	/*
	* check for success message
	*/
	public function Success()
	{
		return $this->request->getVar("success");
	}

	/*
	* check for success message
	*/
	public function Edited()
	{
		return $this->request->getVar("edited");
	}
}