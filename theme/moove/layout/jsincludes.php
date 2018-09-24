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
?>

<link rel="stylesheet" type="text/css" href="">

<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/js/jquery-1.12.0.min.js'?>"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script> -->
<!-- <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/includes/js/adminlte.min.js'?>"></script>
<!-- <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> -->

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
    //jquery for header sidebar toggle---starts
    $(document).ready(function() {
        $('body').addClass('drawer-open-left');
        if($('body').hasClass('drawer-open-right')){
            $('header.navbar').removeClass('cright',1000);
            $('header.navbar').addClass('oright',1000);
            
        }
    });
    $('#sidepreopen-control').click(function () {
        if($('body').hasClass('drawer-open-right')){
            $('header.navbar').removeClass('open-right',1000);
            $('header.navbar').addClass('close-right',1000);
            $('header.navbar').removeClass('oright',1000);
        
        }else{
            $('header.navbar').removeClass('close-right',1000);
            $('header.navbar').addClass('open-right',1000);
            $('header.navbar').removeClass('0right',1000);
        }
    });
    //jquery for header sidebar toggle---ends

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


/*----- Dashboard Datatables------ */
/*$(document).ready(function() {
    for (var i = 1; i >= 3; i++) {
        $('#example'+i).DataTable();
    }
} );*/
//container 1 for partner-admin
Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    credits: {
      enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:.0f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'Training Partners',
        colorByPoint: true,
        data: [{
            name: 'tp1',
            y: 61,
            sliced: true,
            selected: true
        }, {
            name: 'tp2',
            y: 11
        }, {
            name: 'tp3',
            y: 10
        }, {
            name: 'tp4',
            y: 4
        }, {
            name: 'tp5',
            y: 4
        }, {
            name: 'tp6',
            y: 1
        }, {
            name: 'tp7',
            y: 1
        }]
    }]
});

//container2 for partner admin
Highcharts.chart('contain', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    credits: {
      enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
        }
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'Hiring Companies',
        colorByPoint: true,
        data: [{
            name: 'hc1',
            y: 61,
            sliced: true,
            selected: true
        }, {
            name: 'hc2',
            y: 11
        }, {
            name: 'hc3',
            y: 10
        }, {
            name: 'hc4',
            y: 4
        }, {
            name: 'hc5',
            y: 4
        }, {
            name: 'hc6',
            y: 7
        }]
    }]
});
</script>

<script type="text/javascript">

Highcharts.chart('container1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    credits: {
      enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:.0f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'My Courses',
        colorByPoint: true,
        data: [{
            name: 'crs1',
            y: 61,
            sliced: true,
            selected: true
        }, {
            name: 'crs2',
            y: 11
        }, {
            name: 'crs3',
            y: 10
        }, {
            name: 'crs4',
            y: 4
        }, {
            name: 'crs5',
            y: 4
        }, {
            name: 'crs6',
            y: 1
        }, {
            name: 'crs7',
            y: 1
        }]
    }]
});


//container2 for partner admin
Highcharts.chart('container2', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    credits: {
      enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
        }
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'My Batches',
        colorByPoint: true,
        data: [{
            name: 'bat1',
            y: 61,
            sliced: true,
            selected: true
        }, {
            name: 'bat2',
            y: 11
        }, {
            name: 'bat3',
            y: 10
        }, {
            name: 'bat4',
            y: 4
        }, {
            name: 'bat5',
            y: 4
        }, {
            name: 'bat6',
            y: 7
        }]
    }]
});


// Build the chart
Highcharts.chart('container3', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                },
                connectorColor: 'silver'
            }
        }
    },
    credits: {
      enabled: false
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'Student',
        data: [
            { name: 'std1', y: 61.41 },
            { name: 'std2', y: 11.84 },
            { name: 'std3', y: 10.85 },
            { name: 'std4', y: 4.67 },
            { name: 'std5', y: 4.18 },
            { name: 'std6', y: 7.05 }
        ]
    }]
});
// Build the chart
Highcharts.chart('container4', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                },
                connectorColor: 'silver'
            }
        }
    },
    credits: {
      enabled: false
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'Professor',
        data: [
            { name: 'pf1', y: 61.41 },
            { name: 'pf2', y: 11.84 },
            { name: 'pf3', y: 10.85 },
            { name: 'pf4', y: 4.67 },
            { name: 'pf5', y: 4.18 },
            { name: 'pf6', y: 7.05 }
        ]
    }]
});
</script>
<script type="text/javascript">

Highcharts.chart('containerpf1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    credits: {
      enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y:.0f}',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'My Courses',
        colorByPoint: true,
        data: [{
            name: 'crs1',
            y: 61,
            sliced: true,
            selected: true
        }, {
            name: 'crs2',
            y: 11
        }, {
            name: 'crs3',
            y: 10
        }, {
            name: 'crs4',
            y: 4
        }, {
            name: 'crs5',
            y: 4
        }, {
            name: 'crs6',
            y: 1
        }, {
            name: 'crs7',
            y: 1
        }]
    }]
});


// Build the chart
Highcharts.chart('containerpf2', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                },
                connectorColor: 'silver'
            }
        }
    },
    credits: {
      enabled: false
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'My Batches',
        data: [
            { name: 'bat1', y: 61.41 },
            { name: 'bat2', y: 11.84 },
            { name: 'bat3', y: 10.85 },
            { name: 'bat4', y: 4.67 },
            { name: 'bat5', y: 4.18 },
            { name: 'bat6', y: 7.05 }
        ]
    }]
});
// Build the chart
Highcharts.chart('containerpf3', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                },
                connectorColor: 'silver'
            }
        }
    },
    credits: {
      enabled: false
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'My Labs',
        data: [
            { name: 'pf1', y: 61.41 },
            { name: 'pf2', y: 11.84 },
            { name: 'pf3', y: 10.85 },
            { name: 'pf4', y: 4.67 },
            { name: 'pf5', y: 4.18 },
            { name: 'pf6', y: 7.05 }
        ]
    }]
});
</script>
<script type="text/javascript">
//container2 for partner admin
Highcharts.chart('containerstud1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: null
    },
    credits: {
      enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
        }
    },
    navigation: {
        buttonOptions: {
          enabled: false
        }
    },
    series: [{
        name: 'Courses',
        colorByPoint: true,
        data: [{
            name: 'cs1',
            y: 61,
            sliced: true,
            selected: true
        }, {
            name: 'cs2',
            y: 11
        }, {
            name: 'cs3',
            y: 10
        }, {
            name: 'cs4',
            y: 4
        }, {
            name: 'cs5',
            y: 4
        }, {
            name: 'cs6',
            y: 7
        }]
    }]
});
</script>