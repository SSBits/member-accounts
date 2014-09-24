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
	/*
	*	Generate the link to this Members account page
	*/
	public function Link($extra = null)
	{
		return Director::baseURL() . "account/" . $extra;
	}
}