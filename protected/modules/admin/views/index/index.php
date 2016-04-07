<frameset rows="80,*,50" border="0">
  <frame name="header" scrolling="no" noresize src="<?php echo $this->createUrl('/index/header');?>"></frame>
  <frameset cols="25%,75%" border="0">
    <frame name="contents" scrolling="no" noresize src="<?php echo $this->createUrl('/contents/index');?>"></frame>
    <frame name="main" noresize src="<?php echo $this->createUrl('/index/main');?>"></frame>
  </frameset>
  <frame name="footer" scrolling="no" noresize src="<?php echo $this->createUrl('/index/footer');?>"></frame>
</frameset>