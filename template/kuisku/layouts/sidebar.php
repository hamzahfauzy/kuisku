<div style="padding-top: 50px;position:fixed;width:300px;">
	<div class="nav-account">
        <div class="participant-picture">
            <img src="<?= asset('assets/user.png') ?>" width="75px" height="75px" style="margin-right: 10px;" class="nav-account-img">
        </div>
		<div class="nav-account-info">
			<span class="username-info"><?= session()->user()->user_name ?><br></span>
			<span class="email-info"><?= session()->user()->user_email ?> <br></span>
			<span class="ip-info">IP: <?= getUserIpAddr() ?></span>
		</div>
    </div>
	<ul class="nav-menu-item">
    <?php if(session()->user()->user_level == 'admin'): ?>
    <?php foreach(pages() as $key => $page): ?>
        <li>
            <a href="<?= $page['url'] ?>" class="<?= $this->visited == $key ? 'active' : '' ?>"><?= $page['label'] ?></a>
        </li>
    <?php endforeach ?>
    <?php if(session()->get('id') && routes('logout')): ?>
        <li>
            <a href="<?= base_url()?>/logout"><i class="fa fa-sign-out fa-fw"></i> Log Out</a>
        </li>
    <?php elseif(routes('login')): ?>
        <li>
            <a href="<?= base_url()?>/login">Log In</a>
        </li>
    <?php endif ?>
    <?php else: ?>
        <li>
            <a href="<?= base_url()?>"><i class="fa fa-home fa-fw"></i> Beranda</a>
        </li>
        <li>
            <a href="<?= base_url()?>/logout"><i class="fa fa-sign-out fa-fw"></i> Log Out</a>
        </li>
        <?php endif ?>
    </ul>
</div>