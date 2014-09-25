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
		'AccountEditForm',
		'validateemail',
		'sendemailvalidation',
		'validated'		
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

	
	public function getSelectedMember()
	{
		$Params = $this->request->latestParams();
		
		if($SelectedMember = DataObject::get_by_id('Member', (int)$Params['ID']))
		{
			return $SelectedMember;
		}
	}
	
	//action for validating email addresses
	public function validateemail()
	{
		$code = Convert::raw2sql($this->request->getVar('code'));
		
		if($code && $member = DataObject::get_one('Member', "EmailValidationCode = '$code'"))
		{
			$member->ValidateEmail($code);
			$member->login();

			return $this->redirect($this->Link() . 'validated');
		}
		else
		{
			$this->setAlert('Oops something went wrong...we couldn\'t validate your email address.','bad');
			return $this->redirectBack();
		}
	}	
	
	public function sendemailvalidation()
	{
		if($member = Member::CurrentUser())
		{
			$member->sendValidationEmail();
			$this->setAlert('Email has been sent, simply click the link in the email to validate your address.','good');
		}
		else
		{
			$this->setAlert('Oops, you need to be logged in to validate your address.','bad');
		}

		return $this->redirectBack();
	}

}