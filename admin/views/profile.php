<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="">
	<input type="hidden" name="upd_prof" value="1" />
	<label class="label">[uname_label]
		<input type="text" name="uname" class="field" value="[uname_value]" maxlength="[max_input_length]" required readonly />
	</label>
	<label class="label">[email_label]
		<input type="text" name="email" class="field" value="[email_value]" maxlength="[max_input_length]" required />
	</label>
	<label class="label">[fname_label]
		<input type="text" name="fname" class="field" value="[fname_value]" maxlength="[max_input_length]" required />
	</label>
	<label class="label">[lname_label]
		<input type="text" name="lname" class="field" value="[lname_value]" maxlength="[max_input_length]" required />
	</label>
	<label class="label">[password_label]
		<input type="password" name="password" class="field" placeholder="[password_placeholder]" maxlength="[max_password_length]" />
	</label>
	<label class="label">[repeat_password_label]
		<input type="password" name="repeat" class="field" maxlength="[max_password_length]" />
	</label>
	<button type="submit" class="submit">[submit]</button>
</form>