<?php 
    include "../../../lib/DataProvider.php"; 
    session_start();
?>
<div style="overflow-x: scroll;">
    <table cellspacing="0" border="1" class="table  table-hover table-striped" style="font-size:12px;">
        <tr>
            <th width ="100">Mã tài khoản</th>
            <th width ="180">Tên đăng nhập</th>
            <th width ="180">Tên hiển thị</th>
            <th width ="180">Địa chỉ</th>
            <th width ="130">Điện thoại</th>
            <th width ="180">Email</th>
            <th width ="75">Tình trạng</th>
            <th width ="130">Loại tài khoản</th>
            <th width ="200">Thao tác</th>
        </tr>
        <?php
            $txtSearch = 1;
           
            if(isset($_SESSION["search"])){
                $txtSearch = $_SESSION["search"];
                $txtSearch = "t.TenDangNhap LIKE N'%$txtSearch%' ";
            }
        
           $page = 1;
            if(isset($_GET["p"]) && isset($_GET["ps"])){
                if($_GET["p"] >= 1 && $_GET["p"] <= $_GET["ps"]){
                    $page = $_GET["p"];
                }
            }
            $recordStart = ($page - 1)*5; 
            $strPagination = "LIMIT $recordStart, 5";
        
           $sql = " SELECT t.MaTaiKhoan, t.TenDangNhap, t.TenHienThi, t.DiaChi, t.DienThoai, t.Email, t.BiXoa, 
                            l.TenLoaiTaiKhoan 
                    FROM TaiKhoan t, LoaiTaiKhoan l 
                    WHERE t.MaLoaiTaiKhoan = l.MaLoaiTaiKhoan AND $txtSearch 
                    $strPagination ";

            $result = DataProvider::ExecuteQuery($sql);

            while($row = mysqli_fetch_array($result))
            {
               ?>
                   <tr>
                       <td><?php echo $row["MaTaiKhoan"];?></td>
                       <td><?php echo $row["TenDangNhap"];?></td>
                       <td><?php echo $row["TenHienThi"];?></td>
                       <td><?php echo $row["DiaChi"];?></td>
                       <td><?php echo $row["DienThoai"];?></td>
                       <td><?php echo $row["Email"];?></td>
                       <td>
                           <?php
                               if($row["BiXoa"]==1)
                                   echo "<img src ='images/locked.png'/>";
                               else
                                   echo "<img src ='images/active.png'/>";
                           ?>
                       </td>
                       <td><?php echo $row["TenLoaiTaiKhoan"]; ?></td>
                       <td>
                           <a href="pages/qlTaiKhoan/xlKhoa.php?id=<?php echo $row["MaTaiKhoan"]?>">
                               <img src="images/lock.png"/>
                           </a>
                           <a href = "index.php?c=1&a=2&id=<?php echo $row["MaTaiKhoan"]?>">
                               <img src="images/edit.png"/>    
                           </a>
                       </td>
                   </tr>
               <?php
            }
        ?>
    </table>
    <?php
        $sqlGetPagesNumber = "  SELECT *
                                FROM TaiKhoan t, LoaiTaiKhoan l 
                                WHERE t.MaLoaiTaiKhoan = l.MaLoaiTaiKhoan 
                                    AND $txtSearch ";
        
        $result1 = DataProvider::ExecuteQuery($sqlGetPagesNumber);
        
        $count = mysqli_num_rows($result1);
        $numOfPages = floor(($count - 1)/5 + 1);
    ?>
</div>
<ul  class="pagination">
    <li><a style="margin:0" onclick="loadPrePage(<?php if($page == 1) echo $numOfPages; else echo $page - 1; ?>, <?php echo $numOfPages;?>)"
    class="btn-pages"><</a></li>
    <?php
        for($i = 0; $i < $numOfPages; $i++){
            ?>
            <li>
                <a style="margin:0" name="checkPage" <?php if($page == $i+1) echo "class='active'" ;?> 
                    onclick="loadPage(<?php echo $i+1; ?>, <?php echo $numOfPages ?>)">
                    <?php echo $i+1; ?>
                </a>    
            </li>
            <?php
        }
        ?>
    <li><a style="margin:0" onclick="loadNextPage(<?php if($page == $numOfPages) echo 1; echo $page + 1; ?>, <?php echo $numOfPages;?>)">></a></li>
</ul>