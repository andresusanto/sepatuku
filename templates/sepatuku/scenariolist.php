<html>
  <head>
    <h1>Scenario List for Page: <?php echo $pagename ?></h1>
  </head>
  <body>
    <ul style="list-style-type:none"><li><a href="/pages/tests/main">View main test data</a></li></ul>
    <?php if (count($scenarios) > 0): ?>
    <ul>
    <?php foreach ($scenarios as $scenario): ?>
      <li>
        Scenario <?php echo $scenario ?>:
        <a href="/pages/<?php echo $pagename ?>/tests/<?php echo $scenario ?>/" title="View this scenario data">View scenario data</a>
        -
        <a href="/pages/<?php echo $pagename ?>/tests/<?php echo $scenario ?>/execute">Execute</a>
      </li>
    <?php endforeach ?>
    </ul>
    <?php else: ?>
    <p>No scenarios found! Please put some in 'tests/<?php echo $pagename ?>' folder</p>
    <?php endif ?>
  </body>
</html>
 
