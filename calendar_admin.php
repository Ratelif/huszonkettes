<?php
//index.php
 if(!isset($_SESSION)) 
 { 
    session_start(); 
 } 

if (!isset($_SESSION['user_name'])){
	
		header("location: index.php");
    }    
require 'config/dbh.inc.php';

$user_id = $_SESSION['id_users'];
$name = $_SESSION['name'];

?>
<!DOCTYPE html>
<html>
 <head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Időpont foglalás</title>
     
 <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Modernizer for Portfolio -->
    <script src="js/modernizer.js"></script>
     <script src="js/all.js"></script>
<!--     <script src="js/custom.js"></script>-->
    <script src="js/portfolio.js"></script>
    <script src="js/hoverdir.js"></script>    
     
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css" integrity="sha256-9VgA72/TnFndEp685+regIGSD6voLveO2iDuWhqTY3g=" crossorigin="anonymous" />
<!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha256-rByPlHULObEjJ6XQxW/flG2r+22R5dKiAoef+aXWfik=" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
     
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js" integrity="sha256-4+rW6N5lf9nslJC6ut/ob7fCY2Y+VZj2Pw/2KdmQjR0=" crossorigin="anonymous"></script>
     
     <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/hu.js" integrity="sha256-CUGXTCJIa1cn++VKdu9pxkwK8sVRyGGP+cxoEgQSxa4=" crossorigin="anonymous"></script>
<!--  <script src='locale/hu.js'></script>-->

<script>
  var eventsCount = 0;
  var lehetoseg = false;
    
  $(document).ready(function() {
    var calendar = $('#calendar').fullCalendar({
        eventOverlap: false, 
        selectOverlap: false,    
        minTime: "10:00:00",
        maxTime: "19:00:00", 
        longPressDelay: 100,
        allDaySlot: false,     
        header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'                      // basicWeek,basicDay   agendaWeek,agendaDay'
        },
        defaultView: 'agendaWeek',   // vagy  defaultView: 'month',agendaWeek,listWeek,basicWeek,basicDay
        views: {
            month: { // name of view, month settings
                selectable:false,   
                editable:false,           
                // other view-specific options here
            }
        },   
        events: 
            {
                url: 'php/load.php',
                editable:true,
            },   
        selectable:true,    
        selectHelper:true,
        hiddenDays: [0,6,5,4],  
             
        viewRender: function (view, viewContainer){
            
            if ((view.name == "month") ){
                alert('Kérjük foglaláshoz használja a heti vagy napi nézetet!');
            }
        },   
       
        select: function(start, end, allDay) // Select Day(start, end, allDay, jsEvent, view)
        {
        
            var idopont_disable = $("#time_disable").is(":checked");  
            if (idopont_disable == true) {
            
                var title = "time_disabled";
                if(title)
                {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    var userid = "<?php echo test_input($user_id); ?>";
                    var overlap = 'false';
                    var rendering = 'background' ;
                    var color_background = 'yellow';
            
                    $.ajax({
                        url:"php/insert.php",
                        type:"POST",
                        data:{title:title, start:start, end:end, user_id:userid, overlap:overlap,rendering:rendering,color_background:color_background },

                        success:function()
                        {
                            calendar.fullCalendar('refetchEvents');
                            alert("A művelet sikeres!");
                            $('#time_disable')[0].checked = false;
                        }
                    })
                }
                $("#calendar").fullCalendar('unselect');
            
            } 
        
            var extra_idopont = $("#visibilityControl").is(":checked");  
            if (extra_idopont == false && idopont_disable == false) {
                if (eventsCount < 2){    
                    var title = prompt("Adja meg a darabszámot, típust és méretet!");
                    if(title)
                    {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                        var userid = "<?php echo $user_id; ?>";  

                        $.ajax({
                            url:"php/insert.php",
                            type:"POST",
                            data:{title:title, start:start, end:end, user_id:userid },
                        
                            success:function()
                            {
                                calendar.fullCalendar('refetchEvents');
                                alert("A művelet sikeres!");
                            }
                          })
                    }
                    $("#calendar").fullCalendar('unselect');
                } 
                else {
                    alert("A nap betelt! Kérjük válasszon másikat!");
                    $("#calendar").fullCalendar('unselect');
                }
            } 
            else if(extra_idopont == true && idopont_disable == false) {
                var title = prompt("Adja meg a darabszámot, típust és méretet!");
                if(title)
                {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    var color = 'yellow';
                    
                    $.ajax({
                        url:"php/insert.php",
                        type:"POST",
                        data:{title:title, start:start, end:end,
                        color:color},
                        success:function()
                        {
                            calendar.fullCalendar('refetchEvents');
                            alert("A művelet sikeres!");
                            $('#visibilityControl')[0].checked = false;
                        }
                    })
                }
                $("#calendar").fullCalendar('unselect');
               
             }   
                
        },
   
        eventResize:function(event)
        {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            var title = event.title;
            var id = event.id;
            $.ajax({
                url:"php/update.php",
                type:"POST",
                data:{title:title, start:start, end:end, id:id},
                success:function(){
                    calendar.fullCalendar('refetchEvents');
                    alert('Esemény frissítve lett!');
                }
            });
        },
       
        eventDrop:function(event)
        {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            var title = event.title;
            var id = event.id;
            $.ajax({
                url:"php/update.php",
                type:"POST",
                data:{title:title, start:start, end:end, id:id},
                success:function()
                {
                    calendar.fullCalendar('refetchEvents');
                    alert("Esemény frissítve lett!");
                }
            });
         },
       
        eventRender: function (event, element, view) {
             var title_s = event.description;   
        
            if (view.name == 'listDay') {
                element.find(".fc-list-item-time").append("<span class='closeon'> Törlés</span>");
                element.find(".fc-list-item-time").append("<span class='closeon'>"+ title_s + "</span>");
            } 
            else 
            {
                element.find(".fc-content").prepend("<span class='closeon'> Törlés</span>");
                element.find(".fc-content").prepend("<span class='closeon'>"+ title_s + "</span>");
            };
            element.find(".closeon").on('click', function () {
                lehetoseg = true; 
                if(confirm("Tényleg törölni akarja?"))
                {
                    var id = event.id;
                    $.ajax({
                        url:"php/delete.php",
                        type:"POST",
                        data:{id:id},
                        success:function()
                        {
                            calendar.fullCalendar('refetchEvents');
                            alert("Az időpont törölve lett!");
                        }
                    })
                }
            
            });
            lehetoseg = false;  
        }, 

        eventClick:function(event, element, view,)
        {
            var nezet = $('#calendar').fullCalendar('getView');
            if(nezet.name == "month"){
                alert('Havi nézetben van, foglaláshoz heti vagy napi nézet szükséges!');
                return false;
            } else 
            {

                if(lehetoseg !== true){
                    var newTitle = prompt("Adja meg az új adatokat: ", event.title);
                    // If did not pressed Cancel button
                    if (newTitle != null) {
                        event.title = newTitle.trim() != "" ? newTitle : event.title;

                        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                        var title = event.title;
                        var id = event.id;
                        $.ajax({
                            url:"php/update.php",
                            type:"POST",
                            data:{title:title, start:start, end:end, id:id},
                            success:function()
                            {
                                calendar.fullCalendar('refetchEvents');
                                alert("Esemény frissítve lett!");
                            }
                            
                        });   
                    }
                } 
           
             }               
        },                 
       
        dayClick: function(date, allDay, jsEvent, view) {
            eventsCount = 0;
            var date = date.format('YYYY-MM-DD');
            $('#calendar').fullCalendar('clientEvents', function(event) {
                var start = moment(event.start).format("YYYY-MM-DD");
                var end = moment(event.end).format("YYYY-MM-DD");
                var color = event.textColor;
                var tilt = event.rendering;    
                
                if(date == start && color != 'yellow' && tilt != 'background')
                {
                    eventsCount++;
                }
        
            });

          },   

        dayRender: function (date, cell) {
            var date = date.format('YYYY-MM-DD');
            $('#calendar').fullCalendar('clientEvents', function(event) {
                var start = moment(event.start).format("YYYY-MM-DD");
                var end = moment(event.end).format("YYYY-MM-DD");
            });
        },

     });      // end of Calendar
      
  });       // end of jQuery

  var $calendar = $("#calendar").fullCalendar("getCalendar");

