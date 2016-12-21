<div class="videos form">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?= __('Edit Video') ?></h1>
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
                                                                <li><?=
                                    $this->Form->postLink(
                                    __('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete'),
                                    ['action' => 'delete', $video->video_id],['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $video->video_id)]
                                    )
                                    ?>
                                </li>
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
			<div class="form-group"><?php echo $this->Form->input('youtube_id',['class'=>'form-control','placeholder' => 'Youtube Id', 'type' => 'text']); ?></div>
			<div class="form-group"><?php echo $this->Form->input('category_id',['class'=>'form-control','placeholder' => 'Category Id', 'type' => 'text']); ?></div>
			<div class="form-group"><?php echo $this->Form->input('category_name',['class'=>'form-control','placeholder' => 'Category Name']); ?></div>
			<div class="form-group"><?php echo $this->Form->input('video_title',['class'=>'form-control','placeholder' => 'Video Title']); ?></div>
			<div class="form-group"><?php echo $this->Form->input('video_thumb',['class'=>'form-control','placeholder' => 'Video Thumb']); ?></div>
			<div class="form-group"><?php echo $this->Form->input('video_length',['class'=>'form-control','placeholder' => 'Video Length']); ?></div>
			<div class="form-group"><?php echo $this->Form->input('video_channel_id',['class'=>'form-control','placeholder' => 'Video Channel ID', 'type' => 'text']); ?></div>
			<div class="form-group">
				<label for="status">Status</label>
				<?php echo $this->Form->select('status',$status,['class'=>'form-control']); ?>
			</div>
			<div class="form-group"><?php echo $this->Form->input('activate',['class'=>'form-control','placeholder' => 'Activate']); ?></div>
			<div class="form-group">
				<?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
				<?php echo $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-default'])?>
			</div>
			<?php echo $this->Form->end() ?>
		</div><!-- end col md 12 -->
	</div><!-- end row -->
</div>
