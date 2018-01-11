<div class="modal fade" id="<?php echo isset($modal['id'])?$modal['id']:'modal-id' ?>">
    <div class="modal-dialog <?php echo isset($modal['size'])?$modal['id']:'modal-fullscreen' ?>">
        <!-- <div class="modal-content"> -->
        <div class="modal-content panel <?php echo isset($modal['panel'])?$modal['id']:'panel-success' ?>">
            <div class="modal-header panel-heading <?php echo isset($modal['bg'])?$modal['id']:'blue-bg' ?>">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="panel-title"><?php echo isset($modal['title'])?$modal['title']:'Detail' ?></h3>
            </div>
            <div class="modal-body panel-body" style="padding:5px;">
            </div>
            
            <div class="panel-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <!-- </div> -->
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->