<?php
namespace Core;
use App\Models\{Users, Menu};

class H {
  public static function dnd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
  }

  public static function currentPage() {
    $currentPage = $_SERVER['REQUEST_URI'];
    if($currentPage == PROOT || $currentPage == PROOT. strtolower(DEFAULT_CONTROLLER) .'/index') {
      $currentPage = PROOT . strtolower(DEFAULT_CONTROLLER);
    }
    return $currentPage;
  }

  public static function getObjectProperties($obj){
    return get_object_vars($obj);
  }

  public static function buildDynamicMenu(){
    ob_start();
        ?>
        <ul class="navbar-nav mainMenu me-auto mb-2 mb-lg-0">
        <?php  $headMenus = Menu::findHeadMenuItems();
         foreach($headMenus as $headMenu):
            $subMenus = Menu::findSubMenuItems($headMenu->id);?>
      <li class="nav-item dropdown">
        <a class="nav-link bg-dark" href="<?=PROOT.$headMenu->link?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="span"><?=$headMenu->category?></span>
        </a>
        <?php if($subMenus):?><ul class="dropdown-menu subMenu bg-dark" aria-labelledby="navbarDropdown"><?php endif; ?>
          <?php
              foreach($subMenus as $subMenu):
              $thirdMenus = Menu::findThirdMenuItems($subMenu->id);
            ?>
          <li><a class="dropdown-item" href="<?=PROOT.$subMenu->link?>"><span class="span"><?=$subMenu->category?></span></a>
            <?php if($thirdMenus):?><ul class="dropdown-menu SuperSubMenu bg-dark" aria-labelledby="navbarDropdown"><?php endif; ?>
              <?php foreach ($thirdMenus as $thirdMenu):
                ?>
              <li><a class="dropdown-item" href="<?=PROOT.$thirdMenu->link?>"><span class="span"><?=$thirdMenu->category?></span></a></li>

            <?php endforeach; ?>
          <?php if($thirdMenus):?></ul><?php endif; ?>
          </li>
        <?php endforeach; ?>
        <?php if($subMenus):?></ul><?php endif; ?>
      </li>
    <?php endforeach; ?>
    </ul>
       <?php
    return ob_get_clean();
  }


  public static function buildMenuListItems($menu,$dropdownClass=""){
    ob_start();
    $currentPage = self::currentPage();
    foreach($menu as $key => $val):
      $active = '';
      if($key == '%USERNAME%'){
        $key = (Users::currentUser())? "Hello " .Users::currentUser()->fname : $key;
      }
      if(is_array($val)): ?>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$key?></a>
          <div class="dropdown-menu <?=$dropdownClass?>">
            <?php foreach($val as $k => $v):
              $active = ($v == $currentPage)? 'active':''; ?>
              <?php if(substr($k,0,9) == 'separator'): ?>
                <div role="separator" class="dropdown-divider"></div>
              <?php else: ?>
                <a class="dropdown-item <?=$active?>" href="<?=$v?>"><?=$k?></a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </li>
      <?php else:
        $active = ($val == $currentPage)? 'active':''; ?>
        <li class="nav-item"><a class="nav-link <?=$active?>" href="<?=$val?>"><?=$key?></a></li>
      <?php endif; ?>
    <?php endforeach;
    return ob_get_clean();
  }
}
