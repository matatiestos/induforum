<?php

	session_start();

	// Check user logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: /en/login/');
		exit;
	}

?>

<section id="content">
<header>
	<hgroup>
		<h1>Configuración de cuenta</h1>
	</hgroup>
</header>
<article>
	<nav class="tabs_nav">
		<ul>
			<li class="current">Session</li>
			<li><a href="/en/account_settings/password/">Password</a></li>
<?php
	if (isset($_SESSION['user_can_invite']) && $_SESSION['user_can_invite']) {
		echo '<li><a href="/en/account_settings/invite/">Invite</a></li>';
	}
	if (isset($_SESSION['user_can_share_permissions']) && $_SESSION['user_can_share_permissions']) {
		echo '<li><a href="/en/account_settings/permissions/">Permissions</a></li>';
	}
	if (isset($_SESSION['user_can_view_statistics']) && $_SESSION['user_can_view_statistics']) {
		echo '<li><a href="/en/account_settings/statistics/">Statistics</a></li>';
	}
?>
		</ul>
	</nav>
	<div class="tabs_nav_div"></div>
	<p>To log out, click the button bellow:</p>
	<form action="/en/logout/" method="post">
		<input type="submit" value="Log out" accesskey="x" />
	</form>
</article>
<footer>
	<p class="section_title">Configuración de cuenta</p>
</footer>
</section>
