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
		//Create and configure the Image field (lot's of code here :( )
		$imageField = new UploadField('Avatar', 'Profile Picture');
		$imageField->setCanAttachExisting(false);
		$imageField->setCanPreviewFolder(false);
		$imageField->setOverwriteWarning(true);
		$imageField->setAllowedMaxFileNumber(1);
		$imageField->setFileEditFields(new FieldList());
		$imageField->setFolderName('Uploads/profile-pictures');
		//Hack to ensure we don't get an edit or delete button
		Requirements::customCSS("
			button.ss-uploadfield-item-edit,
			button.ss-uploadfield-item-delete{
				display:none !important;
			}
		");
		
		//Create and configure password field
		$passwordField = new ConfirmedPasswordField('Password','Password');
		$passwordField->canBeEmpty = true;

		// Create the fields
	    $fields = new FieldList(
		    new TextField('Name', '* Name'),
			new EmailField('Email', '* Email'),
			new TextField('TwitterHandle', 'Twitter Handle'),
			new TextField('Website', 'Website'),			
			$imageField,
			$passwordField
		);	

	    //Create action
	    $actions = new FieldList(
	    	new FormAction('SubmitForm', 'Save changes')
	    );
		
		// Set the Validator
		$validator = new RequiredFields('Name', 'Email');

		//Create the form
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
			return $form->controller->redirect($member->Link( "?edited=1"));			
		}
		else
		{
			//If no member return a forbidden error
			return $form->controller->httpError('403');
		}
	}
}