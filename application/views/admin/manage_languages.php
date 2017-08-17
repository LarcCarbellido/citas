<?php
$this->load->view('templates/headers/admin_header', $title);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Admin</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>/admin">Admin</a>
            </li>
            <li class="active">
                <strong>Manage Languages</strong>
            </li>
        </ol>
    </div>
</div>
<div class="col-lg-12 block_form">
	<?php
	if(isset($_GET["status"])) {
	?>
	<div class="alert alert-success alert-centered">
		<b>Success!</b> Your settings have been updated!
	</div>
	<?php
	}
	?>
	<form action="" method="post" accept-charset="utf-8" class="general_config well">
		<div class="alert alert-danger alert-centered alert-error-settings">
			
		</div>
	    <fieldset>
	        <legend><i class="fa fa-cogs"></i> Language Settings</legend>
	        <?php		    
			$default_language = $settings["default_language"];
			
			$directory = $_SERVER['DOCUMENT_ROOT'] . "/system/language/*";
			$language_str = "";
			
			$glob_dir = glob($directory, GLOB_ONLYDIR);
			
			$cpt_lg = 0;
			$lg_array = array();
			
		    foreach($glob_dir as $dir) {
			    $dirname = basename($dir);
			    
			    if(sizeof($glob_dir) == $cpt_lg+1)
			    	$language_str .= $dirname;
			    else
			    	$language_str .= $dirname . ", ";
			    	
			    array_push($lg_array, $dirname);
			    
			    $cpt_lg++;
			}
			?>
			
			<div class="alert alert-info alert-centered">
				<?php 
				if($cpt_lg == 1) {
				?>
					<b><?php echo $cpt_lg; ?> language</b> has been detected (<?php echo $language_str; ?>).
				<?php
				} else {
				?>
					<b><?php echo $cpt_lg; ?> languages</b> have been detected (<?php echo $language_str; ?>).
				<?php
				}
				?>
			</div>
			<label for="web_desc">Default Language :</label>
			<select class="form-control defaultlanguage">
				<?php
				$default_language = $settings["default_language"];
					
				foreach($lg_array as $lg) {
					if($default_language == $lg) {
				?>
					<option selected value="<?php echo $lg; ?>"><?php echo ucfirst($lg); ?></option>
				<?php	
					} else {
				?>
					<option value="<?php echo $lg; ?>"><?php echo ucfirst($lg); ?></option>
				<?php
					}
				}	
				?>
			</select>
	    </fieldset>
	    <br />
		<div style="text-align:center;">
            <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Save Changes</button>
        </div>
        <hr />
        <fieldset>
	        <legend><i class="fa fa-globe"></i> Language Options</legend>
	        <div class="row nomargin">
		        <div class="alert alert-info alert-centered col-md-8">
					Languages options you select will be shown in a dropdown to your users (if there is more than one language)
				</div>
				<div class="col-md-4" style="text-align: center;margin-top: 9px;">
				    <a href="" class="btn btn-info btn-add-redirection"><i class="fa fa-plus"></i> Add a Language Option</a>
				</div>
	        </div>
		    <br /><br />
		    <div class="row nomargin">
		        <?php
			    if($nb_redirections == 0):
				?>
				<div class="alert alert-danger alert-centered">
					You didn't create any language option yet.
				</div>
				<?php
				else:
				?>
				<table class="table table-bordered table-striped table-hover">
					<tbody>
						<tr>
							<th class="forum-title-topic-th">Language</th>
							<th class="forum-title-topic-th">Actions</th>
						</tr>
						<?php
						foreach($language_redirections as $redir):	
						?>
						<tr class="topic redir_table">
							<td><?php echo $redir["language"]; ?></td>
							<td>
								<a href="" class="badge badge-danger btn-delete-redir" data-id="<?php echo $redir["id"]; ?>"><i class="fa fa-times"></i> Delete</a>
							</td>
						</tr>
						<?php
						endforeach;
						?>
					</tbody>
				</table>
				<?php
				endif;
				?>
		    </div>
        </fieldset>
	</form>
</div>

<div class="modal fade" id="add_redirection_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add a Language Option</h4>
			</div>
			<div class="modal-body">
				<form role="form" id="login">
					<div class="form-group">
						<label for="redirects_to">Add this Language Option :</label>
						<select class="form-control redirects_to_add">
							<?php
							$directory = $_SERVER['DOCUMENT_ROOT'] . "/system/language/*";
							$glob_dir = glob($directory, GLOB_ONLYDIR);
							foreach($glob_dir as $dir) {
								$dirname = basename($dir);
								
								if($dirname != $default_language)
								{
							?>
								<option value="<?php echo $dirname; ?>"><?php echo ucfirst($dirname); ?></option>
							<?php
								}
							}	
							?>
						</select>
					</div>
					<hr />
					<div class="alert alert-danger alert-centered alert-error-settings">
						
					</div>
					<div  style="text-align:center;">
						<button type="submit" class="btn btn-primary btn-save-redirect btn-embossed">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('templates/footers/admin_footer');
?>