<html>
  <head>
    <title>SIRCLO Development Kit</title>
  </head>
  <body>

    <header>
      <h1>SIRCLO Template Development Kit <a href="https://developer.sirclo.com/tdk_help/release-log" target="_blank">v0.9.51b</a></h1>
    </header>

    <section class="root-dir"><span>Templates Directory</span><span><input type="text" value="<?php echo $rootDir;?>"></span></section>
    <section class="guide">
      <p>
      To add new template.
      </p>
      <ol>
        <li>Open terminal window.</li>
        <li>Go to tdk directory.</li>
        <li>Run this command: <code>./new_template.sh [template_name] [local_address] [desired_simulated_ip_address]</code><br/>
        E.g. <code>./new_template.sh mytemplate mytemplate.local 127.0.0.1</code></li>
        <li>Prompt password if asked</li>
      </ol>
    </section>
    <section class="available-templates">
    <h2>Available Templates</h2>
      <table>
          

        <?php 
        if (!file_exists($rootDir)) {
          echo "Invalid template directory. Path not found.";
        } else {
          $directory = scandir($rootDir);
          $templates = array();
          foreach ($directory as $dir):
            $newDirs = $rootDir.'/'.$dir;
            if(file_exists($newDirs.'/.sirclo-tdk')) {
              $templates []= $dir;
            }
          endforeach; ?>
          <?php 
            if (count($templates) > 0) :
              echo "<tr><th>Template Name</th><th>Local Address</th><th>Archive</th><th></th>";
            foreach ($templates as $template): 

            $tdkConfig = parse_ini_file($rootDir.'/'.$template."/.sirclo-tdk");
          ?>

            <tr><td><?php echo $template ?></td><td><a target="_blank" href="<?php echo $tdkConfig['local_address'] ?>/"><?php echo $tdkConfig['local_address'] ?></a></td><td><a href="/archive/<?php echo $template ?>">Download as .zip</a></td><td><?php if (isset($tdkConfig['src'])) {echo "<a href='/update/".$template."' >Update from source</a>";} {
              # code...
            }?></td>
          <?php endforeach ?>
        <?php else: 
            echo "No templates available. Create one.";
          endif;
        }
      ?>
        
      </table>
    </section>

  </body>
</html>
