<?php if ($submenu): ?>
<div id="dev_docs_submenu">
	<?php foreach ($submenu as $key => $page): ?>
		<?php if ($key === 0): ?>
				<h3>
					<?php if ($this->input->get('docs_page') !== $page->short_name): ?><a href="<?=$page->url?>"><?php endif; ?>
					<?=$page->heading?>
					<?php if ($this->input->get('docs_page') !== $page->short_name): ?></a><?php endif; ?>
				</h3>
			<ul>
		<?php else: ?>
			<?php if ($this->input->get('docs_page') === $page->short_name): ?>
				<li class="current"><?=$page->heading?></li>
			<?php else: ?>
				<li><a href="<?=$page->url?>"><?=$page->heading?></a></li>
			<?php endif ?>
		<?php endif ?>	
	<?php endforeach ?>
	</ul>
</div>
<?php endif ?>

<div id="dev_docs">
	
<?=$content;?>

</div>