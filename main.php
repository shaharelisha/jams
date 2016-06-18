<?php

function connect(){
    $host = "localhost";
    $user = "jams";
    $pass = "pa$$";
    $db = "jams";
    
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
    
    return $conn;
}


function subscribe($email){
    $conn = connect();
    $query = "INSERT INTO hr_newsletter VALUES ('$email')";
    mysqli_query($conn, $query);
    mysqli_close($conn);
    header("Location: index.html");
 
}
    
function register($email, $fname, $sname, $pcode, $pass){
    $conn = connect();
    $query = "INSERT INTO hr_customer VALUES ('$email', '$fname', '$sname', '$pcode', '$pass')";
    mysqli_query($conn, $query);
    mysqli_close($conn);
    header("Location: index.html");
    
}

function login($email, $pass){
    $conn = connect();
    $query = "SELECT * FROM hr_customer WHERE email = '$email' AND pass = '$pass'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $fname = $row[fname];
    }
    mysqli_close($conn);
    
    if (mysqli_num_rows($result) == 1){
        //remember the user name
        session_start();
        setcookie("email", $email, time() + (86400 * 30), "/"); //set cookie for day
        setcookie("fname", $fname, time() + (86400 * 30), "/"); //set cookie for day
        //redirect to index.html    
        header("Location: index.html");
    }else{
        //message the user
        $msg = "Your username/password was not recognized - try again!";
        //redirect to register.html
        echo "<script type = 'text/javascript'>
            alert('$msg');
            window.location = 'login.html';
            </script>
            ";
            
    }
}

function change_password($pass) {
    session_start();
    
    if (isset($_COOKIE['email'])){
        $conn = connect();
        $email = $_COOKIE['email'];
        $query = "UPDATE hr_customer SET pass = '$pass' WHERE email = '$email'";
        mysqli_query($conn, $query);
        header("Location: index.html");
    }
}
        
        

function display_products() {
    //Connect to the database
    $conn = connect();
    
    //write query
    $query = "SELECT * FROM hr_product";
    
    //run query and store results
    $results = mysqli_query($conn, $query);
        
    //print html table and header row
    echo "<table><tr>
        <th>Product Name</th>
        <th>Description</th>
        <th>Image</th>
        <th>Price</th>
        <th>Order</th>
        </tr>
        ";
    //print html table row for each product in the 
    //results (loop)
    while($row = mysqli_fetch_array($results)) {
        echo "<tr>
            <td>$row[name]</td>
            <td>$row[description]</td>
            <td><img src='$row[imagepath]' width='auto' height='200'/></td>
            <td>£$row[price]</td>
            <td>
            
            <form action='basket.php' method='post'>
            
            <input type='submit' value='Add to basket' name='$row[pid]' />
            
            </form>
            
            </td>
            </tr>";
    }
    echo "</table>";
    //close the table tags
    mysqli_close($conn);
}

function add_to_basket($pid) {
    session_start();
    if (isset($_SESSION['basket'])) {
        if (isset($_SESSION['basket'][$pid])) {
            $_SESSION['basket'][$pid]++;    
        }else {
            $_SESSION['basket'][$pid]=1;    
        }
    }else {
        $_SESSION['basket'] = array($pid => 1);
    }
    //print_r($_SESSION);
    header("Location: basket.html");
    
    
}

function remove_from_basket($pid) {
    session_start();
    if (isset($_SESSION['basket'])) {
            unset($_SESSION['basket'][$pid]);    
    }
    header("Location: basket.html");    
}

function display_basket() {
    if (!isset($_SESSION['basket'])) {
        echo "<p>Your basket is empty. Go to the products page to order items.</p>";
        return;
    }
    echo "<table>
        <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        </tr>";
    $conn = connect();
    $total = 0;
    
    foreach ($_SESSION['basket'] as $key=>$value) {
        $query = "SELECT name, price FROM hr_product WHERE pid = '$key'";
        
        $result = mysqli_query($conn,$query);
        $row = mysqli_fetch_array($result);
        echo "<tr>
            <td>$row[name]</td>
            <td>£$row[price]</td>
            <td>$value</td>
            <td>£". ($row['price']*$value)."</td>
            <td><form action='remove.php' method='post'>
        <input type='submit' value='Remove Item' name = '$key'/>
        </form></td>
        <td><form action='decrease.php' method='post'>
        <input type='submit' value='-' name = '$key'/>
        </form></td>
         <td><form action='increase.php' method='post'>
        <input type='submit' value='+' name = '$key'/>
        </form></td>
            </tr>";
        $total = $total + $row['price']*$value;
    }  
    echo "<tr><th>Total Order</th>        
        </tr>
        <td>£$total</td>
        <td><form action='order.php' method='post'>
        <input type='submit' value='Order' />
        </form></td>
        </tr></table>";
    mysqli_close($conn);
}

function order() {
    session_start();
    if(!isset($_COOKIE['email'])){
          //message the user
        $msg = "You must be logged in to order items";
        //redirect to register.html
        echo "<script type = 'text/javascript'>
            alert('$msg');
            window.location = 'login.html';
            </script>";
            return;
    }
    $conn = connect();
    $query = "INSERT INTO hr_order VALUES(NULL, '$_COOKIE[email]')";
    mysqli_query($conn, $query);
    $oid = mysqli_insert_id($conn);
    
        foreach ($_SESSION['basket'] as $key=>$value) {  
            foreach($_SESSION['basket'] as $key => $value) {
                $query = "INSERT INTO hr_orderitems VALUES($oid, $key, $value)"; 
            mysqli_query($conn, $query);
                $resultset = "SELECT * FROM `hr_product` WHERE pid = '$key'";
                $results = mysqli_query($conn, $resultset);
            }
        echo "<script type = 'text/javascript'>
            alert('We have received your order!');
            window.location = 'orderconf.php?oid=$oid';
            </script>";  
        unset($_SESSION['basket']);
        mysqli_close($conn);
        }
    
}

function increase($key) {
    session_start();
    if (isset($_SESSION['basket'])) {
            $_SESSION['basket'][$key]++;    
    }
    header("Location: basket.html");   
}

function decrease($key) {
    session_start();
    if (isset($_SESSION['basket'])) {
            if ($_SESSION['basket'][$key] > 1) {
                $_SESSION['basket'][$key]--;    
            } else {
                unset($_SESSION['basket'][$key]);
            }
    }
    header("Location: basket.html");   
}

function display_order($oid) {
    $conn = connect();
    $query = "SELECT * FROM hr_product LEFT JOIN hr_orderitems ON hr_product.pid = hr_orderitems.pid WHERE hr_orderitems.oid = '$oid'";
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);
    echo "<p>Thank you for ordering! We will email you shortly! Here's a summary of your items: </p>";
    while ($rows = mysqli_fetch_assoc($result)) {
        echo "<p>" . $rows['qty'] . " x " . $rows['name'] . "</p>";   
    } 
}

?>