<?php

/**
* 
*  Member Account Registration Form
* 
*  Full Tutorial on SSbits.com
* 
*  @package member-accounts
* 
*/

class MemberAccountRegistrationForm extends Form 
{
    function __construct($controller, $name) 
	{
		// Create the fields
	    $fields = new FieldList(
		    new TextField('Name', '* Name'),
			new EmailField('Email', '* Email'),
			new ConfirmedPasswordField('Password','* Password')
		);
	 	
	    // Create action
	    $actions = new FieldList(
	    	new FormAction('SubmitForm', 'Register')
	    );
		
		// Set the Validator
		$validator = new RequiredFields('Name', 'Email');

        parent::__construct($controller, $name, $fields, $actions, $validator);
    }

	function SubmitForm($data, $form) 
	{
	  	//We need to check that the email address is not already in use
 		if(Member::get()->filter("Email", $data['Email'])->first()) 
        {
            //Set error message
            $form->AddErrorMessage('Email', "Sorry, a member with email ". $data['Email'] ." exists. Please choose another.", 'bad');       
            //Set form data from submitted values
			Session::set("FormInfo.{$this->FormName()}.data", $data);     
            
            //Return back to form
            return $form->controller->redirectBack();          
        }   
 
        //Otherwise create new member and log them in
        $member = new Member();
        $form->saveInto($member);            
        $member->write();
        $member->login();	  	

		//Create email to send to user
	 	$email = new Email(
	 		"YourSite <noreply@yoursite.com>", //From
	 		$member->Email, //To
	 		"Thanks for Registering with YourSite!" //Subject
		);

		//set template
		$email->setTemplate("MemberAccountRegistrationEmail");
		//populate template
		$email->populateTemplate($member);
		//send mail
		$email->send();

		//Redirect to the account page
		return $form->controller->redirect($member->Link( "?success=1"));
	}
}