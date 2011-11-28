<?php if ($submenu): ?>
<div id="dev_docs_submenu">
	<h3>Related:</h3>
	<ul>
	<?php foreach ($submenu as $page): ?>
		<?php if ($this->input->get('docs_page') === $page->short_name): ?>
			<li class="current"><?=$page->heading?></li>
		<?php else: ?>
			<li><a href="<?=$page->url?>"><?=$page->heading?></a></li>
		<?php endif ?>
	<?php endforeach ?>
	</ul>
</div>
<?php endif ?>

<div id="dev_docs">
	
<?=$content;?>

</div>