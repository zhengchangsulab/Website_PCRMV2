<?php session_start();?>
<?php include("pagination.php");?>
<?php 
    $host = "localhost";
    $user = "drsulab";
    $pass = "drsulab";
    $db = "PCRMSV4";
    $mysqli = new mysqli($host, $user, $pass, $db);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>PCRMs</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<body>

    <nav class="navbar py-0 navbar-expand-sm bg-light fixed-top">
		<a class="navbar-brand" href="">
		<img src="/images/UNCC.png" class="rounded" alt="Logo" style="width:80px">
		</a>
		<ul class="nav nav-pills">
		<li class="nav-item">
			<a class="nav-link" href="index.php"><i class="fa fa-home" style="font-size:20px"></i>Home</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="browse_dataset.php"><i class="fa fa-list-alt" style="font-size:20px"></i>Browse Database</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active" href="detailed_search.php"><i class="fa fa-search" style="font-size:20px"></i>Detailed Search</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="download_data.php"><i class="fa fa-download" style="font-size:20px"></i>Download Data</a>
		</li>

		<li class="nav-item">
			<a class="nav-link" href="https://sulab.uncc.edu/zhengchang-su-phd"><i class="fa fa-flask" style="font-size:20px"></i>Su Lab</a>
		</li>
		</ul>
    </nav>


	<?php
        if(isset($_GET['crmId']) && isset($_GET['chrome'])){
            $crmId = $_GET['crmId'];
            $_SESSION['crmID'] = $crmId;

            $chrome = $_GET['chrome'];
            $_SESSION['chrome'] = $chrome;


            $genome_id = $_GET['genome_id'];
            $_SESSION['genome_id'] = $genome_id;
        }else{
            $crmId = $_SESSION['crmID'];
            $chrome = $_SESSION['chrome'];
            $genome_id = $_SESSION['genome_id'];
        }
	?>


    <?php 
        //$tfbs_sql  = "SELECT TFBS_Start, TFBS_End, umID FROM tfbss WHERE crmID=\"{$crmId}\" ORDER BY TFBS_Start ASC";
        $tfbs_sql  = "SELECT tfbss.TFBS_Start, tfbss.TFBS_End, tfbss.umID, tfbss.Binding_Score, umotif_tfs_association_multiple.tfNames FROM tfbss INNER JOIN 
        umotif_tfs_association_multiple ON tfbss.umID=umotif_tfs_association_multiple.umID
        WHERE tfbss.crmID=\"{$crmId}\" AND umotif_tfs_association_multiple.Genome={$genome_id} ORDER BY tfbss.TFBS_Start";

        $rs = $mysqli->query($tfbs_sql);   
    ?>


	<div class="jumbotron py-6 bg-info mb-1 jumbotron-fluid py-3"></div>

    <div class="container">
            <div class="row">
                <div class="col">
                    <table class="display order-column compact" width="100%" id="tfbss_table">
                    <thead>
                        <tr>
                            <th scope="col">TFBS Coordinate</th>
                            <th scope="col">Umotif ID</th>
                            <th scope="col">TF Binding Score</th>
                            <th scope="col">Umotif logo</th>
                            <th scope="col">Match TFs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($tfbs_row = $rs->fetch_assoc()){
                            //foreach($tfbs_results->data as $tfbs_row){
                                echo "<tr>";
                                echo "<td>{$chrome}:{$tfbs_row['TFBS_Start']}-{$tfbs_row['TFBS_End']}</td>";
                                //echo "<th><a data-toggle=\"popover-hover\" data-img=\"/motifs_version/{$genome_id}/{$tfbs_row['umID']}.png\" href=umotif.php?umID={$tfbs_row['umID']}&genome_id={$genome_id}>{$tfbs_row['umID']}</a></th>";
                                echo "<td>{$tfbs_row['umID']}</td>";
                                echo "<td>{$tfbs_row['Binding_Score']}</td>";
                                $img_path = "/motifs_version/{$genome_id}/{$tfbs_row['umID']}.png";
                                echo "<td><img src=\"{$img_path}\" alt=\"\" border=3 height=50 width=150></img></td>";
                                echo "<td style=\"word-wrap: break-word;min-width: 200px;max-width: 500px;\">{$tfbs_row['tfNames']}</td>";
                                echo "</tr>";
                            }

                        ?>
                    
                    </tbody>

                    <tfoot>
                        <tr>
                            <th scope="col">TFBS Coordinate</th>
                            <th scope="col">Umotif ID</th>
                            <th scope="col">TF Binding Score</th>
                            <th scope="col">Umotif logo</th>
                            <th scope="col">Match TFs</th>
                        </tr>
                    </tfoot>


                </table>        
                
                </div>

                    
            </div>

    
    
    </div>

    <script>
                // popovers initialization - on hover
            $('[data-toggle="popover-hover"]').popover({
            html: true,
            trigger: 'hover',
            placement: 'top',
            content: function () { return '<img src="' + $(this).data('img') + '" style="width:100px;height:150px;">'; }
            });

            // popovers initialization - on click
            $('[data-toggle="popover-click"]').popover({
            html: true,
            trigger: 'click',
            placement: 'top',
            content: function () { return '<img src="' + $(this).data('img') + '" style="width:100px;height:150px;">'; }
            });


    </script>

    <script>
        $(document).ready(function(){

            $('#tfbss_table').dataTable({
                "lengthMenu": [[ 100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]],
                "scrollY":        "1200px",
                "scrollCollapse": true,
                "dom": '<"wrapper"fltip>',
            });
        });   
    </script>

</body>
</html>