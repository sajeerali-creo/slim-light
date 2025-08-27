<?php 
$page_title = "FAQ Management";

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$faq = null;

if($id > 0) {
    $qry = $conn->query("SELECT * FROM faqs WHERE id = '$id'");
    if($qry->num_rows > 0) {
        $faq = $qry->fetch_assoc();
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo $id > 0 ? 'Edit FAQ' : 'Add New FAQ'; ?></h4>
                    <div class="card-tools">
                        <a href="javascript:void(0)" onclick="location.href='<?php echo base_url ?>?page=faqs'" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to FAQs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="faqForm" method="POST" action="javascript:void(0)">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        
                        <div class="form-group">
                            <label for="question">Question <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="question" name="question" rows="3" required><?php echo $faq ? htmlspecialchars($faq['question']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="answer">Answer <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="answer" name="answer" rows="6" required><?php echo $faq ? htmlspecialchars($faq['answer']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo $faq ? $faq['sort_order'] : '0'; ?>" min="0">
                            <small class="form-text text-muted">Lower numbers appear first</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1" <?php echo ($faq && $faq['is_active'] == 1) ? 'selected' : ''; ?>>Active</option>
                                <option value="0" <?php echo ($faq && $faq['is_active'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> <?php echo $id > 0 ? 'Update FAQ' : 'Save FAQ'; ?>
                            </button>
                            <a href="javascript:void(0)" onclick="location.href='<?php echo base_url ?>?page=faqs'" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#faqForm').on('submit', function(e) {
        e.preventDefault();
        
        start_loader();
        
        $.ajax({
            url: _base_url_+'faqs/save.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert_toast('FAQ saved successfully!', 'success');
                    setTimeout(function() {
                        location.href = _base_url_+'?page=faqs';
                    }, 1500);
                } else {
                    alert_toast('Error: ' + response.message, 'error');
                }
                end_loader();
            },
            error: function() {
                alert_toast('An error occurred while saving the FAQ.', 'error');
                end_loader();
            }
        });
    });
});
</script>
