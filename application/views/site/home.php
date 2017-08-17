<?php
$this->load->view('templates/headers/main_header', $title);
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
			<h3><?php echo $this->lang->line('Home'); ?></h3>
		</div>
		<div class="pull-right">
			<a class="btn btn-primary filters settingsfilter" href="">
				<i class="fa fa-sliders"></i> <?php echo $this->lang->line('filter_users_btn'); ?>
			</a>
		</div>
	</div>
	<div class="row">
		<?php
		if(isset($_GET["action"])) {
			if($_GET["action"] == "welcome") {
			?>
			<div class="alert alert-success" style="text-align:center;margin-top: 10px;">
				<b><?php echo $this->lang->line('Welcome'); ?></b><br />
				<?php echo $this->lang->line('welcome_txt'); ?>
			</div>
			<?php
			} else if($_GET["action"] == "filter_applied") {
			?>
			<div class="alert alert-success" style="text-align:center;margin-top: 10px;">
				<?php echo $this->lang->line('filters_applied'); ?>
			</div>
			<?php	
			}
		}	
		?>
		<div class="main_container">
			<?php
			if($this->session->userdata('filter_age_from')) 
	        {
		    	if($this->session->userdata('filter_gender') == 0)
		        	$gender	= $this->lang->line('Men');
		        else if($this->session->userdata('filter_gender') == 1)
		        	$gender = $this->lang->line('Women');
		        else
		        	$gender = $this->lang->line('Men_and_Women');   
		        	
		        $age_from 	= intval($this->session->userdata('filter_age_from'));
		        $age_to		= intval($this->session->userdata('filter_age_to'));
		        
		        $country = $this->session->userdata('filter_country');
		        $city = $this->session->userdata('filter_city');
		        
		        if($settings["hide_country"] == 0)
		        {
			        if($city != "")
			        	$sentence_filter = sprintf($this->lang->line("filters_sentence_with_city"), $gender, $age_from, $age_to, $country, $city);
			        else
			        {
				        if($country != 0)
			        		$sentence_filter = sprintf($this->lang->line("filters_sentence_without_city"), $gender, $age_from, $age_to, $country);
			        	else
			        		$sentence_filter = sprintf($this->lang->line("filters_sentence_without_city_everywhere"), $gender, $age_from, $age_to, strtolower($this->lang->line('Everywhere')));
			        }
			        
		        } else {
			       	if($city != "")
			       	{
				       	$sentence_filter = sprintf($this->lang->line("filters_sentence_without_country"), $gender, $age_from, $age_to, $city);
			       	} else {
				       	$sentence_filter = sprintf($this->lang->line("filters_sentence_without_location"), $gender, $age_from, $age_to);
			       	}
		        }
		    } else {
			    $sentence_filter = $this->lang->line("no_filters_sentence");
		    }
			?>
			
			<div class="filter_sentences">
			<?php
				echo $sentence_filter;	
			?>
			</div>
			
			<div id="userslst" class="clearfix">

				<?php
				$cpt = 1;
				
				$total_users = sizeof($users) + sizeof($featured_users);
				
				if($total_users == 0) {
				?>
				<div class="alert alert-danger alert-center">
					<?php echo $this->lang->line("no_users_found_filters"); ?>
				</div>
				<?php
				}
				?>
				
				<?php foreach($featured_users as $user):
		    	$birthdate = new DateTime($user["birthday"]);
				$today     = new DateTime();
				$interval  = $today->diff($birthdate);
				$age	   = $interval->format('%y');
				
				if($user["gender"] == 0) {
					$gender_color = "male_color";
				} else {
					$gender_color = "female_color";
				}
				
				if($user["thumb_url"] == "" || $user["thumb_url"] == NULL) {
					$user["thumb_url"] = "images/avatar.png";
				}
				?>
				<div class="col-lg-3 col-md-4 col-xs-6 clearfix user_block" data-user-id="<?php echo $user["uid"]; ?>" data-cpt="<?php echo $cpt; ?>">
					<div class="thumb">
		                <a class="thumbnail" href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>">
			                <?php
				            if($user["is_fake"] == 1):
				            ?>
				            <img src="<?php echo $user["thumb_url"]; ?>" alt="Photo User" />
				            <?php
				           	else:  
				            ?>
			                <img src="<?php echo base_url() . $user["thumb_url"]; ?>" alt="Photo User" />
			                <?php
				            endif;
				            ?>
			                <?php
				            if($user["st_love"] == 1)
				            {
					        ?>
					        <div class="love_button loved" data-profile-id="<?php echo $user["uid"]; ?>"><i class="fa fa-heart"></i></div>
					        <?php
						    } else {
							?>
							<div class="love_button" data-profile-id="<?php echo $user["uid"]; ?>"><i class="fa fa-heart"></i></div>
							<?php
						    }
				            ?>
			                <?php
				                if($user["is_online"] == 1):
				            ?>
			                <div class="online_status"><i class="fa fa-circle"></i></div>
			                <?php
				                endif;
				                
				                if($user["city"] == "") {
					                $user["city"] = $this->lang->line("unknown_city");
				                }
				            ?>
				            <div class="featured_status"><i class="fa fa-tag"></i> <?php echo $this->lang->line('user_featured'); ?></div>
		               	</a>
					   	<div class="userslst_infos">
						   	<a href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>" class="userslst_username <?php echo $gender_color; ?>"><?php echo $user["username"]; ?></a>
						   	<div class="userslst_age"><?php echo $age; ?> &#8226; <?php if($settings["hide_country"] == 0): ?><?php echo get_country_name_by_code($user["country"]); ?><?php else: ?><?php echo $user["city"]; ?><?php endif; ?></div>
						</div>
					</div>
	            </div>
				<?php
				$cpt++;
				endforeach;
				?>
				
				<?php
		    	$cpt = 1;
		    	?>
		    	<?php foreach($users as $user): ?>
		    	
		    	<?php
		    	$birthdate = new DateTime($user["birthday"]);
				$today     = new DateTime();
				$interval  = $today->diff($birthdate);
				$age	   = $interval->format('%y');
				
				if($user["gender"] == 0) {
					$gender_color = "male_color";
				} else {
					$gender_color = "female_color";
				}
				
				if($user["thumb_url"] == "" || $user["thumb_url"] == NULL) {
					$user["thumb_url"] = "images/avatar.png";
				}
				?>
		    	
				<div class="col-lg-3 col-md-4 col-xs-6 clearfix user_block" data-user-id="<?php echo $user["uid"]; ?>" data-cpt="<?php echo $cpt; ?>">
					<div class="thumb">
		                <a class="thumbnail" href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>">
			                <?php
				            if($user["is_fake"] == 1):
				            ?>
				            <img src="<?php echo $user["thumb_url"]; ?>" alt="Photo User" />
				            <?php
				           	else:  
				            ?>
			                <img src="<?php echo base_url() . $user["thumb_url"]; ?>" alt="Photo User" />
			                <?php
				            endif;
				            ?>
			                <?php
				            if($user["st_love"] == 1)
				            {
					        ?>
					        <div class="love_button loved" data-profile-id="<?php echo $user["uid"]; ?>"><i class="fa fa-heart"></i></div>
					        <?php
						    } else {
							?>
							<div class="love_button" data-profile-id="<?php echo $user["uid"]; ?>"><i class="fa fa-heart"></i></div>
							<?php
						    }
				            ?>
			                <?php
				            if($user["is_online"] == 1):
				            ?>
			                <div class="online_status"><i class="fa fa-circle"></i></div>
			                <?php
			                endif;
			                

				            ?>
		               	</a>
					   	<div class="userslst_infos">
						   	<?php
							if($user["city"] == "") {
				                $user["city"] = $this->lang->line("unknown_city");
			                }
			                ?>
						   	<a href="<?php echo base_url(); ?>user/profile/<?php echo $user["uid"]; ?>" class="userslst_username <?php echo $gender_color; ?>"><?php echo $user["username"]; ?></a>
						   	<div class="userslst_age"><?php echo $age; ?> &#8226; <?php if($settings["hide_country"] == 0): ?><?php echo get_country_name_by_code($user["country"]); ?><?php else: ?><?php echo $user["city"]; ?><?php endif; ?></div>
						</div>
					</div>
	            </div>
				<?php
				$cpt++;
				endforeach;
				?>
			</div>
			<?php
			if(sizeof($users) >= 20):
			?>
			<div class="btnmoreplaceholder message">
				<?php echo $links; ?>
			</div>
			<?php
			endif; 
			?>
		</div>
	</div>
