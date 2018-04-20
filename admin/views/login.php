<?php defined( '_INCLUDE' ) or die(); ?>
		<form method="post" action="" id="authForm">
			<h1 class="authTitle">[form_title]</h1>
			[message]
			<input type="hidden" name="auth" value="1" />
			<label class="authLabel">[uname_label]
				<input type="text" name="uname" class="authField" maxlength="[input_max_length]" autofocus required />
			</label>
			<label class="authLabel">[password_label]
				<input type="password" name="password" class="authField" maxlength="[password_max_length]" required />
			</label>
			<label class="authLabel lessMarginBottom">[captcha_label]
				<input type="number" name="math" class="authField" placeholder="[captcha_holder]" required />
			</label>
			<div class="authCheckBlock">
				<a href="[restore_password_uri]">[restore_password_label]</a>
				<div class="fr">
					<label><input type="checkbox" name="remember_me" value="1" />&nbsp;[remember_me_label]</label>
				</div>
			</div>
				<div class="authCheckBlock">
					<button type="submit" class="submit fr">[button_submit]</button>
				</div>
			</div>
		</form>