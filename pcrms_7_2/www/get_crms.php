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
    <title>CRMs</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>

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
			<a class="nav-link active" href="browse_dataset.php"><i class="fa fa-list-alt" style="font-size:20px"></i>Browse Database</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="detailed_search.php"><i class="fa fa-search" style="font-size:20px"></i>Detailed Search</a>
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
        session_start();    
        if(isset($_POST['genome_id']) && isset($_POST['chr_id']) && isset($_POST['p_value'])){
            $genome_id = $_POST['genome_id'];
            $chr_id = $_POST['chr_id'];
            $p_value = $_POST['p_value'];
            $_SESSION['back_genome_id'] = $genome_id;
            $_SESSION['back_chr_id'] = $chr_id;
            $_SESSION['back_p_value'] = $p_value;
            //print_r($chr_id);

        }else{
            $genome_id = $_SESSION['back_genome_id'];
            $chr_id = $_SESSION['back_chr_id'];
            $p_value = $_SESSION['back_p_value'];
        }
    ?>

    <div class="sticky-top">
        <div class="jumbotron py-6 bg-info mb-1 jumbotron-fluid"></div>
    </div>
    <div class="container py-2 mt-2">

        <?php
            $crm_query  = "SELECT crmID, Chromosome, Start_Pos, End_Pos, Score, P_value, Genes_Symbol FROM crms_genes WHERE 
            Genome={$genome_id} AND Chromosome IN ('".implode("','",$chr_id)."') AND P_value<=\"{$p_value}\"";

            $rs = $mysqli->query($crm_query);

        ?>


        <div class="row">
            <div class="col">
                <div class="card border-dark p-2">
                    <div class="row">
                        <div class="col">
                        <form action="get_crms.php" method="POST">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="genome_id">Select species:</label>
                                            <select name="genome_id" class="form-control" id="genome_id">
                                                <option selected>Select species</option>
                                                <option value="1">Homo sapiens</option>
                                                <option value="2">Mus musculus</option>
                                                <option value="3">Caenorhabditis elegans</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="chr_id">Select chromosome:</label>
                                        <select multiple class="custom-select" name="chr_id[]" id="chr_id">
                                            <option selected>Select chromosome</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="p_value">Input p value threshold:</label>
                                        <select class="custom-select" name="p_value" id="p_value">
                                            <option selected>Select p value:</option>
                                            <option value="0.05">0.05</option>
                                            <option value="0.01">0.01</option>
                                            <option value="0.000005">5E10-6</option>
                                            <option value="0.000001">1E10-6</option>

                                        </select>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                                
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr/>


        <div class="row">
            <div class="col">
            <table class="table table-striped table-hover table-sm" width="100%" id="crms_table">
                <thead>
                    <tr>
                        <th scope="col">CRM ID</th>
                        <th scope="col">Chromosome</th>
                        <th scope="col">Start</th>
                        <th scope="col">End</th>
                        <th scope="col">Closest Gene</th>
                        <th scope="col">Score</th>
                        <th scope="col">P value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($row = $rs->fetch_assoc()){
                            echo "<tr>";
                            echo "<td><a href=crm.php?crmId={$row['crmID']}&genome_id={$genome_id}>{$row['crmID']}</td>";
                            //echo "<td>{$row['Chromosome']}:{$row['Start_Pos']}-{$row['End_Pos']}</td>";
                            echo "<td style=\"text-align:center\">{$row['Chromosome']}</td>";
                            echo "<td>{$row['Start_Pos']}</td>";
                            echo "<td>{$row['End_Pos']}</td>";
                            echo "<td style=\"word-wrap: break-word;min-width: 100px;max-width: 500px;\">{$row['Genes_Symbol']}</td>";
                            echo "<td>{$row['Score']}</td>";
                            echo "<td>{$row['P_value']}</td>";
                            echo "</tr>";
                        }

                    ?>
                
                </tbody>


                <tfoot>
                <tr>
                    <th scope="col">CRM ID</th>
                    <th scope="col">Chromosome</th>
                    <th scope="col">Start</th>
                    <th scope="col">End</th>
                    <th scope="col">Closest Gene</th>
                    <th scope="col">Score</th>
                    <th scope="col">P value</th>

                </tr>
                </tfoot>
            </table>  
            </div>
        </div>

    </div>

    <script src="dynamics.js"></script>
    <script>

        $(document).ready(function() {
            var tableX = $('#crms_table').DataTable( {
            dom: "<'row'<'col-4'l><'col-5'B><'col-2'f>>" + 
                "<'row'<'col-12'tr>>" + 
                "<'row'<'col-6'i><'col-6'p>>",
            "lengthMenu": [[ 100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]],
            "scrollY":        "1200px",
            "scrollCollapse": true,

            buttons: [  'copy', 
                    'csv',
                    'excel', 
                    'colvis' ],
            select: true,

        } );

    // tableX.button(0).nodes().css({"background-color": "#0275d8"});
    // tableX.button(1).nodes().css({"background-color": "#0275d8"});
    // tableX.button(2).nodes().css({"background-color": "#0275d8"});
    // tableX.button(3).nodes().css({"background-color": "#0275d8"});

        } );
    </script>



    
</body>
</html>
