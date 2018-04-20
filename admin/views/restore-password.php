<?php defined( '_INCLUDE' ) or die(); ?>
		<form method="post" action="" id="authForm">
			<h1 class="authTitle">[form_title]</h1>
			[message]
			<input type="hidden" name="restore" value="1" />
			<label class="authLabel">[uname_label]
				<input type="text" name="uname" class="authField" maxlength="[input_max_length]" required autofocus />
			</label>
			<label class="authLabel">[email_label]
				<input type="text" name="email" class="authField" maxlength="[input_max_length]" required />
			</label>
			<label class="authLabel lessMarginBottom">[captcha_label]
				<input type="number" name="math" class="authField" placeholder="[captcha_holder]" required />
			</label>
				<div class="authCheckBlock">
					<a href="[login_page_url]" class="submit grey">[login_page_link]</a>
					<button type="submit" class="submit fr">[button_submit]</button>
				</div>
			</div>
		</form>