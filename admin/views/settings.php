<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="">
	<input type="hidden" name="set" value="1" />
	<label class="label">[home_uri_label]
		<input type="url" name="home_uri" class="field" value="[home_uri_value]" maxlength="[max_input_length]" required />
	</label>
	<label class="label">
		<div class="text">
			[home_redirect_uri_label]
			<a href="javascript:void(0);" class="formHelpLink">?</a>
			<div class="formHelpWindow">
				[home_redirect_uri_help]
			</div>
		</div>
		<input type="url" name="home_page_redirect" class="field" value="[home_redirect_uri_value]" maxlength="[max_input_length]" />
	</label>
	<div class="checks half">
		<span class="text">[langs_label]</span>
		<select name="sys_language">
			[langs_options]
		</select>
	</div>
	<div class="checks half">
		<span class="text">
			[browser_lang_label]
			<a href="javascript:void(0);" class="formHelpLink">?</a>
			<div class="formHelpWindow">
				[browser_lang_help]
			</div>
		</span>
		<select name="browser_language">
			[browser_lang_options]
		</select>
	</div>
	<div class="checks half">
		<span class="text">[date_format_label]</span>
		<select name="date_format">
			[date_formats]
		</select>
	</div>
	<div class="checks half">
		<span class="text">[time_format_label]</span>
		<select name="time_format">
			[time_formats]
		</select>
	</div>
	<button type="submit" class="submit">[submit]</button>
</form>