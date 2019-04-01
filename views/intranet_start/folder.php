
<?var_dump($folderwithfiles);?>
<?= $this->render_partial('_partials/folder_with_files', array('folderwithfiles' => $folderwithfiles, 'parentfolder' => $parentfolder, 'parent' => NULL, 'display' => 'block')) ?>
