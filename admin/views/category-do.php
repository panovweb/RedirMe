<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="">
	<input type="hidden" name="cat_do" value="1" />
	<label class="label">
		<div class="text">
			[name_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="text" name="title" class="field" value="[name_value]" maxlength="[max_input_length]" required autofocus />
	</label>
	<div class="checks">
		<span class="text">[paren_label]</span>
		<select name="parent">
			<option value="0">[no_parent]</option>
			[parents]
		</select>
	</div>
	<button type="submit" class="submit">[submit]</button>
	[remove]
</form>