</div>
<div class="modal fade" id="settings_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $this->lang->line('filter_users_results'); ?></h4>
			</div>
			<div class="modal-body">
				<form role="form">
					<div class="form-group">
						<label for="settings_showonly"><?php echo $this->lang->line('show_only'); ?></label>
						<select id="settings_showonly" class="form-control settings_showonly" style="text-align:center !important;">
							<option value="0"><?php echo $this->lang->line('Men'); ?></option>
							<option value="1"><?php echo $this->lang->line('Women'); ?></option>
							<option value="2"><?php echo $this->lang->line('Men_and_Women'); ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="settings_agerange"><?php echo $this->lang->line('Ages'); ?></label>
						<div class="settings_agerange"></div>
						<span class="value_from"></span> - <span class="value_to"></span> <?php echo $this->lang->line('y_o'); ?>
					</div>
					<?php
					if($settings["hide_country"] == 0):	
					?>
					<div class="form-group">
						<label class="control-label"><?php echo $this->lang->line('Country'); ?></label>
						<select id="country" name="country" class="form-control country" style="text-align:center !important;"> 
							<option value="0" selected="selected"><?php echo $this->lang->line('Everywhere'); ?></option> 
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
							<option value="MK">Macedonia</option>
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
							<option value="FM">Micronesia</option>
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
							<option value="SH">Saint Helena</option>
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
							<option value="GS">South Georgia</option>
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
							<option value="TZ">Tanzaniaf</option>
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
							<option value="UM">United States Minor Out Isl</option>
							<option value="UY">Uruguay</option>
							<option value="UZ">Uzbekistan</option>
							<option value="VU">Vanuatu</option>
							<option value="VE">Venezuela</option>
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
					<?php
					endif;
					?>
					<div class="form-group city_form">
						<label class="control-label"><?php echo $this->lang->line('City'); ?></label>
						<input type="text" id="cityform" placeholder="<?php echo $this->lang->line('city_placeholder'); ?>" name="city" class="form-control cityform">
						<span class="help-block m-b-none"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_city_entered'); ?></span>
					</div>
					<div class="form-group">
						<label class="control-label"><?php echo $this->lang->line('Sort'); ?></label>
						<select name="sort_by" class="form-control sort_by" style="text-align:center !important;"> 
							<option value="0"><?php echo $this->lang->line('newest_first'); ?></option>
							<option value="1"><?php echo $this->lang->line('last_online'); ?></option>
						</select>
					</div>
					<div class="settings_btn_placeholder">
						<div class="btn btn-danger resetfilters btn-sm"><?php echo $this->lang->line('reset_filters'); ?></div>
					</div>
					<hr />
					<div class="settings_btn_placeholder">
						<div class="btn btn-primary btn-apply-filters"><?php echo $this->lang->line('apply_filters'); ?></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/main_footer');
?>