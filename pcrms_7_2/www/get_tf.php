<?php 
    $host = "localhost";
    $user = "drsulab";
    $pass = "drsulab";
    $db = "PCRMSV4";
    $mysqli = new mysqli($host, $user, $pass, $db);

?>

<?php session_start();?>
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


	<div class="jumbotron py-6 bg-info mb-1 jumbotron-fluid"></div>
	
	<?php
	

        if(isset($_POST['genome_id']) && isset($_POST['tf_id']) && isset($_POST['chr_id'])){
            $genome_id = $_POST['genome_id'];
			$tf_id = $_POST['tf_id'];
			$chr_id = $_POST['chr_id'];
			
			$_SESSION['back_genome_id'] = $genome_id;
			$_SESSION['back_tf_id'] = $tf_id;
			$_SESSION['back_chr_id'] = $chr_id;
			
            
        }else{
			$genome_id = $_SESSION['back_genome_id'];
			$tf_id = $_SESSION['back_tf_id'];
			$chr_id = $_SESSION['back_chr_id'];
			
		}

		$tf_sql  = "SELECT c.crmID, c.Chromosome, t.TFBS_Start, t.TFBS_End, t.umID, t.Binding_Score, u.tfID, u.Genome FROM tfbss t,
			umotif_tfs_association u, crms c WHERE (u.Genome = {$genome_id} AND c.Genome = {$genome_id} AND c.Chromosome IN ('".implode("','",$chr_id)."')) 
			AND t.umID = u.umID AND c.crmID = t.crmID AND u.tfID LIKE \"%{$tf_id}%\"";
		

		$rs = $mysqli->query($tf_sql);
    ?>

	<div class="container">
		<div class="row">
			<div class="col">
				<div class="card border-dark p-2">
					<div class="row">
						<div class="col">
							<form action="/get_tf.php" method="POST">
								<div class="form-row align-items-top">
									<div class="col-3">
										<div class="form-group">
											<label for="genome_id">Select a specie:</label>
											<select name="genome_id" class="form-control" id="genome_id">
												<option disabled selected>Select species</option>
												<option value="1">Homo sapiens</option>
												<option value="2">Mus musculus</option>
												<option value="3">Caenorhabditis elegans</option>
											</select>
										</div>
									</div>
									<div class="col-4">
										<div class="form-group">
											<label for="tf_id">Input TF:</label>
											<input type="text" class="form-control" name="tf_id" id="tf_id" value='RUNX1..'>
										</div>
									</div>

									<div class="col-4">
										<div class="form-group">
											<label for="chr_id">Select chromosomes:</label>
											<select multiple class="custom-select" name="chr_id[]" id="chr_id">
												<option disabled selected>Select chromosome</option>
											</select>
										</div>
									</div>

									<div class="col-1 mt-4 pt-2">
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
				<table class="display order-column compact" width="100%" id="tfs_table">
					<thead>
						<tr>
							<th scope="col">CRM ID</th>
							<th scope="col">TFBS coordinate</th>
							<th scope="col">Umotif ID</th>
							<th scope="col">Binding Score</th>
							<th scope="col">Umotif logo</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($row = $rs->fetch_assoc()){
								echo "<tr>";
								echo "<td><a href=crm.php?crmId={$row['crmID']}&genome_id={$genome_id}>{$row['crmID']}</td>";
								echo "<td>{$row['Chromosome']}:{$row['TFBS_Start']}-{$row['TFBS_End']}</td>";
								echo "<td>{$row['umID']}</td>";
								echo "<td>{$row['Binding_Score']}</td>";

								$img_path = "/motifs_version/{$row['Genome']}/{$row['umID']}.png";

								echo "<td><img src=\"{$img_path}\" alt=\"\" border=3 height=50 width=150></img></td>";
								echo "</tr>";
							}

						?>
					
					</tbody>

					<tfoot>
						<tr>
							<th scope="col">CRM ID</th>
							<th scope="col">TFBS coordinate</th>
							<th scope="col">Umotif ID</th>
							<th scope="col">Binding Score</th>
							<th scope="col">Umotif logo</th>
						</tr>
					</tfoot>



				</table> 

			</div>


		</div>


	</div>
	
	<script src="dynamics_detail_search.js"></script>
	<script>
		$(document).ready(function(){

			$('#tfs_table').dataTable({
				"lengthMenu": [[ 100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]],
				"scrollY":        "1200px",
				"scrollCollapse": true,
				"dom": '<"wrapper"fltip>',
			});
		});   
    </script>


</body>

</html> 
