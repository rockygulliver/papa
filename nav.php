<?php if(isset($page)) { $homedir = '../'; } else { $homedir = ''; } ?>

<?php if(isset($page)) : ?>
<nav class="navbar navbar-default navbar-affix-top">
<?php else :?>
<nav class="navbar navbar-default" data-spy="affix" data-offset-top="" style="border-bottom: 1px solid rgba(0,0,0,0);">
<?php endif; ?>
  <a href="#maincontent" class="offscreen">Skip Navigation </a>
  <div class="container nav-container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navmenu" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $homedir; ?>">
        <img class="navbar-logo" src="<?php echo $homedir; ?>img/logo.svg" alt="Erica Dreisbach | Chicago Web Designer + Developer" title="Erica Dreisbach | Chicago Web Designer + Developer" />
        <span class="navbar-name">ericadreisbach</span></a>
    </div>

    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown <?php if($bodyclass == 'work') { echo 'active'; } ?>">
          <a href="#" class="" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false">
            <span>Work</span>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="<?php if($page == 'Sentegrity') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>sentegrity">Sentegrity </a></li>
            <li class="<?php if($page == 'Educare') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>educare">Educare </a></li>
            <li class="<?php if($page == 'PortionPac') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>portionpac">PortionPac </a></li>
            <li class="<?php if($page == 'WellTrail') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>welltrail">WellTrail </a></li>
            <li class="<?php if($page == 'Government Energy Management') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>gem">Government Energy Management </a></li>
            <li class="<?php if($page == 'Savory Living') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>savory">Savory Living </a></li>
            <li class="<?php if($page == 'Nearby Registry') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>nearby">Nearby Registry </a></li>
            <li class="<?php if($page == 'Sugar Glen Farm') { echo 'active'; }?>"><a href="<?php echo $homedir; ?>sugarglen">Sugar Glen Farm </a></li>
          </ul>
        </li>
        <li><a href="<?php echo $homedir; ?>#about">About </a>
          <ul class="hidden">
            <li><a href="<?php echo $homedir; ?>how-i-work">How I Work </a></li>
            <li><a href="<?php echo $homedir; ?>testimonials">Testimonials </a></li>
          </ul>
        </li>
        <li><a href="<?php echo $homedir; ?>#skills">Skills </a>
          <ul class="hidden">
            <li><a href="<?php echo $homedir; ?>pdf/ericadreisbach-resume.pdf">Resume (PDF)</a></li>
          </ul>
        </li>
        <li><a href="<?php echo $homedir; ?>#contact">Contact</a></li>
      </ul>

    </div><!-- /.navbar-collapse -->

  </div><!-- /.nav-container -->
</nav>
