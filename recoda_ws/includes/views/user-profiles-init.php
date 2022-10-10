<?php 

function recoda_workspace_on_init() {
	if( 'not-exist' === get_option( 'recoda_workspace_user_preference', 'not-exist' )){
		$default = json_encode(array("init"=>'initialized'));
		add_option( 'recoda_workspace_user_preference' , $default , '' , 'no');
	}
  }
  recoda_workspace_on_init();

?>
