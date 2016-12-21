<div class="videos form">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= __('Add Video') ?></h1>
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
							<li>
								<?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Videos'), ['action' => 'index']
										,['escape' => false]) ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div><!-- end col md 3 -->
		<div class="col-md-9">
			<?php echo  $this->Form->create($video); ?>
			<div class="form-group"><?php echo $this->Form->input('youtube_id',['class'=>'form-control','placeholder' => 'Youtube Id', 'type' => 'text', 'required']); ?></div>
			<div class="form-group">
				<label for="category_id">Category</label>
				<?php echo $this->Form->select('category_id', $YT_Category, ['class'=>'form-control', 'empty' => 'Select']); ?>
			</div>
			<div class="form-group"><?php echo $this->Form->input('activate',['class'=>'form-control','placeholder' => 'Activate', 'checked' => 'checked']); ?></div>
			<div class="form-group">
				<?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
				<?php echo $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-default'])?>
			</div>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
