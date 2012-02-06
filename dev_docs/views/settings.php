<form action="<?=$form_base?>" method="post">

	<input type="hidden" name="XID" value="<?=XID_SECURE_HASH?>">
		
	<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th style="width:50%;">Preference</th>
				<th>Setting</th>
			</tr>
		</thead>
		<tbody>
			<tr class="even">
				<td>
					<strong><label for="docs_path">Docs Path</label></strong>
					<br/>
					<small>Path to your documentation flat file or directory of files.</small>
				</td>
				<td>
					<input type="text" name="docs_path" value="<?=$docs_path?>" />
				</td>
			</tr>
			<tr class="odd">
				<td>
					<strong><label for="name_override">Name Override</label></strong>
					<br/>
					<small>Name Override in the CP (for if you want your client to see something other than "Dev Docs" if you give them access to the module).</small>
				</td>
				<td>
					<input type="text" name="name_override" value="<?=$name_override?>" />
				</td>
			</tr>
			<tr class="even">
				<td>
					<strong><label for="member_groups">Member Groups</label></strong>
					<br/>
					<small>Member Groups that should see the Dev Docs pages in the CP main menu.</small>
				</td>
				<td>
					<select name="member_groups" multiple="yes">
					<?php foreach ($member_groups as $group): ?>
						<option value="<?=$group->group_id?>"><?=$group->group_title?></option>
					<?php endforeach ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>

	<?=form_submit(array('name'=>'submit', 'class'=>'submit'),'Submit')?>

</form>