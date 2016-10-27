<?php $bodyclass = 'home'; $testform = 'testform'; include('header.php'); ?>

<link rel="stylesheet" type="text/css" href="css/style.css" />

<div style="display: block; height: 95vh;"> </div>


<section>
  <div class="container">
    <div class="row">
      <div class="col-md-2"> </div>

      <div class="col-md-8 col-content">

        <form action="process.php" method="POST" role="form" id="contactform" class="form -successhide">

          <h3 class="-sectionhead -txtcenter" style="text-transform: none;">Wanna Work Together? </h3>

          <fieldset class="form-group row">
            <div class="col-md-6">
              <label class="control-label sr-only" for="name">Name </label>
              <input name="name" id="name" type="text" maxlength="40" class="form-control <?php echo $nameErrCode;?>"  placeholder="Name*" value="<?php echo $name; ?>" required>
              <p class="nameerror"><?php echo $nameErr;?></p>
              <br class="-desktophide" />
            </div>

            <div class="col-md-6">
              <label class="control-label sr-only" for="email">Email </label>
              <input name="email" id="email" type="email" maxlength="50" class="form-control <?php echo $emailErrCode;?>" placeholder="Email*" value="<?php echo $email; ?>" required>
              <p class="emailerror"> <?php echo $emailErr; ?></p>
            </div>
          </fieldset>


          <fieldset class="form-group">
            <label class="control-label sr-only" for="website">Website <span class="-small">(if you have one)</span></label>
            <input name="website" id="website" type="text" maxlength="80" class="form-control <?php echo $websiteErrCode;?>"  placeholder="Website (if you have one)" value="<?php echo $website;?>">
            <p class="websiteerror"> <?php echo $websiteErr;?></p>
          </fieldset>

          <!-- detect referrer -->
          <?php if (!empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'https://www.google.com/') {
            $server = $_SERVER['HTTP_REFERER'];
            $googleq = '<fieldset class="form-group"><p class="pform">It looks like you came here via Google. What search terms did you use?</p><label class="control-label sr-only" for="searchterms">Search terms</label><input name="searchterms" id="searchterms" type="text" maxlength="80" class="form-control" placeholder=""></fieldset>';
            echo $googleq;
          } else {
          } ?>


          <fieldset class="form-group">
            <p class="pform">In a few sentences, describe your project. <br />
              <span class="small"><em>Limit: 300 characters</em> </span>
            </p>
            <p class="clientprojecterror"> <?php echo $clientprojectErr; ?></p>

            <textarea id="clientproject" name="clientproject" class="textarea form-control" rows="4" maxlength="300"><?php echo $clientproject;?></textarea>
          </fieldset>


          <input type="submit" name="submit" value="Submit" class="-ghost -naturalsize">

          <div id="msgSubmit" class="-sectionhead -txtcenter hidden" style="text-transform: none;">Message Submitted! </div>

        </form>


      </div><!-- /.col-content -->

      <div class="col-md-2"> </div>
    </div>
  </div>
</section>





<?php include('footer.php'); ?>