<?php include "init.php"; ?>
	
	<div class="container">
		<h1 class="text-center my-4 text-secondary display-3">Show Categories</h1>
		<div class="row">
			<?php

				if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
				$pageId = intval($_GET['pageid']);
				$getItem = getAll('*','items',"WHERE Cat_ID ={$pageId}",'AND Approve = 1','ItemID');

					foreach ($getItem as $item) { ?>
						<div class="col-md-3 col-sm-6">
							<div class="card">

								<img src="iphone-13.jpeg" class="card-img-top" alt="...">
								<div class="card-body">
								    <h5 class="card-title">
								    	<a href="item.php?itemid=<?php echo $item['ItemID'] ?>">
								    		<?php echo $item['Name'] ?>
								    	</a>
								   	</h5>
								    <p class="card-text text-desc"><?php echo $item['Description'] ?></p>
								    <strong class="fst-italic text-secondary"><?php echo $item['Price'] ?></strong>
								    <dfn class="card-text d-block"><?php echo $item['Add_Date'] ?></dfn>
								</div>
							</div>
						</div>

				 <?php } }?>
			
		</div>
	</div>

	
<?php include $tpl ."footer.php"; ?>