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
	public function ViewLink($extra = null)
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
}