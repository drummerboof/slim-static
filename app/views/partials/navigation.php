<ul>
    <?php foreach ($this->pages()->pages() as $page): ?>
        <li><a href="<?php echo $page->url(); ?>"><?php echo $page->title(); ?></a></li>
    <?php endforeach; ?>
</ul>

<?php if (count($this->pages()->pages($this->page())) > 0): ?>
    <ul>
        <?php foreach ($this->pages()->pages($this->page()) as $page): ?>
            <li><a href="<?php echo $page->url(); ?>"><?php echo $page->title(); ?></a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<ul>
    <?php $path = $this->pages()->path($this->page());
    foreach ($path as $page): ?>
        <li><?php echo $page->title(); ?></li>
    <?php endforeach; ?>
</ul>