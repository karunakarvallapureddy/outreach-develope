<?php 
foreach ($Workshopedit as $editWorkshop) {

?>
<div role="tabpanel" class="tab-pane container" id="messages" style="
    margin-top: 70px;
">
	<!-- FORMS -->
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<!-- product details -->
				<div class="col-md-12">
					<div class="box border dark gray">
						<div class="box-title">
							<h4>Plan a New Workshop</h4>
						</div>
						<div class="box-body big">
							<span id="error" class='error'></span>

							<form class="form-horizontal" method="post" name="workshopedit" id="workshopedit" action="<?php echo site_url('updateWorkshop'); ?>" role="form">
								<input type="hidden" name="workshop_id" value="<?php echo $editWorkshop['workshop_id']; ?> " />
								<!-- Product Name -->
								<div class="form-group">
									<label class="col-sm-3 control-label">Name</label>
									<div class="col-sm-6">
										<input type="text"  onkeypress="return onlyAlphabets(event,this);"name = "name" id = "name" class="required form-control" value="<?php echo $editWorkshop['name']; ?>">
										<?php echo "<span style='color:red'>" . form_error('name') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Location</label>
									<div class="col-sm-6">
										<input type="text" onkeypress="return onlyAlphabets(event,this);" name = "location" id = "last_name" class=" form-control" value="<?php echo $editWorkshop['location']; ?>">
										<?php echo "<span style='color:red'>" . form_error('location') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Participating institutes</label>
									<div class="col-sm-6">
										<textarea  class=" form-control" name="institutes"><?php echo $editWorkshop['institutes']; ?></textarea>																				
													  <?php echo "<span style='color:red'>" . form_error('participate_institute') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Date</label>
									<div class="col-sm-6">
										<input type="date" name = "date" id = "mou" class="required" value="<?php echo $editWorkshop['date']; ?>">
										<?php echo "<span style='color:red'>" . form_error('date') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Number of participants</label>
									<div class="col-sm-6">
										<input type="text" name = "no_of_participants" id = "no_of_participants" class="required" value="<?php echo $editWorkshop['no_of_participants']; ?>">
										<?php echo "<span style='color:red'>" . form_error('number_of_participants') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Agenda for workshop</label>

								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Number of sessions</label>
									<div class="col-sm-6">
										<input type="text" name = "no-of_sessions" id = "no-of_sessions" class="required" value="<?php echo $editWorkshop['no-of_sessions']; ?>">
										<?php echo "<span style='color:red'>" . form_error('no_of_sessions') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">discipline</label>
									<div class="col-sm-6">
										<input type="text" name = "discipline" id = "discipline" class="required form-control" value="<?php echo $editWorkshop['discipline']; ?>">
										<?php echo "<span style='color:red'>" . form_error('subject_of_session') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Labs planned</label>
									<div class="col-sm-6">
										<input type="text" name = "labs_planned" id = "labs_planned" class="required form-control" value="<?php echo $editWorkshop['labs_planned']; ?>">
										<?php echo "<span style='color:red'>" . form_error('labs_planned') . "</span>"; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Other details</label>
									<div class="col-sm-6">
										<textarea class="form-control" rows="3" name="other_details"><?php echo $editWorkshop['other_details']; ?></textarea>																				
													  <?php echo "<span style='color:red'>" . form_error('other_details') . "</span>"; ?>
									</div>
								</div>

								<?php /* */ ?>

						</div>
					</div>
				</div>
				<!-- product details -->

			</div>
		</div>
	</div>
	<!-- /FORMS -->

	<!-- Save -->
	<p class="btn-toolbar">
		<button class="btn btn-success">
			Update Nodal Center
		</button></form>
		<a href="<?php echo base_url('')?>NodalDashboard">
		<button class="btn btn-warning">
			Cancel
		</button> </a>
	</p>
	<!-- Button trigger modal -->
	<button type="button" class="btn btn pull-right" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">
		Cancel Workshop
	</button>

	<div class="modal fade" data-backdrop="" id="exampleModal"  role="dialog" >
		<div class="modal-dialog" style="
		margin-top: 120px;
		" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="exampleModalLabel">Cancel Workshop</h4>
				</div>
				<div class="modal-body">
					<form method="post" name="addStaff" id="addStaff" action="<?php echo site_url('cancelWorkshop'); ?>">
						<div class="form-group">
							<label for="recipient-name" class="control-label">* Reason to cancel the workshop:</label>
							<input type="text" class="form-control" id="recipient-name" name="reason" required >
							<input type="hidden" class="form-control" id="recipient-name" name="workshop_id" value="<?php echo $editWorkshop['workshop_id']; ?> ">
						</div>
						<div class="modal-footer">
							<input type="submit" name="submit" value="Confirm Cancellation" class="btn pull-left">
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
	<!-- /Save -->
	</form>
</div>
<?php
}
?>