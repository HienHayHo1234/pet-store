<?php
    try {
        $host = "localhost";
        $dbname = "pet-store";
        $username = "root";
        $password = "";

        // Tạo kết nối
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

        // Thiết lập chế độ lỗi PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die("Lỗi: " . $e->getMessage());
    }

    function layChiTietTin($id = 0) { 
        try {
            $sql = "SELECT idTin, TieuDe, TomTat, Ngay, SoLanXem, Content FROM tin WHERE idTin = :id AND AnHien = 1";
            global $conn;
            $kq = $conn->prepare($sql);
            $kq->bindParam(':id', $id, PDO::PARAM_INT);
            $kq->execute();   
            return $kq->fetch();
        } catch (Exception $e) {
            die("Lỗi trong hàm: " . __FUNCTION__ . ": " . $e->getMessage());
        }
    }
    function tangSoLanXem($id = 0) {
        try {
            $sql = "UPDATE tin SET SoLanXem = SoLanXem + 1 WHERE idTin = $id";
            global $conn;
            $conn->exec($sql);
        } catch (Exception $e) {
            die("Lỗi trong hàm: " . __FUNCTION__ . ": " . $e->getMessage());
        }
    }
    function layTinTrongLoai($id = 0, $page_num = 1, $page_size = 5) {
        try {
            $startRow = ($page_num-1) * $page_size;
            // Thêm khoảng trắng trước LIMIT
            $sql = "SELECT idTin, TieuDe, TomTat, Ngay, urlHinh, Content FROM tin WHERE idTL = $id AND AnHien = 1 " . "LIMIT $startRow, $page_size";
            global $conn;
            $kq = $conn->query($sql);
            return $kq;
        } catch (Exception $e) {
            die("Lỗi trong hàm: " . __FUNCTION__ . ": " . $e->getMessage());
        }
    }
    
    function layTenLoai($id = 0) {
        try {
            $sql = "SELECT TenTL FROM theloai WHERE idTL = $id";
            global $conn;
            $kq = $conn->query($sql);
            $row = $kq->fetch();
            return $row['TenTL'];
        } catch (Exception $e) {
            die("Lỗi trong hàm: " . __FUNCTION__ . ": " . $e->getMessage());
        }
    }
    function demTinTrongLoai($id=0){
        $sql="SELECT count(*) FROM tin WHERE idTL=$id AND AnHien=1";
        global $conn;
        $kq = $conn->query($sql);
        $row = $kq->fetch();
        return $row[0];
       }
       
    function taoLinkPhanTrang($base_url, $total_rows, $page_num, $page_size=5) {
        if ($page_num<=0) return "";
        $total_pages= ceil($total_rows/$page_size); //tính tổng số trang
        if ($total_pages<=1) return "";
        $links="<ul class='pagination'>";
        if ($page_num>1) {//chỉ hiện 2 link đầu, trước khi user từ trang 2 trở đi
        $first ="<li><a href='{$base_url}'> << </a></li>";
        $page_prev= $page_num-1;
        $prev ="<li><a href='{$base_url}&page_num={$page_prev}'> < </a></li>";
        $links .= $first. $prev;
        }
        //aaa
        if ($page_num<$total_pages){ //chỉ hiện link cuối, kế khi user kô ở trang cuối
        $page_next= $page_num + 1;
        $next ="<li><a href='{$base_url}&page_num={$page_next}'> > </a></li>";
        $last ="<li><a href='{$base_url}&page_num={$total_pages}'> >> </a></li>";
        $links .=$next.$last;
        }
        $links .="</ul>";
        return $links;
       }
       function layKetQuaTim($tukhoa="aabbccdd", $page_num, $page_size=5){
        try {
         $sql="SELECT idTin, TieuDe,TomTat,Ngay,urlHinh FROM tin WHERE AnHien=1 AND (TieuDe like
        '%$tukhoa%' OR TomTat like '%$tukhoa%')";
         global $conn;
         $kq = $conn->query($sql);
         return $kq;
        }
        catch (Exception $e){ die("Lỗi trong hàm:". __FUNCTION__ . ":" . $e->getMessage() );}
        }
        
?>  

