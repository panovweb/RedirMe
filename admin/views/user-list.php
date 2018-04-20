<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="" id="searchUsersForm">
	<input type="hidden" name="search_users" value="1" />
</form>
<form method="post" action="">
	<input type="hidden" name="remove_users" value="1" />
	<button type="submit" class="submit grey"[disabled]>[remove]</button>
	<input type="text" name="s" class="searchField" value="[search_field_value]" placeholder="[search_field_placeholder]" form="searchUsersForm" maxlength="[max_input_length]" />
	<section class="block users">
		<div class="row top">
			<section class="check">
				<input type="checkbox" class="checkAll" />
			</section>
			<section class="uname">[uname_label]</section>
			<section class="name">[name_label]</section>
			<section class="date">[reg_date_label]</section>
		</div>
	[users]
		<div class="row bottom">
			<section class="check">
				<input type="checkbox" class="checkAll" />
			</section>
			<section class="uname">[uname_label]</section>
			<section class="name">[name_label]</section>
			<section class="date">[reg_date_label]</section>
		</div>
	</section>
	<button type="submit" class="submit grey"[disabled]>[remove]</button>
</form>