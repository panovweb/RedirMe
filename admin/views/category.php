<?php defined( '_INCLUDE' ) or die(); ?>
[message]
<form method="post" action="" id="searchLinksForm"></form>
<form method="post" action="">
	<input type="hidden" name="remove_links" value="1" />
	<button type="submit" class="submit grey"[disabled]>[remove]</button>
	[edit_cat_link]
	<input type="text" name="s" class="searchField" form="searchLinksForm" placeholder="[search_placeholder]" value="[query]" maxlength="[max_input_length]" />
	<section class="block links">
		<div class="row top">
			<section class="check">
				<input type="checkbox" class="checkAll" />
			</section>
			<section class="name">[name_label]</section>
			<section class="target">[target_label]</section>
			<section class="author">[author_label]</section>
			<section class="hits">[hits_label]</section>
			<section class="copy">[copy_label]</section>
		</div>
	[links]
		<div class="row bottom">
			<section class="check">
				<input type="checkbox" class="checkAll" />
			</section>
			<section class="name">[name_label]</section>
			<section class="target">[target_label]</section>
			<section class="author">[author_label]</section>
			<section class="hits">[hits_label]</section>
			<section class="copy">[copy_label]</section>
		</div>
	</section>
	<button type="submit" class="submit grey"[disabled]>[remove]</button>
	[edit_cat_link]
</form>