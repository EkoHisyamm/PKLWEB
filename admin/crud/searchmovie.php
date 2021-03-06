<?php
	if (isset($_POST['search'])) {
		require_once 'config.php';
		$th      = [];
		$limit   = 10;
		$current = $_POST['different'];
		$type    = $_POST['type'];

		switch ($current) {
			case 'movie':
				switch ($type) {
					case 'kosong':
						$sql = mysqli_query($con, 'SELECT `durasi`,`episode`,`gambar`,`genre`,`id`,`judul`,`rate`, `rilis`, `sinopsis`, `status`, `studio`,`type`,`views`,`time` FROM `movies` ORDER BY `id` DESC ');
						array_push($th, 'judul', '', 'durasi', 'rate','rilis','type','studio','status');
					break;
					
					case 'movie':
						$sql = mysqli_query($con, 'SELECT `durasi`,`episode`,`gambar`,`genre`,`id`,`judul`,`rate`, `rilis`, `sinopsis`, `status`, `studio`,`type`,`views`,`time` FROM `movies` WHERE `judul` LIKE "%' . $_POST['search'] . '%"');
						array_push($th, 'judul', '', 'durasi', 'rate','rilis','type','studio','status');
					break;
				}
			break;
			
			case 'episode':
				switch ($type) {
					case 'kosong':
						$sql = mysqli_query($con, 'SELECT `judul`,`id`,`episode`,`link` FROM `episode` ORDER BY `id` DESC ');
						array_push($th, 'judul', 'episode');
						break;
					
					case 'episode':
						$sql = mysqli_query($con, 'SELECT `judul`,`id`,`episode`,`link` FROM `episode` WHERE `judul` LIKE "%' . $_POST['search'] . '%"');
						array_push($th, 'judul', 'episode');
						break;
				}
			break;
		}

		while ($a = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
			$result[] = $a;
		}

		$pages = 1;
		$lenght = mysqli_num_rows($sql);
		$result = limitSql($sql, $pages, $limit);

		?>
			<thead>
				<tr>
					<?php
					$i = 0;
					foreach ($th as $a) {
						$i++;
						if ($i > 2) {
							$hidden = 'hidden';
						}
						?>
						<th class="<?php echo $hidden ?>"><?php echo $a ?></th>
						<?php
					}
					?>
					<th style="width: 50px">Action</th>
				</tr>
			</thead>
		<?php

		foreach ($result as $row) {
			?>
				<tbody id="bodylist" >
					<tr>
						<?php
						$i = 0;
						foreach ($th as $c) {
							$i++;
							$hidden = "";
							if ($i > 2) {
								$hidden = 'hidden';
							}
							?>
							<td class="<?php echo $hidden ?>"><?php echo $row[$c] ?></td>
							<?php
						}
						?>
						<td>
							<a href="add<?php echo $current ?>.php?id=<?php echo $row['id'] . '&current=' . $current . '&pages=' . $pages . '&action=edit' ?>" name="edit" title='Update Record' data-toggle='tooltip'><span class='fas fa-edit'></span></a>
							<a href="#deletemodal" name="delete" data-id="<?php echo $row['id']; ?>" title='Delete Record' data-toggle='modal' class="delete"> <span class='fas fa-trash-alt'></span></a>
						</td>
					</tr>
				</tbody>

				<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="delete">Delete Data</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body" style="padding: 0;">
								<form method="post" action="">
									<input type="hidden" class="id" name="id" id="id" />
									<div class="card-body">
										<div class="form-group">
											<p for="Confirm">data yang telah dihapus tidak dapat di kembalikan</p>
										</div>
									</div>
									<div class="modal-footer">
										<button name="delete" type="submit" class="btn btn-danger">Delete</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			<?php
		}
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		var id;
		$(".delete").click(function() {
			id = $(this).data("id");
		});

		var val = "<?php echo "$current" ?>";
		$(".delete").click(function() {
      var id = $(this).data("id");
      swal({
        title: "Beneran mau hapus?",
        text: "Sekali lu hapus, kagak bisa di backup lho!!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          swal("Nice, Berhasil Terhapus!", {
            icon: "success",
          });
          $.ajax({
            method: "POST",
            url: "crud/delete.php",
            data : {idDel : id, showdata : val},
            success: function(data){;
              $('#deletemodal').modal('hide');
              $('#listmovies').html(data);
            }
          });
        } else {
          swal("Pikirkan dengan baik sebelum menghapus!");
        }
      });
    });
	});
</script>