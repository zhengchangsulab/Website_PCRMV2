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

  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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
			<a class="nav-link" href="index.php">Home</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="browse_dataset.php">Browse Database</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active" href="detailed_search.php">Detailed Search</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="download_data.php">Download Data</a>
		</li>

		<li class="nav-item">
			<a class="nav-link" href="https://sulab.uncc.edu/zhengchang-su-phd">Su Lab</a>
		</li>

		</ul>

  	</nav>

	<div class="jumbotron py-6 bg-info mb-1 jumbotron-fluid"></div>
	

	<?php
		session_start();   
        if(isset($_POST['genome_id']) && isset($_POST['gene_id']) && isset($_POST['p_value'])){
            $genome_id = $_POST['genome_id'];
			$gene_id = $_POST['gene_id'];
			$p_value = $_POST['p_value'];
			$location = $_POST['location'];
			$distance = $_POST['distance'];
			
			
			$_SESSION['back_genome_id'] = $genome_id;
			$_SESSION['back_gene_id'] = $gene_id;
			$_SESSION['back_p_value'] = $p_value;
			$_SESSION['back_location'] = $location;
			$_SESSION['back_distance'] = $distance;

        }else{
			$genome_id = $_SESSION['back_genome_id'];
			$gene_id = $_SESSION['back_gene_id'];
			$p_value = $_SESSION['back_p_value'];
			$location = $_SESSION['back_location'];
			$distance = $_SESSION['distance'];
		}

		$genes_sql  = "SELECT crms.crmID AS crmscrmID, crms.Chromosome AS crmsChromosome, crms.Start_Pos AS crmsStart, crms.End_Pos AS crmsEnd, 
		crms.Score, crms.P_value AS crmsP_value FROM crms
		INNER JOIN crms_genes_association ON crms.crmID=crms_genes_association.crmID AND crms.P_value<=\"{$p_value}\"
		INNER JOIN genes ON genes.geneID=crms_genes_association.geneID
		WHERE (genes.geneID LIKE \"{$gene_id}%\" OR genes.Symbol LIKE \"%{$gene_id}%\") AND crms.Genome={$genome_id}";
  
	?>  

	<?php
		$rs = $mysqli->query($genes_query);
	?>

	<div class="container">
		<div class="row">
			<div class="col">
				<div class="card border-dark py-2 px-4">
					<div class="row">
						<div class="col">							
							<form>
								<div class="form-row">
									<div class="col">
										<div class="form-group">
											<label for="genome_id">Select a specie:</label>
											<select class="form-control" name="genome_id" id="genome_id">
												<option disable selected>Select a specie</option>
												<option value="1">Homo sapiens</option>
												<option value="2">Mus musculus</option>
												<option value="3">Caenorhabditis elegans</option>
											</select>
										</div>
									</div>

									<div class="col">
										<div class="form-group">
											<label for="gene_id">Input a gene:</label>
											<input type="text" class="form-control" name="gene_id" id="gene_id" value="RUNX1..">
										</div>

									</div>

									<div class="col">
										<div class="form-group">
											<label for="p_value">Input a threshold of p value for CRMs:</label>
											<select class="custom-select" name="p_value" id="p_value">
												<option value="0.05">0.05</option>
												<option value="0.01">0.01</option>
												<option value="0.0000005">5e-06</option>
												<option value="0.0000001">1e-06</option>
											</select>
										</div>

									</div>

								</div>

								
								<div class="form-row align-items-baseline">
									<div class="col">

										<div class="form-group">

											<label for="location" class="control-label">Location of CRMs:</label>

											<div class="form-check-inline">
												<label class="form-check-label" for="radio1">
													<input type="radio" class="form-check-input" id="radio1" name="location" value="upstream"> Upstream
												</label>
											</div>

											<div class="form-check-inline">
												<label class="form-check-label" for="radio2">
													<input type="radio" class="form-check-input" id="radio2" name="location" value="downstream"> Downstream
												</label>
											</div>

											<div class="form-check-inline">
												<label class="form-check-label" for="radio3">
													<input type="radio" class="form-check-input" id="radio3" name="location" value="both"> Both
												</label>
											</div>

										</div>

									</div>

									<div class="form-group">

										<label for="location">Range of the gene:</label>

										<div class="form-check-inline">
											<label class="form-check-label" for="radio1">
												<input type="radio" class="form-check-input" id="radio1" name="location" value="500000"> 0.5M
											</label>
										</div>

										<div class="form-check-inline">
											<label class="form-check-label" for="radio2">
												<input type="radio" class="form-check-input" id="radio2" name="location" value="1000000"> 1M
											</label>
										</div>

										<div class="form-check-inline">
											<label class="form-check-label" for="radio3">
												<input type="radio" class="form-check-input" id="radio3" name="location" value="2000000"> 2M
											</label>
										</div>

									</div>

									<button type="submit" class="btn btn-primary">Search</button>
								</div>
                			</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr/>
		
		<div class="row mt-4">

			<div class="col">
				
				<form action="get_genes.php" method='POST'>
                    <!-- <div class='form-inline ml-auto row'> -->
                    <div class='form-row align-items-center'>
                        <label for="limit" class="col-4 mr-0 pr-0">Records per page:</label> 
                        <select class='form-control col-8' name="limit" id="limit" onchange="this.form.submit()">
                            <option value="-1">select records per page..</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>

                        </select>

                    </div>
                </form>  

			</div>
		</div>

		<div class="row">
			<div class="col">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th scope="col">CRM ID</th>
							<th scope="col">CRM coordinates</th>
							<th scope="col">Score</th>
							<th scope="col">P value</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($row = $rs->fetch_assoc()){
								echo "<tr>";
								echo "<th><a href=crm.php?crmId={$row['crmscrmID']}&genome_id={$genome_id}>{$row['crmscrmID']}</th>";
								echo "<th>{$row['crmsChromosome']}:{$row['crmsStart']}-{$row['crmsEnd']}</th>";
								echo "<th>{$row['Score']}</th>";
								echo "<th>{$row['crmsP_value']}</th>";
								echo "</tr>";
							}

						?>
					
					</tbody>
				</table> 


			</div>

		</div>

	</div>

</body>

</html> 
