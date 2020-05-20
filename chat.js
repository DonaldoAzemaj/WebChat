function fetchAllUser(){
    
    $.ajax({
        dataType : "json",
        url : "api_msg/fetch_all_user.php",
        data: null,
        success: function(data){

             insertUser(data);
             rowHandlers();

          
        }
        
    });


}



function insertUser(data){
  var value="";
  var count=0; 
  
  console.log(data);

  if(data.length==0){

    value += 
    '<div class="chat_list">'+
    '<p class="text-center">No user</p>'+
    '</div>';

  }else{

  data.forEach(user => {
    
      value += 
      '<div class="chat_list">'+
      '<div class="chat_people">'+
      '<div class="chat_img"> <img src="http://'+window.location.hostname+'/webchat/images/profildefault.png" alt="sunil"> </div>'+
      '<div class="chat_name ">'+
      '<h5>'+user["username"]+'</h5>'+
      '</div>'+
      ' <input type="hidden" id="login_id" name="login_id" value="'+user["login_id"]+'"></input>'+
      '</div>'+
      '</div>';
    });
  }

   document.getElementById("inbox_chat").innerHTML = value; 
}



function rowHandlers() {
  
  
  var chatList = document.getElementsByClassName("chat_people");
 
  


    
    var createClickHandler = function(chat) {
        return function() {

          var activeChat = document.getElementsByClassName("chat_list");

          for(var i =0; i<activeChat.length;i++){
            $(activeChat[i]).removeClass("active_chat");
          }

          activeChat = chat.parentElement;
          $(activeChat).addClass("active_chat");

          var id = chat.childNodes[3].value;
          var username = chat.childNodes[1].firstChild.innerHTML;

         
          document.getElementById("write_msg").setAttribute("data_to_login_id",id);
          
          document.getElementById("no_msg").innerHTML=username;

          getAllMessages(id);

        };
      };


    for (i = 0; i < chatList.length; i++) {
      var currentChat = chatList[i];
     // $(currentChat).removeClass("active_chat");
      currentChat.onclick = createClickHandler(currentChat);
    }


   
}

function getAllMessages(id){

  

  $.ajax({
type: "post",
dataType: "json",
url: "api_msg/get_all_msg.php",
data: {"to_login_id":id},
success: function(data){

showMessage(data,id);
updateScroll();

}});

}


function showMessage(data,id){
  
  var htmlmsg="";

if(data.length!=0){
  data.forEach(user => {
    if(user["from_login_id"]== id){
      htmlmsg+=
      '<div class="incoming_msg">'+
      '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>'+
      '<div class="received_msg">'+
      '<div class="received_withd_msg">'+
      '<p>'+user["message"]+'</p>'+
      '<span class="time_date">'+user["date"]+'</span></div>'+
      '</div>'+
      '</div>';
    }else{
      htmlmsg += '<div class="outgoing_msg">'+
      '<div class="sent_msg">'+
      '<p>'+user["message"]+'</p>'+
      '<span class="time_date">'+user["date"]+'</span> </div>'+
      ' </div>';
    }
    
  });

 
  }else{
    htmlmsg='<center>No Message</center>';
  }
  
  document.getElementById("msg_history").innerHTML = htmlmsg; 
}



function updateScroll() {
  var element = document.getElementById("msg_history");
  var elementHeight = element.scrollHeight;
  element.scrollTop = elementHeight;

}



function sendMessage(){
  var inputFild = document.getElementById("write_msg");
  var id = inputFild.getAttribute("data_to_login_id");

var text = inputFild.value;

if(text.replace(/\s+/g, '')==""){
alert("You have to wirte somwthing before sending it!");
return ;
}



if(id>0){

$.ajax({
  dataType: "json",
  type : "post",
  url : "api_msg/send_msg.php",
  data: {to_login_id: id, message: text},
  success: function(data){

    // will do send succesfulli check here

  }});




  var msgBox =document.getElementById("msg_history");
  var htmlmsg="";

  var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

  console.log(date);
  var d = new Date();
  htmlmsg = htmlmsg += '<div class="outgoing_msg">'+
  '<div class="sent_msg">'+
  '<p>'+text+'</p>'+
  '<span class="time_date">'+d.getHours()+":"+d.getMinutes() +'</span> </div>'+
  ' </div>';

  var newP = document.createElement('div');
   newP.innerHTML = htmlmsg;

  msgBox.appendChild(newP.firstChild);
  inputFild.value="";

  updateScroll();

 
}else{
  alert("You have to choose one user where you want to send message! thank you.");
}
  
}


function getAllLatestMessage(){
  console.log("1111ads");

  $.ajax({
    dataType: "json",
    type: "post",
    url: "api_msg/get_all_latest_msg.php",
    data: {"to_login_id":1},
    success: function(data){

      console.log(data);

      }
   });


}


function latestMessage(){
  var inputFild = document.getElementById("write_msg");
  var id = inputFild.getAttribute("data_to_login_id");
  if(id>0){
  $.ajax({
    dataType: "json",
    type: "post",
    url: "api_msg/get_latest_msg.php",
    data: {"to_login_id":id},
    success: function(data){

     var htmlmsg = "";
     var newDiv = document.createElement('div');

data.forEach(user => {
  


  if(user["from_login_id"]== id){
    htmlmsg = 
    '<div class="incoming_msg">'+
    '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>'+
    '<div class="received_msg">'+
    '<div class="received_withd_msg">'+
    '<p>'+user["message"]+'</p>'+
    '<span class="time_date">'+user["date"]+'</span></div>'+
    '</div>'+
    '</div>';
  }else{
    htmlmsg = 
    '<div class="outgoing_msg">'+
      '<div class="sent_msg">'+
      '<p>'+user["message"]+'</p>'+
      '<span class="time_date">'+user["date"]+'</span> </div>'+
      ' </div>';
  }
  
  newDiv.innerHTML = htmlmsg;
  document.getElementById("msg_history").appendChild(newDiv.firstChild);

});

if(htmlmsg!=""){
  updateScroll();
}

htmlmsg="";
   

}});
}}

var input = document.getElementById("write_msg");
// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
   // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
   event.preventDefault();
   document.getElementById("msg_send_btn").click();
  }
});

setInterval(
  function(){ 
    latestMessage();
    getAllLatestMessage();
   }, 3000);


  
   function onInputKeySearchBar(value){
    
    $.ajax({
      dataType: "json",
      type : "post",
      url : "api_msg/search_bar.php",
      data: {"keyPres": value},
      success: function(data){
       
          insertUser(data);
          rowHandlers();
    
      }});




   
  }

   