<?php 
$page_title = "FAQs Management";
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">FAQs Management</h4>
                    <div class="card-tools">
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm new_faq">
                            <i class="fa fa-plus"></i> Add New FAQ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="faq_list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Sort Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $qry = $conn->query("SELECT * FROM `faqs` ORDER BY sort_order ASC, id ASC");
                                $i = 1;
                                while($row = $qry->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars(substr($row['question'], 0, 100)) . (strlen($row['question']) > 100 ? '...' : ''); ?></td>
                                    <td><?php echo htmlspecialchars(substr($row['answer'], 0, 150)) . (strlen($row['answer']) > 150 ? '...' : ''); ?></td>
                                    <td><?php echo $row['sort_order']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $row['is_active'] ? 'success' : 'danger'; ?>">
                                            <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" class="btn btn-primary btn-sm manage_faq">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete_faq" data-id="<?php echo $row['id']; ?>">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.new_faq').click(function(){
        location.href = _base_url_+"?page=faqs/manage";
    });
    
    $('.manage_faq').click(function(){
        location.href = _base_url_+"?page=faqs/manage&id="+$(this).attr('data-id');
    });
    
    $('.delete_faq').click(function(){
        _conf("Are you sure to delete this FAQ?","delete_faq",[$(this).attr('data-id')]);
    });
    
    $('#faq_list').dataTable();
});

function delete_faq($id){
    start_loader();
    $.ajax({
        url: _base_url_+'faqs/delete.php',
        method: 'POST',
        data: {id: $id},
        dataType: 'json',
        success: function(resp){
            if(resp.success){
                location.reload();
            } else {
                alert_toast(resp.message, 'error');
            }
            end_loader();
        },
        error: function(){
            alert_toast('An error occurred while deleting the FAQ.', 'error');
            end_loader();
        }
    });
}
</script>
