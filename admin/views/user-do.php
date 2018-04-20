<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="">
	<input type="hidden" name="user_do" value="1" />
	<label class="label">
		<div class="text">
			[uname_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="text" name="uname" class="field lowercase" value="[uname_value]" maxlength="[max_input_length]" required autofocus />
	</label>
	<label class="label">
		<div class="text">
			[email_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="text" name="email" class="field" value="[email_value]" maxlength="[max_input_length]" required />
	</label>
	<label class="label">
		<div class="text">
			[fname_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="text" name="fname" class="field" value="[fname_value]" maxlength="[max_input_length]" required />
	</label>
	<label class="label">
		<div class="text">
			[lname_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="text" name="lname" class="field" value="[lname_value]" maxlength="[max_input_length]" required />
	</label>
	<div class="checks">
		<span class="text">[perms_label]</span>
		[perms]
	</div>
	<button type="submit" class="submit">[submit]</button>
	[send_pass]
</form>