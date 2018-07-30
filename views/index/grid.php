<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
 
<body>


  <div class="cards">
    
        
    <?php foreach($tabs as $key => $tab): ?>
      <?php if($key != 'overview_vhs'): ?>
      <div class="col-md-4">
        <a href='<?= URLHelper::getURL($tab->getUrl()) ?>' >
        <div class="card mb-4 box-shadow">
        <img class="card-img-top" src="<?= Assets::url('images/sidebar/' . $this->controller->sidebar_image($key)) ?>" alt="Card image cap">
        
        <div class="card-body">
            <div style='display:none' ><?= $key ?></div>
            <p class="card-text"><?= $tab->getTitle() ?></p>
             <!--
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                 
                  <a type="button" class="btn btn-sm btn-outline-secondary">Edit</a>
                </div>
                <small class="text-muted">...</small>
            </div> -->
        </div>    
        </div>
        </a>
      </div>
      <?php endif ?>
    <?php endforeach ?>
       
  </div>


