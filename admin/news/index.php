<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>
<script type="text/javascript">
    document.title = "News | VinaEnter Edu";
</script>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Quản lý tin</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr />

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-sm-6">
                                    <a href="/admin/news/add.php" class="btn btn-success btn-md">Thêm</a>
                                    <form style="display: inline;" action="/admin/news/export.php" method="post">
                                        <input type="submit" name="export" class="btn btn-danger" value="Export To Excel With News">
                                    </form>
                                    <form style="display: inline;" action="/admin/news/export.php" method="post">
                                        <input type="submit" name="export-counter" class="btn btn-danger" value="Export To Excel With Counter">
                                    </form>
                                </div>
                                <div class="col-sm-6" style="text-align: right;">
                                    <form method="get" action="/admin/news/search.php">
                                        <input type="submit" name="submit" value="Tìm kiếm" class="btn btn-warning btn-sm" style="float:right" />
                                        <input type="text" name="search" class="form-control input-sm" placeholder="Nhập từ khóa tìm kiếm" style="float:right; width: 300px;" />
                                        <div style="clear:both"></div>
                                    </form><br />
                                </div>
                            </div>
                            <?php
                                if(isset($_GET['msg'])){
                            ?>
                            <p style="color: red; text-align: center;"><?php echo urldecode($_GET['msg']); ?></p>
                            <?php
                                }
                            ?>
                            <form action="/admin/news/del.php" method="post">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><input type="checkbox" id="checkall"/></th>
                                            <th>ID</th>
                                            <th>Tên tin</th>
                                            <th>Danh mục tin</th>
                                            <th>Người thêm</th>
                                            <th>Lượt đọc</th>
                                            <th>Trạng thái</th>
                                            <th>Hình ảnh</th>
                                            <th width="160px">Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sql = "SELECT COUNT(*) AS total FROM news";
                                            $rs = mysql_query($sql);
                                            $arTotal = mysql_fetch_assoc($rs);
                                            $total = $arTotal['total'];
                                            $row_count = ROW_COUNT15;
                                            $total_page = ceil($total / $row_count);
                                            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                                            if($current_page < 1){
                                                $current_page = 1;
                                            }elseif($current_page > $total_page){
                                                $current_page = $total_page;
                                            }elseif(preg_match("/\D/", $current_page)){
                                                $current_page = 1;
                                            }
                                            $offset = ($current_page - 1) * $row_count;
                                            $query = "SELECT m.id AS id, m.name AS newsName, n.name AS catName, username, counter, m.active AS active, m.picture AS picture FROM news AS m INNER JOIN cat_list AS n ON m.cat_id = n.id INNER JOIN users AS p ON m.created_by = p.id ORDER BY m.id DESC LIMIT {$offset},{$row_count}";
                                            $result = mysql_query($query);
                                            while($ar = mysql_fetch_assoc($result)){
                                                $id = $ar['id'];
                                                $news_name = $ar['newsName'];
                                                $picture = $ar['picture'];
                                                $cat_name = $ar['catName'];
                                                $active = $ar['active'];
                                                $counter = $ar['counter'];
                                                $username = $ar['username'];
                                                $urlDel = "/admin/news/del.php?id={$id}&page={$current_page}";
                                                $urlEdit = "/admin/news/edit.php?id={$id}&page={$current_page}";
                                        ?>
                                        <tr class="gradeX">
                                            <td class="text-center">
                                            <?php if($_SESSION['userInfo']['username'] == 'admin'){ ?>
                                            <input type="checkbox" name="checkid[]" value="<?php echo $id; ?>" />
                                            <?php }elseif($_SESSION['userInfo']['username'] == $username){ ?>
                                            <input type="checkbox" name="checkid[]" value="<?php echo $id; ?>" />
                                            <?php } ?>
                                            </td>
                                            <td><?php echo $id; ?></td>
                                            <td><?php echo $news_name; ?></td>
                                            <td><?php echo $cat_name; ?></td>
                                            <td><?php echo $username; ?></td>
                                            <td><?php echo $counter; ?></td>
                                            <?php
                                                if($active == 1){
                                                    $img = "active.gif";
                                                }else{
                                                    $img = "deactive.gif";
                                                }
                                            ?>
                                            <td class="text-center">
                                                <?php if($_SESSION['userInfo']['username'] == 'admin'){ ?>
                                                <a href="javascript:void(0)" class="active" active="<?php echo $active; ?>" id="<?php echo $id; ?>"><img src="/templates/admin/assets/img/<?php echo $img; ?>" alt="<?php echo $img; ?>" ></a>
                                                <?php }elseif($_SESSION['userInfo']['username'] == $username){ ?>
                                                <a href="javascript:void(0)" class="active" active="<?php echo $active; ?>" id="<?php echo $id; ?>"><img src="/templates/admin/assets/img/<?php echo $img; ?>" alt="<?php echo $img; ?>" ></a>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    if($picture != ''){
                                                ?>
                                                <img src="/files/<?php echo $picture ?>" alt="<?php echo $picture ?>" height="90px" width="90px">
                                                <?php
                                                    }else{
                                                ?>
                                                    <strong>Không có hình</strong>
                                                <?php
                                                    }
                                                ?>
                                            </td>
                                            <td class="center">
                                            <?php if($_SESSION['userInfo']['username'] == 'admin'){ ?>
                                                <a href="<?php echo $urlEdit ?>" title="Sửa" class="btn btn-primary"><i class="fa fa-edit "></i> Sửa</a>
                                                <a href="<?php echo $urlDel ?>" title="Xóa" onclick="return confirm('Bạn có chắc chắn xóa không?')" class="btn btn-danger"><i class="fa fa-pencil"></i> Xóa</a>
                                            <?php }elseif($_SESSION['userInfo']['username'] == $username){ ?>
                                                <a href="<?php echo $urlEdit ?>" title="Sửa" class="btn btn-primary"><i class="fa fa-edit "></i> Sửa</a>
                                                <a href="<?php echo $urlDel ?>" title="Xóa" onclick="return confirm('Bạn có chắc chắn xóa không?')" class="btn btn-danger"><i class="fa fa-pencil"></i> Xóa</a>
                                            <?php } ?>  
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <input type="submit" name="submit" class="btn btn-danger" value="Xóa">
                            </form>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" id="dataTables-example_info" style="margin-top:27px">Hiển thị từ 1 đến <?php echo $row_count ?> của <?php echo $total ?> tin</div>
                                </div>
                                <div class="col-sm-6" style="text-align: right;">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                                        <ul class="pagination1">
                                            <?php
                                                for($i = 1; $i <= $total_page; $i++){
                                                    $active = '';
                                                    if($i == $current_page){
                                                        $active = 'active';
                                            ?>
                                            <li class="paginate_button <?php echo $active ?>"><span><?php echo $i ?></span></li>
                                            <?php            
                                                    }else{
                                            ?>
                                            <li class="paginate_button <?php echo $active ?>"><a href="/admin/news/index.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>
</div>
<!-- /. PAGE INNER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>