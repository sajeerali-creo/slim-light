<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-primary new_banner" href="javascript:void(0)"><i class="fa fa-plus"></i>&nbsp;Add New</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table tabe-hover table-bordered table-compact" id="list">
					<colgroup>
						<col width="10%">
						<col width="35%">
						<col width="35%">
						<col width="20%">
						<!-- <col width="15%"> -->
					</colgroup>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Image</th>
							<th>Banner Title</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT * FROM `banners` order by unix_timestamp(`date_updated`) desc, unix_timestamp(`date_created`) desc");
						while($row= $qry->fetch_assoc()):
						?>
						<tr>
							<th class="text-center"><?php echo $i++ ?></th>
							<td class='text-center'>
								<img src="<?php echo validate_image($row['file_path']) ?>" alt="Image"  style="object-fit:scale-down;object-position:center center;border-radius: 8px;height: auto;max-width: 55%;" class="img-thumbnail">
							</td>
							<td><b class=""><?php echo ucwords($row['heading']) ?></b></td>
							<td class="text-center">
								<div class="btn-group d-flex" style="gap:8px;">
									<a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat btn-sm manage_banner">
									<i class="fas fa-edit"></i>
									</a>
									<button type="button" class="btn btn-danger btn-sm btn-flat delete_banner" data-id="<?php echo $row['id'] ?>">
									<i class="fas fa-trash"></i>
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
<script>
	$(document).ready(function(){
		$('.new_banner').click(function(){
			location.href = _base_url_+"?page=banners/manage";
		})
		$('.manage_banner').click(function(){
			location.href = _base_url_+"?page=banners/manage&id="+$(this).attr('data-id')
		})
		$('.delete_banner').click(function(){
		_conf("Are you sure to delete this Banner?","delete_banner",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_banner($id){
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Content.php?f=banner_delete',
			method:'POST',
			data:{id:$id},
			dataType:'json',
			success:function(resp){
				if(resp.status == 'success'){
					location.reload()
				}else{
					alert_toast(resp.err_msg,'error')
				}
				end_loader();
			}
		})
	}
</script>