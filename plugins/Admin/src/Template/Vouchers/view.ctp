<div class="view">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Voucher</h1>
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
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit Voucher'), ['action' => 'edit', $voucher->voucher_id],['escape' => false]) ?> </li>
                            <li><?= $this->Form->postLink(__('<span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;Delete Voucher'), ['action' => 'delete', $voucher->voucher_id], ['escape' => false,'confirm' => __('Are you sure you want to delete # {0}?', $voucher->voucher_id)]) ?> </li>
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;List Vouchers'), ['action' => 'index'],['escape' => false]) ?> </li>
                            <li><?= $this->Html->link(__('<span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;New Voucher'), ['action' => 'add'],['escape' => false]) ?> </li>
                        </ul>
                    </div><!-- end body -->
                </div><!-- end panel -->
            </div><!-- end actions -->
        </div><!-- end col md 3 -->
        <div class="vouchers col-md-9">
            <table cellpadding="0" cellspacing="0" class="table table-striped">
                <tbody>


                <tr>
                    <th><?= __('Image') ?></th>
                    <td><a href="<?= $this->request->webroot . $voucher->image_url ?>" class="fancybox"><img height="75" src="<?= $this->request->webroot . $voucher->image_url ?>"></a></td>
                </tr>
                <tr>
                    <th><?= __('Activate') ?></th>
                    <td><?= $voucher->activate == 1 ? 'Yes' : 'No' ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($voucher->created) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.fancybox').fancybox();
    });
</script>

