<script>
$(document).ready(function($) {
$('#new_password').strength({
            strengthClass: 'strength',
            strengthMeterClass: 'strength_meter',
            //strengthButtonClass: 'button_strength',
            strengthButtonText: '',
            //strengthButtonTextToggle: 'Hide Password'
        });
});
</script>
<div class="users form">

	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php echo __('Admin Change Password User'); ?></h1>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="col-md-3">
			<div class="actions">
				<div class="panel panel-default">
					<div class="panel-heading">Actions</div>
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
                                <li><?php echo $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Users'), array('action' => 'index'), array('escape' => false)); ?></li>
							</ul>
						</div>
					</div>
				</div>			
		</div><!-- end col md 3 -->
		<div class="col-md-9">
			<?php echo $this->Form->create(); ?>
				<div class="form-group">
					<?php echo $this->Form->input('password', ['class' => 'form-control', 'placeholder' => 'Enter the old password', 'autocomplete' => 'off']);?>
				</div>
                <div class="form-group">
                    <?php echo $this->Form->input('new_password',['type'=>'password','id'=> 'new_password','class'=>'form-control','placeholder' => 'Enter the new password', 'autocomplete' => 'off']);?>
				</div>
                <div class="form-group">
					<?php echo $this->Form->input('reenter_new_password', ['type'=>'password','class' => 'form-control', 'placeholder' => 'Reenter the new password', 'autocomplete' => 'off']);?>
				</div>

				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), ['class' => 'btn btn-default']); ?>
				</div>

			<?php echo $this->Form->end() ?>

		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
