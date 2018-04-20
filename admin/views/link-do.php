<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="">
	<input type="hidden" name="link_do" value="1" />
	<label class="label">
		<div class="text">
			[name_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="text" name="title" class="field" value="[name_value]" maxlength="[max_input_length]" required autofocus />
	</label>
	<label class="label">
		<div class="text">
			[alias_label]
			<a href="javascript:void(0);" class="formHelpLink">?</a>
			<div class="formHelpWindow">
				[alias_help]
			</div>
		</div>
		<input type="text" name="alias" class="field lowercase" value="[alias_value]" maxlength="[max_input_length]" />
	</label>
	<div class="checks">
		<span class="text">
			[type_label]
			<span class="requiredField" title="[required]">*</span>
		</span>
		<select name="code" id="selectLinkCode" onchange="siwtchType();">
			[types]
		</select>
	</div>
	<div class="checks dn" id="selectFile">
		<a href="[files_url]" class="openFiles">[file_label]</a>
	</div>
	<div class="checks">
		<span class="text">[extra_features_label]</span>
		<select name="meta-extra_features" id="selectExtraFeature" onchange="siwtchFeatures();">
			[extra_features]
		</select>
	</div>
	<div class="dn" id="enterDelayPage">
		<label class="label">
			<div class="text">
				[delay_page_label]
				<span class="requiredField" title="[required]">*</span>
				<a href="javascript:void(0);" class="formHelpLink">?</a>
				<div class="formHelpWindow">
					[delay_page_help]
				</div>
			</div>
			<input type="url" name="delay_page" class="field" id="delayPageField" value="[delay_page_value]" maxlength="[max_input_length]" />
		</label>
		<label class="label">[delay_time_label]
			<input type="number" name="meta-delay_time" class="field" value="[delay_time_value]" min="[delay_time_min]" max="[delay_time_max]" />
		</label>
	</div>
	<label class="label dn" id="setPassLabel">
		<div class="text">
			[password_label]
			<span class="requiredField" title="[required]">*</span>
		</div>
		<input type="password" name="password" class="field" id="setPassField" placeholder="[password_holder]" maxlength="[max_pass_length]" />
	</label>
	<label class="label">
		<div class="text">
			[target_label]
			<span class="requiredField" title="[required]">*</span>
			<a href="javascript:void(0);" class="formHelpLink">?</a>
			<div class="formHelpWindow">
				[target_help]
			</div>
		</div>
		<input type="url" name="target" class="field" id="targetField" value="[target_value]" maxlength="[max_input_length]" required />
	</label>
	<div class="checks">
		<span class="text">
			[category_label]
			<span class="requiredField" title="[required]">*</span>
		</span>
		<select name="category">
			<option value="0">[no_category_option]</option>
			[categories]
		</select>
	</div>
	<button type="submit" class="submit">[submit_button]</button>
</form>
<script type="text/javascript">
	function siwtchType() {
		var type = $('#selectLinkCode').val();
		if ( type === '1001' ) {
			$('#selectFile').fadeIn(0);
		} else {
			$('#selectFile').fadeOut(0);
		}
	}
	function siwtchFeatures() {
		var type = $('#selectExtraFeature').val();
		if ( type === 'delay' ) {
			$('#enterDelayPage').fadeIn(0);
			$('#delayPageField').attr('required', 'required');
			$('#setPassLabel').fadeOut(0);
		} else if ( type === 'password' ) {
			$('#setPassLabel').fadeIn(0);
			$('#enterDelayPage').fadeOut(0);
			$('#delayPageField').removeAttr('required');
		} else {
			$('#setPassLabel').fadeOut(0);
			$('#enterDelayPage').fadeOut(0);
			$('#delayPageField').removeAttr('required');
		}
	}
	$(document).ready(function() {
		siwtchType();
		siwtchFeatures();
	});
</script>