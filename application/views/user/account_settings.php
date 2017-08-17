<?php
$this->load->view('templates/headers/main_header', $title);

if($user["gender"] == 0) {
	$gender_word = $this->lang->line("his");
} else {
	$gender_word = $this->lang->line("her");
}

$birthdate = new DateTime($user["birthday"]);
$today     = new DateTime();
$interval  = $today->diff($birthdate);
$age	   = $interval->format('%y');

if($user["gender"] == 0) {
	$gender_color = "male_color";
} else {
	$gender_color = "female_color";
}
?>	
<div class="container">
	<?php
	if($settings["ads_code"] != ""):
	?>
	<div class="ad_block">
		<?php echo $settings["ads_code"]; ?>
	</div>
	<?php
	endif;
	?>
	<div class="row">
		<div class="pull-left">
			<h3><?php echo $this->lang->line("Settings"); ?></h3>
		</div>
    </div>

    <div class="row">
	    <?php
		if(isset($_GET["action"]))
		{
			if($_GET["action"] == "edit_success") {
			?>
			<div class="alert alert-success alert-centered">
				<?php echo $this->lang->line("account_edited"); ?>
			</div>
			<?php
			} else if($_GET["action"] == "picture_success") {
			?>
			<div class="alert alert-success alert-centered">
				<?php echo $this->lang->line("profile_pic_edited"); ?>
			</div>
			<?php
			} else if($_GET["action"] == "photos_added") {
			?>
			<div class="alert alert-success alert-centered">
				<?php echo $this->lang->line("photos_uploaded"); ?>
			</div>
			<?php
			}
		}	
		?>
	    <div class="main_container">

			<div class="panel-group panel-settings" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-success" id="addPhotos">
					<div class="panel-heading" role="tab">
						<h4 class="panel-title">
						<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#add_photos" aria-expanded="false" aria-controls="add_photos">
						<?php echo $this->lang->line("p_manage_photos"); ?> </a>
						</h4>
					</div>
				</div>
				<div class="panel panel-success">
					<div class="panel-heading" role="tab" id="headingFive">
						<h4 class="panel-title">
						<a role="button" data-toggle="collapse" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
						<?php echo $this->lang->line("change_profile_picture"); ?> </a>
						</h4>
					</div>
					<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
						<div class="panel-body">
							<?php
							if(sizeof($user_photos) <= 1):	
							?>
							<div class="alert alert-centered alert-info">
								<?php echo sprintf($this->lang->line("only_one_photo"), $settings["site_name"]); ?>
								<br /><br />
								<a href="<?php echo base_url(); ?>photo/add" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $this->lang->line("add_more_photos"); ?></a>
							</div>
							<?php
							else:
							?>
							<div class="alert alert-centered alert-info">
								<?php echo $this->lang->line("slide_photo"); ?>
							</div>
							<div id="profile_picture_carousel" class="owl-carousel">
							<?php
							foreach($user_photos as $photo):
							?>
							<div>
						    	<img height="250" src="<?php echo base_url(); ?><?php echo $photo["thumb_url"]; ?>" alt="<?php echo $photo["id"]; ?>" data-id="<?php echo $photo["id"]; ?>" />
						    </div>
							<?php
							endforeach;
							?>
							</div>
							<div style="text-align: center;">
								<div class="btn btn-primary btn-save-profile-picture"><?php echo $this->lang->line("Save"); ?></div>
							</div>
							<?php
							endif;
							?>
						</div>
					</div>
				</div>
		
				<div class="panel panel-success">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
						<?php echo $this->lang->line("public_infos"); ?> </a>
						</h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<form action="<?php echo base_url() ?>user/edit_user_submit" method="POST" class="col-md-8 col-md-offset-2 edit_form form-horizontal" enctype='multipart/form-data'>
								<?php
								if($show_browse_invisibly_option) {
								?>
								<h4><?php echo $this->lang->line("premium_features"); ?></h4>
								<div class="form-group">
									<label class="control-label"><?php echo $this->lang->line("browse_invisibly"); ?></label>
									<input <?php if($user["browse_invisibly"] == 1): ?>checked="checked"<?php endif; ?> type="checkbox" name="browse_invisibly" class="form-control browse_invisibly" value="1" />
								</div>
								<?php
								}	
								?>
								<h4><?php echo $this->lang->line("who_are_you_title"); ?></h4>
								<div class="form-group">
									<label class="control-label"><?php echo $this->lang->line("Gender"); ?></label>
									<select name="gender" class="form-control gender" style="text-align:center !important;">
										<option value="0" selected="selected"><?php echo $this->lang->line("select_gender"); ?></option>
										<option value="male"><?php echo $this->lang->line("Male"); ?></option>
										<option value="female"><?php echo $this->lang->line("Female"); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label class="control-label"><?php echo $this->lang->line("Birthday"); ?></label>
									<div class="col-ws-12">
										<div class="col-xs-4">
											<select name="birthday_day" class="form-control birthday_day" style="text-align:center !important;">
												<option value="0"><?php echo $this->lang->line("Day"); ?></option>
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
												<option value="0"><?php echo $this->lang->line("Month"); ?></option>
												<option value="january"><?php echo $this->lang->line('January'); ?></option>
												<option value="february"><?php echo $this->lang->line('February'); ?></option>
												<option value="march"><?php echo $this->lang->line('March'); ?></option>
												<option value="april"><?php echo $this->lang->line('April'); ?></option>
												<option value="may"><?php echo $this->lang->line('May'); ?></option>
												<option value="june"><?php echo $this->lang->line('June'); ?></option>
												<option value="july"><?php echo $this->lang->line('July'); ?></option>
												<option value="august"><?php echo $this->lang->line('August'); ?></option>
												<option value="september"><?php echo $this->lang->line('September'); ?></option>
												<option value="october"><?php echo $this->lang->line('October'); ?></option>
												<option value="november"><?php echo $this->lang->line('November'); ?></option>
												<option value="december"><?php echo $this->lang->line('December'); ?></option>
											</select>
										</div>
										<div class="col-xs-4">
											<select name="birthday_year" class="form-control birthday_year" style="text-align:center !important;">
												<option value="0"><?php echo $this->lang->line("Year"); ?></option>
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
									<label class="control-label"><?php echo $this->lang->line("about_you"); ?></label>
									<textarea name="about_you_txt" id="about_you_txt" class="form-control about_you_txt" rows="4" placeholder="<?php echo $this->lang->line("about_you_placeholder"); ?>"></textarea>
								</div>
								<?php
		                        $extra_fields = $settings["user_extra_fields"];
		                        
		                        $user_fields = explode(",", $extra_fields);  
		                        
		                        if(sizeof($user_fields) > 1)
			                    {
		                        		                        
		                        foreach($user_fields as $u_field) {
			                    if($u_field != "")
			                    {
			                    ?>
			                    <div class="form-group" style="text-align:left;margin-top:20px;">
				                    <label class="control-label"><?php echo ucfirst($u_field); ?></label>
				                    <?php
					                $is_found = false;
					                foreach($extra_user as $e_user) {
						                if($e_user["attr_name"] == $u_field) {
							            ?>
								        <textarea name="<?php echo preg_replace('/\s+/', '', $u_field); ?>" id="<?php echo preg_replace('/\s+/', '', $u_field); ?>" class="form-control <?php echo preg_replace('/\s+/', '', $u_field); ?>" rows="4"><?php echo $e_user["attr_val"]; ?></textarea>
							            <?php
								        $is_found = true;
								        break;
						                }
					                }  
					                
					                if(!$is_found)
					                {
						            ?>
										<textarea name="<?php echo preg_replace('/\s+/', '', $u_field); ?>" id="<?php echo preg_replace('/\s+/', '', $u_field); ?>" class="form-control <?php echo preg_replace('/\s+/', '', $u_field); ?>" rows="4"></textarea>
						            <?php
					                }
					                ?>
			                    </div>
			                    <?php
				                }
		                        }
		                        
		                        }
		                        ?>
								<hr />
								<h4><?php echo $this->lang->line("where_do_you_live"); ?></h4>
								<div class="form-group">
									<label class="control-label"><?php echo $this->lang->line("Country"); ?></label>
									<select name="country" id="country" class="form-control country" style="text-align:center !important;"> 
										<option value="0" selected="selected"><?php echo $this->lang->line("select_country"); ?></option> 
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
									<label class="control-label"><?php echo $this->lang->line("City"); ?></label>
									<input name="city" type="text" id="cityform" class="form-control city" placeholder="<?php echo $this->lang->line("city_placeholder"); ?>" />
								</div>
								<hr />
								<div style="text-align:center;">
									<button class="btn  btn-primary  btn-finish-edit">
										<i class="fa fa-pencil"></i> <?php echo $this->lang->line("edit_my_profile_btn"); ?>
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
						<?php echo $this->lang->line("account_infos_title"); ?> </a>
						</h4>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						<div class="panel-body">
							<form action="<?php echo base_url() ?>user/edit_account_submit" method="POST" class="form-horizontal form-edit-infos col-md-8 col-md-offset-2">
								<div class="form-group">
									<label class="control-label"><?php echo $this->lang->line("your_username"); ?></label>
									<input type="text" class="form-control username" name="username" placeholder="<?php echo $this->lang->line("username_placeholder"); ?>" value="<?php echo $user["username"]; ?>" />
								</div>
								<div class="form-group">
									<label class="control-label"><?php echo $this->lang->line("your_email"); ?></label>
									<input type="email" class="form-control email" name="email" placeholder="<?php echo $this->lang->line("your_email_placeholder"); ?>" value="<?php echo $user["email"]; ?>" />
								</div>
								<hr style="margin-top:20px !important;">
								<div class="error-account-infos alert alert-danger">
								</div>
								<div style="text-align:center;">
									<button class="btn  btn-primary  btn-finish-edit-account-infos">
										<i class="fa fa-pencil"></i> <?php echo $this->lang->line("edit_my_infos"); ?>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="panel panel-primary panel-logout">
					<div class="panel-heading" role="tab">
						<h4 class="panel-title">
						<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="" aria-expanded="false"><?php echo $this->lang->line("logout"); ?></a>
						</h4>
					</div>
				</div>
				<div class="panel panel-danger">
					<div class="panel-heading" role="tab" id="headingFour">
						<h4 class="panel-title">
						<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
						<?php echo $this->lang->line("delete_account"); ?> </a>
						</h4>
					</div>
					<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
						<div class="panel-body" style="text-align: center;">
							<a class="btn btn-danger" href="<?php echo base_url(); ?>user/deleteaccountconfirm"><?php echo $this->lang->line("confirm_account_deletion"); ?></a>
						</div>
					</div>
				</div>
			</div>
	    </div>
    </div>
</div>		

<?php
$this->load->view('templates/footers/main_footer');
?>