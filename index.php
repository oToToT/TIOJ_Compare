<!DOCTYPE HTML>
<!--
   Identity by HTML5 UP
   html5up.net | @ajlkn
   Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
   <head>
      <title>TIOJ Coder Compare</title>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <!--[if lte IE 8]><script src="assets/js/html5shiv.js"></script><![endif]-->
      <link rel="stylesheet" href="assets/css/main.css" />
      <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
      <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
      <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
      <style>
         input{
            text-transform: none;
         }
         .bar{
            cursor:pointer !important;
            background-color: rgb(200,200,200);
            border-radius: 2px;
            text-align:right;
            border: solid 0.2px rgb(100,100,100);
            box-shadow: 1px 1px 1px #888888;
            margin-top: 5px;
            margin-bottom: 5px;
            transition: 0.3s;
            user-select:none;
            width: 28em;
         }
         .bar:hover{
            opacity: 0.8;
         }
         .list{
            margin: 0;
            width: 28em;
         }
      </style>
   </head>
   <body class="is-loading">

      <!-- Wrapper -->
         <div id="wrapper">

            <!-- Main -->
               <section id="main">
                  <header>
                     <span class="avatar"><img src="images/icon.jpg" height="122" alt="icon" /></span>
                     <h1>TIOJ Coder Compare</h1>
                     <p>Help you to strengthen yourself</p>
                  </header>
                  <div id="main_view">
                  <form method="get" action="n.php">
                     <div class="field">
                        <input type="text" name="name1" id="name1" placeholder="Your Account" />
                     </div>
                     <div class="field" style="text-align:left;">
                        <label>Which Type?</label>
                        <input type="radio" id="manual" name="another" value="own" checked/>
                        <label for="manual">Choose by myself</label>
                        <input type="text" name="name2" id="name2" placeholder="Others Account" />
                        <br>
                        <input type="radio" id="auto" name="another" value="robot" />
                        <label for="auto">The Person Higher than Me</label>
                     </div>
                     <ul class="actions">
                        <li><a id="submit" href="#" class="button">Compare!</a></li>
                     </ul>
                  </form>
                  </div>
                  <hr />

                  <footer>
                     <ul class="icons">
                        <li><a href="http://tioj.infor.org/" class="fa-globe" target="_blank">TIOJ</a></li>
                        <li><a href="https://github.com/oToToT/TIOJ_Compare" class="fa-github">GitHub</a></li>
                     </ul>
                  </footer>
               </section>

            <!-- Footer -->
               <footer id="footer">
                  <ul class="copyright">
                     <li>&copy; oToToT</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
                  </ul>
               </footer>
         </div>
         <div id="loading" style="display:none;align-items:center;justify-content:center;position:fixed;top:0;left:0;z-index:9999999;background-color:rgba(0,0,0,0.7);width:100%;height:100%;user-select:none;">
            <img src="images/loading.svg" />
         </div>

      <!-- Scripts -->
      <!--[if lte IE 8]><script src="assets/js/respond.min.js"></script><![endif]-->
      <script src="//code.jquery.com/jquery.min.js"></script>
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.css" >
      <script src="//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.5/sweetalert2.min.js"></script>
      <script>
      if ('addEventListener' in window) {
         window.addEventListener('load', function() { document.body.className = document.body.className.replace(/\bis-loading\b/, ''); });
         document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
         var radios = document.getElementsByName("another");
         $("input[name=another]").on("change", function(){
            if($("input[name=another]:checked").val()==="own"){
               $("#name2").show();
            }else{
               $("#name2").hide();
            }
         });
         $("#submit").click(function(){
            var regEx = /^[a-zA-z0-9][a-zA-z0-9]*$/;
            if(!regEx.test($("name1").val())){
               swal("Error","Please check your account only contains English characters and numbers.","error");
            }else if($("input[name=another]:checked").val()==="own" && !regEx.test($("#name2").val())){
               swal("Error","Please check others account only contains English characters and numbers.","error");
            }else{
               $("#loading").css("display","flex");
               $.ajax({
               url: './n.php',
                  type: 'GET',
                  data: $("form").serialize(), 
                  dataType: "json"
               }).done(function(data){
                     $("#loading").hide();
                     if(data.status===500){
                        swal("Error", "Can't find users", "error");
                     }else if(data.status===400){
                        swal("Error", "Please input data", "error");
                     }else if(data.status===200){
                        $("#main header").html("<h1>Result</h1>");
                        $("#main_view").html('<div style="margin-bottom: 22px;"><div class="bar" id="aBar">You ('+String(data.a.length)+')<i class="fa fa-chevron-circle-down" aria-hidden="true"></i> </div><div id="a" class="list" style="display:none;" data-shown="0"></div><div class="bar" id="abBar">Both ('+String(data.ab.length)+')<i class="fa fa-chevron-circle-down" aria-hidden="true"></i> </div><div id="ab" class="list" style="display:none;" data-shown="0"></div><div class="bar" id="bBar">Others ('+String(data.b.length)+')<i class="fa fa-chevron-circle-down" aria-hidden="true"></i> </div><div id="b" class="list" style="display:none;" data-shown="0"></div></div><ul class="actions"><li><a id="reload" href="#" class="button">Compare Another!</a></li></ul>');
                        data.a.forEach(function(sth){
                           $("#a").append("<a target='_blank' href='http://tioj.infor.org/problems/"+String(sth)+"'>"+String(sth)+"</a>");
                           $("#a")[0].innerHTML += ', ';
                        });
                        $("#a")[0].innerHTML = $("#a")[0].innerHTML.slice(0, $("#a")[0].innerHTML.length-2);
                        data.b.forEach(function(sth){
                           $("#b").append("<a target='_blank' href='http://tioj.infor.org/problems/"+String(sth)+"'>"+String(sth)+"</a>");
                           $("#b")[0].innerHTML += ', ';
                        });
                        $("#b")[0].innerHTML = $("#b")[0].innerHTML.slice(0, $("#b")[0].innerHTML.length-2);
                        data.ab.forEach(function(sth){
                           $("#ab").append("<a target='_blank' href='http://tioj.infor.org/problems/"+String(sth)+"'>"+String(sth)+"</a>");
                           $("#ab")[0].innerHTML+=", ";
                        });
                        $("#ab")[0].innerHTML = $("#ab")[0].innerHTML.slice(0, $("#ab")[0].innerHTML.length-2);
                        $("#aBar").click(function(){
                           if($("#a").data("shown")){
                              $("#a").hide();
                              $("#a").data("shown", 0);
                           }else{
                              $("#a").show();
                              $("#a").data("shown", 1);
                           }
                        });   
                        $("#bBar").click(function(){
                           if($("#b").data("shown")){
                              $("#b").hide();
                              $("#b").data("shown", 0);
                           }else{
                              $("#b").show();
                              $("#b").data("shown", 1);
                           }
                        });
                        $("#abBar").click(function(){
                           if($("#ab").data("shown")){
                              $("#ab").hide();
                              $("#ab").data("shown", 0);
                           }else{
                              $("#ab").show();
                              $("#ab").data("shown", 1);
                           }
                        });
                        $("#reload").click(function(){
                           location.reload();
                        });
                     }else{
                        swal("Error", "Something went wrong", "error");
                     }
               });
            }
         });
      }
      </script>
   </body>
</html>