</script>
</head>
<body>
    <div class="top-bar">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 col-sm-6">
					<div class="left-top">
						<div class="email-box">
							<a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i> huszonkettesinfo@gmail.com</a>
						</div>
						<div class="phone-box">
							<a href="tel:1234567890"><i class="fa fa-phone" aria-hidden="true"></i> + 36 20/851-74-44</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<div class="right-top">
						<div class="social-box">
							<ul>
								<li><a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
								<li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
						        <li><a href="#"><i class="fa fa-rss-square" aria-hidden="true"></i></a></li>
                            </ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <header class="header header_style_01">
        <nav class="megamenu navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="image"></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                   <?PHP 
                     if(isset($_SESSION['user_name'])) {
                        echo' 
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="calendar_admin">Időpont foglalás</a></li>
                            <li><a href="kereso">Kereső</a></li>
                            <li><a href="php/logout.inc.php">Kilépés</a></li>
                        </ul>';
                    }
					 else 
                    {
                        echo' 
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="active" href="index.php">Főoldal</a></li>
                            <li><a href="index.php#rolunk">Rólunk</a></li>
                            <li><a href="alert.php">Időpont foglalás</a></li>
                            <li><a href="index.php#csomag">Csomagküldés</a></li>
                            <li><a href="reg.php">Regisztráció</a></li>
                            <li><a href="belep.php">Belépés</a></li>
                            <li><a href="elerhetoseg.php">Elérhetőség</a></li>
                        </ul>';
                    };
                     
                 ?>     
                </div>
            </div>
        </nav>
    </header>
 
     <br />
     <br />

     <div id="soron_kivuli">
        <form>
            <label for="visibilityControl" style="display:inline-block">Soron kívűli időpontot kérek</label>
            <input type="checkbox" name="visibilityControl" id="visibilityControl" />
            <br>
            <label for="time_disable" style="display:inline-block">Időpont tiltás</label>
            <input type="checkbox" name="time_disable" id="time_disable" />
        </form>
     </div>
    <br/>
     <div class="container">
        <div id="calendar">
        </div>
     </div>
    
  <div class="copyrights">
        <div class="container">
            <div class="footer-distributed">
                <div class="footer-left">                   
                    <p class="footer-company-name">Minden jog fenntartva. &copy; 2019 <a href="#">Huszonkettes</a> Design By : 
					<a href="https://hs2000.hu/">HS2000</a></p>
                </div>

                
            </div>
        </div><!-- end container -->
    </div><!-- end copyrights -->

    <a href="#" id="scroll-to-top" class="dmtop global-radius"><i class="fa fa-angle-up"></i></a>

    </body>
    </html>

