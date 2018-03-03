 <!-- menu profile quick info -->
 <div class="profile clearfix">
              <div class="profile_pic">
                <img src="images/user.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php
                if(isset($_SESSION['fullname'])) echo $_SESSION['fullname']
                ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->