<?php

/**
* 
*  Member Account Edit Form
* 
*  Full Tutorial on SSbits.com
* 
*  @package member-accounts
* 
*/
class MemberAccountEditForm extends Form 
{
    function __construct($controller, $name) 
	{
		// Create the fields
	    $fields = new FieldList(
		    new TextField('Name', '* Name'),
			new EmailField('Email', '* Email'),
			$passwordField = new ConfirmedPasswordField('Password','Password')
		);

		//Needed to avoid users having to add password every time
		$passwordField->canBeEmpty = true;

	    //Create action
	    $actions = new FieldList(
	    	new FormAction('SubmitForm', 'Save changes')
	    );
		
		// Set the Validator
		$validator = new RequiredFields('Name', 'Email');

        parent::__construct($controller, $name, $fields, $actions, $validator);

        //Load the current members info into the edit form
        $this->loadDataFrom(Member::currentUser());
    }

	function SubmitForm($data, $form) 
	{
		if($member = Member::CurrentUser())
		{
		  	//We need to check that the email address is not already in use
	 		if(Member::get()->filter("Email", $data['Email'])->exclude('ID', $member->ID)->first()) 
	        {
	            //Set error message
	            $form->AddErrorMessage('Email', "Sorry, a member with email ". $data['Email'] ." exists. Please choose another.", 'bad');       
	            //Set form data from submitted values
				Session::set("FormInfo.{$this->FormName()}.data", $data);     
	            
	            //Return back to form
	            return $form->controller->redirectBack();          
	        }   
	 
	        //Otherwise create new member and log them in
	        $form->saveInto($member);            
	        $member->write();

			//Redirect to the account page
			return $form->controller->redirect($member->ViewLink( "?edited=1"));			
		}
		else
		{
			//If no member return a forbidden error
			return $form->controller->httpError('403');
		}
	}
}