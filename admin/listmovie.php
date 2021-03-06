<?php
  require 'crud/config.php';
  include 'tamplate/header.php';

  $limit = 10;

  $current = $_GET['current'];
  $active = "active";
  $pages = 1;
  $th = [];

  if (isset($_GET['pages'])) {
    $pages = $_GET['pages'];
  }

  switch ($current) {
    case 'movie':
    $sql = mysqli_query($con, 'SELECT `durasi`,`episode`,`gambar`,`genre`,`id`,`judul`,`rate`,
      `rilis`, `sinopsis`, `status`, `studio`,`type`,`views`,`time` FROM `movies` ORDER BY `id` DESC');
    array_push($th, 'judul', '', 'durasi', 'rate','rilis','type','studio','status');
    break;

    case 'episode':
    $sql = mysqli_query($con, 'SELECT `judul`,`id`,`episode`,`link` FROM `episode` ORDER BY `id` DESC');
    array_push($th, 'judul', 'episode');
    break;

    case 'kosong':
      switch ($type) {
        case 'movie':
          $sql = mysqli_query($con, 'SELECT `judul`,`id`,`episode`,`link` FROM `movies` ORDER BY `id` DESC ');
          array_push($th, 'judul', '', 'durasi', 'rate','rilis','type','studio','status');
        break;
          
      case 'episode':
          $sql = mysqli_query($con, 'SELECT `judul`,`id`,`episode` FROM `episode` ORDER BY `id` DESC ');
          array_push($th, 'judul', 'episode');
        break;
      }
    break;
  }

  while ($a = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
    $result[] = $a;
  }

  $lenght = mysqli_num_rows($sql);
  $result = limitSql($sql, $pages, $limit);

  if (empty($result)) {
    $pages = ceil($lenght / $limit);
    header('Location: ?current=' . $current . '&pages=' . $pages);
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
      <section class="content" style="margin-top: 10px;">
        <div class="card">
          <div class="card-header">
            <h4 style="display: inline" class="text-success text-uppercase"><b> <?php echo $current; ?> </b></h4>
            <div class="card-tools">
              <ul class="pages pagination pagination-sm float-right"></ul>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table" id="listmovies">
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
              <tbody id="bodylist" >
                <?php
                  foreach ($result as $row) {
                    ?>
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
                          <a href="#" name="delete" data-id="<?php echo $row['id']; ?>" title='Delete Record' class="delete"> <span class='fas fa-trash-alt'></span></a>
                        </td>
                      </tr>
                    <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>
    <?php
      include 'tamplate/footer.php'
    ?>
  </div>
</body>

<script type="text/javascript">
  $(document).ready(function() {
    var count = <?php echo $lenght ?>;
    var limit = <?php echo $limit ?>;
    if (count > limit) {
      $('.pages').append("<li><a href='?current=<?php echo $current ?>&pages=<?php echo limitPage($pages, $lenght, $limit, 'left') ?>'class='page-link'>&laquo;</a></li>");
      $('.pages').append("<li class='page-item <?php echo openPage($pages, $arr[0], $active) ?>'><a href='?current=<?php echo $current ?>&pages=<?php echo $arr[0] ?>' class='page-link'><?php echo $arr[0] ?></a></li>");
      $('.pages').append("<li class='page-item <?php echo openPage($pages, $arr[1], $active) ?>'><a href='?current=<?php echo $current ?>&pages=<?php echo $arr[1] ?>' class='page-link'><?php echo $arr[1] ?></a></li>");
      if (count > limit * 2) {
        $('.pages').append("<li class='page-item <?php echo openPage($pages, $arr[2], $active) ?>'><a href='?current=<?php echo $current ?>&pages=<?php echo $arr[2] ?>' class='page-link'><?php echo $arr[2] ?></a></li>");
      }
      $('.pages').append("<li><a href='?current=<?php echo $current ?>&pages=<?php echo limitPage($pages, $lenght, $limit, 'right') ?>'class='page-link'>&raquo;</a></li>");
    }

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

    var val = "<?php echo "$current" ?>";
    $('#search').on('keyup', function() {
      let search = $(this).val();

      if(search != ""){
        $.ajax({
          method: "POST",
          url:    "crud/searchmovie.php",
          data: { search : $(this).val(), different : val, type : val },
          success: function(data){
            $('#listmovies').html(data);
          }
        });
      } else {
        $.ajax({
          method: "POST",
          url:    "crud/searchmovie.php",
          data: { search : $(this).val(), different : val, type : 'kosong' },
          success: function(data){
            $('#listmovies').html(data);
          }
        });
      }

    });
  });
</script>