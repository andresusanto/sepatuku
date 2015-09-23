<html>
  <head>
    <h1>Page List</h1>
  </head>
  <body>
    <ul>
    <?php foreach ($pagenames as $pagename): ?>
      <li><a href="/pages/<?php echo $pagename ?>/tests"><?php echo $pagename ?></a></li>
    <?php endforeach ?>
    </ul>
  </body>
</html>
