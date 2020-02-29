<div class="top-header">
	<div class="">
		<div class="top-flex">
			<div class="top-brand">
				<a href="#">
					<?php if(session()->user()->customer() && session()->user()->customer()->logo()): ?>
					<img src="<?= session()->user()->customer()->logo()->file_url ?>" width="35px" height="38px" style="object-fit:contain">
					<?php else: ?>
					<img src="<?= asset('assets/logo.png') ?>" width="35px">
					<?php endif ?>
					<span><?= $this->application_name ?></span>
				</a>
			</div>
			<div class="top-account">
				<a href="#">
					<span><?= session()->user()->user_name; ?></span>
					<img src="<?= asset('assets/user.png') ?>" width="38px" height="38px" style="border-radius:50%;object-fit:cover;">
				</a>
				<a href="javascript:void(0)" class="nav-toggle" style="padding:18px;" onclick="document.querySelector('.left-sidebar').classList.toggle('sidebar-toggle')"><i class="fa fa-bars fa-lg"></i></a>
			</div>
		</div>
	</div>
</div>