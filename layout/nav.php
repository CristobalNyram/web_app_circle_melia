<!-- Header START -->
<div class="header" style="">
    <div class="logo logo-dark"
    style="
    display: flex;
    justify-content: center;
    align-items: center;
    "
    >
        <a  href="javascript:void(0);" >
            <img src="assets/images/logo/logo-main-colo.png" alt="Logo">
            <img class="logo-fold" src="assets/images/logo/logo-main-mobile.png" alt="Logo">
        </a>
    </div>
    <div class="logo logo-white">
        <a  href="javascript:void(0);">
            <img src="assets/images/logo/logo-white.png" alt="Logo">
            <img class="logo-fold" src="assets/images/logo/logo-fold-white.png" alt="Logo">
        </a>
    </div>
    <div class="nav-wrap">
        <ul class="nav-left">
            <li class="desktop-toggle">
                <a href="javascript:void(0);">
                    <i class="anticon"></i>
                </a>
            </li>
            <li class="mobile-toggle">
                <a href="javascript:void(0);">
                    <i class="anticon"></i>
                </a>
            </li>
            <li hidden>
                <a href="javascript:void(0);" data-toggle="modal" data-target="#search-drawer">
                    <i class="anticon anticon-search"></i>
                </a>
            </li>
        </ul>
        <ul  
        <?php if (isset($_SESSION['tipo'])): ?>
        <?php else: ?>
        hidden
        <?php endif; ?>
            class="nav-right">
            <li class="dropdown dropdown-animated scale-left">
                <div class="pointer" data-toggle="dropdown">
                    <div class="avatar avatar-image  m-h-10 m-r-15">
                        <img src="https://w7.pngwing.com/pngs/81/570/png-transparent-profile-logo-computer-icons-user-user-blue-heroes-logo-thumbnail.png"  alt="">
                    </div>

                    <div class="p-b-15 p-t-20 dropdown-menu pop-profile">
                                <div class="p-h-20 p-b-15 m-b-10 border-bottom">
                                    <div class="d-flex m-r-50">
                                        <div class="avatar avatar-lg avatar-image">
                                            <img src="https://w7.pngwing.com/pngs/81/570/png-transparent-profile-logo-computer-icons-user-user-blue-heroes-logo-thumbnail.png" alt="">
                                        </div>
                                        <div class="m-l-10">
                                            <p class="m-b-0 text-dark font-weight-semibold">
                                                
                                            <?php if (isset($_SESSION['tipo'])): ?>
                                                <?php echo "USUARIO: ".strtoupper(htmlspecialchars($_SESSION['tipo'], ENT_QUOTES, 'UTF-8')); ?>
                                                <?php else: ?>
                                                <span>... </span>
                                            <?php endif; ?>
                                            </p>
                                            <p class="m-b-0 opacity-07">..</p>
                                        </div>
                                    </div>
                                </div>
                                <a   onclick="window.location.href='pages/logout/'"  class="dropdown-item d-block p-h-15 p-v-10">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                                            <span class="m-l-10">Logout</span>
                                        </div>
                                        <i hidden class="anticon font-size-10 anticon-right"></i>
                                    </div>
                                </a>
                            </div>
                </div>
            </li>
            <li>
                <a hidden href="javascript:void(0);" data-toggle="modal" data-target="#quick-view">
                    <i class="anticon anticon-appstore"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Header END -->
