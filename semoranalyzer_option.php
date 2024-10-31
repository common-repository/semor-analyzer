<div class="wrap">
	<h1>SEMOR Analyzer</h1>
 
	<form method="post" action="options.php">
		<?php
			settings_fields ( "semoranalyzer_config" );
			do_settings_sections ( "semoranalyzer" );
			submit_button ();
		?>
	 </form>
</div>