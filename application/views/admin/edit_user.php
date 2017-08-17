<?php
$this->load->view('templates/headers/admin_header', $title);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Admin</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>admin">Admin</a>
            </li>
			<li>
                <a href="<?php echo base_url(); ?>admin/manage_users">Manage Users</a>
            </li>
            <li class="active">
                <strong><?php echo $user["username"]; ?></strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<?php
	if(isset($_GET["demo"])) {
	?>
	<div class="alert alert-danger alert-centered">
		<b>Whoops!</b> This user can't be edited in demo mode.
	</div>
	<?php
	}
	?>
	<?php
	if(isset($_GET["action"])) {
	?>
	<div class="alert alert-success alert-centered">
		<b>Success!</b> This user has been edited!
	</div>
	<?php
	} else {
	?>
	<div class="alert alert-info alert-centered">
		Click on the sections of the user you want to edit.
	</div>
	<?php
	}
	?>

	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

		<div class="panel panel-success">
			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
				Public Infos </a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<form action="<?php echo base_url() ?>admin/edit_user_submit/<?php echo $user["uid"]; ?>" method="POST" class="col-md-8 col-md-offset-2 edit_form form-horizontal" enctype='multipart/form-data'>
						<h4>Who is <?php echo $user["username"]; ?>?</h4>
						<div class="form-group">
							<label class="control-label">Gender</label>
							<select name="gender" class="form-control gender" style="text-align:center !important;">
								<option value="0" selected="selected">Select your gender</option>
								<option value="male">Male</option>
								<option value="female">Female</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">Birthday</label>
							<div class="col-ws-12">
								<div class="col-xs-4">
									<select name="birthday_day" class="form-control birthday_day" style="text-align:center !important;">
										<option value="0">Day</option>
										<?php
										for($i = 1; $i <= 31; $i++) {
										?>
										<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="col-xs-4">
									<select name="birthday_month" class="form-control birthday_month" style="text-align:center !important;">
										<option value="0">Month</option>
										<option value="january">January</option>
										<option value="february">February</option>
										<option value="march">March</option>
										<option value="april">April</option>
										<option value="may">May</option>
										<option value="june">June</option>
										<option value="july">July</option>
										<option value="august">August</option>
										<option value="september">September</option>
										<option value="october">October</option>
										<option value="november">November</option>
										<option value="december">December</option>
									</select>
								</div>
								<div class="col-xs-4">
									<select name="birthday_year" class="form-control birthday_year" style="text-align:center !important;">
										<option value="0">Year</option>
										<?php
										$year_now = (int) date("Y");
										$year = $year_now - 100;
										
										for($j = $year_now; $j >= $year; $j--) {
										?>
										<option value="<?php echo $j ?>"><?php echo $j ?></option>
										<?php
										} 
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group" style="text-align:left;margin-top:20px;">
							<label class="control-label">About</label>
							<textarea name="about_you_txt" id="about_you_txt" class="form-control about_you_txt" rows="4" placeholder="Describe yourself, who you are, what you are looking for, your hobbies..."></textarea>
						</div>
						<hr />
						<h4>Where do you live?</h4>
						<div class="form-group">
							<label class="control-label">Country</label>
							<select name="country" class="form-control country" style="text-align:center !important;"> 
								<option value="0" selected="selected">Select your country</option> 
								<option value="AF">Afghanistan</option>
								<option value="AX">Åland Islands</option>
								<option value="AL">Albania</option>
								<option value="DZ">Algeria</option>
								<option value="AS">American Samoa</option>
								<option value="AD">Andorra</option>
								<option value="AO">Angola</option>
								<option value="AI">Anguilla</option>
								<option value="AQ">Antarctica</option>
								<option value="AG">Antigua and Barbuda</option>
								<option value="AR">Argentina</option>
								<option value="AM">Armenia</option>
								<option value="AW">Aruba</option>
								<option value="AU">Australia</option>
								<option value="AT">Austria</option>
								<option value="AZ">Azerbaijan</option>
								<option value="BS">Bahamas</option>
								<option value="BH">Bahrain</option>
								<option value="BD">Bangladesh</option>
								<option value="BB">Barbados</option>
								<option value="BY">Belarus</option>
								<option value="BE">Belgium</option>
								<option value="BZ">Belize</option>
								<option value="BJ">Benin</option>
								<option value="BM">Bermuda</option>
								<option value="BT">Bhutan</option>
								<option value="BO">Bolivia, Plurinational State of</option>
								<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
								<option value="BA">Bosnia and Herzegovina</option>
								<option value="BW">Botswana</option>
								<option value="BV">Bouvet Island</option>
								<option value="BR">Brazil</option>
								<option value="IO">British Indian Ocean Territory</option>
								<option value="BN">Brunei Darussalam</option>
								<option value="BG">Bulgaria</option>
								<option value="BF">Burkina Faso</option>
								<option value="BI">Burundi</option>
								<option value="KH">Cambodia</option>
								<option value="CM">Cameroon</option>
								<option value="CA">Canada</option>
								<option value="CV">Cape Verde</option>
								<option value="KY">Cayman Islands</option>
								<option value="CF">Central African Republic</option>
								<option value="TD">Chad</option>
								<option value="CL">Chile</option>
								<option value="CN">China</option>
								<option value="CX">Christmas Island</option>
								<option value="CC">Cocos (Keeling) Islands</option>
								<option value="CO">Colombia</option>
								<option value="KM">Comoros</option>
								<option value="CG">Congo</option>
								<option value="CD">Congo, the Democratic Republic of the</option>
								<option value="CK">Cook Islands</option>
								<option value="CR">Costa Rica</option>
								<option value="CI">Côte d'Ivoire</option>
								<option value="HR">Croatia</option>
								<option value="CU">Cuba</option>
								<option value="CW">Curaçao</option>
								<option value="CY">Cyprus</option>
								<option value="CZ">Czech Republic</option>
								<option value="DK">Denmark</option>
								<option value="DJ">Djibouti</option>
								<option value="DM">Dominica</option>
								<option value="DO">Dominican Republic</option>
								<option value="EC">Ecuador</option>
								<option value="EG">Egypt</option>
								<option value="SV">El Salvador</option>
								<option value="GQ">Equatorial Guinea</option>
								<option value="ER">Eritrea</option>
								<option value="EE">Estonia</option>
								<option value="ET">Ethiopia</option>
								<option value="FK">Falkland Islands (Malvinas)</option>
								<option value="FO">Faroe Islands</option>
								<option value="FJ">Fiji</option>
								<option value="FI">Finland</option>
								<option value="FR">France</option>
								<option value="GF">French Guiana</option>
								<option value="PF">French Polynesia</option>
								<option value="TF">French Southern Territories</option>
								<option value="GA">Gabon</option>
								<option value="GM">Gambia</option>
								<option value="GE">Georgia</option>
								<option value="DE">Germany</option>
								<option value="GH">Ghana</option>
								<option value="GI">Gibraltar</option>
								<option value="GR">Greece</option>
								<option value="GL">Greenland</option>
								<option value="GD">Grenada</option>
								<option value="GP">Guadeloupe</option>
								<option value="GU">Guam</option>
								<option value="GT">Guatemala</option>
								<option value="GG">Guernsey</option>
								<option value="GN">Guinea</option>
								<option value="GW">Guinea-Bissau</option>
								<option value="GY">Guyana</option>
								<option value="HT">Haiti</option>
								<option value="HM">Heard Island and McDonald Islands</option>
								<option value="VA">Holy See (Vatican City State)</option>
								<option value="HN">Honduras</option>
								<option value="HK">Hong Kong</option>
								<option value="HU">Hungary</option>
								<option value="IS">Iceland</option>
								<option value="IN">India</option>
								<option value="ID">Indonesia</option>
								<option value="IR">Iran, Islamic Republic of</option>
								<option value="IQ">Iraq</option>
								<option value="IE">Ireland</option>
								<option value="IM">Isle of Man</option>
								<option value="IL">Israel</option>
								<option value="IT">Italy</option>
								<option value="JM">Jamaica</option>
								<option value="JP">Japan</option>
								<option value="JE">Jersey</option>
								<option value="JO">Jordan</option>
								<option value="KZ">Kazakhstan</option>
								<option value="KE">Kenya</option>
								<option value="KI">Kiribati</option>
								<option value="KP">Korea, Democratic People's Republic of</option>
								<option value="KR">Korea, Republic of</option>
								<option value="KW">Kuwait</option>
								<option value="KG">Kyrgyzstan</option>
								<option value="LA">Lao People's Democratic Republic</option>
								<option value="LV">Latvia</option>
								<option value="LB">Lebanon</option>
								<option value="LS">Lesotho</option>
								<option value="LR">Liberia</option>
								<option value="LY">Libya</option>
								<option value="LI">Liechtenstein</option>
								<option value="LT">Lithuania</option>
								<option value="LU">Luxembourg</option>
								<option value="MO">Macao</option>
								<option value="MK">Macedonia, the former Yugoslav Republic of</option>
								<option value="MG">Madagascar</option>
								<option value="MW">Malawi</option>
								<option value="MY">Malaysia</option>
								<option value="MV">Maldives</option>
								<option value="ML">Mali</option>
								<option value="MT">Malta</option>
								<option value="MH">Marshall Islands</option>
								<option value="MQ">Martinique</option>
								<option value="MR">Mauritania</option>
								<option value="MU">Mauritius</option>
								<option value="YT">Mayotte</option>
								<option value="MX">Mexico</option>
								<option value="FM">Micronesia, Federated States of</option>
								<option value="MD">Moldova, Republic of</option>
								<option value="MC">Monaco</option>
								<option value="MN">Mongolia</option>
								<option value="ME">Montenegro</option>
								<option value="MS">Montserrat</option>
								<option value="MA">Morocco</option>
								<option value="MZ">Mozambique</option>
								<option value="MM">Myanmar</option>
								<option value="NA">Namibia</option>
								<option value="NR">Nauru</option>
								<option value="NP">Nepal</option>
								<option value="NL">Netherlands</option>
								<option value="NC">New Caledonia</option>
								<option value="NZ">New Zealand</option>
								<option value="NI">Nicaragua</option>
								<option value="NE">Niger</option>
								<option value="NG">Nigeria</option>
								<option value="NU">Niue</option>
								<option value="NF">Norfolk Island</option>
								<option value="MP">Northern Mariana Islands</option>
								<option value="NO">Norway</option>
								<option value="OM">Oman</option>
								<option value="PK">Pakistan</option>
								<option value="PW">Palau</option>
								<option value="PS">Palestinian Territory, Occupied</option>
								<option value="PA">Panama</option>
								<option value="PG">Papua New Guinea</option>
								<option value="PY">Paraguay</option>
								<option value="PE">Peru</option>
								<option value="PH">Philippines</option>
								<option value="PN">Pitcairn</option>
								<option value="PL">Poland</option>
								<option value="PT">Portugal</option>
								<option value="PR">Puerto Rico</option>
								<option value="QA">Qatar</option>
								<option value="RE">Réunion</option>
								<option value="RO">Romania</option>
								<option value="RU">Russian Federation</option>
								<option value="RW">Rwanda</option>
								<option value="BL">Saint Barthélemy</option>
								<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
								<option value="KN">Saint Kitts and Nevis</option>
								<option value="LC">Saint Lucia</option>
								<option value="MF">Saint Martin (French part)</option>
								<option value="PM">Saint Pierre and Miquelon</option>
								<option value="VC">Saint Vincent and the Grenadines</option>
								<option value="WS">Samoa</option>
								<option value="SM">San Marino</option>
								<option value="ST">Sao Tome and Principe</option>
								<option value="SA">Saudi Arabia</option>
								<option value="SN">Senegal</option>
								<option value="RS">Serbia</option>
								<option value="SC">Seychelles</option>
								<option value="SL">Sierra Leone</option>
								<option value="SG">Singapore</option>
								<option value="SX">Sint Maarten (Dutch part)</option>
								<option value="SK">Slovakia</option>
								<option value="SI">Slovenia</option>
								<option value="SB">Solomon Islands</option>
								<option value="SO">Somalia</option>
								<option value="ZA">South Africa</option>
								<option value="GS">South Georgia and the South Sandwich Islands</option>
								<option value="SS">South Sudan</option>
								<option value="ES">Spain</option>
								<option value="LK">Sri Lanka</option>
								<option value="SD">Sudan</option>
								<option value="SR">Suriname</option>
								<option value="SJ">Svalbard and Jan Mayen</option>
								<option value="SZ">Swaziland</option>
								<option value="SE">Sweden</option>
								<option value="CH">Switzerland</option>
								<option value="SY">Syrian Arab Republic</option>
								<option value="TW">Taiwan, Province of China</option>
								<option value="TJ">Tajikistan</option>
								<option value="TZ">Tanzania, United Republic of</option>
								<option value="TH">Thailand</option>
								<option value="TL">Timor-Leste</option>
								<option value="TG">Togo</option>
								<option value="TK">Tokelau</option>
								<option value="TO">Tonga</option>
								<option value="TT">Trinidad and Tobago</option>
								<option value="TN">Tunisia</option>
								<option value="TR">Turkey</option>
								<option value="TM">Turkmenistan</option>
								<option value="TC">Turks and Caicos Islands</option>
								<option value="TV">Tuvalu</option>
								<option value="UG">Uganda</option>
								<option value="UA">Ukraine</option>
								<option value="AE">United Arab Emirates</option>
								<option value="GB">United Kingdom</option>
								<option value="US">United States</option>
								<option value="UM">United States Minor Outlying Islands</option>
								<option value="UY">Uruguay</option>
								<option value="UZ">Uzbekistan</option>
								<option value="VU">Vanuatu</option>
								<option value="VE">Venezuela, Bolivarian Republic of</option>
								<option value="VN">Viet Nam</option>
								<option value="VG">Virgin Islands, British</option>
								<option value="VI">Virgin Islands, U.S.</option>
								<option value="WF">Wallis and Futuna</option>
								<option value="EH">Western Sahara</option>
								<option value="YE">Yemen</option>
								<option value="ZM">Zambia</option>
								<option value="ZW">Zimbabwe</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">City</label>
							<input name="city" type="text" class="form-control city" placeholder="The city where you actually live" />
						</div>
						<hr />
						<div style="text-align:center;">
							<button class="btn btn-hg btn-primary btn-embossed btn-finish-edit">
								<i class="fa fa-pencil"></i> Edit Profile
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				Account Infos </a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
				<div class="panel-body">
					<form action="<?php echo base_url() ?>admin/edit_account_submit/<?php echo $user["uid"]; ?>" method="POST" class="form-horizontal form-edit-infos col-md-8 col-md-offset-2">
						<div class="form-group">
							<label class="control-label">Username</label>
							<input type="text" class="form-control username" name="username" placeholder="Your Username" value="<?php echo $user["username"]; ?>" />
						</div>
						<div class="form-group">
							<label class="control-label">Email</label>
							<input type="email" class="form-control email" name="email" placeholder="you@email.com" value="<?php echo $user["email"]; ?>" />
						</div>
						<hr style="margin-top:20px !important;">
						<div class="error-account-infos alert alert-danger">
						</div>
						<div style="text-align:center;">
							<button class="btn btn-hg btn-primary btn-embossed btn-finish-edit-account-infos" data-id="<?php echo $user["uid"]; ?>">
								<i class="fa fa-pencil"></i> Edit User Infos
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="panel panel-success">
			<div class="panel-heading" role="tab" id="headingSix">
				<h4 class="panel-title">
				<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
				Coins / Premium </a>
				</h4>
			</div>
			<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
				<div class="panel-body">
					<form action="<?php echo base_url() ?>admin/edit_account_submit/<?php echo $user["uid"]; ?>" method="POST" class="form-horizontal form-edit-infos col-md-8 col-md-offset-2">
						<div class="alert alert-info">
							You can attribute some free coins below.
						</div>
						<div class="form-group">
							<label class="control-label">Coins</label>
							<input type="text" class="form-control user_coins" name="user_coins" placeholder="Enter the number of coins" value="<?php echo $user_coins; ?>" />
						</div>
						<hr style="margin-top:20px !important;">
						<div class="error-account-infos alert alert-danger">
						</div>
						<div style="text-align:center;">
							<button class="btn btn-hg btn-primary btn-embossed btn-finish-edit-coins" data-id="<?php echo $user["uid"]; ?>">
								<i class="fa fa-pencil"></i> Edit Coins
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
		if($this->session->userdata("user_rank") == 2)
		{
			if($user["rank"] > 0):
			?>
			<div class="panel panel-danger">
				<div class="panel-heading" role="tab" id="headingFour">
					<h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
					Remove this user from admins </a>
					</h4>
				</div>
				<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
					<div class="panel-body" style="text-align: center;">
						<a class="btn btn-danger" href="<?php echo base_url(); ?>admin/demoteuseradmin/<?php echo $user["uid"]; ?>">I confirm I want to remove admin access to this user</a>
					</div>
				</div>
			</div>
			<?php
			else:
			?>
			<div class="panel panel-info">
				<div class="panel-heading" role="tab" id="headingFour">
					<h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
					Make this user an Admin </a>
					</h4>
				</div>
				<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
					<div class="panel-body" style="text-align: center;">
						<a class="btn btn-danger" href="<?php echo base_url(); ?>admin/makeuseradmin/<?php echo $user["uid"]; ?>">I confirm I give admin access</a>
					</div>
				</div>
			</div>
			<div class="panel panel-danger">
				<div class="panel-heading" role="tab" id="headingFive">
					<h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
					Delete Account </a>
					</h4>
				</div>
				<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
					<div class="panel-body" style="text-align: center;">
						<a class="btn btn-danger btn-delete-user" href="#" data-user-id="<?php echo $user["uid"]; ?>">I confirm I want to delete this account</a>
					</div>
				</div>
			</div>
			<?php
			endif;
		}
		?>
	</div>

</div>
<script type="text/javascript">
var user_id = "<?php echo $user["uid"]; ?>";
</script>
<?php
$this->load->view('templates/footers/admin_footer');
?>