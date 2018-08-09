<?php

//  Display the course home page.

    require_once('../config.php');
    global $PAGE,$CFG;
    ?>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/js/cdn/jquery.dataTables.min.js'?>"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/js/cdn/dataTables.bootstrap.js'?>"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/js/cdn/dataTables.fixedColumns.min.js'?>"></script>
<script src="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/tabletool/js/dataTables.tableTools.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/css/cdn/dataTables.bootstrap.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/css/cdn/fixedColumns.bootstrap.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot.'/theme/moove/layout/datatable/tabletool/css/dataTables.tableTools.css'?>">">
    <?php
    echo "<script>
            $(document).ready(function() {
                $('#example').DataTable();
            } );
            </script>";
    echo '<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011/04/25</td>
                <td>$320,800</td>
            </tr>
            <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011/07/25</td>
                <td>$170,750</td>
            </tr>
            </tbody>
            </table>';