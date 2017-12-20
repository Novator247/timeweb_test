<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 22:03
 */
/**
 * @var $results \Model\SearchResult[]
 * @var $this \Controller\IndexController
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">Results</div>
    <table class="table">
        <thead>
        <tr>
            <td>Url</td>
            <td>Object count</td>
            <td>Date</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $r): ?>
            <tr>
                <td>
                    <span class="btn-link" style="cursor:pointer;"><?= $this->escapeHtml($r->getUrl()) ?></span>
                    <div class="data" style="display: none;">
                        <?php foreach ($r->getData() as $d): ?>
                            <?= $this->escapeHtml($d); ?><br/>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td><?= $r->getCount() ?></td>
                <td><?= $r->getCreatedAt() ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="resultModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Request result</h4>
            </div>
            <div id="modalContent" class="modal-body"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.btn-link').on('click', function () {
            $('#modalContent').html($(this).next('.data').html());
            $('#resultModal').modal();
        });
    });
</script>