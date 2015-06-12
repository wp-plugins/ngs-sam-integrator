<?php
/**
 * This file contains the form displayed at the top and bottom of the
 * "Top Requests" page to handle navigation.
 * 
 * @since 0.1.0
 */
?>
<form method="post" action="<?php echo clean_playlist_url( $_SERVER["REQUEST_URI"] ); ?>">
<p><input type="submit" value="Prev" name="PlayListPrev">
	&nbsp;&nbsp;
	Display <select name="NumPlaylistResults" id="NumPlaylistResults" >
		<option id="List5Results" value="5" <?php if( 5 == $number_to_show ) { echo 'selected="selected"'; } ?>>5</option>
		<option id="List10Results" value="10" <?php if( 10 == $number_to_show ) { echo 'selected="selected"'; } ?>>10</option>
		<option id="List25Results" value="25" <?php if(25 ==  $number_to_show ) { echo 'selected="selected"'; } ?>>25</option>
		<option id="List50Results" value="50" <?php if( 50 == $number_to_show ) { echo 'selected="selected"'; } ?>>50</option>
		<option id="List100Results" value="100" <?php if( 100 == $number_to_show ) { echo 'selected="selected"'; } ?>>100</option>
	</select> Results
	&nbsp;&nbsp;
	Jump To: <input type="text" value="<?php echo $start_at + 1 ?>" name="PlayListJumpTo" maxlength="5"  size="5" />
	<input type="submit" value="Go" name="PlayListJump" />
	&nbsp;&nbsp;
	<input type="submit" value="Next" name="PlayListNext" /></p>
	<input type="hidden" value="<?php echo $start_at; ?>" name="PlayListStart" />
	<input type="hidden" value="<?php echo $start_at + $number_to_show; ?>" name="PlayListEnd" />
</form>
