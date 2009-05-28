<div>
	<form action="/" method="post" enctype="multipart/form-data">
		<table>
			<tr>
				<th>User Id</th>
				<td><input type="text" name="user" value="{if $smarty.get.user}{$smarty.get.user}{else}guest{/if}" maxlength="40" /></td>
			</tr>
			
			<tr>
				<th>Image to read</th>
				<td><input type="file" name="image" onchange="this.form.submit()" /></td>
			</tr>

			<tr>
				<td>
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</table>
	</form>
</div>
