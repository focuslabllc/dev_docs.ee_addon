<?php if ($submenu): ?>
<div id="dev_docs_submenu">
	<?php foreach ($submenu as $key => $page): ?>
		<?php if ($key === 0): ?>
			<h3><?=$page->heading?></h3>
			<ul>
				<?php if ($this->input->get('docs_page') === $page->short_name): ?>
				<li class="current"><?=lang('dd:overview')?></li>
				<?php else: ?>
				<li><a href="<?=$page->url?>"><?=lang('dd:overview')?></a></li>
				<?php endif ?>
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