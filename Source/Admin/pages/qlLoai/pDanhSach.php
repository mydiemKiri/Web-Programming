<div class="sideBar">
    <a href="index.php?c=3&a=3" style="text-decoration: none;">
        <div>
            <button class="btn btn-success btn-block center"> Thêm loại sản phẩm </button>
        </div>
    </a>
    <div>
        <form action="index.php?c=3&a=1" method="POST" style="display:flex;">
            <input class="sidebar-search" type="text" name="txtSearch" placeholder="Nhập tên loại sản phẩm cần tìm..." required>
            <input class="btn btn-success btn-block center" type="submit" name="search" value="Tìm Kiếm">
        </form>
    </div>
</div>
<div id="pagesAjax">
    <div style="overflow-x:scroll;">
        <table cellspacing="0" border="1" class="table table-hover table-striped" style="font-size:12px">
            <tr>
                <th width ="100">Mã loại sản phẩm</th>
                <th width ="180">Tên loại sản phẩm</th>
                <th width ="75">Tình trạng</th>
                <th width ="100">Thao tác</th>
            </tr>
            <?php
                $txtSearch = 1;

                if(isset($_SESSION["search"])){
                    unset($_SESSION["search"]);
                }

                if(isset($_POST["search"])){
                    if(isset($_POST["txtSearch"])){
                        $_SESSION["search"] = $_POST["txtSearch"];
                        $txtSearch = $_POST["txtSearch"];
                        $txtSearch = "TenLoaiSanPham LIKE N'%$txtSearch%' ";
                    }
                }
           
                $page = 1;
                $recordStart = ($page - 1)*5;
                $strPagination = "LIMIT $recordStart, 5";
               
                $sql = "SELECT MaLoaiSanPham, TenLoaiSanPham, BiXoa 
                        FROM LoaiSanPham
                        WHERE $txtSearch 
                        $strPagination ";
    
                $result = DataProvider::ExecuteQuery($sql);
                while($row = mysqli_fetch_array($result))
                {
                    ?>
                        <tr>
                            <td><?php echo $row["MaLoaiSanPham"];?></td>
                            <td><?php echo $row["TenLoaiSanPham"];?></td>
                            <td>
                                <?php
                                    if($row["BiXoa"]==1)
                                        echo "<img src ='images/locked.png'/>";
                                    else
                                        echo "<img src ='images/active.png'/>";
                                ?>
                            </td>
                            <td>
                               <a href="pages/qlLoai/xlKhoa.php?id=<?php echo $row ["MaLoaiSanPham"]?>">
                                   <img src ="images/lock.png"/>
                               </a>
                                <a href = "index.php?c=3&a=2&id=<?php echo $row["MaLoaiSanPham"]?>">
                                    <img src="images/edit.png"/>    
                                </a>
                            </td>
                        </tr>
                    <?php
                }
            ?>
        </table>
        <?php
            $sqlGetPagesNumber = "  SELECT * FROM LoaiSanPham
                                    WHERE $txtSearch  ";

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
</div>

<script>
    function loadPrePage(page, numOfPages){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagesAjax").innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "pages/qlLoai/pAjaxPagination.php?p="+page+"&ps="+numOfPages, true);
        xhttp.send();
    }
    function loadNextPage(page, numOfPages){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagesAjax").innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "pages/qlLoai/pAjaxPagination.php?p="+page+"&ps="+numOfPages, true);
        xhttp.send();
    }
    function loadPage(page, numOfPages){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagesAjax").innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "pages/qlLoai/pAjaxPagination.php?p="+page+"&ps="+numOfPages, true);
        xhttp.send();
    }
</script>