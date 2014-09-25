<?php

/**
* 
*  Account Member extension
* 
*  Full Tutorial on SSbits.com
* 
*  @package member-accounts
* 
*/

class AccountMember extends DataExtension
{
	//Add some custom fields to our member
	private static $db = array(
		"TwitterHandle" => "Varchar",
		"Website" => "Varchar",
		"EmailValidated" => "Boolean",
		"EmailValidationCode" => "Varchar(30)"	
	);	

	private static $has_one = array(
		"Avatar" => "Image"
	);

	/*
	*	If the email changes we need to re-validate it
	*/	
	function onBeforeWrite()
	{
		if($this->owner->isChanged('Email') || !$this->owner->EmailValidationCode)
		{	
			$this->owner->EmailValidationCode = $this->generateValidationCode();
		}
		if($this->owner->isChanged('Email'))
		{
			$this->owner->EmailValidated = false;
		}
	}

	/*
	*	Generate the link to this Members account page
	*/
	public function Link($extra = null)
	{
		return Director::baseURL() . "account/" . $extra;
	}

	/*
	*	Generate the link to this Members edit account page
	*/
	public function EditAccountLink($extra = null)
	{
		return Director::baseURL() . "account/edit/" . $extra;
	}

	/*
	*	Generate the link to this Members twitter Page
	*/	
	public function TwitterLink()
	{
		if($this->owner->TwitterHandle)
		{
			//Strip the @
			$handle = str_replace("@", "", $this->owner->TwitterHandle);

			return "http://www.twitter.com/" . urlencode($handle);
		}
	}

	/*
	* Validate the email address of this member
	*/
	function ValidateEmail($code)
	{
		if($code == $this->owner->EmailValidationCode)
		{
			$this->owner->EmailValidated = true;
			return $this->owner->write();
		}
	}
	
	/*
	* Generate the link to validate for inclusion in the email
	*/
	function getEmailValidationLink()
	{
		return Singleton("MemberAccount_Controller")->absoluteLink() . 'validateemail?code=' . $this->owner->EmailValidationCode;
	}

	/*
	* Generate the link to trigger the validation email to be sent
	*/
	function getSendValidationEmailLink()
	{
		return Singleton("MemberAccount_Controller")->Link('sendemailvalidation');
	}	
	
	/*
	*	Generate the validation code
	*/
	private function generateValidationCode()
	{
	    $length = 20;
	    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
	    $string = '';    
	    for ($p = 0; $p < $length; $p++) {
	        $string .= $characters[mt_rand(0, (strlen($characters)-1))];
	    }
	    return $string;
	}
		
	/*
	* Send the email to validate the members email address
	*/
	function sendValidationEmail()
	{
		if($member = $this->owner)
		{
			$email = new Email("YourSite <noreply@yoursite.com>", $member->Email, 'Please Validate your Email address');
				
			$email->setTemplate('MemberAccountValidationEmail');
			
			$data = array(
				'Member' => $member,
				'Link' => $member->getEmailValidationLink()
			);
	
			$email->populateTemplate($data);
			
			$email->send();			
		}
	}
}