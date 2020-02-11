<div class="header">
    <div class="site-title">
        <h2><?= $this->application_name ?></h2>
    </div>
    <ul class="nav-menu">
    <?php foreach(pages() as $page): ?>
        <li>
            <a href="<?= $page['url'] ?>"><?= $page['label'] ?></a>
        </li>
    <?php endforeach ?>
    <?php if(session()->get('id') && routes('logout')): ?>
        <li>
            <a href="<?= base_url()?>/logout">Log Out</a>
        </li>
    <?php elseif(routes('login')): ?>
        <li>
            <a href="<?= base_url()?>/login">Log In</a>
        </li>
    <?php endif ?>
    </ul>
</div>