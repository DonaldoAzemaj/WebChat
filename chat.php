<?php
session_start();

if (!isset($_SESSION['active_time'])) {
  $_SESSION['active_time'] = time();
} elseif (time() - $_SESSION['active_time'] > 60 * 60) {
  unset($_SESSION['email']);
  unset($_SESSION['active_time']);

  session_destroy();
} else {
  $_SESSION['active_time'] = time();
}


if (isset($_SESSION['email'])) {
 // echo $_SESSION['username'];
} else {
  header("location:login.php");
}

?>




<!DOCTYPE html>
<html>

<head>
  <title>WebChat</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="chatstyle.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet"></script>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>


  <script>
    $(function() {
      $("#footer").load("footer.html");
    });

  </script>


  

</head>

<body >

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="home.php">WebChat</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#chat"><?php if(isset($_SESSION['username'])){echo $_SESSION['username']; }else echo "Chat"; ?></a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li ><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
    </ul>
  </div>
</nav>


<div class="container">
<div  class="parent">
<div class="messaging child"  >
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Recent</h4>
            </div>
            <div class="srch_bar">
              
                <input type="text" class="search-bar"  placeholder="Search" id="search_profil" >
                
                <button type="button btn"> <i class="fa fa-search" aria-hidden="true"></i> </button>
            
            </div>
          </div>
          <div class="inbox_chat" id="inbox_chat">
            
          </div>
        </div>
        <div class="mesgs">
        <p class="text-center" id="no_msg">Web Chat!</P>
          <div class="msg_history" id="msg_history">
          
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" id="write_msg" 
              placeholder="Type a message" onKeyPress="onInputKeySearchBar()" onKeyUps="onInputKeySearchBar()"/>
              <button class="msg_send_btn fa fa-paper-plane-o" id="msg_send_btn" type="button" onclick="sendMessage()"><i aria-hidden="true"></i></button>
            </div> 
          </div>
        </div>
      </div>
      
      
      <p  class="text-center top_spac"> Created by <a href="mailto:donaldazemaj@hotmail.com">Donaldo Azemaj</a></p>
      
    </div>
  </div>
  </div> 



  <script>



  $(document).ready(function () {
  fetchAllUser();

  
  $('#search_profil').keyup(function() {
  
    onInputKeySearchBar(document.getElementById('search_profil').value);
  });



    });


  </script>



<script src="chat.js"></script>
</body>

</html>