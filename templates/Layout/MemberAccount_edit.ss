<div class="content-container unit size3of4 lastUnit">
	<% if $CurrentUser %>
		<article>
			<h1>Edit your account details</h1>

			$AccountEditForm
		</article>
	<% else %>
		<p>You must be <a href="Security/login">logged in</a> to edit your account.</p>
	<% end_if %>
</div>