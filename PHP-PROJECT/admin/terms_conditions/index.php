<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php
$qry = $conn->query("SELECT * from `terms_conditions` ORDER BY date_updated DESC LIMIT 1");
$row = $qry->fetch_array(MYSQLI_ASSOC);
if (is_array($row) && !empty($row)) {
    foreach ($row as $k => $v) {
        if (!is_numeric($k)) {
            $$k = $v; // Dynamically create variables
        }
    }
}
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h5 class="card-title">Terms & Conditions Management</h5>
		</div>
		<div class="card-body">
			<form id="terms_c">
				<div class="form-group">
					<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
					<label for="" class="control-label">Content</label>
					<textarea name="content" id="content" cols="30" rows="25" class="form-control summernote"><?php echo (isset($content)) ? html_entity_decode(($content)) : '' ?></textarea>
					<small class="form-text text-muted">You can use HTML tags for formatting (e.g., &lt;p&gt;, &lt;strong&gt;, &lt;ol&gt;, &lt;li&gt;)</small>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button class="btn btn-primary btn-sm" form="terms_c">Update</button>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#terms_c').submit(function(e){
		e.preventDefault();
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Content.php?f=terms_conditions",
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			dataType: 'json',
			error: err=>{
				alert_toast("An error occured",'error')
				console.log(err);
				end_loader();
			},
			success:function(resp){
				if(resp != undefined){
					if(resp.status == 'success'){
						location.reload()
					}else{
						alert_toast("An error occured",'error')
						console.log(resp);
					}
					end_loader();
				}
			}
		})
	})

	$('#content').summernote({
		height: 500,
		focus: true,
		disableResizeEditor: false,
		airMode: false,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ol', 'ul', 'paragraph', 'height']],
			['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			['view', ['fullscreen', 'codeview', 'help']]
		],
		fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48', '64', '82', '150'],
		styleTags: [
			'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
		],
		callbacks: {
			onInit: function() {
				console.log('Summernote initialized');
			},
			onFocus: function() {
				console.log('Editor focused');
			}
		}
	});

	// Enhanced Select All functionality
	
	// Alternative: Add a Select All button
	// $('.card-header').append('<button type="button" class="btn btn-info btn-sm float-right ml-2" id="selectAllBtn"><i class="fa fa-check-square"></i> Select All</button>');
	
	$('#selectAllBtn').on('click', function() {
		try {
			$('#content').summernote('selectAll');
		} catch(err) {
			var editor = $('#content').next('.note-editor');
			if (editor.length > 0) {
				var noteEditable = editor.find('.note-editable');
				if (noteEditable.length > 0) {
					noteEditable.focus();
					var range = document.createRange();
					range.selectNodeContents(noteEditable[0]);
					var selection = window.getSelection();
					selection.removeAllRanges();
					selection.addRange(range);
				}
			}
		}
	});

	// Fix font size editing
	$(document).on('click', '.note-fontsize', function() {
		setTimeout(function() {
			$('.note-fontsize .dropdown-menu li a').on('click', function() {
				var size = $(this).data('value');
				$('#content').summernote('fontSize', size);
			});
		}, 100);
	});
})
</script>