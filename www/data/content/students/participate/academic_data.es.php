<?php

	session_start();

	// Check user logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: /en/login/');
		exit;
	}

	// Check user privileges
	if ($_SESSION['type'] != 'student_session') {
		header('Location: /en/restricted_area/');
		exit;
	}

?>

<section id="content">
<header>
	<hgroup>
		<h1>Participación</h1>
	</hgroup>
</header>
<article>
	<nav id="participate_nav">
		<ul>
			<li><a href="/es/students/participate/personal_data/">Personal</a></li>
			<li class="current">Académico</li>
			<li><a href="/es/students/participate/languages/">Idiomas</a></li>
			<li><a href="/es/students/participate/proffessional_experience/">Profesional</a></li>
			<li><a href="/es/students/participate/computer_science/">Infomática</a></li>
		</ul>
	</nav>
	<div id="participate_nav_div"></div>

<?php

	$student_number = $_SESSION['user_id'];

	require_once('../../../config.php');

	// Connect to the database
	$db = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

	// Check for database connection errors
	if (mysqli_connect_errno()) {

		echo '<p class="error"><strong>Error: </strong>could not connect to the database. Please, try again later.</p>';

	}

	if (!mysqli_set_charset($db, 'utf8')) {

		echo '<p class="error"><strong>Error: </strong>could not set charset to UTF8. Please, try again later.</p>';

	}

	if (isset($_POST['type']) && $_POST['type'] == 'student_academic_data') {

		// Form data overrides any other data

		$spd['studies'] = mysqli_real_escape_string($db, trim($_POST['studies']));
		$spd['higher_course'] = mysqli_real_escape_string($db, trim($_POST['higher_course']));
		$spd['speciality'] = mysqli_real_escape_string($db, trim($_POST['speciality']));
		$spd['begin_year'] = mysqli_real_escape_string($db, trim($_POST['begin_year']));
		$spd['additional_information'] = mysqli_real_escape_string($db, trim($_POST['additional_information']));

		// Check if all fields have a non-empty value
		if ($spd['studies'] != "" &&
			$spd['higher_course'] != "" &&
			$spd['speciality'] != "" &&
			$spd['begin_year'] != "") {

			// Try to add a new row
			$query = "insert into academic_data values
									('".$student_number."',
									'".$spd['studies']."',
									'".$spd['higher_course']."',
									'".$spd['speciality']."',
									'".$spd['begin_year']."',
									'".$spd['additional_information']."')";
			// Check if we wrote the new row, otherwise, data existed for this student
			$result = mysqli_query($db, $query);

			// If data existed for this student, update it
			if (!$result) {
				$query = "update academic_data set
						   studies='".$spd['studies']."',
						   higher_course='".$spd['higher_course']."',
						   speciality='".$spd['speciality']."',
						   begin_year='".$spd['begin_year']."',
						   additional_information='".$spd['additional_information']."'
						   where student_number='".$student_number."'";
				$result = mysqli_query($db, $query);
			}

			// Inform the user about the operation
			if ($result) echo '<p class="info">Data saved successfuly.</p>';
			else echo '<p class="error"><strong>Error: </strong>could not write to the database. Please, try again later.</p>';

		} else {

			// Need to fill more fields in the form
			echo '<p class="warning">Please, fill all the required fields in the form!</p>';

		}

	} else {

		// Try to get data from the database
		$query = "select * from academic_data where student_number='".$student_number."'";
		$result = mysqli_query($db, $query);

		if ($result) {

			$num_results = mysqli_num_rows($result);

			for ($i=0; $i<$num_results; $i++) { // $num_results should be 1 in this case

				$row = mysqli_fetch_assoc($result);

				$spd['studies'] = $row['studies'];
				$spd['higher_course'] = $row['higher_course'];
				$spd['speciality'] = $row['speciality'];
				$spd['begin_year'] = $row['begin_year'];
				$spd['additional_information'] = $row['additional_information'];
			}

		}

	}

	// Close database connection
	mysqli_free_result($result);
	mysqli_close($db);

