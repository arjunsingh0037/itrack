<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A two column layout for the moove theme.
 *
 * @package   theme_moove
 * @copyright 2017 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$course_array = array('abc','xyz','pqr');
?>
<link rel="stylesheet" type="text/css" href="">
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/js/jquery-1.12.0.min.js'?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/js/adminlte.min.js'?>"></script>

<script type="text/javascript">
    /*$(document).ready(function() {
        var path = window.location.pathname.split('/').pop();
        $('ul li').each(function(i)
        {
            var h = $("a",this).attr('href');
            var p = h.split("/").pop();
            if(p == path){
                var target = $(this).parent().parent().prev('.accordion');
                target.addClass('active');
                $(this).parent().parent().css("max-height","fit-content");
                $(this).find('a:first').css("border-left","2px solid red");
            }
        });
    });*/
     $(".dropdown-toggle").click(function() {
        $(this).parents(".menubar").find(".dropdown-menu").slideToggle();
        $(this).parents(".menubar").find(".dropdown-menu").css("display","inline-table");
        $(this).parents(".menubar").find(".dropdown-menu").css("right","0%");
        $(this).parents(".menubar").find(".dropdown-menu").css("left","auto");
    });
    function startTime(){
        var today=new Date();
        var h=today.getHours();
        var m=today.getMinutes();
        var s=today.getSeconds();
          // add a zero in front of numbers<10
          m=checkTime(m);
          s=checkTime(s);
          document.getElementById('time').innerHTML=h+":"+m+":"+s;
          t=setTimeout(function(){startTime()},500);
        }

        function checkTime(i) {
          if (i<10){
            i="0" + i;
          }
          return i;
    }


    $('#sidepreopen-control').click(function () {
            if($('header.navbar').hasClass('open-right')){
                $('header.navbar').removeClass('open-right',1000);
            }else{
                $('header.navbar').addClass('open-right',1000);
            }
    });
    
    //$('aside#block-region-side-pre .content').hide();
    $( ".card-title" ).append( "<span class='plus'>-</span>" );
    $('aside#block-region-side-pre .card-block').on('click', function(event) {
        //$('aside#block-region-side-pre .content').slideUp();
        //$(this).find('.content').slideDown();
        //event.stopPropagation();
        var jqInner = $(this).find('.content');
        if (jqInner.is(":visible")){
            jqInner.slideUp();
            $(this).find('.plus').html('+');
        }else{
            jqInner.slideDown();
            $(this).find('.plus').html('-');
            //event.stopPropagation();
            event.stopImmediatePropagation();
            event.preventDefault();
        }
    });

//course pie chart for dashboard
$('#course_container').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        spacingBottom: 0,
        spacingTop: 0,
        spacingLeft: 0,
        spacingRight: 0,
        width: 305,
        height: 300,
        backgroundColor:'#27314b',
        position: 'relative',
        overflow: 'hidden',
    },
    title: {
        text: false
    },
    navigation: {
        buttonOptions: {
        enabled: false
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                showInLegend: false
          },
          series: {
                dataLabels: {
                    enabled: false,
                    color: '#fff'
                }
          }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Chrome',
            y: 61.41,
            sliced: true,
            selected: true
        }, {
            name: 'Internet Explorer',
            y: 11.84
        }, {
            name: 'Firefox',
            y: 10.85
        }, {
            name: 'Edge',
            y: 4.67
        }, {
            name: 'Safari',
            y: 4.18
        }, {
            name: 'Sogou Explorer',
            y: 1.64
        }, {
            name: 'Opera',
            y: 1.6
        }, {
            name: 'QQ',
            y: 1.2
        }, {
            name: 'Other',
            y: 2.61
        }]
    }]
});

//progress bar for dashboard

var chart = new Highcharts.Chart({
  title: {
    text: false,
    align: 'left',
    margin: 0,
  },
  chart: {
    renderTo: 'progress_bar',
    type: 'bar',
    height: 70,
    backgroundColor:'#27314b',
  },
  credits: false,
  tooltip: false,
  legend: false,
  navigation: {
    buttonOptions: {
      enabled: false
    }
  },
  xAxis: {
    visible: false,
  },
  yAxis: {
    visible: false,
    min: 0,
    max: 100,
  },
  series: [{
    data: [100],
    grouping: false,
    animation: false,
    enableMouseTracking: false,
    showInLegend: false,
    color: 'lightskyblue',
    pointWidth: 25,
    borderWidth: 0,
    borderRadiusTopLeft: '4px',
    borderRadiusTopRight: '4px',
    borderRadiusBottomLeft: '4px',
    borderRadiusBottomRight: '4px',
    dataLabels: {
      className: 'highlight',
      format: '150 / 600',
      enabled: true,
      align: 'right',
      style: {
        color: 'white',
        textOutline: false,
      }
    }
  }, {
    enableMouseTracking: false,
    data: [25],
    borderRadiusBottomLeft: '4px',
    borderRadiusBottomRight: '4px',
    color: '#43e023',
    borderWidth: 0,
    pointWidth: 25,
    animation: {
      duration: 250,
    },
    dataLabels: {
      enabled: true,
      inside: true,
      align: 'left',
      format: '{point.y}%',
      style: {
        color: 'white',
        textOutline: false,
      }
    }
  }]
});

//course pie chart for dashboard
$('#projects_container').highcharts({
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie',
        spacingBottom: 0,
        spacingTop: 0,
        spacingLeft: 0,
        spacingRight: 0,
        width: 305,
        height: 300,
        backgroundColor:'#27314b',
        position: 'relative',
        overflow: 'hidden',
    },
    title: {
        text: false
    },
    navigation: {
        buttonOptions: {
        enabled: false
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                showInLegend: false
          },
          series: {
                dataLabels: {
                    enabled: false,
                    color: '#fff'
                }
          }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Chrome',
            y: 11.41,
            sliced: true,
            selected: true
        }, {
            name: 'Edge',
            y: 14.67
        }, {
            name: 'Safari',
            y: 4.18
        }, {
            name: 'Sogou Explorer',
            y: 1.64
        }]
    }]
});

/*----- Dashboard Datatables------ */
$(document).ready(function() {
    for (var i = 1; i >= 3; i++) {
        $('#example'+i).DataTable();
    }
} );
</script>
