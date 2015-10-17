<h3><?php echo __("Shortcode List", "bbpp-thankmelater"); ?></h3>
<p><?php echo __("You can use the following shortcodes in the from name, from email, subject and message:", "bbpp-thankmelater"); ?></p>

<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php echo _x("Shortcode", "noun", "bbpp-thankmelater"); ?></th>
			<th scope="col"><?php echo _x("Options", "noun", "bbpp-thankmelater"); ?></th>
			<th scope="col"><?php echo __("Description", "bbpp-thankmelater"); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td valign="top" width="20%"><code>[author_url]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The URL for the post author's page.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[comment]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
					<li><code>maxlength</code> <?php echo __("Maximum number of characters to show.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The comment content.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[comment_url]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The URL for the comment.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[date]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
					<li><code>format</code> <?php echo sprintf(__("%sDate format%s. e.g. 'd M Y' for '14 Jan 2013'.", "bbpp-thankmelater"), '<a href="http://php.net/date" target="_blank">', '</a>'); ?></li>
					<li><code>gmt</code> <?php echo __("Set to 1 for GMT date/time.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The date of the comment.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[email]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The email address of the commenter.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[h1]</code> - <code>[h6]</code></td>
			<td valign="top" width="30%">
			</td>
			<td valign="top">
				<?php echo __("Insert a h1-h6 heading.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[htmlonly]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>apply_shortcodes</code> <?php echo __("Set to 1 to apply shortcodes inside of tag.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("Content to show in HTML messages only.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[id]</code></td>
			<td valign="top" width="30%">
			</td>
			<td valign="top">
				<?php echo __("The ID of the comment.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[img]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>src</code> <?php echo __("URL of the image.", "bbpp-thankmelater"); ?></li>
					<li><code>width</code> <?php echo __("Width in pixels.", "bbpp-thankmelater"); ?></li>
					<li><code>height</code> <?php echo __("Height in pixels.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("Inserts an image.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[ip]</code></td>
			<td valign="top" width="30%">
			</td>
			<td valign="top">
				<?php echo __("The IP address of the commenter.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[name]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The name of the commenter.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[opt_out]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>url</code> <?php echo __("Set to 1 if you want the opt out URL only. Default: '1'", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("Get opt out message/URL", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[p]</code></td>
			<td valign="top" width="30%">
			</td>
			<td valign="top">
				<?php echo __("A paragraph of text (paragraphs are added automatically when leaving two blank lines between text).", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[post_id]</code></td>
			<td valign="top" width="30%">
			</td>
			<td valign="top">
				<?php echo __("The ID of the post.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[post_title]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The post title.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[snip]</code></td>
			<td valign="top" width="30%">
			</td>
			<td valign="top">
				<?php echo __("Hide a section of message (keeping for future reference).", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[t_part]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>name</code> <?php echo __("Name of the template part. Default: 'main'", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("A template part.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[textonly]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>apply_shortcodes</code> <?php echo __("Set to 1 to apply shortcodes inside of tag.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("Content to show in text messages only.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[user_agent]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>attr</code> <?php echo __("Set to 1 if used in HTML attribute.", "bbpp-thankmelater"); ?></li>
				</ul>
			</td>
			<td valign="top">
				<?php echo __("The user agent of the commenter.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
	</tbody>
</table>

<h3><?php echo __("Templates", "bbpp-thankmelater"); ?></h3>
<p><?php echo __("You can wrap your message in one of the following tags to use a HTML template:"); ?></p>

<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php echo _x("Shortcode", "noun", "bbpp-thankmelater"); ?></th>
			<th scope="col"><?php echo _x("Options", "noun", "bbpp-thankmelater"); ?></th>
			<th scope="col"><?php echo __("Template parts", "bbpp-thankmelater"); ?></th>
			<th scope="col"><?php echo __("Description", "bbpp-thankmelater"); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td valign="top" width="20%"><code>[t_simple]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>background_color</code> <?php echo __("Background color for email, in hexadecimal format. Default: '#F6F6F6'", "bbpp-thankmelater"); ?></li>
					<li><code>page_background_color</code> <?php echo __("Background color for content, in hexadecimal format. Default: '#FFFFFF'", "bbpp-thankmelater"); ?></li>
				</ul>				
			</td>
			<td valign="top" width="30%">
				<ul>
					<li><code>[t_part name="header_banner"]</code> <?php echo __("Content for the pre-header banner. You can insert a 600px width banner image here.", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="header"]</code> <?php echo __("Header content", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="main"]</code> <?php echo __("Main page content", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="footer"]</code> <?php echo __("Footer content", "bbpp-thankmelater"); ?></li>
				</ul>	
			</td>
			<td valign="top">
				<?php echo __("A simple template with header, main body and footer.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="20%"><code>[t_sidebar]</code></td>
			<td valign="top" width="30%">
				<ul>
					<li><code>side</code> <?php echo __("Set to 'left' for a left sidebar. Set to 'right' for a right sidebar. Default: 'right'", "bbpp-thankmelater"); ?></li>
					<li><code>background_color</code> <?php echo __("Background color for email, in hexadecimal format. Default: '#F6F6F6'", "bbpp-thankmelater"); ?></li>
					<li><code>page_background_color</code> <?php echo __("Background color for content, in hexadecimal format. Default: '#FFFFFF'", "bbpp-thankmelater"); ?></li>
				</ul>				
			</td>
			<td valign="top" width="30%">
				<ul>
					<li><code>[t_part name="header_banner"]</code> <?php echo __("Content for the pre-header banner. You can insert a 600px width banner image here.", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="header"]</code> <?php echo __("Header content", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="main"]</code> <?php echo __("Main page content", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="sidebar"]</code> <?php echo __("Sidebar content", "bbpp-thankmelater"); ?></li>
					<li><code>[t_part name="footer"]</code> <?php echo __("Footer content", "bbpp-thankmelater"); ?></li>
				</ul>				
			</td>
			<td valign="top">
				<?php echo __("A template with a sidebar.", "bbpp-thankmelater"); ?>
			</td>
		</tr>
	</tbody>
</table>