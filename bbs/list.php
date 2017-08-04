<?php
require_once '../preset.php';
include '../header.php';
?>

<?php


$q = "SELECT * FROM ap_bbs";
$result = $mysqli->query($q);


$total_record = $result->num_rows;

$mem_q = "SELECT * FROM ap_member";
$mem_result = $mysqli->query($mem_q);

?>
<br/>
<br/>
</div>
<div class="container">
  <h4>자유게시판</h4>
  <hr style="border-top: 1px solid #34495E">
  <div class="row">
    <div class="col-md-12">
      <p>
        <button type="button" class="btn btn-info" onclick="location.href='http://<?php echo $_SERVER['HTTP_HOST'];?>/gimotti/bbs/write'">글쓰기</button>
      </p>
    </div>
  </div>



  <?php if($total_record==0) :?>
    글이 없습니다.
  <?php else :?>
    <?php
    if( isset($page) && $page!='') {
      $now_page = $page;
    }
    else {
      $now_page = 1;
    }


    $record_per_page = 10;
    $page_per_block = 10;

    $now_block = ceil($now_page / $page_per_block);

    $start_record = $record_per_page*($now_page-1);
    $record_to_get = $record_per_page;

    if( $start_record+$record_to_get > $total_record) {
      $record_to_get = $total_record - $start_record;
    }

    $q = "SELECT * FROM ap_bbs WHERE 1 ORDER BY doc_idx DESC LIMIT $start_record, $record_to_get";
    $result = $mysqli->query($q);
    if($result==false) {

    }
    else {

    }


    ?>

    <table class="table">
      <thead>
        <th style="text-align:center; vertical-align:middle">글번호</th>
        <th style="text-align:center;">제목</th>
        <th style="text-align:center;">작성자</th>
        <th style="text-align:center;">등록일시</th>
      </thead>
      <?php while($data = $result->fetch_array()) :?>
        <tr style="height: 150px;text-align:center;" onclick="location.href='http://<?php echo $_SERVER['HTTP_HOST'];?>/gimotti/bbs/view?doc_idx=<?php echo $data['doc_idx']; ?>'" >
          <td style="font-size: 30px; padding-top: 5%;"><?php echo $data['doc_idx']?></td>
          <td style="padding-top: 5.5%;"><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/gimotti/bbs/view?doc_idx=<?php echo $data['doc_idx']; ?>" style="padding-bottom: 15%; padding-top: 15%; padding-left: 10%; padding-right: 10%;"><?php echo htmlspecialchars($data['subject'])?></a></td>
          <td style="font-size: 20px; padding-top: 4.7%;"><?php echo $data["nick"]?>
            &nbsp&nbsp


            <?php
            $dataId = $data['id'];
            $mem_q = "SELECT * FROM ap_member WHERE id='$dataId'";
            $mem_result = $mysqli->query($mem_q);
            ?>

            <?php while($mem_data = $mem_result->fetch_array()) :?>
              <?php if($mem_data['id'] == $data['id']): ?>
                <img class="circular--square" width="50px" height="50px" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/gimotti/bbs/se/upload/<?php echo $mem_data['profile'];?>">
              <?php endif ?>
            <?php endwhile ?>

          </td>

          <td style="font-size: 20px; padding-top: 5.5%;"><?php 
            if(date( 'Y-m-d',$data['reg_date'] ) == date( 'Y-m-d',time() ))
            {
              echo date( 'H:i:s',$data['reg_date'] );
            }
            else
            {
              echo date( 'Y-m-d H:i:s',$data['reg_date'] );
            }
            ?>
          </td>
        </tr>
      <?php endwhile ?>
    </table>

    <div class="row" >
      <div class="col-lg-6" style="left:23%;" >
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-btn">
              <form method="get" action="list" type="get">
                <select name="src_name" data-toggle="select" class="form-control select select-default mrs mbm" style="width: 50px;">
                  <option value="0">제목</option>
                  <option value="1" selected>이름</option>
                  <option value="2">아이디</option>
                  <option value="3">내용</option>
                </select>
                <input type="text" class="form-control" name = "src_value" style="width: 328px;">
                <input type="submit" value="검색" class="btn" style="margin-left: 5px;" >
              </form>
            </div><!-- /btn-group -->                
          </div><!-- /input-group -->
        </div><!-- /.form-group -->
      </div><!-- /.col-lg-6 -->
    </div><!-- /.rol -->

    <div class="pagination" style="left:45%;">
      <ul style="margin-bottom: 0px;padding-left: 0px;">
        <?php
        $total_page = ceil($total_record / $record_per_page);
        $total_block = ceil($total_page / $page_per_block);

        if(1<$now_block ) {
          $pre_page = ($now_block-1)*$page_per_block;
          echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/gimotti/bbs/list?page='.$pre_page.'">이전</a>';
        }

        $start_page = ($now_block-1)*$page_per_block+1;
        $end_page = $now_block*$page_per_block;
        if($end_page>$total_page) {
          $end_page = $total_page;
        }

        ?>

        <?php for($i=$start_page;$i<=$end_page;$i++) :?>
          <li><a href="./list?id=<?php echo $id ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor?>
      </ul>
      <?php
      if($now_block < $total_block) {
        $post_page = ($now_block)*$page_per_block+1;
        echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/bbs/list?page='.$post_page.'">다음</a>';
      }

      ?>
    </div><!-- .pagination -->


  </div>
<?php endif?>



<?php
include '../footer.php';
?>