<div class="content-container unit size3of4 lastUnit">
	
	<% if $CurrentUser %>
		<% with $CurrentUser %>
			<article>
				<% if $Top.Success %>
					<h1>$FirstName, your account has been created!</h1>
				<% else %>
					<h1>Welcome back $FirstName!</h1>
				<% end_if %>

				<p><strong>First Name:</strong> $FirstName</p>
				<p><strong>Surname:</strong> $Surname</p>
				<p><strong>Email:</strong> $Email</p>
			</article>
		<% end_with %>
	<% else %>
		<p>You must be <a href="Security/login">logged in</a> to edit your account.
	<% end_if %>
</div>