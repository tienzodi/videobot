<div class="view">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Video</h1>
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
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit Video'), ['action' => 'edit', $video->video_id],['escape' => false]) ?> </li>
                            <li><?= $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete Video'), ['action' => 'delete', $video->video_id], ['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $video->video_id)]) ?> </li>
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Videos'), ['action' => 'index'],['escape' => false]) ?> </li>
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Video'), ['action' => 'add'],['escape' => false]) ?> </li>
                        </ul>
                    </div><!-- end body -->
                </div><!-- end panel -->
            </div><!-- end actions -->
        </div><!-- end col md 3 -->
        <div class="videos col-md-9">
            <table cellpadding="0" cellspacing="0" class="table table-striped">
                <tbody>
                <tr>
                    <th><?= __('Youtube Id') ?></th>
                    <td><?= h($video->youtube_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category Id') ?></th>
                    <td><?= h($video->category_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category YT Id') ?></th>
                    <td><?= h($video->category_yt_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Category Name') ?></th>
                    <td><?= h($video->category_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Video Title') ?></th>
                    <td><?= $video->video_title ?></td>
                </tr>
                <tr>
                    <th><?= __('Video Thumb') ?></th>
                    <td><?= h($video->video_thumb) ?></td>
                </tr>
                <tr>
                    <th><?= __('Video Length') ?></th>
                    <td><?= $this->Number->format($video->video_length) ?></td>
                </tr>
                <tr>
                    <th><?= __('Video Channel Id') ?></th>
                    <td><?= h($video->video_channel_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= isset($status[$video->status]) ? $status[$video->status] : $video->status ?></td>
                </tr>
                <tr>
                    <th><?= __('Activate') ?></th>
                    <td><?= $video->activate == 1 ? 'Yes' : 'No' ?></td>
                </tr>
                <tr>
                    <th><?= __('Video Published At') ?></th>
                    <td><?= h($video->video_published_at) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($video->created) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



