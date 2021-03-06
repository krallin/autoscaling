<?php
include_once("constants.php");
include_once("functions.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Autoscaling Demo App</title>
    <link type="text/css" href="/static/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="/static/css/bootstrap-responsive.css" rel="stylesheet">
    <link type="image/x-icon" href="/static/img/favicon.ico" rel="shortcut icon" />
    <style type="text/css">code{color:#15d;} code.error{color:#d14}.hero-unit img{margin-right:10px;margin-top:10px}</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="hero-unit">
      <img src="/static/img/scalr-logo.png" alt="Scalr Logo" class="pull-left"/>
      <h1>Autoscaling Demo App</h1>
      <p>This is the autoscaling demo app.</p>
      <p>This application lets you monitor, as well as simulate CPU load.</p>
    </div>
    <div class="container">
<?php
$n_cpus = get_n_cpus();
$stress_cli = sprintf("stress --cpu %s --io %s", $n_cpus * LOAD_FACTOR, $n_cpus * LOAD_FACTOR);

if (METHOD === "GET") {
?>
      <h2>CPU Status</h2>
      <div>
        <p>Number of CPUS: <code><?php echo $n_cpus; ?></code></p>
      </div>
      <div>
        <p>Load generation command: <code><?php echo $stress_cli; ?></code></p>
      </div>
      <div>
        <p>Uptime: <code><?php echo exec('uptime'); ?></code></p>
        <small class="muted">This value will be used by Scalr for autoscaling.</small>
      </div>

      <h2>Load Simulation</h2>
<?php
if (is_running(PID_FILE)) {
  $status  = "Currently simulating load";
  $cta     = "Stop simulating load";
  $button  = "Stop";
  $cls     = "btn-danger";
  $action  = ACTION_STOP;
} else {
  $status  = "Currently not simulating load";
  $cta     = "Start simulating load";
  $button  = "Start";
  $cls     = "btn-success";
  $action  = ACTION_START;
}
?>

      <p><?php echo $status; ?></p>

      <h2><?php echo $cta; ?></h2>
      <form action="/" method="post" class="form-inline">
        <fieldset>
          <div class="input-append">
            <input type="hidden" name="action" value="<?php echo $action; ?>" />
            <input type="submit" class="btn <?php echo $cls; ?>" value="<?php echo $button; ?>"/>
          </div>
        </fieldset>
      </form>

      <hr/>

      <h2>Configuring autoscaling</h2>
      <p>To make sure that Scalr autoscales your app, define an autoscaling policy using Load Averages.</p>
<?php
} elseif (METHOD === "POST") {
  $action = $_POST["action"];

  if ($action === ACTION_START) {
    if (!is_running(PID_FILE)) {
      start_process($stress_cli, OUTPUT_FILE, PID_FILE);
    }
    header("Location: /");
  } elseif ($action === ACTION_STOP) {
    if (is_running(PID_FILE)) {
      kill_process(PID_FILE);
    }
    header("Location: /");
  } else {
    ?>
      <div class="alert alert-error">
        This action is invalid: "<?php echo $action; ?>"
      </div>
      <div class="text-center">
        <a href="/" class="btn btn-large btn-primary" type="button">Please try again!</a>
      </div>
    <?php
  }
} else {
  echo "Unsupported method! (Try GET!)";
}
?>
    </div>
  </body>
</html>
