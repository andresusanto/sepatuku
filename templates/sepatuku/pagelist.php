<html>
  <head>
    <h1>Page List</h1>
  </head>
  <body>
    <?php if (count($pagenames) > 0): ?>
    <ul>
    <?php foreach ($pagenames as $pagename): ?>
      <li><a href="/pages/<?php echo $pagename ?>/"><?php echo $pagename ?></a></li>
    <?php endforeach ?>
    </ul>
    <?php else: ?>
    <p>No pages found! Please put some in 'pages' folder</p>
    <?php endif ?>
  </body>
</html>