?>

	<form action="" method="post">
		<fieldset>
			<legend>Formación académica:</legend>
			<div class="form_wrapper">
				<label for="form_degree" class="singleline">Titulo: <span class="form_required" title="This field is required">*</span></label>
				<select name="studies" id="form_degree" required="required" class="singleline">
					<option value=""></option>
					<option value="industrialengineering" <?php if (isset($spd['studies'])&&$spd['studies']=="industrialengineering") echo 'selected="selected"'?>>Ingenieria Industrial</option>
					<option value="chemicalengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="chemicalengineering") echo 'selected="selected"'?>>Ingenieria Química</option>
					<option value="organizationengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="organizationengineering") echo 'selected="selected"'?>>Ingenieria Organización Industrial</option>
					<option value="electronicengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="electronicengineering") echo 'selected="selected"'?>>Ingeniero Automática y Electrónica Industrial</option>
					<option value="industrialtechengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="industrialtechengineering") echo 'selected="selected"'?>>Grado en Ingeniería en Tecnologías Industriales</option>
					<option value="electricengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="electricengineering") echo 'selected="selected"'?>>Grado en Ingeniería Eléctrica</option>
					<option value="automaticengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="chemicalengineering") echo 'selected="selected"'?>>Grado en Ingeniería Electrónica Industrial y Automática</option>
					<option value="mechanicalengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="chemicalengineering") echo 'selected="selected"'?>>Grado en Ingeniería Mecánica</option>
					<option value="orgnizationgradeengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="chemicalengineering") echo 'selected="selected"'?>>Grado en Ingeniería de Organización</option>
					<option value="chemicalgradeengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="chemicalengineering") echo 'selected="selected"'?>>Grado en Ingeniería Química</option>
					<option value="energyengineering"<?php if (isset($spd['studies'])&&$spd['studies']=="chemicalengineering") echo 'selected="selected"'?>>Grado en Ingeniería de la Energía</option>


				</select>
				<label for="form_course" class="singleline">Curso más alto: <span class="form_required" title="This field is required">*</span></label>
				<select name="higher_course" id="form_course"  required="required" class="singleline">
					<option value=""></option>
					<option value="3" <?php if (isset($spd['higher_course'])&&$spd['higher_course']=="3") echo 'selected="selected"'?>>3</option>
					<option value="4" <?php if (isset($spd['higher_course'])&&$spd['higher_course']=="4") echo 'selected="selected"'?>>4</option>
					<option value="5" <?php if (isset($spd['higher_course'])&&$spd['higher_course']=="5") echo 'selected="selected"'?>>5</option>
				</select>
				<label for="form_speciality" class="singleline">Especialidad:<span class="form_required" title="This field is required">*</span></label>
					<select name="speciality" id="form_speciality" required="required" class="singleline">
						<option value=""></option>
						<option value="Electric" <?php if (isset($spd['speciality'])&&$spd['speciality']=="Electric") echo 'selected="selected"'?>>Eléctrica</option>
						<option value="Mechanic" <?php if (isset($spd['speciality'])&&$spd['speciality']=="Mechanic") echo 'selected="selected"'?>>Mecánica - Máquinas</option>
						<option value="Electronic"<?php if (isset($spd['speciality'])&&$spd['speciality']=="Electronic") echo 'selected="selected"'?>>Electrónica - Automática</option>
						<option value="Construction" <?php if (isset($spd['speciality'])&&$spd['speciality']=="Building") echo 'selected="selected"'?>>Mecánica - Construcción</option>
						<option value="Material" <?php if (isset($spd['speciality'])&&$spd['speciality']=="Material") echo 'selected="selected"'?>>Materiales</option>
						<option value="Organization"<?php if (isset($spd['speciality'])&&$spd['speciality']=="Organization") echo 'selected="selected"'?>>Organización Industrial</option>
						<option value="Chemical" <?php if (isset($spd['speciality'])&&$spd['speciality']=="Chemical") echo 'selected="selected"'?>>Química Industrial y Medio Ambiente</option>
						<option value="Energetic" <?php if (isset($spd['speciality'])&&$spd['speciality']=="Energetic") echo 'selected="selected"'?>>Técnicas Energéticas</option>
						<option value="Manufacturing"<?php if (isset($spd['speciality'])&&$spd['speciality']=="Manufacturing") echo 'selected="selected"'?>>Fabricación</option>

					</select>
				<label for="form_startingyear" class="singleline">Año de comienzo:<span class="form_required" title="This field is required">*</span></label>
						<select name="begin_year" id="form_startingyear"  required="required" class="singleline">
							<option value=""></option>
							<option value="2005" <?php if (isset($spd['begin_year'])&&$spd['begin_year']=="2005") echo 'selected="selected"'?>>2005</option>
							<option value="2006" <?php if (isset($spd['begin_year'])&&$spd['begin_year']=="2006") echo 'selected="selected"'?>>2006</option>
							<option value="2007" <?php if (isset($spd['begin_year'])&&$spd['begin_year']=="2007") echo 'selected="selected"'?>>2007</option>
							<option value="2008" <?php if (isset($spd['begin_year'])&&$spd['begin_year']=="2008") echo 'selected="selected"'?>>2008</option>
							<option value="2009" <?php if (isset($spd['begin_year'])&&$spd['begin_year']=="2009") echo 'selected="selected"'?>>2009</option>
							<option value="2010" <?php if (isset($spd['begin_year'])&&$spd['begin_year']=="2010") echo 'selected="selected"'?>>2010</option>
						</select>
				<label for="form_additionalinfo" class="singleline">Información adicional:</label>
				<textarea name="additional_information" id="form_additionalinfo" cols="50" rows="10" class="singleline"><?php if (isset($spd['additional_information'])) echo str_replace('\r\n',"\r\n",$spd['additional_information']); ?></textarea>
			</div>
		</fieldset>
		<input  type="hidden" name="type" value="student_academic_data" />
		<input type="submit" value="Save" accesskey="x" />
	</form>
</article>
<footer>
	<p class="section_title">Participación</p>
</footer>
</section>
