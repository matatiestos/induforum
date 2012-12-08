<section id="content">
<header>
	<hgroup>
		<h1>Log in</h1>
	</hgroup>
</header>
<article>
	<header>
		<hgroup>
			<h1 id="Resume">Access form</h1>
		</hgroup>
		<hr />
	</header>

<?php

	require_once('../config.php');

	// Connect to the database
	$db = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

	// Check for database connection errors
	if (mysqli_connect_errno()) {

		echo '<p class="error"><strong>Error: </strong>could not connect to the database. Please, try again later.</p>';

	}

	if (isset($_POST['type']) && $_POST['type'] == 'login_form') {

		// Form data overrides any other data

		$spd['user'] = mysqli_real_escape_string($db, trim($_POST['user']));
		$spd['pass'] = mysqli_real_escape_string($db, trim($_POST['pass']));

		// Check if all fields have a non-empty value
		if ($spd['user'] != "" &&
			$spd['pass'] != "") {

			// Try to get data from the database
			$query = "select * from students where registration_number='".$spd['user']."'";
			$result = mysqli_query($db, $query);
			$num_results = mysqli_num_rows($result);

			if ($num_results) {

				// $num_results should be 1 in this case

				$row = mysqli_fetch_assoc($result);

				if ($spd['user'] == $row['registration_number'] &&
				    hash('sha512', $db_salt.$spd['pass']) == $row['password']) {
					echo '<p class="info">Successful login.</p>';
				} else {
					echo '<p class="error"><strong>Error: </strong>Wrong user name or password. Please, try again.</p>';
				}

			} else {
				echo '<p class="error"><strong>Error: </strong>Wrong user name or password. Please, try again.</p>';
			}

		} else {

			// Need to fill more fields in the form
			echo '<p class="warning">Please, fill all the required fields in the form!</p>';

		}

	}

	// Close database connection
	mysqli_free_result($result);
	mysqli_close($db);

?>

	<form action="" method="post">
		<fieldset>
			<legend>Log in:</legend>
			<div class="form_warp">
				<label for="form_user" class="singleline">User: <span class="form_required" title="This field is required">*</span></label>
				<input type="text" maxlength="30" name="user" id="form_user" class="singleline" required="required" value="<?php if (isset($spd['user'])) echo $spd['user']; ?>" />
				<label for="form_pass" class="singleline">Password: <span class="form_required" title="This field is required">*</span></label>
				<input type="password" maxlength="60" name="pass" id="form_pass" class="singleline" required="required" />
			</div>
		</fieldset>
		<input type="hidden" name="type" value="login_form" />
		<input type="submit" value="Save" accesskey="x" />
	</form>
</article>
<footer>
	<p class="section_title">Log in</p>
</footer>
</section>
