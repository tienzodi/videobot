<?php
	use Cake\Utility\Text;
?>
<div class="videos index">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1><?php echo "Video"; ?></h1>
            </div>
        </div><!-- end col md 12 -->
    </div><!-- end row -->
    <div class="row">
        <div class="col-md-3">
            <div class="actions">
                <div class="panel panel-default">
                    <div class="panel-heading">Actions</div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <?php echo $this->Form->create(null, ['type' => 'get', 'id' => 'search_form']); ?>
                            <li>
                                <?php echo $this->Form->input('search',['class'=>'form-control','placeholder' => 'Search', 'value' => $search]); ?>
                            </li>
                            <li>
                                <label for="category_id">Category name</label>
                                <?php echo $this->Form->select('category', $YT_Category, ['class'=>'form-control','empty' => 'ALL', 'default' => $category]); ?>
                            </li>
                            <li>
                                <label for="status">Status</label>
                                <?php echo $this->Form->select('status', $status, ['class'=>'form-control','empty' => 'ALL', 'default' => $status_id]); ?>
                            </li>
                            <?php echo $this->Form->end(); ?>
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Video'), ['action' => 'add'],['escape' => false]); ?></li>
                        </ul>
                    </div><!-- end body -->
                </div><!-- end panel -->
            </div><!-- end actions -->
        </div><!-- end col md 3 -->

        <div class="col-md-9">
            <table cellpadding="0" cellspacing="0" class="table table-striped">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('video_title', 'Video') ?></th>
                    <th><?= $this->Paginator->sort('category_name') ?></th>
                    <th>Thumbnail</th>
                    <th><?= $this->Paginator->sort('video_published','Published at') ?></th>
                    <th><?= $this->Paginator->sort('video_length','Length') ?></th>
                    <th><?= $this->Paginator->sort('total_suggest') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('activate') ?></th>
                    <th class="actions"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($videos as $video): ?>
                    <tr video_id="<?= $video->video_id ?>">
                        <td>
                            <?php
                                $video_link = '';
                                if($video->youtube_id != ''){
                                    $video_link = 'https://www.youtube.com/watch?v=' . $video->youtube_id;
                                }
                                $video_title = $video->video_title;								
								$video_title =   Text::truncate($video_title , 45 ,['ellipsis' => '...','exact' => false]);
                                echo '<a class="fancybox-media" rel="media-gallery" href="'.$video_link.'" target="_blank">'.$video_title.'</a>'
                            ?>
                        </td>
                        <td><?= h($video->category_name) ?></td>
                        <td><img width=100 src="<?php echo $video->video_thumb; ?>" /></td>
                        <td><?= $video->video_published_at ?></td>
                        <td><?php
								$t = $video->video_length;
								echo date('H:i:s', mktime(0, 0,$t));
								
							?></td>
                        <td><?php echo $video->total_suggest > 0 ? $video->total_suggest : 0 ?></td>
                        <td width="100">
                            <?php
                                echo $this->Form->select('status',$status,['class'=>'form-control', 'default' => $video->status]);
                            ?>
                        </td>                        
                        <td><?= $video->activate == 1 ? 'Yes' : 'No' ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('<span class="glyphicon glyphicon-search"></span>'), ['action' => 'view', $video->video_id],['escape' => false]) ?>
                            <?= $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>'), ['action' => 'edit', $video->video_id],['escape' => false]) ?>
                            <?= $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>'), ['action' => 'delete', $video->video_id], ['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $video->video_id)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <small><?= $this->Paginator->counter() ?></small>
            </p>

            <?php
            $params = $this->Paginator->params();
            if($params['pageCount'] > 1):
                ?>
                <ul class="pagination pagination-sm">
                    <?php
                    echo $this->Paginator->prev(__('Previous'));
                    echo $this->Paginator->numbers();
                    echo $this->Paginator->next(__('Next'));
                    ?>
                </ul>
            <?php endif; ?>

        </div> <!-- end col md 9 -->
    </div><!-- end row -->


</div><!-- end containing of content -->

<script>
    $(document).ready(function(){
        $('select[name=status]').change(function(){
            var status = $(this).val();
            var video_id = $(this).closest('tr').attr('video_id');
            $.ajax({
                url:'videos/change_status',
                type:'post',
                data:{video_id:video_id,status:status},
                success:function(data){
                },
                error: function (data) {
                    alert('ajax failed');
                }
            });
        });
        $('.fancybox-media')
            .attr('rel', 'media-gallery')
            .fancybox({
                openEffect : 'none',
                closeEffect : 'none',
                prevEffect : 'none',
                nextEffect : 'none',

                arrows : false,
                helpers : {
                    media : {},
                    buttons : {}
                }
            });

        $('#search_form select[name=category], #search_form select[name=status]').change(function(){
            $('form#search_form').submit();
        });
    });
</script>