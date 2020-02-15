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
    <?php elseif(session()->user()->user_level == 'master'): ?>
        <li>
            <a href="<?= base_url()?>" class="<?= $this->visited == 'index' ? 'active' : '' ?>"><i class="fa fa-home fa-fw"></i> Dashboard</a>
        </li>
        <li>
            <a href="<?= route('master/category')?>" class="<?= $this->visited == 'kategori' ? 'active' : '' ?>"><i class="fa fa-list fa-fw"></i> Categories</a>
        </li>
        <!-- <li>
            <a href="<?= route('master/category')?>" class="<?= $this->visited == 'product' ? 'active' : '' ?>"><i class="fa fa-briefcase fa-fw"></i> Products</a>
        </li> -->
        <li>
            <a href="<?= route('master/customers')?>" class="<?= $this->visited == 'customers' ? 'active' : '' ?>"><i class="fa fa-vcard fa-fw"></i> Customers</a>
        </li>
        <!-- <li>
            <a href="<?= route('master/category')?>" class="<?= $this->visited == 'customer' ? 'active' : '' ?>"><i class="fa fa-pie-chart fa-fw"></i> Subscriptions</a>
        </li> -->
        <li>
            <a href="<?= route('master/users')?>" class="<?= $this->visited == 'users' ? 'active' : '' ?>"><i class="fa fa-users fa-fw"></i> All Users</a>
        </li>
        <li>
            <a href="<?= base_url()?>/logout"><i class="fa fa-sign-out fa-fw"></i> Log Out</a>
        </li>
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