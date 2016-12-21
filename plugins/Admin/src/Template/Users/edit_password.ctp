<?php echo $this->Html->css('/admin/assets/admin/css/signin', array('inline' => false)) ?>
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
<div class="users form form-signin">
<?php echo $this->Flash->render(); ?>
<?php echo $this->Form->create('User', array('autocomplete' => 'off')); ?>
    <p class="form-signin-heading" style="color: red"><?php echo __('Your password had over 60 days, please change password!!'); ?></p>
	<h2 class="form-signin-heading"><?php echo __('Change password'); ?></h2>
	<?php //echo $this->Form->input('email', array('type' => 'email', 'class' => 'form-control','autofocus' => true, 'placeholder' => 'Email address', 'autocomplete' => 'off')); ?>
	<?php echo $this->Form->input('password', array('class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off')); ?>
    <?php echo $this->Form->input('new_password', array('type'=>'password','id'=> 'new_password','class' => 'form-control', 'placeholder' => 'New password', 'autocomplete' => 'off')); ?>
    <?php echo $this->Form->input('reenter_new_password', ['type'=>'password','class' => 'form-control', 'placeholder' => 'Reenter the new password', 'autocomplete' => 'off']);?>
    
	<!-- <div class="checkbox">
	  <label>
	    <input type="checkbox" value="remember-me"> Remember me
	  </label>
	</div> -->
	<?php echo $this->Form->button(__('Submit'), array('class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit')); ?>
</div>