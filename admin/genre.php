<?php
  require 'crud/config.php';
  include 'tamplate/header.php';

  $limit = 10;

  $active = "active";
  $genres = 'genre';
  $pages = 1;
  $th = [];
  $q = $_GET['q'];

  if (isset($_GET['pages'])) {
    $pages = $_GET['pages'];
  }

  if ($pages == 0){
    header('Location: genre.php');
    die();
  }

  $sql = mysqli_query($con, 'SELECT * FROM `genre` ORDER BY `id` DESC');
  array_push($th, 'nama', 'info');

  while ($a = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
    $result[] = $a;
  }

  $lenght = mysqli_num_rows($sql);
  $result = limitSql($sql, $pages, $limit);

  if(empty($result)){
    $pages = ceil($lenght/$limit);
    if(!empty($q)){
      $pages = $pages.'&q='.$q;
    }
    header('Location: ?pages='.$pages);
  }

  $arr = selectPage($pages, $lenght, $limit);
?>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php
      include 'tamplate/navbar.php';
      include 'tamplate/sidebar.php';
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Genre</h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-4" style="margin-bottom: 10px;">
              <h4 class="judul"><strong>Add new genre</strong></h4>
              <label>name</label>
              <input id="nama" type="text" class="form-control inputnama" placeholder="name" style=" margin-bottom: 12px;">
              <label>description</label>
              <textarea id="info" type="text" class="form-control inputinfo" placeholder="deskripsi" style="margin-bottom: 10px; height: 100px;"></textarea>
              <input id="add" class="btn btn-danger" type="button" value="Add">
            </div>
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <div class="card-tools">
                    <form action="" method="GET">
                      <ul class="pagination pagination-sm float-right pages"></ul>
                    </form>
                  </div>
                </div>
                <div class="card-body p-0">
                  <table class="table" id="genrelist">
                    <thead>
                      <tr>
                        <?php
                          foreach ($th as $a) {
                            ?>
                              <th><?php echo $a ?></th>
                            <?php
                          }
                        ?>
                        <th style="width: 50px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($result as $row) {
                          $temp = implode(',', $row); 
                          ?>
                            <tr>
                              <?php
                                foreach ($th as $c) {
                                  ?>
                                    <td><?php echo $row[$c] ?></td>
                                  <?php
                                }
                              ?>
                              <td>
                                <a href="#editmodal" name="edit" data-id="<?php echo $temp ?>" title='Update Record' data-toggle='modal' class="edit"> <span class='fas fa-edit'></span></a>
                                <a href="#" class="delete" data-id="<?php echo $row['id'] ?>" title='Delete Record' data-toggle='modal'> <span class='fas fa-trash-alt'></span></a>
                              </td>
                            </tr>
                          <?php
                        }
                      ?>
                      <!-- MODAL EDIT -->
                      <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Edit Banner</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <label for="namaEdit" class="text-primary">Nama:</label><br>
                                <input class="form-control" rows="3" id="namaEdit" placeholder="isi deskrisi...">
                              </div>
                              <div class="form-group">
                                <label for="descEdit" class="text-primary">Deskripsi:</label><br>
                                <textarea class="form-control" rows="3" id="descEdit" placeholder="isi deskrisi..."></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-primary" id="saveEdit" >Edit Data</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <?php include 'tamplate/footer.php' ?>
  </div>
</body>

<script>
  $(document).ready(function() {
    var count = <?php echo $lenght ?>;
    var limit = <?php echo $limit ?>;
    var data;

    if (count > limit) {
      $('.pages').append("<li><a href='?pages=<?php echo limitPage($pages, $lenght, $limit, 'left') ?>'class='page-link'>&laquo;</a></li>");
      $('.pages').append("<li class='page-item <?php echo openPage($pages, $arr[0], $active) ?>'><a href='?pages=<?php echo $arr[0] ?>' class='page-link'><?php echo $arr[0] ?></a></li>");
      $('.pages').append("<li class='page-item <?php echo openPage($pages, $arr[1], $active) ?>'><a href='?pages=<?php echo $arr[1] ?>' class='page-link'><?php echo $arr[1] ?></a></li>");
      if (count > limit * 2) {
        $('.pages').append("<li class='page-item <?php echo openPage($pages, $arr[2], $active) ?>'><a href='?pages=<?php echo $arr[2] ?>' class='page-link'><?php echo $arr[2] ?></a></li>");
      }
      $('.pages').append("<li><a href='?pages=<?php echo limitPage($pages, $lenght, $limit, 'right') ?>'class='page-link'>&raquo;</a></li>");
    }

    $('#add').click(function() {
      var nama = $('#nama').val();
      var info = $('#info').val();

      if(nama != ""){
        $.ajax({
          method : 'POST',
          url: "crud/genreManager.php",
          data   : {genreTask : 'add', name : nama, desc : info},
          success: function(data){
            $('#genrelist').html(data);
            swal("Nice, Berhasil Terhapus!", {
              icon: "success",
            });
            $('#nama').val(""); $('#info').val("");
          }
        });
      } else {
        swal("Isi Nama Terlebih Dahulu!");
      }
    })

    $('#search').on('keyup', function() {
      let search = $(this).val();

      if(search != ""){
        $.ajax({
          method: "POST",
          url: "crud/genreManager.php",
          data: {genreTask : 'search', search : $(this).val()},
          success: function(data){
            $('#genrelist').html(data);
          }
        });
      } else {
        $.ajax({
          method: "POST",
          url: "crud/genreManager.php",
          data: {genreTask : 'kosong', search : $(this).val()},
          success: function(data){
            $('#genrelist').html(data);
          }
        });
      }
    });

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
            url: "crud/genreManager.php",
            data : {idDel : id, genreTask : 'delete'},
            success: function(data){;
              $('#genrelist').html(data);
            }
          });
        } else {
          swal("Pikirkan dengan baik sebelum menghapus!");
        }
      });
    });

    $('.edit').click(function() {
      $('#namaEdit').val(""); $('#descEdit').val("");
      datas = $(this).data("id");
      datas = datas.split(",");
      $('#namaEdit').val(datas[1]);
      $('#descEdit').val(datas[2]);
    });

    $('#saveEdit').click(function() {
      nama = $('#namaEdit').val();
      desc = $('#descEdit').val();

      swal({
        title: "Beneran mau Edit?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          swal("Nice, Berhasil Mengedit!", {
            icon: "success",
          });
          $.ajax({
            method : 'POST',
            url: "crud/genreManager.php",
            data : {genreTask : 'edits', nameGenre : nama, idGenre : datas[0], infoGenre : desc},
            success: function(data) {
              $('#editmodal').modal('hide');
              $('#genrelist').html(data);
            }
          });
        } else {
          swal("Pikirkan dengan baik sebelum menghapus!");
        }
      });
    });
  })
</script>