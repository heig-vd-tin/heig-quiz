<template>
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <div class="row">
            <div class="col-xs-6 col-sm-4">
              <p class="text-uppercase">Ressources</p>
              <ul class="list-unstyled">
                <li><a href="http://intra.heig-vd.ch/">Intranet</a></li>
              </ul>
            </div>
            <div class="col-xs-6 col-sm-4">
              <p class="text-uppercase">Social</p>
              <ul class="list-inline">
                <li>
                  <a href="https://www.facebook.com/HEIGVD"
                    ><i class="fa fa-facebook" aria-hidden="true"></i
                  ></a>
                </li>
                <li>
                  <a href="https://www.linkedin.com/edu/school?id=154032"
                    ><i class="fa fa-linkedin" aria-hidden="true"></i
                  ></a>
                </li>
                <li>
                  <a href="https://twitter.com/heigvd"
                    ><i class="fa fa-twitter" aria-hidden="true"></i
                  ></a>
                </li>
                <li>
                  <a href="https://www.youtube.com/user/HEIGVD"
                    ><i class="fa fa-youtube-play" aria-hidden="true"></i
                  ></a>
                </li>
              </ul>
            </div>
            <div class="col-sm-4 hidden-xs">
              <div>
                <a href="http://www.vd.ch">
                  <img
                    class="p-2"
                    src="../../img/logo-vd.svg"
                    alt="Canton de Vaud"
                    height="80px"
                  />
                </a>
                <a href="http://www.hes-so.ch">
                  <img
                    class="p-2"
                    src="../../img/logo-hes-so.svg"
                    alt="HES-SO"
                    height="80px"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
<style scoped lang="scss">

@import '../../sass/variables';

footer {
  padding: 5px;
  background-color: $gray-800;
  color: #fff;
}
</style>
