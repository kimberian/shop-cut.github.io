<?php
  session_start();
  include("classes/connect.php");
  include("classes/log-in.php");
  include("classes/add-item.php");
  $category = "";
  $items = "";
  $description = "";
  $stocks = "";
  $price = "";
  $item_id = "";
  $ws_price = "";

  $login = new login();
  $user_data = $login->check_login($_SESSION['userid']);
  // adding data
  if (isset($_POST['addproduct'])) {
    if($_SERVER['REQUEST_METHOD']=='POST'){
      echo "<pre>";
      print_r($_POST);
      echo "</pre>";
      $item = new item();
      $userid = $_SESSION['userid'];
      // print_r($userid);
      // echo "<br>";
      $result = $item->evaluate($userid,$_POST);
      if($result == ""){
        header("Location:Shop.php");
        die;
      }else{
        echo $result;
      }
    }
  // updating data
  }else if (isset($_POST['submit-btn'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
      // echo "<pre>";
      // print_r($_POST);
      // echo "</pre>";
      $item_id = $_POST['item_id'];
      $category = $_POST['category'];
      $items = $_POST['items'];
      $description = $_POST['description'];
      $stocks = $_POST['stock'];
      $price = $_POST['price'];
      $ws_price = $_POST['ws_price'];
    }
  // deleting data 
  }else if (isset($_POST['delete-btn'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
      // echo "<pre>";
      // print_r($_POST['item_id']);
      // echo "</pre>";
      $itemid = $_POST['item_id'];
      $query = "delete from items where item_id = '$itemid'";

      $item = new database();
      $item->save($query);


    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Page | Shop-Cut</title>
    <link rel="stylesheet" href="page-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="lightbox.min.css?v=<?php echo time(); ?>"">
    <script type="text/javascript" src="lightbox-plus-jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/cferdinandi/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
  </head>



  <body>
    <!-- menubar -->
    <section id="menubar">
      <div class="menutab">
        <h2><a href="#">SHOP-cut</a></h2>
        <div class="search_box">
          <form class="" action="index.html" method="post">
            <input type="search" name="" placeholder="Find">
          </form>
        </div>
        <div class="menu-tabs">
          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Order</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </div>
      </div>
    </section>

    <section id="shop-info">
      <div class="cover-photo">
        <img src="Images/Home/cover.png" alt="">
      </div>
      <div class="shop-tab" id="myBtnContainer">
        <div class="tab-btn">
          <a href="page.php"><button id="home" class="btn " onclick="opentab(event, 'Home')">Home</button></a>
          <a href="Shop.php"><button id="shop" class="btn active" onclick="opentab(event, 'Shop')">Shop</button></a>
          <a href="Photos.php"><button id="photos" class="btn" onclick="opentab(event, 'Photos')">Photos</button></a>
          <a href="About.php"><button id="about" class="btn" onclick="opentab(event, 'About')">About Shop</button></a>
        </div>
        <div class="shop-search">
          <form class="" method="post">
            <input type="search" name="" value="" placeholder="find product">
            <input id="search-shop-button" type="submit" name="" value="Search">
          </form>
        </div>
      </div>
    </section>

    <!-- shop content -->
    <section id="Shop"  class="tabcontent">
      <div class="add-product">
        <form method="post">
        <select name="category" id="">
            <option value="<?php echo $category ?>"><?php echo $category ?></option>
            <option value="motor">motor</option>
            <option value="bicycle">bicycle</option>
          </select>
          <input type="text" value="<?php echo $items ?>" name="items" placeholder="Items">
          <input type="text" value="<?php echo $description ?>" name="description" placeholder="Description">
          <input type="text" value="<?php echo $stocks ?>" name="stock" placeholder="Stocks">
          <div class="price_menu">
            <input type="text" value="<?php echo $ws_price ?>" name="ws_price" placeholder="Whole Sale Price">
            <input type="text" value="<?php echo $price ?>" name="price" placeholder="Retail Price">
          </div>
          <input type='hidden' name='item_id' value='<?php echo $item_id ?>'>
          <span class="add-btn">
            <input type="submit" name="addproduct"value="Add/Update Product">
          </span>
        </form>

      </div>
      
      <div class="product-list">
        <caption>All Product</caption>
        <table>
          <tr>
            <th>no.</th>
            <th>Category</th>
            <th>Items</th>
            <th>Description</th>
            <th>Qty.</th>
            <th colspan="2">Price</th>
            <th>Option</th>
          </tr>
          <form class="edit_table" method="post">
            <?php
              $item = new item();
              $userid = $_SESSION['userid'];
              $item = $item->get_item($userid);
              if($item){
                // echo "<pre>";
                // print_r($item);
                // echo "</pre>";
                foreach ($item as $key => $value) {
                  // echo "<pre>";
                  // print_r($value);
                  // echo "</pre>";
                  if($value == ""){
                  }else{
                    $number = $key +1;
                    $category = $value['category'];
                    $items = $value['items'];
                    $description = $value['description'];
                    $stock = $value['stock'];
                    $price = $value['price'];
                    $item_id = $value["item_id"];
                    $ws_price = $value['ws_price'];

                    echo "<tr>
                    <td> $number </td>
                    <td>$category </td>
                    <td>$items</td>
                    <td>$description</td>
                    <td>$stock pcs</td>
                    <td>P $ws_price</td>
                    <td>P $price</td>
                    <td><form action='Shop.php' method='post'>
                        <input type='hidden' name='item_id' value='$item_id'>
                        <input type='hidden' name='category' value='$category'>
                        <input type='hidden' name='items' value='$items'>
                        <input type='hidden' name='description' value='$description'>
                        <input type='hidden' name='stock' value='$stock'>
                        <input type='hidden' name='price' value='$price'>
                        <input type='hidden' name='ws_price' value='$ws_price'>
                        <input type='submit' name='submit-btn' value='Edit'>
                        <input type='submit' name='delete-btn' value='Delete'>
                    </form></td>
                </tr>";
                  }
                }
              }
            ?>
          </form>
          
        </table>
      </div>
      <div class="line">
        
      </div>
    </section>
    
    <!-- java script -->
    <script>
      // tabs selected   
      opentab(event, 'Shop') // to active the home ta
      function opentab(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
      }
        


        // Add active class to the current button (highlight it)
        var btnContainer = document.getElementById("myBtnContainer");
        var btns = btnContainer.getElementsByClassName("btn");
        for (var i = 0; i < btns.length; i++) {
          btns[i].addEventListener("click", function(){
            var current = document.getElementsByClassName("active");
            current[0].className = current[0].className.replace(" active", "");
            this.className += " active";
          });
        }
    </script>



  </body>
</html>
