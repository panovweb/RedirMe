<?php defined( '_INCLUDE' ) or die();
$config = new RedirMe\Config;
$incder = new RedirMe\Includer;
$uri = new RedirMe\URI;
$user = new Redirme\User;
$primary = new RedirMe\Primary;
$lang = new RedirMe\Language;
echo "\n";
if ( $user->logged() ) { ?>
		</section><!-- #mainContent -->
		<footer id="footer">
			<section class="menu">
				<a href="<?= $uri->home() . "/?action={$config->actions['copyrights']}"; ?>"><?= $lang->_tr( 'Copyrights', 1 ); ?></a>
			</section>
			<section class="copyright">
				<?= "&copy;&nbsp;Copyright&nbsp;" . date( 'Y' ) . ",&nbsp;<i>{$primary->systemName()}</i>&nbsp;v{$config->product_version}&nbsp;{$lang->_tr( 'by', 1 )}&nbsp;"; ?>
				<i><a href="<?= $config->company_uri; ?>" target="_blank"><?= $config->company_name; ?></a></i>
			</section>
		</footer>
		<div class="whiteWindow" id="updatingProgress">
			<section id="updateProcess">
				<img src="<?= $uri->home() . '/' . $incder->img_dir; ?>update.gif" />
			</section>
		</div>
		<div class="screen" id="fileManager"></div>
		<script type="text/javascript">
			// Opens/Closes the main menu by clicking the special link (for mobile/tablet browsers)
			$(document).ready(function() {
				var flag = 0;
				$('#openMobileMenu').click(function() {
					if (flag == 0) {
						$('#leftBar').animate({left: '0'}, 200 );
						flag = 1;
					} else if(flag == 1) {
						$('#leftBar').animate({left: '-100%'}, 200 );
						flag = 0;
					}
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				// Opens the File Picker
				$("#mainContent").on("click", '.openFiles', function(event) {
					event.preventDefault();
					$('#fileManager').fadeIn(200);
					$('body').css('overflow', 'hidden');
					$.get($(this).attr('href'), function(data) {
						document.getElementById('fileManager').innerHTML = data;
					});
				});
				// Toggles files in the File Picker by clicking on them
				$('#fileManager').on( 'change', '.fmCheckFile', function() {
					$(this).parents('label').addClass( 'checked' ).siblings('label').removeClass('checked');
					$('#fmDeletePerm,#fmInsertHere').removeAttr('disabled');
				});
			});
		</script>
		<script type="text/javascript">
			// Toggle between tabs
			$(window).on( 'load', function() {
				$('body').on( 'click', '.tabs a', function() {
					switch_tabs($(this));
				});
				switch_tabs($('.defaulttab'));
			});
			function switch_tabs(obj){
				$('.tab-content').hide();
				$('.tabs a').removeClass("selected");
				var id = obj.attr("rel");
				$('#'+id).show();
				obj.addClass("selected");
			}
		</script>
		<script type="text/javascript">
			// Uploading File(s) via AJAX. Uses in the File Picker
			var files;
			$('#fileManager').on( 'change', '#fmUploadAjax', function() {
				$('.ajax-respond').html( '<?= $lang->_tr( 'Uploading...', 1 ); ?>' );
				files = this.files;
				event.stopPropagation();
				event.preventDefault();
				var data = new FormData();
				$.each( files, function( key, value ){
					data.append( key, value );
				});
				$.ajax({
					url: '<?= $uri->home() . '/?action=file-picker&upload=1'; ?>',
					type: 'POST',
					data: data,
					cache: false,
					dataType: 'json',
					processData: false,
					contentType: false,
					success: function( respond, textStatus, jqXHR ) {
						if( typeof respond.error === 'undefined' ) {
							var files_path = respond.files;
							var html = '';
							$.each( files_path, function( key, val ){ html += val +'<br>'; } )
							$('.ajax-respond').html( html );
						} else {
							alert('<?= $lang->_tr( 'Error server answer', 1 ); ?>: ' + respond.error );
						}
					},
					error: function( jqXHR, textStatus, errorThrown ) {
						alert('<?= $lang->_tr( 'Error AJAX request', 1 ); ?>: ' + textStatus );
					}
				});
			});
		</script>
		<script type="text/javascript">
			// The function to send a post data from a form to a script via AJAX
			function ajax_form_post( result_id, form_id, url ) {
				$.ajax( {
					url: url,type: "POST",dataType: "html",data: $("#"+form_id).serialize(),
					success: function(response) {
						document.getElementById(result_id).innerHTML = response;
					},
					error: function(response) {
						document.getElementById(result_id).innerHTML = "<?= $lang->_tr( 'Error sending the request', 1 ); ?>";
					}
				});
			}
		</script>
		<script type="text/javascript">
			// Closing a modal window with the '.screen' class by pressing the 'Esc' key or clicking a button with the '.close' class
			$(document).ready(function(){
				$(this).keydown(function(eventObject){
					if (eventObject.which == 27) {
						$('.screen').fadeOut(200);
						$('body').css('overflow', 'auto');
					}
				});
				$('body').on( 'click', '.close', function() {
					$('.screen').fadeOut(200);
					$('body').css('overflow', 'auto');
				});
			});
		</script>
		<script type="text/javascript">
			// Styling checkboxes
			$(document).ready(function() {
				$('input').iCheck( {
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					checkedClass: 'checked',
					increaseArea: '20%'
				});
			});
		</script>
		<script type="text/javascript">
			// Styling select boxes
			$('select').chosen({width: '70%'});
		</script>
		<script type="text/javascript">
			// Show/Hide help modal window
			$("#mainContent").on("mouseover", ".formHelpLink", function() {
				$(this).next('.formHelpWindow').fadeIn(0);
			});
			$("#mainContent").on("mouseout", ".formHelpLink", function() {
				$(this).next('.formHelpWindow').fadeOut(0);
			});
		</script>
		<script type="text/javascript">
			// Selects all checkboxes with same class in the table when a checkbox with the '.checkAll' class is selected
			$(document).ready(function() {
				$('.checkAll').on('ifChecked', function(event) {
					$('.autoCheck,.checkAll').iCheck('check');
				});
				$('.checkAll').on('ifUnchecked', function(event) {
					$('.autoCheck,.checkAll').iCheck('uncheck');
				});
			});
		</script>
		<script type="text/javascript">
			// Copying a redirect URL to the clipboard
			var clipboard = new Clipboard('a.copy');
				clipboard.on('success', function(e) {
					alert( '<?= $lang->_tr( 'The link URL address was successfully copied to clipboard!', 1 ); ?>' );
					e.clearSelection();
				});
					clipboard.on('error', function(e) {
					alert( '<?= $lang->_tr( 'Error copying to clipboard', 1 ); ?>' );
				});
		</script>
<?php } ?>
	</body>
</html>